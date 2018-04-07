/* eslint-env browser */

import {
  addClickListener,
  evaluateQuerySelector,
  hide,
  isInViewport,
  reveal,
  toggleHiddenNoJS,
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
  }
  function recentCallback() {
    const recent = evaluateQuerySelector('a[name="recent"]');
    if (open) {
      hide(evaluateQuerySelector('.terminal-popular-container'));
      reveal(evaluateQuerySelector('.terminal-content-container'));
      toggleInfinite();
      if (!isInViewport(recent)) {
        recent.scrollIntoView(true);
      }
      open = !open;
    } else if (!isInViewport(recent)) {
      recent.scrollIntoView(true);
    }
  }

  if (!parsely || !apikey || !apisecret) {
    return;
  }
  toggleHiddenNoJS(evaluateQuerySelector('.terminal-nav-bar-inside-popular-link'));
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

