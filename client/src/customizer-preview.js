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
  console.log('hello');
  wp.customize('content_stories_header', (value) => {
    value.bind((newval) => {
      let topStoriesHeader = document.getElementById('top-stories-header');
      if (!topStoriesHeader) {
        const content = document.getElementById('content');
        topStoriesHeader = document.createElement('h2');
        topStoriesHeader.id = 'top-stories-header';
        content.insertBefore(topStoriesHeader, content.firstChild);
      }
      if (!newval) {
        topStoriesHeader.style.display = 'none';
      } else {
        topStoriesHeader.innerText = newval;
        topStoriesHeader.style.display = 'block';
      }
    });
  });
  wp.customize('typography', (value) => {
    value.bind((newval) => {
      Object.keys(mapping)
        .forEach((key) => {
          const elements = document.querySelectorAll(mapping[key]);
          [].forEach.call(elements, (element) => {
            if (newval[`${key}_size`] !== 'default') {
              element.style['font-size'] = newval[`${key}_size`]; // eslint-disable-line no-param-reassign
            } else {
              element.style['font-size'] = null; // eslint-disable-line no-param-reassign
            }
            if (newval[`${key}_transform`] !== 'default') {
              element.style['text-transform'] = newval[`${key}_transform`]; // eslint-disable-line no-param-reassign
            } else {
              element.style['text-transform'] = null; // eslint-disable-line no-param-reassign
            }
            if (newval[`${key}_font`] !== 'default') {
              element.style['font-family'] = newval[`${key}_font`]; // eslint-disable-line no-param-reassign
            } else {
              element.style['font-family'] = 'initial'; // eslint-disable-line no-param-reassign
            }
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
});
