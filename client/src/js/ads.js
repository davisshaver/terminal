/* eslint-env browser */

export function setAdLinks() {
  [...document.querySelectorAll('.covered-target.widget_ad_layers_ad_widget')]
    .forEach((element) => {
      if (window.terminal.inlineAds.adblockLink) {
        element.addEventListener('click', () => {
          window.location.href = window.terminal.inlineAds.adblockLink;
        });
        element.setAttribute('style', 'cursor: pointer');
      }
    });
  [...document.querySelectorAll('.terminal-adblock-notice')]
    .forEach((element) => {
      if (window.terminal.inlineAds.adblockLink) {
        element.addEventListener('click', () => {
          window.dataLayer.push({
            event: 'AdBlockBlockClick',
            terminal: {
              adBlockLink: window.terminal.inlineAds.adblockLink
            },
          });
          window.location.href = window.terminal.inlineAds.adblockLink;
        });
        element.setAttribute('style', 'cursor: pointer');
      }
    });
  [...document.querySelectorAll('.terminal-adblock-subscribed')]
    .forEach((element) => {
      if (window.terminal.inlineAds.adblockLink) {
        element.addEventListener('click', () => {
          // removeUncovered(body);
          document.querySelector('body').classList.remove('uncovered');
        });
        element.setAttribute('style', 'cursor: pointer');
      }
    });
}

export default {
  setAdLinks,
};
