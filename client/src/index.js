/* eslint-env browser */
/* global jQuery */

import './index.scss';
import { loadInfiniteAds } from './js/utils';

document.addEventListener('DOMContentLoaded', () => {
  const { broadstreet = false } = window;
  if (broadstreet) {
    broadstreet.watch({ networkId: 5348 });
  }
});


(($) => {
  $(document.body).on('post-load', () => {
    loadInfiniteAds();
  });
})(jQuery);
