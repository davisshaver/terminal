/* eslint-env browser */
import './single.scss';
import { setupMenu } from './js/menu';
import { setupComments } from './js/comments';

document.addEventListener('load', () => {
  setupMenu();
  setupComments();
});

