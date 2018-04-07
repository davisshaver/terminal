/* eslint-env browser */

import {
  addClickListener,
  evaluateQuerySelector,
  hide,
  isInViewport,
  reveal,
  toggleInfinite,
} from './utils';


export function setupPopular() {
  const parsely = window.terminal.parsely.enabled;
  const apikey = window.terminal.parsely.apiKey;
  const apisecret = window.terminal.parsely.apiSecret;

  let open = false;

  function popularCallback() {
    open = !open;
    toggleInfinite();
    const popular = evaluateQuerySelector('a[name="popular"]');
    if (!isInViewport(popular)) {
      popular.scrollIntoView();
    }
  }
  function recentCallback() {
    const recent = evaluateQuerySelector('a[name="recent"]');
    if (open) {
      hide(evaluateQuerySelector('.terminal-popular-container'));
      reveal(evaluateQuerySelector('.terminal-content-container'));
      toggleInfinite();
      if (!isInViewport(recent)) {
        recent.scrollIntoView();
      }
      open = !open;
    } else if (!isInViewport(recent)) {
      recent.scrollIntoView(true);
    }
  }

  if (!parsely || !apikey || !apisecret) {
    return;
  }
  addClickListener(
    evaluateQuerySelector('.terminal-nav-bar-inside-popular-link a'),
    [evaluateQuerySelector('.terminal-content-container'), evaluateQuerySelector('.terminal-popular-container')],
    false,
    false,
    popularCallback,
  );
  addClickListener(
    evaluateQuerySelector('.terminal-nav-bar-recent'),
    [],
    false,
    false,
    recentCallback,
  );
}

export default {
  setupPopular,
};

