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
    addClickListener(searchLink, [moreSearch, searchTarget], searchLinkSVG);
    toggleHidden(widget);
    if (!window.terminal.isSearch) {
      addInputListener(navSearch, (event, inputArgs) => {
        const results = ['one', 'two'];
        searchTarget.innerHTML = `<div class="terminal-header terminal-header-font"><h2>Searching for ${inputArgs[0].value}</h2></div>${results.map((result) => `${result}`)}`;
      });
    }
  }
}

export default {
  setupMenu,
};
