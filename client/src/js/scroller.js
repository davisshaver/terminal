/* eslint-env browser */

export function setupScroller() {
  const left = document.querySelector('.terminal-breakout-container .terminal-arrow-left');
  const right = document.querySelector('.terminal-breakout-container .terminal-arrow-right');
  const last = document.querySelector('.terminal-breakout-container .terminal-card-featured:last-child');
  const first = document.querySelector('.terminal-breakout-container .terminal-card-featured:first-child');

  if (right && last) {
    right.addEventListener('click', () => {
      last.scrollIntoView({ behavior: 'smooth' });
    }, false);
  }
  if (left && first) {
    left.addEventListener('click', () => {
      first.scrollIntoView({ behavior: 'smooth' });
    }, false);
  }
}

export default {
  setupScroller,
};
