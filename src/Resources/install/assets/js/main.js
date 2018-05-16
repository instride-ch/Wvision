import './base/uikit';
import { loadFonts, loadImages } from './utils';
import pageLoader from './modules/page-loader';
import glitchImage from './modules/glitch-image';

// Utils
loadFonts();
loadImages();

// Modules
pageLoader();
glitchImage();
