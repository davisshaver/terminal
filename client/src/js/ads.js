/* eslint-env browser */
import Mcsub from './mcsub';
import Cookies from 'js-cookie';
import { Toast } from 'toaster-js';

export const isCovered = () => Cookies.get('terminal-opt-out');

export const setOptOutCookie = () => {
  Cookies.set('terminal-opt-out', true, { expires: 7 });
};

export const removeUncovered = () => {
  document.querySelector('body').classList.remove('uncovered');
};

export function setupMailchimp() {
  new Mcsub('#terminal-adblock-signup', { // eslint-disable-line no-new
      user: window.terminal.mailchimpUser,
      list: window.terminal.mailchimpList,
      reponse: '#terminal-mailchimp-response',
      onInit() {
          console.log('Init Sucess'); // Example
      },
      onSubmit() {
          console.log('Submit Sucess'); // Example
      },
      onSucess() {
          console.log('Subscribe Sucess'); // Example
      },
      onError() {
          console.log('Subscribe Error'); // Example
      },
      complete() {
          if (this.response && this.response.result && this.response.result === 'error') {
            window.dataLayer.push({
              event: 'AdBlockMailChimpError',
              terminal: {
                error: this.response.message
              }
            });
            new Toast('Error with Mailchimp. Go ahead...'); // eslint-disable-line no-new
            removeUncovered();
            setOptOutCookie();
          }
      }
  });
}

export function setAdLinks() {
  [...document.querySelectorAll('.covered-target.widget_ad_layers_ad_widget')]
    .forEach((element) => (element.style.display = 'none'));

  [...document.querySelectorAll('.terminal-adblock-notice')]
    .forEach((element) => {
      if (window.terminal.inlineAds.adblockLink) {
        element.addEventListener('click', (event) => {
          event.preventDefault();
          window.dataLayer.push({
            event: 'AdBlockBlockClick',
            terminal: {
              adBlockLink: window.terminal.inlineAds.adblockLink
            }
          });
          window.location.href = window.terminal.inlineAds.adblockLink;
        });
        element.setAttribute('style', 'cursor: pointer');
      }
    });
    // Easy out.
  [...document.querySelectorAll('.terminal-adblock-subscribed')]
    .forEach((element) => {
      if (window.terminal.inlineAds.adblockLink) {
        element.addEventListener('click', () => {
          new Toast('We\'ll try not to both you for a while.'); // eslint-disable-line no-new
          removeUncovered();
        });
      }
    });
}

export default {
  setAdLinks,
  setupMailchimp,
  setOptOutCookie,
  removeUncovered,
  isCovered
};
