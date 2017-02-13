var config      = require('../../config');
var fs          = require('fs');
var path        = require('path');
var gulp        = require('gulp');
var realFavicon = require('gulp-real-favicon');
var faviconData = 'favicon-manifest.json';

var paths = {
  src: path.join(config.root.src, config.tasks.favicon.src, '/favicon.png'),
  dest: path.join(config.root.dest, config.tasks.favicon.dest),
};

gulp.task('generate', function (done) {
  realFavicon.generateFavicon({
    masterPicture: paths.src,
    dest: paths.dest,
    iconsPath: paths.dest,
    design: {
      ios: {
        pictureAspect: 'backgroundAndMargin',
        backgroundColor: config.tasks.favicon.ios.background,
        margin: '14%',
        assets: {
          ios6AndPriorIcons: false,
          ios7AndLaterIcons: true,
          precomposedIcons: false,
          declareOnlyDefaultIcon: true,
        },
      },
      desktopBrowser: {},
      windows: {
        pictureAspect: 'whiteSilhouette',
        backgroundColor: config.tasks.favicon.windows.background,
        onConflict: 'override',
        assets: {
          windows80Ie10Tile: false,
          windows10Ie11EdgeTiles: {
           small: false,
           medium: true,
           big: false,
           rectangle: false,
          },
        },
      },
      androidChrome: {
        pictureAspect: 'shadow',
        themeColor: config.tasks.favicon.androidChrome.themeColor,
        manifest: {
          name: config.tasks.favicon.androidChrome.name,
          display: 'standalone',
          orientation: 'notSet',
          onConflict: 'override',
          declared: true,
        },
        assets: {
          legacyIcon: false,
          lowResolutionIcons: false,
        },
      },
      safariPinnedTab: {
        pictureAspect: 'silhouette',
        themeColor: config.tasks.favicon.safariPinnedTab.themeColor,
      },
    },
    settings: {
      scalingAlgorithm: 'Mitchell',
      errorOnImageTooSmall: false,
    },
    markupFile: faviconData,
  }, function () {
    done();
  });
});
