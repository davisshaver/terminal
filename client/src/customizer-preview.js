/* eslint-env browser */
/* global wp */

document.addEventListener('DOMContentLoaded', () => {
  const mapping = {
    utility: '.terminal-utility-font',
    headline: '.terminal-headline-font',
    sidebar_header: '.terminal-sidebar-header-font',
    sidebar_body: '.terminal-sidebar-body-font',
    single_meta: '.terminal-single-meta-font',
    body: '.terminal-body-font',
  };
  wp.customize('option_fields', (value) => {
    value.bind((newval) => {
      Object.keys(mapping)
        .forEach((key) => {
          const elements = document.querySelectorAll(mapping[key]);
          [].forEach.call(elements, (element) => {
            element.style['font-size'] = newval[key].size; // eslint-disable-line no-param-reassign
            element.style['font-size'] = newval[key].transform; // eslint-disable-line no-param-reassign
            element.style['font-size'] = newval[key].font; // eslint-disable-line no-param-reassign
          });
        });
    });
  });

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
  wp.customize('content_stories_header', (value) => {
    value.bind((newval) => {
      const topStoriesHeader = document.getElementById('top-stories-header');
      topStoriesHeader.innerText = newval;
    });
  });
});
