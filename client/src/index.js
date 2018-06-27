/* eslint-env browser */
/* global jQuery, AdLayersAPI, adLayersDFP, terminal, checkPubInterference */

import './index.scss';
import { setupMenu } from './js/menu';
import { setupPopular } from './js/popular';
import { setupScroller } from './js/scroller';
import { setAdLinks } from './js/ads';

require('./js/adblock');
require('./js/smartbanner');

document.addEventListener('DOMContentLoaded', () => {
  function exponentialBackoff(toTry, maxTries, delay, callback, finalCallback = false) {
    let target;
    if (maxTries === target) {
      target = 5;
    } else {
      target = maxTries;
    }
    const result = toTry();
    let max = target;
    if (result) {
      callback(result);
    } else if (max > 0) {
      setTimeout(() => {
        max -= 1;
        exponentialBackoff(toTry, max, delay * 2, callback, finalCallback);
      }, delay);
    } else if (max === 0 && finalCallback) {
      finalCallback();
    }
  }
  setupMenu();
  setupPopular();
  setupScroller();
  const body = document.querySelector('body');

  function addUncovered(element) {
    element.classList.add('uncovered');
  }

  function removeUncovered(element) {
    element.classList.remove('uncovered');
  }

  const coveredUncovered = () => {
    if (
      (!window.terminal.inlineAds.subscribed && !window.terminal.inlineAds.disabled)
    ) {
      window.dataLayer.push({
        event: 'adBlockDetected',
        terminal: {
          adBlockDetected: true,
        },
      });
      addUncovered(body);
      setAdLinks();
    } else {
      removeUncovered(body);
    }
  };

  if (window.AdLayersAPI &&
    window.adLayersDFP &&
    window.jQuery &&
    window.terminal &&
    window.terminal.inlineAds &&
    window.terminal.inlineAds.enabled &&
    window.terminal.inlineAds.unit &&
    !window.terminal.inlineAds.subscribed
  ) {
    let slotNum = 1;
    jQuery(document.body).on('post-load', () => {
      const slotName = `${terminal.inlineAds.unit}_${slotNum}`;
      const infiniteTarget = `.infinite-loader:nth-of-type(${slotNum})`;
      const adTagContainer = jQuery('<div />')
        .attr('id', `ad_layers_${slotName}`)
        .attr('class', 'terminal-sidebar-card terminal-card terminal-card-single terminal-alignment-center covered-target widget_ad_layers_ad_widget');
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
  if (window.terminal.debugMode) {
    checkPubInterference.setOption('debug', true);
  }
  checkPubInterference.onDetected(coveredUncovered);
});
