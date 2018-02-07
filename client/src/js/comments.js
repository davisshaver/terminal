/* eslint-env browser */

export function setupComments() {
  const comments = document
    .getElementById('facebook-comments');
  if (!comments) {
    return;
  }
  const height = comments
    .contentWindow
    .document.body.scrollHeight;
  document.getElementById('facebook-comments').height =
    height + 100;
}

export default {
  setupComments,
};
