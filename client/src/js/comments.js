/* eslint-env browser */

export function setupComments() {
  const height = document
    .getElementById('facebook-comments')
    .contentWindow
    .document.body.scrollHeight;
  document.getElementById('facebook-comments').height =
    height + 50;
}

export default {
  setupComments,
};