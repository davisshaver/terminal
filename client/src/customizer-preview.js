/* eslint-env browser */
/* global wp */

document.addEventListener('DOMContentLoaded', () => {
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
  wp.customize('post_page_background_color_setting', (value) => {
    value.bind((newval) => {
      const elements = document.querySelectorAll('.post');
      [].forEach.call(elements, (element) => {
        if (!newval) {
          element.style['background-color'] = 'unset'; // eslint-disable-line no-param-reassign
        }
        element.style['background-color'] = newval; // eslint-disable-line no-param-reassign
      });
      const pages = document.querySelectorAll('.page');
      [].forEach.call(pages, (element) => {
        if (!newval) {
          element.style['background-color'] = 'unset'; // eslint-disable-line no-param-reassign
        }
        element.style['background-color'] = newval; // eslint-disable-line no-param-reassign
      });
    });
  });
  wp.customize('featured_section_background_color_setting', (value) => {
    value.bind((newval) => {
      const elements = document.querySelectorAll('.featured-section');
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
