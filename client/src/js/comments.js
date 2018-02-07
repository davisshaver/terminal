/* eslint-env browser */

export function setupComments() {
  const comments = document
    .getElementById('facebook-comments');
  if (comments) {
    const height = comments
      .contentWindow
      .document.body.scrollHeight;
    document.getElementById('facebook-comments').height =
      height + 200;
  } else {
    console.log('No comments');
  }
}

export default {
  setupComments,
};
