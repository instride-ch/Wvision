var config      = require('../../config');
var fs          = require('fs');
var gulp        = require('gulp');
var realFavicon = require('gulp-real-favicon');
var faviconData = 'favicon-manifest.json';

gulp.task('inject', function() {
  return gulp.src('./website/views/layouts/partials/favicon.html')
		.pipe(realFavicon.injectFaviconMarkups(JSON.parse(fs.readFileSync(faviconData)).favicon.html_code))
		.pipe(gulp.dest('./website/views/layouts/partials'));
});
