/* eslint-env browser */
/* global jQuery, AdLayersAPI, adLayersDFP, terminal */

import {
  addClickListener,
  evaluateQuerySelector,
  toggleHiddenNoJS,
  toggleHidden,
  throttle,
  toggleInfinite,
  checkScrolled,
  headerInViewport
} from './utils';

export function setupMenu() {
  const header = evaluateQuerySelector('#terminal-nav-bar-header');
  const filter = evaluateQuerySelector('.terminal-filter');
  const featured = evaluateQuerySelector('a[name="featured"]');
  const popular = evaluateQuerySelector('a[name="popular"]');
  const recent = evaluateQuerySelector('a[name="recent"]');
  const moreLink = evaluateQuerySelector('.terminal-nav-bar-inside-more-link a');
  const moreLinkContainer = evaluateQuerySelector('.terminal-nav-bar-inside-more-link');
  const moreNav = evaluateQuerySelector('.terminal-nav-bar-inside-more');
  const moreSearch = evaluateQuerySelector('.terminal-nav-bar-inside-search');
  const searchContainer = evaluateQuerySelector('.terminal-nav-bar-inside-search-link');
  const searchLink = evaluateQuerySelector('.terminal-nav-bar-inside-search-link a');
  const searchLinkSVG = evaluateQuerySelector('.terminal-nav-bar-inside-search-link svg');
  const svgLink = evaluateQuerySelector('.terminal-nav-bar-inside-more-link svg');
  const widget = evaluateQuerySelector('.widget_search');

  let menuOpen = false;
  const toggleMenuOpen = () => {
    menuOpen = !menuOpen;
  };
  window.addEventListener('scroll', throttle(() => {
    checkScrolled();
  }, 10));
  if (featured && popular && recent) {
    toggleHidden(filter);
  }
  if (moreLink) {
    toggleHiddenNoJS(moreLinkContainer);
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
      null
    );
    toggleHidden(widget);
    addClickListener(searchLink, [moreSearch], searchLinkSVG);
  }
}
export default {
  setupMenu,
  checkScrolled
};
