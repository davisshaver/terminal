/* eslint-env browser */

import {
  addClickListener,
  evaluateQuerySelector,
  isInViewport,
  toggleHiddenNoJS,
  toggleInfinite,
  add,
  remove,
} from './utils';

export function setupPopular() {
  const parsely = window.terminal.parsely.enabled;
  const apikey = window.terminal.parsely.apiKey;
  const apisecret = window.terminal.parsely.apiSecret;

  let open = false;
  const popular = evaluateQuerySelector('.terminal-popular-container');
  if (!popular) {
    return;
  }
  toggleHiddenNoJS(evaluateQuerySelector('.terminal-popular-container'));
  function popularCallback() {
    open = !open;
    toggleInfinite();
    const popularLink = evaluateQuerySelector('a[name="popular"]');
    if (open) {
      add(evaluateQuerySelector('body'), 'terminal-viewing-popular');
    } else {
      remove(evaluateQuerySelector('body'), 'terminal-viewing-popular');
    }
    if (!isInViewport(popularLink)) {
      popularLink.scrollIntoView();
    }
  }

  if (!parsely || !apikey || !apisecret) {
    return;
  }
  addClickListener(
    evaluateQuerySelector('.terminal-popular-link'),
    [],
    false,
    false,
    popularCallback,
  );
}

export default {
  setupPopular,
};

