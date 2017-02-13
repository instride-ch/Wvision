var config    = '../config.json';
var gulp      = require('gulp');
var jeditor   = require('gulp-json-editor');
var argv      = require('yargs').argv;

var configTask = function () {
  var module = argv.module ? argv.module : 'website';

  return gulp.src(config)
    .pipe(jeditor(function (json) {
      if (module === 'website') {
        json.root.src = './' + module;
      } else {
        json.root.src = './modules/' + module;
      }

      json.root.dest = './static/' + module;

      return json;
    }))
    .pipe(gulp.dest('./gulpfile.js'));
};

gulp.task('config', configTask);
module.exports = configTask;
