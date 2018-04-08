/* eslint-env browser */
/* global jQuery, AdLayersAPI, adLayersDFP, terminal */

import {
  addClickListener,
  evaluateQuerySelector,
  evaluateQuerySelectorAll,
  addEventListenerOnce,
  reveal,
  hide,
  toggleHiddenNoJS,
  toggleHidden,
  isInViewport,
  throttle,
  removeOpen,
  toggleInfinite,
  addScrolled,
  removeScrolled,
} from './utils';

export function setupMenu() {
  const header = evaluateQuerySelector('#terminal-nav-bar-header');
  const footer = evaluateQuerySelector('.terminal-footer');
  const moreLink = evaluateQuerySelector('.terminal-nav-bar-inside-more-link a');
  const moreLinkContainer = evaluateQuerySelector('.terminal-nav-bar-inside-more-link');
  const moreNav = evaluateQuerySelector('.terminal-nav-bar-inside-more');
  const moreSearch = evaluateQuerySelector('.terminal-nav-bar-inside-search');
  const navSearch = evaluateQuerySelector('.terminal-nav-bar-inside-search');
  const navSearchField = evaluateQuerySelector('.terminal-nav-bar-inside-search .search-field');
  const navSearchFieldTwo = evaluateQuerySelector('.terminal-nav-bar-inside-search .search-field-filter-one');
  const results = () => evaluateQuerySelector('.terminal-results');
  const resultsMoreAll = () => evaluateQuerySelectorAll('.terminal-results-more');
  const resultsMore = () => evaluateQuerySelector('.terminal-results-more');
  const searchContainer = evaluateQuerySelector('.terminal-nav-bar-inside-search-link');
  const searchFormMore = evaluateQuerySelector('.terminal-search-form-more');
  const searchFormMoreLink = evaluateQuerySelector('.terminal-search-form-more-link');
  const searchFormMoreSVG = evaluateQuerySelector('.terminal-search-form-more-link svg');
  const searchFormResetLink = evaluateQuerySelector('.terminal-search-form-reset-link');
  const searchHeader = () => evaluateQuerySelector('.terminal-search-header');
  const searchHeaderParams = evaluateQuerySelector('.terminal-search-header-params');
  const searchLink = evaluateQuerySelector('.terminal-nav-bar-inside-search-link a');
  const searchLinkSVG = evaluateQuerySelector('.terminal-nav-bar-inside-search-link svg');
  const searchTarget = evaluateQuerySelector('#terminal-search');
  const share = evaluateQuerySelector('.essb_bottombar');
  const shareMobile = evaluateQuerySelector('.essb-mobile-sharebottom');
  const svgLink = evaluateQuerySelector('.terminal-nav-bar-inside-more-link svg');
  const widget = evaluateQuerySelector('.widget_search');
  const searches = evaluateQuerySelector('.terminal-example-searches');
  const container = evaluateQuerySelector('.terminal-container');
  const content = evaluateQuerySelector('.terminal-content-container');
  const top = evaluateQuerySelector('.terminal-top-container');
  const breakout = evaluateQuerySelector('.terminal-breakout-container');

  function headerInViewport() {
    return isInViewport(evaluateQuerySelector('.terminal-logos'));
  }

  function checkScrolled() {
    if (!headerInViewport()) {
      addScrolled(evaluateQuerySelector('body'));
    } else {
      removeScrolled(evaluateQuerySelector('body'));
    }
  }
  function resetForm() {
    results.innerHTML = '';
    [...resultsMoreAll()].forEach(node => node.parentNode.removeChild(node));
    searchHeader().innerText = 'Enter a search term for instant results';
    searchHeaderParams.innerText = '';
  }

  function addInputListener(inputContainer, resultsCallback) {
    const inputs = inputContainer.querySelectorAll('input');
    const select = inputContainer.querySelector('select');
    const form = inputContainer.querySelector('form');
    form.addEventListener('submit', e => e.preventDefault());
    const getValues = () => [...inputs, select].map(({ type, value, name }) => ({
      name,
      type,
      value,
    }))
      .filter(input => input.type !== 'submit');
    [...inputs].forEach(input => input.addEventListener('change', (e) => {
      resultsCallback(e, getValues());
    }));
    select.addEventListener('change', (e) => {
      resultsCallback(e, getValues());
    });
  }
  let menuOpen = false;
  const toggleMenuOpen = () => {
    menuOpen = !menuOpen;
  };
  window.addEventListener('scroll', throttle(() => {
    checkScrolled();
  }, 10));

  if (moreLink) {
    toggleHiddenNoJS(moreLinkContainer);
    toggleHiddenNoJS(searchFormMoreLink);
    toggleHiddenNoJS(searchContainer);
    addClickListener(
      moreLink,
      [moreNav],
      svgLink,
      null,
      () => {
        toggleInfinite();
        toggleMenuOpen();
        if (!headerInViewport()) {
          header.scrollIntoView(true);
        }
      },
      null,
    );
    const parsely = window.terminal.parsely.enabled;
    const apikey = window.terminal.parsely.apiKey;
    toggleHidden(widget);
    if (parsely && !window.terminal.isSearch) {
      addClickListener(
        searchLink,
        [moreSearch, searchTarget],
        searchLinkSVG,
        navSearchField,
        () => {
          toggleInfinite();
          checkScrolled();
        },
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
        if (searches) {
          hide(searches);
        }
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
              let searchResults = '';
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
                searchResults = values.reduce((agg, datum) => {
                  const image = datum.image_url ? `<a href="${datum.url}" class="terminal-card-image"><img src="${datum.image_url}" /></a>` : '';
                  const noImageClass = image ? '' : 'terminal-no-photo';
                  return `${agg} <div class="terminal-card terminal-card-single terminal-search-card ${noImageClass}"><div class="terminal-card-title terminal-no-select">${datum.section}</div>${image}<div class="terminal-card-text terminal-limit-max-content-width-add-margin terminal-index-meta-font"><h1 class="terminal-headline-font terminal-stream-headline"><a href="${datum.url}">${datum.title}</a></h1><div class="terminal-byline terminal-index-meta-font terminal-mobile-hide">By ${datum.author}</div></div></div>`;
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
                  searchResults = `${searchResults} ${adTagContainer}`;
                  slotNum += 1;
                  results().insertAdjacentHTML('beforeend', searchResults);
                  (new AdLayersAPI())
                    .lazyLoadAd({
                      slotName,
                      format: terminal.inlineAds.unitSearch,
                    });
                } else {
                  results().insertAdjacentHTML('beforeend', searchResults);
                }
                if (links.next !== null && values.length !== 0) {
                  addEventListenerOnce(resultsMore(), 'click', () => {
                    loadSearchURL(links.next);
                  });
                  reveal(resultsMore());
                } else {
                  hide(resultsMore());
                }
              } else if (
                (
                  links.first.includes(`&q=${currentQuery}&`) ||
                  links.first.endsWith(`&q=${currentQuery}`)
                ) &&
                !links.prev
              ) {
                hide(resultsMore());
                searchResults = '<div class="terminal-card-single terminal-no-photo terminal-search-card"><div class="terminal-card-text terminal-limit-max-content-width-add-margin"><h1 class="terminal-headline-font terminal-stream-headline terminal-search-header">No results found.</h1></div></div>';
                results().insertAdjacentHTML('beforeend', searchResults);
              } else {
                hide(resultsMore());
              }
            })
            .catch(err => console.error(err));
        }
        const maybeFirstLink = `https://api.parsely.com/v2/search?apikey=${apikey}${params}&q=${query}`;
        if (firstLink !== maybeFirstLink && query !== '') {
          paramsObject = maybeParamsObject;
          firstLink = maybeFirstLink;
          currentQuery = query;
          results().innerHTML = '';
          searchHeader().innerText = `Searching for ${inputArgs[0].value}`;
          searchHeaderParams.innerText = `${[sorting, dateFilteringAfter, dateFilteringBefore].filter(item => item).join(' and ')}`;
          const more = document.querySelectorAll('.terminal-results-more');
          [...more].forEach(node => node.parentNode.removeChild(node));
          evaluateQuerySelector('#terminal-search').insertAdjacentHTML('beforeend', `<button id="terminal-current-query-${currentQuery}" class="terminal-results-more terminal-header terminal-header-font terminal-hidden">Load more</div>`);
          loadSearchURL(firstLink);
        } else if (query === '') {
          resetForm();
        }
        searchFormResetLink.addEventListener(
          'click',
          (e) => {
            e.target.closest('form').reset();
            resetForm();
            if (searches) {
              reveal(searches);
            }
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
