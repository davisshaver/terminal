/* eslint-env browser */

export function setupMenu() {
  const moreLinkContainer = document.querySelector('.terminal-nav-bar-inside-more-link');
  const moreLink = document.querySelector('.terminal-nav-bar-inside-more-link a');
  const moreNav = document.querySelector('.terminal-nav-bar-inside-more');
  const container = document.querySelector('.terminal-container');
  const footer = document.querySelector('.terminal-footer');
  const header = document.querySelector('.terminal-header-container');
  const svgLink = document.querySelector('.terminal-nav-bar-inside-more-link svg');
  function toggleOpen(element) {
    element.classList.toggle('terminal-flipped');
  }
  function toggleShow(element) {
    element.classList.toggle('terminal-show');
  }
  function toggleHiddenNoJS(element) {
    element.classList.toggle('terminal-hidden-no-js');
  }
  function toggleHidden(element) {
    element.classList.toggle('terminal-hidden');
  }
  function addClickListener(element) {
    element.addEventListener(
      'click',
      (e) => {
        e.preventDefault();
        e.stopImmediatePropagation();
        toggleOpen(svgLink);
        toggleHidden(moreNav);
        toggleHidden(container);
        toggleShow(footer);
      },
    );
  }
  if (moreLink) {
    toggleHiddenNoJS(moreLinkContainer);
    addClickListener(moreLink);
  }
}

export default {
  setupMenu,
};
