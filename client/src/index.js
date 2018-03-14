/* eslint-env browser */
/* global jQuery, AdLayersAPI, adLayersDFP, terminal */

import './index.scss';
import { setupMenu } from './js/menu';

document.addEventListener('DOMContentLoaded', () => {

  function exponentialBackoff(toTry, maxTries = 5, delay, callback) {
    const result = toTry();
    let max = maxTries || 10;
    if (result) {
      callback(result);
    } else if (max > 0) {
      setTimeout(() => {
        max -= 1;
        exponentialBackoff(toTry, max, delay * 2, callback);
      }, delay);
    } else {
      console.log('we give up');
    }
  }

  setupMenu();
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
      const slotName = `${terminal.inlineAds.unit}_${slotNum}`;
      const infiniteTarget = `.infinite-loader:nth-of-type(${slotNum})`;
      const adTagContainer = jQuery('<div />')
        .attr('class', 'terminal-sidebar-card terminal-card terminal-card-single terminal-alignment-center');
      const adTag = jQuery('<div />')
        .attr('id', adLayersDFP.adUnitPrefix + slotName)
        .attr('class', 'dfp-ad');
      adTagContainer.append(adTag);
      exponentialBackoff(
        (thisSlotName = `${slotName}`, thisTag = adTagContainer, thisTarget = infiniteTarget) => {
          if (jQuery(thisTarget).length !== 0) {
            return [thisSlotName, thisTag, thisTarget];
          }
          return false;
        },
        10,
        500,
        ([thisSlotName, thisTag, thisTarget]) => {
          jQuery(thisTarget).after(thisTag);
          (new AdLayersAPI())
            .lazyLoadAd({
              slotName: thisSlotName,
              format: terminal.inlineAds.unit,
            });
        },
      );
      slotNum += 1;
    });
  }

});
