/* eslint-env browser */

export function toggleInfinite() {
  if (!window.infiniteScroll) {
    return;
  }
  if (!window.terminalInfinitePause && window.infiniteScroll.scroller) {
    window.terminalInfinitePause = true;
    window.infiniteScroll.scroller.pause();
  } else if (window.terminalInfinitePause && window.infiniteScroll.scroller) {
    window.terminalInfinitePause = false;
    window.infiniteScroll.scroller.resume();
  }
}
export function isInViewport(element) {
  if (!element) {
    return false;
  }
  const rect = element.getBoundingClientRect();
  const html = document.documentElement;
  return (
    rect.top >= 0 &&
    rect.left >= 0 &&
    rect.bottom <= (window.innerHeight || html.clientHeight) &&
    rect.right <= (window.innerWidth || html.clientWidth)
  );
}

export function isAnyPortionViewable(element) {
  if (!element) {
    return false;
  }
  const rect = element.getBoundingClientRect();
  const windowHeight = (window.innerHeight || document.documentElement.clientHeight);
  const windowWidth = (window.innerWidth || document.documentElement.clientWidth);
  const vertInView = (rect.top <= windowHeight) && ((rect.top + rect.height) >= 0);
  const horInView = (rect.left <= windowWidth) && ((rect.left + rect.width) >= 0);
  return (vertInView && horInView);
}

export function throttle(fn, wait) {
  let time = Date.now();
  return () => {
    const targetTime = time + wait;
    if ((targetTime - Date.now()) < 0) {
      fn();
      time = Date.now();
    }
  };
}

export function evaluateQuerySelectorAll(selector) {
  return document.querySelectorAll(selector);
}

export function evaluateQuerySelector(selector) {
  return document.querySelector(selector);
}

export function addEventListenerOnce(target, type, listener) {
  target.addEventListener(type, function fn(event) {
    target.removeEventListener(type, fn);
    listener(event);
  });
}

export function add(element, string) {
  element.classList.add(string);
}

export function remove(element, string) {
  element.classList.remove(string);
}

export function reveal(element) {
  remove(element, 'terminal-hidden');
}
export function hide(element) {
  add(element, 'terminal-hidden');
}

export function removeOpen(element) {
  add(element, 'terminal-flipped');
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

export function addClickListener(
  listen,
  targets,
  icon = false,
  focus = false,
  callback = false,
  scroll = false,
) {
  listen.addEventListener(
    'click',
    (e) => {
      e.preventDefault();
      e.stopImmediatePropagation();
      if (icon) {
        toggleOpen(icon);
      }
      targets.forEach((target) => {
        if (target) {
          toggleHidden(target);
        }
      });
      if (focus && focus.offsetParent !== null) {
        focus.focus();
      }
      if (callback) {
        callback();
      }
      if (scroll && !isInViewport(scroll)) {
        scroll.scrollIntoView(false);
      }
    },
  );
}

export default {
  add,
  addClickListener,
  addEventListenerOnce,
  evaluateQuerySelector,
  evaluateQuerySelectorAll,
  hide,
  isInViewport,
  isAnyPortionViewable,
  remove,
  reveal,
  toggleHidden,
  toggleHiddenNoJS,
  toggleOpen,
};
