var gulp         = require('gulp');
var gulpSequence = require('gulp-sequence');

var faviconTask = function(cb) {
  gulpSequence(
    // 1) Generate the icons
    'generate',
    // 2) Inject the favicon markups in your pages
    'inject',
    // 3) Check for updates on RealFaviconGenerator
    'update',
  cb);
};

gulp.task('favicon', faviconTask);
module.exports = faviconTask;
