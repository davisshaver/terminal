/* eslint-env browser */
/* global wp */

document.addEventListener('DOMContentLoaded', () => {
  const mapping = {
    utility: '.terminal-utility-font',
    headline: '.terminal-headline-font',
    sidebar_header: '.terminal-sidebar-header-font',
    sidebar_body: '.terminal-sidebar-body-font',
    single_meta: '.terminal-single-meta-font',
    index_meta: '.terminal-index-meta-font',
    body: '.terminal-body-font',
    tagline: '.terminal-cta-tagline-font',
    cta_button: '.terminal-cta-button-font',
    loop_header: '.terminal-loop-header-font',
  };
  wp.customize('content_stories_header', (value) => {
    value.bind((newval) => {
      if (!document.getElementsByTagName('body')[0].className.match(/home/)) {
        return;
      }
      let topStoriesHeader = document.getElementById('index-header');
      if (!topStoriesHeader) {
        const content = document.getElementsByClassName('content');
        const stories = document.getElementById('stories');
        if (!content || !stories) {
          return;
        }
        topStoriesHeader = document.createElement('h2');
        topStoriesHeader.id = 'index-header';
        content[0].insertBefore(topStoriesHeader, stories);
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
              element.style['font-size'] = 'unset'; // eslint-disable-line no-param-reassign
            }
            if (newval[`${key}_transform`] !== 'default') {
              element.style['text-transform'] = newval[`${key}_transform`]; // eslint-disable-line no-param-reassign
            } else {
              element.style['text-transform'] = 'unset'; // eslint-disable-line no-param-reassign
            }
            if (newval[`${key}_font`] !== 'default') {
              element.style['font-family'] = newval[`${key}_font`]; // eslint-disable-line no-param-reassign
            } else {
              element.style['font-family'] = 'initial'; // eslint-disable-line no-param-reassign
            }
            if (newval[`${key}_weight`] !== 'default') {
              element.style['font-weight'] = newval[`${key}_weight`]; // eslint-disable-line no-param-reassign
            } else {
              element.style['font-weight'] = null; // eslint-disable-line no-param-reassign
            }
            if (newval[`${key}_style`] !== 'default') {
              element.style['font-style'] = newval[`${key}_style`]; // eslint-disable-line no-param-reassign
            } else {
              element.style['font-style'] = 'initial'; // eslint-disable-line no-param-reassign
            }
            if (newval[`${key}_color`] !== '') {
              element.style.color = newval[`${key}_color`]; // eslint-disable-line no-param-reassign
            } else {
              element.style.color = 'initial'; // eslint-disable-line no-param-reassign
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
      if (!newval) {
        header.style['background-color'] = 'unset';
      }
      header.style['background-color'] = newval;
    });
  });
  wp.customize('header_ad_background_color_setting', (value) => {
    value.bind((newval) => {
      const header = document.getElementById('header-leaderboard');
      if (!newval) {
        header.style['background-color'] = 'unset';
      }
      header.style['background-color'] = newval;
    });
  });
  wp.customize('footer_background_color_setting', (value) => {
    value.bind((newval) => {
      const footer = document.getElementById('footer');
      if (!newval) {
        footer.style['background-color'] = 'unset';
      }
      footer.style['background-color'] = newval;
    });
  });
  wp.customize('footer_accent_color_setting', (value) => {
    value.bind((newval) => {
      const footer = document.getElementById('footer');
      footer.style['border-bottom'] = `1px solid ${newval}`;
    });
  });
  wp.customize('footer_ad_background_color_setting', (value) => {
    value.bind((newval) => {
      const footer = document.getElementById('footer-leaderboard');
      if (!newval) {
        footer.style['background-color'] = 'unset';
      }
      footer.style['background-color'] = newval;
    });
  });
  wp.customize('sidebar_section_background_color_setting', (value) => {
    value.bind((newval) => {
      const elements = document.querySelectorAll('.sidebar-section');
      [].forEach.call(elements, (element) => {
        if (!newval) {
          element.style['background-color'] = 'unset'; // eslint-disable-line no-param-reassign
        }
        element.style['background-color'] = newval; // eslint-disable-line no-param-reassign
      });
    });
  });
  wp.customize('loop_header_background_color_setting', (value) => {
    value.bind((newval) => {
      const elements = document.querySelectorAll('.loop-header');
      [].forEach.call(elements, (element) => {
        if (!newval) {
          element.style['background-color'] = 'unset'; // eslint-disable-line no-param-reassign
        }
        element.style['background-color'] = newval; // eslint-disable-line no-param-reassign
      });
    });
  });
  wp.customize('byline_background_color_setting', (value) => {
    value.bind((newval) => {
      const elements = document.querySelectorAll('.topbar');
      [].forEach.call(elements, (element) => {
        if (!newval) {
          element.style['background-color'] = 'unset'; // eslint-disable-line no-param-reassign
        }
        element.style['background-color'] = newval; // eslint-disable-line no-param-reassign
      });
    });
  });
});
