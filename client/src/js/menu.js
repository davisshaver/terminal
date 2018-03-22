/* eslint-env browser */

export function setupMenu() {
  const moreLinkContainer = document.querySelector('.terminal-nav-bar-inside-more-link');
  const moreLink = document.querySelector('.terminal-nav-bar-inside-more-link a');
  const moreNav = document.querySelector('.terminal-nav-bar-inside-more');
  const footer = document.querySelector('.terminal-footer');
  const share = document.querySelector('.essb_bottombar');
  const svgLink = document.querySelector('.terminal-nav-bar-inside-more-link svg');
  const header = document.querySelector('.terminal-header-container');

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
        toggleHidden(header);
        if (share) {
          toggleHidden(share);
        }
        if (footer) {
          toggleHidden(footer);
        }
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
