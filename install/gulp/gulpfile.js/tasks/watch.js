var config = require('../config');
var gulp   = require('gulp');
var path   = require('path');
var watch  = require('gulp-watch');

var watchTask = function() {
  var watchableTasks = ['fonts', 'images', 'svgSprite', 'php', 'css', 'emails'];

  watchableTasks.forEach(function(taskName) {
    var task = config.tasks[taskName];
    
    if (task) {
      var glob = path.join(config.root.src, task.src, '**/*.{' + task.extensions.join(',') + '}');

      if (taskName === 'php') {
        glob = path.join(config.root.src, task.src, '**/src/*.{' + task.extensions.join(',') + '}');
      }

      watch(glob, function() {
        require('./' + taskName)();
      });
    }
  });
};

gulp.task('watch', ['browserSync'], watchTask);
module.exports = watchTask;
