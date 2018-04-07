/* eslint-env browser */

import {
  addClickListener,
  evaluateQuerySelector,
  toggleHiddenNoJS,
  toggleInfinite,
} from './utils';

function popularCallback() {
  toggleInfinite();
}

export function setupPopular() {
  const parsely = window.terminal.parsely.enabled;
  const apikey = window.terminal.parsely.apiKey;
  const apisecret = window.terminal.parsely.apiSecret;

  if (!parsely || !apikey || !apisecret) {
    return;
  }
  toggleHiddenNoJS(evaluateQuerySelector('.terminal-nav-bar-inside-popular-link'));
  addClickListener(
    evaluateQuerySelector('.terminal-nav-bar-inside-popular-link a'),
    [evaluateQuerySelector('.terminal-container')],
    false,
    false,
    popularCallback,
  );
}

export default {
  setupPopular,
};

