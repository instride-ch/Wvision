import './base/uikit';
import fontLoader from './utils/font-loader';
import pageLoader from './utils/page-loader';
import staticAssets from './utils/static-assets';
import glitchImage from './modules/glitch-image';

// Utils
fontLoader();
pageLoader();
staticAssets();

// Modules
glitchImage();
