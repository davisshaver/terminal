/* eslint-env browser */
/* global jQuery, AdLayersAPI, adLayersDFP, terminal */

import './index.scss';
import { setupMenu } from './js/menu';

document.addEventListener('DOMContentLoaded', () => {
  setupMenu();
});

if (window.AdLayersAPI &&
  window.adLayersDFP &&
  window.jQuery &&
  window.terminal &&
  window.terminal.inlineAds &&
  window.terminal.inlineAds.enabled &&
  window.terminal.inlineAds.unit
) {
  let slotNum = 1;
  jQuery(document.body).on('post-load', () => {
    setTimeout(() => {
      const slotName = `${terminal.inlineAds.unit}_${slotNum}`;
      const infiniteTarget = `#infinite-view-${slotNum}`;
      const adTag = jQuery('<div />')
        .attr('id', adLayersDFP.adUnitPrefix + slotName)
        .attr('class', 'dfp-ad');
      if (jQuery(infiniteTarget).length !== 0) {
        jQuery(infiniteTarget).after(adTag);
        (new AdLayersAPI()).lazyLoadAd({ slotName, format: terminal.inlineAds.unit });
      } else {
        setTimeout(() => {
          if (jQuery(infiniteTarget).length !== 0) {
            jQuery(infiniteTarget).after(adTag);
            (new AdLayersAPI()).lazyLoadAd({ slotName, format: terminal.inlineAds.unit });
          } else {
            setTimeout(() => {
              if (jQuery(infiniteTarget).length !== 0) {
                jQuery(infiniteTarget).after(adTag);
                (new AdLayersAPI()).lazyLoadAd({ slotName, format: terminal.inlineAds.unit });
              }
            }, 1500);
          }
        }, 1000);
      }
      slotNum += 1;
    }, 500);
  });
}
