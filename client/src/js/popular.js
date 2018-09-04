/* eslint-env browser */

import {
  hide,
  reveal,
  toggleHiddenNoJS,
  evaluateQuerySelector
} from './utils';

export function setupPopular() {
  const popular = evaluateQuerySelector('.terminal-popular-container');
  if (!popular) {
    return;
  }
  toggleHiddenNoJS(popular);
  const select = evaluateQuerySelector('.terminal-popular-select-filter');
  const pastDay = evaluateQuerySelector('.terminal-popular-list-day');
  const pastTwoDays = evaluateQuerySelector('.terminal-popular-list-two-days');
  const pastWeek = evaluateQuerySelector('.terminal-popular-list-week');
  const pastMonth = evaluateQuerySelector('.terminal-popular-list-month');

  select.addEventListener('change', (e) => {
    if (e.target.selectedOptions) {
      const selected = e.target.selectedOptions[0].value;
      switch (selected) {
        case 'past-day':
          reveal(pastDay);
          hide(pastWeek);
          hide(pastMonth);
          hide(pastTwoDays);
          break;
        case 'past-two-days':
          reveal(pastTwoDays);
          hide(pastWeek);
          hide(pastMonth);
          hide(pastDay);
          break;
        case 'past-week':
          reveal(pastWeek);
          hide(pastTwoDays);
          hide(pastDay);
          hide(pastMonth);
          break;
        case 'past-month':
          hide(pastWeek);
          hide(pastTwoDays);
          hide(pastDay);
          reveal(pastMonth);
          break;
        default:
          break;
      }
    }
  });
}

export default {
  setupPopular
};
