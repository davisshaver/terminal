/* eslint-env browser */
/* global wp */

document.addEventListener('DOMContentLoaded', () => {
  wp.customize('blogname', (value) => {
    value.bind((newval) => {
      const logo = document.getElementById('logo-image');
      logo.alt = newval;
      const footerTitle = document.getElementById('footer-title');
      footerTitle.innerText = newval;
    });
  });
  wp.customize('header_image', (value) => {
    value.bind((newval) => {
      const logo = document.getElementById('logo-image');
      logo.src = newval;
    });
  });
  wp.customize('header_background_color_setting', (value) => {
    value.bind((newval) => {
      const header = document.getElementById('header');
      header.style['background-color'] = newval;
    });
  });
  wp.customize('footer_background_color_setting', (value) => {
    value.bind((newval) => {
      const footer = document.getElementById('footer');
      footer.style['background-color'] = newval;
    });
  });
});
