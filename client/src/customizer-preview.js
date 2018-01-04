/* eslint-env browser */

const { wp } = window;

wp.customize('blogname', (value) => {
  const logo = document.getElementById('logo-image');
  logo.alt = value;
});

wp.customize('header_image', (value) => {
  const logo = document.getElementById('logo-image');
  logo.src = value;
});
