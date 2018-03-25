/* eslint-env browser */
/* global jQuery, AdLayersAPI, adLayersDFP, terminal */

import './index.scss';
import { setupMenu } from './js/menu';

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
  setTimeout(scaleAllAds, 500);
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
        .attr('id', `ad_layers_${slotName}`)
        .attr('class', 'terminal-sidebar-card terminal-card terminal-card-single terminal-alignment-center covered-target');
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
          maybeScaleAd(`#${adLayersDFP.adUnitPrefix}${slotName}`);
        },
      );
      slotNum += 1;
    });
  }
  const body = document.querySelector('body');


  function toggleUncovered(element) {
    element.classList.toggle('uncovered');
  }
  const coveredUncovered = () => {
    if (window.googletag.pubadsReady) {
      toggleUncovered(body);
    }
  };
  exponentialBackoff(
    () => window.googletag.pubadsReady,
    50,
    50,
    coveredUncovered,
  );
  window.addEventListener('resize', () => {
    scaleAllAds();
  });
  jQuery(document.body).on('lazyloaded', () => {
    scaleAllAds();
  });
});
