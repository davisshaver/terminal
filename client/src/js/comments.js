/* eslint-env browser */

export function setupComments() {
  const height = document
    .getElementById('facebook-comments')
    .contentWindow
    .document.body.scrollHeight;
  document.getElementById('facebook-comments').height =
    height;
}

export default {
  setupComments,
};
