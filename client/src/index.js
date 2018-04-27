/* eslint-env browser */
/* global jQuery, AdLayersAPI, adLayersDFP, terminal */

import './index.scss';
import { setupMenu } from './js/menu';
import { setupPopular } from './js/popular';
import { setupScroller } from './js/scroller';
import { setAdLinks } from './js/ads';

function scaleAd(ID) {
  const adDiv = jQuery(ID);
  const adIframe = adDiv.children().find('iframe');
  const adFrameContainer = adIframe
    .parent()
    .closest('.terminal-card ');
  const widthRatio = adFrameContainer.innerWidth() / adIframe.innerWidth();
  const heightRatio = adFrameContainer.innerHeight() / adIframe.innerHeight();
  const scale = Math.min(heightRatio, widthRatio);
  if ((adIframe.innerHeight() * widthRatio) <= adFrameContainer.innerHeight() && scale > 1) {
    adDiv.css('transform', `scale(${scale})`);
  } else if ((adIframe.innerWidth() * heightRatio) <= adFrameContainer.innerWidth() && scale > 1) {
    adDiv.css('transform', `scale(${scale})`);
  }
}

function maybeScaleAd(ID) {
  const adDiv = jQuery(ID);
  const adIframe = adDiv.children();
  if (!adIframe) {
    setTimeout(
      () => {
        maybeScaleAd(ID);
      },
      500,
    );
  } else {
    scaleAd(ID);
  }
}

function scaleAllAds() {
  jQuery('div[id^="div-gpt-ad"]')
    .each((index, item) => {
      maybeScaleAd(`#${item.getAttribute('id')}`);
    });
}

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
      (
        !window.terminal.inlineAds.subscribed &&
        (!window.googletag || (window.googletag && !window.googletag.pubAdsReady))
      ) &&
      (!window.terminal.inlineAds.susbcribed &&
      !window.pbjs)
    ) {
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
          coveredUncovered();
          maybeScaleAd(`#${adLayersDFP.adUnitPrefix}${slotName}`);
        },
      );
      slotNum += 1;
    });
  }
  exponentialBackoff(
    () => (window.pbjs) || (window.googletag && window.googletag.pubadsReady),
    5,
    5,
    coveredUncovered,
    coveredUncovered,
  );
  window.addEventListener('resize', () => {
    scaleAllAds();
  });
  jQuery(document.body).on('lazyloaded', () => {
    scaleAllAds();
  });
});
