
import {
  addClickListener,
  evaluateQuerySelector,
  toggleHiddenNoJS,
  toggleInfinite,
} from './utils';

export function setupPopular() {
  function popularCallback() {
    toggleInfinite();
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

