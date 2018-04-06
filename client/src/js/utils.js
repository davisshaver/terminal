/* eslint-env browser */

export function isInViewport(element) {
  const rect = element.getBoundingClientRect();
  const html = document.documentElement;
  return (
    rect.top >= 0 &&
    rect.left >= 0 &&
    rect.bottom <= (window.innerHeight || html.clientHeight) &&
    rect.right <= (window.innerWidth || html.clientWidth)
  );
}

export function evaluateQuerySelectorAll(selector) {
  return document.querySelectorAll(selector);
}

export function evaluateQuerySelector(selector) {
  return document.querySelector(selector);
}

export function addEventListenerOnce(target, type, listener) {
  target.addEventListener(type, function fn(event) {
    event.preventDefault();
    event.stopImmediatePropagation();
    target.removeEventListener(type, fn);
    listener(event);
  });
}

export function reveal(element) {
  element.classList.remove('terminal-hidden');
}
export function hide(element) {
  element.classList.add('terminal-hidden');
}
export function toggleOpen(element) {
  element.classList.toggle('terminal-flipped');
}
export function toggleHiddenNoJS(element) {
  element.classList.toggle('terminal-hidden-no-js');
}
export function toggleHidden(element) {
  element.classList.toggle('terminal-hidden');
}

export default {
  isInViewport,
  evaluateQuerySelector,
  evaluateQuerySelectorAll,
  addEventListenerOnce,
  reveal,
  hide,
  toggleOpen,
  toggleHiddenNoJS,
  toggleHidden,
};
