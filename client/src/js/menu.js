/* eslint-env browser */

export function setupMenu() {
  const moreLink = document.getElementById('nav-bar-inside-more-link-container');
  const moreNav = document.getElementById('nav-bar-inside-more');
  function toggleHoverReveal(element) {
    element.classList.toggle('hover-reveal');
  }
  function toggleHidden(element) {
    element.classList.toggle('hidden');
  }
  function addClickListener(element) {
    element.addEventListener(
      'click',
      (e) => {
        e.preventDefault();
        e.stopImmediatePropagation();
        toggleHidden(moreNav);
      },
      {
        passive: true,
      },
    );
  }
  function addHoverListener(element) {
    element.addEventListener(
      'mouseover',
      () => {
        toggleHoverReveal(moreNav);
        element.addEventListener(
          'mouseout',
          () => {
            toggleHoverReveal(moreNav);
          },
          {
            passive: true,
          },
        );
      },
      {
        passive: true,
      },
    );
  }
  toggleHidden(moreLink);
  addClickListener(moreLink);
  addHoverListener(moreLink);
}

export default {
  setupMenu,
};
