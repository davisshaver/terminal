
import {
  evaluateQuerySelector,
  toggleHiddenNoJS,
} from './utils';

export function setupPopular() {
  toggleHiddenNoJS(evaluateQuerySelector('.terminal-nav-bar-inside-popular-link'));
}

export default {
  setupPopular,
};

