var fs          = require('fs');
var gulp        = require('gulp');
var realFavicon = require('gulp-real-favicon');
var faviconData = 'favicon-manifest.json';

gulp.task('update', function (cb) {
  var currentVersion = JSON.parse(fs.readFileSync(faviconData)).version;

  realFavicon.checkForUpdates(currentVersion, function (err) {
    if (err) throw err;
    cb();
  });
});
