/* eslint-env browser */

export function loadInfiniteAds() {
  const { googletag = false } = window;
  const adUnits = document.querySelectorAll('[data-ad-unit]');
  [].forEach.call(adUnits, (unit) => {
    if (unit.dataset.googleQueryId) {
      return;
    }
    if (googletag) {
      // googletag.cmd.push(() => { googletag.display(`dfp-${unit}`); });
    }
  });
}

const utils = {
  loadInfiniteAds,
};

export default utils;
