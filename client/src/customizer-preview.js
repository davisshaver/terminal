/* eslint-env browser */
/* global wp */

document.addEventListener('DOMContentLoaded', () => {
  wp.customize('blogname', (value) => {
    value.bind((newval) => {
      const logo = document.getElementById('logo-image');
      logo.alt = newval;
    });
  });
  wp.customize('header_image', (value) => {
    value.bind((newval) => {
      const logo = document.getElementById('logo-image');
      logo.src = newval;
    });
  });
});
