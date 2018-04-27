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
  if (!element) {
    return;
  }
  element.classList.add(string);
}

export function remove(element, string) {
  if (!element) {
    return;
  }
  element.classList.remove(string);
}

export function reveal(element) {
  remove(element, 'terminal-hidden');
}
export function hide(element) {
  add(element, 'terminal-hidden');
}

export function removeOpen(element) {
  if (!element) {
    return;
  }
  add(element, 'terminal-flipped');
}
export function toggleOpen(element) {
  if (!element) {
    return;
  }
  element.classList.toggle('terminal-flipped');
}
export function toggleHiddenNoJS(element) {
  if (!element) {
    return;
  }
  element.classList.toggle('terminal-hidden-no-js');
}
export function toggleHidden(element) {
  if (!element) {
    return;
  }
  element.classList.toggle('terminal-hidden');
}

export function popularInViewport() {
  return isAnyPortionViewable(evaluateQuerySelector('.terminal-popular-container'));
}

export function headerInViewport() {
  return isAnyPortionViewable(evaluateQuerySelector('.terminal-logos'));
}

export function breakoutInViewport() {
  const breakout = evaluateQuerySelector('.terminal-breakout-container');
  return isAnyPortionViewable(breakout);
}

export function contentInViewport() {
  const content = evaluateQuerySelector('.terminal-content-container');
  return isAnyPortionViewable(content);
}

export function topInViewport() {
  const top = evaluateQuerySelector('.terminal-top-container');
  return isAnyPortionViewable(top);
}

export function checkScrolled() {
  if (!headerInViewport()) {
    add(evaluateQuerySelector('body'), 'terminal-scrolled');
  } else {
    remove(evaluateQuerySelector('body'), 'terminal-scrolled');
  }

  if (contentInViewport()) {
    add(evaluateQuerySelector('body'), 'terminal-viewing-content');
  } else {
    remove(evaluateQuerySelector('body'), 'terminal-viewing-content');
  }
  if (!contentInViewport() && popularInViewport()) {
    add(evaluateQuerySelector('body'), 'terminal-viewing-popular');
  } else {
    remove(evaluateQuerySelector('body'), 'terminal-viewing-popular');
  }
  if ((topInViewport() || breakoutInViewport()) && !contentInViewport() && !popularInViewport()) {
    add(evaluateQuerySelector('body'), 'terminal-viewing-featured');
  } else {
    remove(evaluateQuerySelector('body'), 'terminal-viewing-featured');
  }
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
  checkScrolled,
  toggleOpen,
  headerInViewport,
};
