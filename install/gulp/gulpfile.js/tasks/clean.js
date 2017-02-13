var gulp   = require('gulp');
var del    = require('del');
var path   = require('path');
var config = require('../config');

var paths = {
  static: path.join(config.root.dest, '/**'),
  exclude: {
    static: path.join('!', config.root.dest),
    php: path.join('!**/src/**'),
  },
};

var cleanTask = function (cb) {
  del([
      paths.static,
      paths.exclude.static,
  ]).then(function (paths) {
    cb();
  });
};

gulp.task('clean', cleanTask);
module.exports = cleanTask;
