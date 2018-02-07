/* eslint-env browser */

export function setupComments() {
  setTimeout(() => {
    const comments = document
      .getElementById('facebook-comments');
    if (comments) {
      const height = comments
        .contentWindow
        .document.body.scrollHeight;
      document.getElementById('facebook-comments').height =
        height + 100;
    } else {
      console.log('No comments');
    }
  }, 1000);
}

export default {
  setupComments,
};
