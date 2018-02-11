/* eslint-env browser */

export function setupComments() {
  const svgLink = document.querySelector('#facebook-comments-header svg');
  const facebookComments = document.getElementById('facebook-comments');
  const facebookCommentsHeader = document.getElementById('facebook-comments-header');

  function toggleOpen(element) {
    element.classList.toggle('open');
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
        toggleOpen(svgLink);
        toggleHidden(facebookComments);
      },
    );
  }
  if (facebookComments) {
    const height = facebookComments
      .contentWindow
      .document.body.scrollHeight;
    document.getElementById('facebook-comments').height =
      height + 200;
    toggleHidden(facebookComments);
    addClickListener(facebookCommentsHeader);
  } else {
    console.log('No facebookComments');
  }
}

export default {
  setupComments,
};
