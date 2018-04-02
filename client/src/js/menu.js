/* eslint-env browser */
/* global jQuery, AdLayersAPI, adLayersDFP, terminal */

export function setupMenu() {
  const moreLinkContainer = document.querySelector('.terminal-nav-bar-inside-more-link');
  const searchContainer = document.querySelector('.terminal-nav-bar-inside-search-link');
  const moreLink = document.querySelector('.terminal-nav-bar-inside-more-link a');
  const navSearch = document.querySelector('.terminal-nav-bar-inside-search');
  const navSearchField = document.querySelector('.terminal-nav-bar-inside-search .search-field');
  const navSearchFieldTwo = document.querySelector('.terminal-nav-bar-inside-search .search-field-filter-one');
  const searchTarget = document.querySelector('#terminal-search');
  const searchLink = document.querySelector('.terminal-nav-bar-inside-search-link a');
  const moreNav = document.querySelector('.terminal-nav-bar-inside-more');
  const moreSearch = document.querySelector('.terminal-nav-bar-inside-search');
  const footer = document.querySelector('.terminal-footer');
  const share = document.querySelector('.essb_bottombar');
  const shareMobile = document.querySelector('.essb-mobile-sharebottom');
  const searchHeader = () => document.querySelector('.terminal-search-header');
  const searchHeaderParams = document.querySelector('.terminal-search-header-params');
  const svgLink = document.querySelector('.terminal-nav-bar-inside-more-link svg');
  const searchLinkSVG = document.querySelector('.terminal-nav-bar-inside-search-link svg');
  const widget = document.querySelector('.widget_search');
  const searchFormMore = document.querySelector('.terminal-search-form-more');
  const searchFormMoreSVG = document.querySelector('.terminal-search-form-more-link svg');
  const searchFormMoreLink = document.querySelector('.terminal-search-form-more-link');
  const searchFormResetLink = document.querySelector('.terminal-search-form-reset-link');
  function isInViewport(element) {
    const rect = element.getBoundingClientRect();
    const html = document.documentElement;
    return (
      rect.top >= 0 &&
      rect.left >= 0 &&
      rect.bottom <= (window.innerHeight || html.clientHeight) &&
      rect.right <= (window.innerWidth || html.clientWidth)
    );
  }
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

  function resetForm() {
    document.querySelector('.terminal-results').innerHTML = '';
    const more = document.querySelectorAll('.terminal-results-more');
    [...more].forEach(node => node.parentNode.removeChild(node));
    searchHeader().innerText = 'Enter a search term for instant results';
    searchHeaderParams.innerText = '';
  }

  function addClickListener(listen, targets, icon = false, focus = false, callback = false, scroll = false) {
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
        if (shareMobile) {
          toggleHidden(shareMobile);
        }
        if (footer) {
          toggleHidden(footer);
        }
        if (focus && focus.offsetParent !== null) {
          focus.focus();
        }
        if (callback) {
          callback();
        }
        if (scroll && !isInViewport(scroll)) {
          scroll.scrollIntoView(false);
        }
      },
    );
  }

  function addInputListener(inputContainer, resultsCallback) {
    const inputs = inputContainer.querySelectorAll('input');
    const select = inputContainer.querySelector('select');
    const form = inputContainer.querySelector('form');
    form.addEventListener('submit', e => e.preventDefault());
    [...inputs].filter(input => input.type === 'submit').forEach(input => input.setAttribute('style', 'display: none'));
    const getValues = () => [...inputs, select].map(({ type, value, name }) => ({
      name,
      type,
      value,
    }))
      .filter(input => input.type !== 'submit');
    [...inputs].forEach(input => input.addEventListener('change', (e) => {
      resultsCallback(e, getValues());
    }));
    [...inputs].forEach(input => input.addEventListener('keyup', (e) => {
      resultsCallback(e, getValues());
    }));
    select.addEventListener('change', (e) => {
      resultsCallback(e, getValues());
    });
  }
  if (moreLink) {
    toggleHiddenNoJS(moreLinkContainer);
    toggleHiddenNoJS(searchFormMoreLink);
    toggleHiddenNoJS(searchContainer);
    addClickListener(moreLink, [moreNav], svgLink);
    const parsely = window.terminal.parsely.enabled;
    const apikey = window.terminal.parsely.apiKey;
    toggleHidden(widget);
    if (parsely && !window.terminal.isSearch) {
      addClickListener(
        searchLink,
        [moreSearch, searchTarget],
        searchLinkSVG,
        navSearchField,
        false,
        searchHeader(),
      );
      addClickListener(
        searchFormMoreLink,
        [searchFormMore],
        searchFormMoreSVG,
        navSearchFieldTwo,
      );
      let currentQuery;
      let slotNum = 1;
      addInputListener(navSearch, (event, inputArgs) => {
        event.stopImmediatePropagation();
        const inputValues = Object.values(inputArgs);
        const query = encodeURIComponent(inputValues.find(element => element.name === 's').value.trim().replace(' ', '+'));
        const boost = encodeURIComponent(inputValues.find(element => element.name === 'boost').value);
        const pubDateEnd = encodeURIComponent(inputValues.find(element => element.name === 'pub_date_end').value);
        const pubDateStart = encodeURIComponent(inputValues.find(element => element.name === 'pub_date_start').value);
        let params = '';
        let sorting = '';
        let dateFilteringBefore = '';
        let dateFilteringAfter = '';
        if (boost && boost !== 'recency' && boost !== 'default') {
          params = `${params}&boost=${boost}`;
          switch (boost) {
            case 'social_referrals': {
              sorting = 'Sorted by social referrals';
              break;
            }
            case 'engaged_minutes': {
              sorting = 'Sorted by engaged minutes';
              break;
            }
            case 'fb_referrals': {
              sorting = 'Sorted by Facebook referrals';
              break;
            }
            case 'tw_referrals': {
              sorting = 'Sorted by Twitter referrals';
              break;
            }
            default: {
              sorting = '';
              break;
            }
          }
        }
        if (pubDateStart) {
          params = `${params}&pub_date_start=${pubDateStart}`;
          dateFilteringAfter = `Starting ${pubDateStart}`;
        }
        if (boost === 'recency') {
          params = `${params}&sort=pub_date`;
          sorting = 'Sorted by recency';
        }
        params = `${params}&limit=12&page=1`;
        if (pubDateEnd) {
          params = `${params}&pub_date_end=${pubDateEnd}`;
          dateFilteringBefore = `Ending ${pubDateEnd}`;
        }
        const maybeParamsObject = {
          pub_date_end: pubDateEnd,
          pub_date_start: pubDateStart,
          q: query,
        };
        if (boost && boost !== 'recency' && boost !== 'default') {
          maybeParamsObject.boost = boost;
        }
        if (boost === 'recency') {
          maybeParamsObject.sort = 'pub_date';
        }
        let firstLink = '';
        let paramsObject = {};
        function loadSearchURL(link) {
          return fetch(link)
            .then(response => response.json())
            .then(({ data, links }) => {
              let values = [];
              if (data) {
                values = Object.values(data);
              }
              const resultMore = document.querySelector('.terminal-results-more');
              let results = '';
              console.log(paramsObject, links.first);
              if (
                Object.keys(paramsObject)
                  .reduce((agg, key) => {
                    if (links.first.includes(`=${paramsObject[key]}&`) || links.first.endsWith(`=${paramsObject[key]}`)) {
                      return true;
                    }
                    return false;
                  }, false) &&
                values.length !== 0
              ) {
                results = values.reduce((agg, datum) => {
                  const image = datum.image_url ? `<a href="${datum.url}" class="terminal-card-image"><img src="${datum.image_url}" /></a>` : '';
                  const noImageClass = image ? '' : 'terminal-no-photo';
                  return `${agg} <div class="terminal-card terminal-card-single terminal-search-card terminal-card-no-grow ${noImageClass}"><div class="terminal-card-title terminal-no-select">${datum.section}</div>${image}<div class="terminal-card-text terminal-limit-max-content-width-add-margin terminal-index-meta-font"><h1 class="terminal-headline-font terminal-stream-headline"><a href="${datum.url}">${datum.title}</a></h1><div class="terminal-byline terminal-index-meta-font terminal-mobile-hide">By ${datum.author}</div></div></div>`;
                }, '');
                if (window.AdLayersAPI &&
                  window.adLayersDFP &&
                  window.jQuery &&
                  window.terminal &&
                  window.terminal.inlineAds &&
                  window.terminal.inlineAds.enabled &&
                  window.terminal.inlineAds.unitSearch
                ) {
                  const slotName = `${terminal.inlineAds.unitSearch}_${slotNum}`;
                  const adTagContainer = jQuery('<div />')
                    .attr('id', `ad_layers_${slotName}`)
                    .attr('class', 'terminal-card terminal-card-single terminal-alignment-center covered-target');
                  const adTag = jQuery('<div />')
                    .attr('id', adLayersDFP.adUnitPrefix + slotName)
                    .attr('class', 'dfp-ad');
                  adTagContainer.append(adTag);
                  results = `${results} ${adTagContainer}`;
                  slotNum += 1;
                  document.querySelector('.terminal-results').insertAdjacentHTML('beforeend', results);
                  (new AdLayersAPI())
                    .lazyLoadAd({
                      slotName,
                      format: terminal.inlineAds.unitSearch,
                    });
                } else {
                  document.querySelector('.terminal-results').insertAdjacentHTML('beforeend', results);
                }
                if (links.next !== null && values.length !== 0) {
                  addEventListenerOnce(resultMore, 'click', () => {
                    loadSearchURL(links.next);
                  });
                  reveal(resultMore);
                } else {
                  hide(resultMore);
                }
              } else if (
                (
                  links.first.includes(`&q=${currentQuery}&`) ||
                  links.first.endsWith(`&q=${currentQuery}`)
                ) &&
                !links.prev
              ) {
                hide(resultMore);
                results = '<div class="terminal-card terminal-card-no-grow terminal-card-single terminal-no-photo terminal-search-card"><div class="terminal-card-text terminal-limit-max-content-width-add-margin"><h1 class="terminal-headline-font terminal-stream-headline terminal-search-header">No results found.</h1></div></div>';
                document.querySelector('.terminal-results').insertAdjacentHTML('beforeend', results);
              } else {
                hide(resultMore);
              }
            })
            .catch(err => console.error(err));
        }
        const maybeFirstLink = `https://api.parsely.com/v2/search?apikey=${apikey}${params}&q=${query}`;
        if (firstLink !== maybeFirstLink && query !== '') {
          paramsObject = maybeParamsObject;
          firstLink = maybeFirstLink;
          currentQuery = query;
          document.querySelector('.terminal-results').innerHTML = '';
          searchHeader().innerText = `Searching for ${inputArgs[0].value}`;
          searchHeaderParams.innerText = `${[sorting, dateFilteringAfter, dateFilteringBefore].filter(item => item).join(' and ')}`;
          const more = document.querySelectorAll('.terminal-results-more');
          [...more].forEach(node => node.parentNode.removeChild(node));
          document.querySelector('#terminal-search').insertAdjacentHTML('beforeend', `<button id="terminal-current-query-${currentQuery}" class="terminal-results-more terminal-header terminal-header-font terminal-hidden">Load more</div>`);
          loadSearchURL(firstLink);
        } else if (query === '') {
          resetForm();
          if (searchHeader && !isInViewport(searchHeader())) {
            searchHeader().scrollIntoView(false);
          }
        }

        searchFormResetLink.addEventListener(
          'click',
          (e) => {
            e.target.closest('form').reset();
            resetForm();
            document.querySelector('.terminal-search-header').scrollIntoView(false);
            navSearchField.focus();
          },
        );
      });
    } else {
      addClickListener(searchLink, [moreSearch], searchLinkSVG);
    }
  }
}
export default {
  setupMenu,
};
