/* eslint-env browser */

export function setupMenu() {
  const moreLink = document.getElementById('nav-bar-inside-more-link-container');
  const moreNav = document.getElementById('nav-bar-inside-more');
  const container = document.getElementById('container');
  const footer = document.getElementById('footer');

  function toggleFixed(element) {
    element.classList.toggle('fixed');
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
        toggleHidden(container);
        toggleFixed(footer);
      },
    );
  }
  toggleHidden(moreLink);
  addClickListener(moreLink);
}

export default {
  setupMenu,
};
