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
  toggleHiddenNoJS(evaluateQuerySelector('.terminal-popular-container'));
  function popularCallback() {
    open = !open;
    toggleInfinite();
    const popular = evaluateQuerySelector('a[name="popular"]');
    if (open) {
      add(evaluateQuerySelector('body'), 'terminal-viewing-popular');
    } else {
      remove(evaluateQuerySelector('body'), 'terminal-viewing-popular');
    }
    if (!isInViewport(popular)) {
      popular.scrollIntoView();
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

