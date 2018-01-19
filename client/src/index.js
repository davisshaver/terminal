/* eslint-env browser */
import './index.scss';

document.addEventListener('DOMContentLoaded', () => {
  const { broadstreet = false } = window;
  if (broadstreet) {
    broadstreet.watch({ networkId: 5348 });
  }
});
