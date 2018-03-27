/* eslint-env browser */

export function setupMenu() {
  const moreLinkContainer = document.querySelector('.terminal-nav-bar-inside-more-link');
  const searchContainer = document.querySelector('.terminal-nav-bar-inside-search-link');
  const moreLink = document.querySelector('.terminal-nav-bar-inside-more-link a');
  const navSearch = document.querySelector('.terminal-nav-bar-inside-search');
  const searchTarget = document.querySelector('#terminal-search');
  const searchLink = document.querySelector('.terminal-nav-bar-inside-search-link a');
  const moreNav = document.querySelector('.terminal-nav-bar-inside-more');
  const moreSearch = document.querySelector('.terminal-nav-bar-inside-search');
  const footer = document.querySelector('.terminal-footer');
  const share = document.querySelector('.essb_bottombar');
  const svgLink = document.querySelector('.terminal-nav-bar-inside-more-link svg');
  const searchLinkSVG = document.querySelector('.terminal-nav-bar-inside-search-link svg');
  const widget = document.querySelector('.widget_search');
  const container = document.querySelector('.terminal-container');
  function addEventListenerOnce(target, type, listener) {
    target.addEventListener(type, function fn(event) {
      event.preventDefault();
      event.stopImmediatePropagation();
      target.removeEventListener(type, fn);
      listener(event);
    });
  }
  function reveal(element) {
    element.classList.remove('terminal-hidden');
  }
  function hide(element) {
    element.classList.add('terminal-hidden');
  }
  function toggleOpen(element) {
    element.classList.toggle('terminal-flipped');
  }
  function toggleHiddenNoJS(element) {
    element.classList.toggle('terminal-hidden-no-js');
  }
  function toggleHidden(element) {
    element.classList.toggle('terminal-hidden');
  }
  function addClickListener(listen, targets, icon = false) {
    listen.addEventListener(
      'click',
      (e) => {
        e.preventDefault();
        e.stopImmediatePropagation();
        if (icon) {
          toggleOpen(icon);
        }
        targets.forEach(target => toggleHidden(target));
        if (share) {
          toggleHidden(share);
        }
        if (footer) {
          toggleHidden(footer);
        }
      },
    );
  }

  function addInputListener(inputContainer, resultsCallback) {
    const inputs = inputContainer.getElementsByTagName('input');
    const form = inputContainer.querySelector('form');
    form.addEventListener('submit', e => e.preventDefault());
    [...inputs].filter(input => input.type === 'submit').forEach(input => input.setAttribute('style', 'display: none'));
    const getValues = () => [...inputs].map(({ type, value }) => ({
      type,
      value,
    }))
      .filter(input => input.type !== 'submit');
    [...inputs].forEach(input => input.addEventListener('keyup', (e) => {
      resultsCallback(e, getValues());
    }));
  }
  if (moreLink) {
    toggleHiddenNoJS(moreLinkContainer);
    toggleHiddenNoJS(searchContainer);
    addClickListener(moreLink, [moreNav], svgLink);
    const parsely = window.terminal.parsely.enabled;
    const apikey = window.terminal.parsely.apiKey;
    toggleHidden(widget);
    if (parsely && !window.terminal.isSearch) {
      addClickListener(searchLink, [moreSearch, searchTarget, container], searchLinkSVG);
      addInputListener(navSearch, (event, inputArgs) => {
        event.stopImmediatePropagation();
        const query = encodeURIComponent(inputArgs[0].value.replace(' ', '+'));
        let firstLink = '';
        function loadSearchURL(link) {
          return fetch(link)
            .then(response => response.json())
            .then(({ data, links }) => {
              if (links.first === firstLink) {
                const values = Object.values(data);
                let results = '';
                const resultMore = document.querySelector('.terminal-results-more');
                if (values.length !== 0) {
                  results = values.reduce((agg, datum) => {
                    const image = datum.image_url ? `<a href="${datum.url}" class="terminal-card-image"><img src="${datum.image_url}" /></a>` : '';
                    return `${agg} <div class="terminal-sidebar-card terminal-card terminal-card-single terminal-card-no-grow"><div class="terminal-card-title terminal-no-select">${datum.section}</div>${image}<div class="terminal-limit-max-content-width-add-margin terminal-index-meta-font"><h1 class="terminal-headline-font terminal-stream-headline"><a href="${datum.url}">${datum.title}</a></h1><div class="terminal-byline terminal-index-meta-font terminal-mobile-hide">By ${datum.author}</div></div></div>`;
                  }, '');
                  if (links.next !== null) {
                    addEventListenerOnce(resultMore, 'click', () => {
                      loadSearchURL(links.next);
                    });
                    reveal(resultMore);
                  } else {
                    hide(resultMore);
                  }
                  document.querySelector('.terminal-results').insertAdjacentHTML('beforeend', results);
                } else {
                  results = '<div class="terminal-sidebar-card terminal-card terminal-card-single terminal-no-photo"><div class="terminal-card-text terminal-limit-max-content-width-add-margin"><h1 class="terminal-headline-font terminal-stream-headline">No results found.</h1></div></div>';
                  document.querySelector('.terminal-results').innerHTML = results;
                  hide(resultMore);
                }
              }
            })
            .catch(err => console.error(err));
        }
        const maybeFirstLink = `https://api.parsely.com/v2/search?apikey=${apikey}&limit=6&page=1&q=${query}`;
        if (firstLink !== maybeFirstLink) {
          searchTarget.innerHTML = '';
          if (inputArgs[0].value !== '') {
            searchTarget.innerHTML = `<div class="terminal-header terminal-header-font"><h2>Searching for ${inputArgs[0].value}</h2></div><div class="terminal-results"></div><div class="terminal-results-more terminal-header terminal-header-font terminal-hidden">Load more</div></div>`;
          } else {
            searchTarget.innerHTML = '<div class="terminal-header terminal-header-font"><h2>Enter a search term for instant results</h2></div><div class="terminal-results"></div><div class="terminal-results-more terminal-header terminal-header-font terminal-hidden">Load more</div></div>';
          }
          firstLink = maybeFirstLink;
          loadSearchURL(firstLink);
        }
      });
    } else {
      addClickListener(searchLink, [moreSearch], searchLinkSVG);
    }
  }
}

export default {
  setupMenu,
};
