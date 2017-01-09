// Requis
var gulp = require('gulp');

//Include (plugins)
var del = require('del');
var jsmin = require('gulp-jsmin');
var rename = require('gulp-rename');

// Variables de chemins
var source = './src'; // dossier de travail
var destination = '../public'; // dossier à livrer

gulp.task('js', function () {
  return gulp.src(source+'/js/**')
    .pipe(jsmin())
    .pipe(rename({suffix: '.min'}))
    .pipe(gulp.dest(destination+'/js'));
});

gulp.task('default', ['clean'], function() {
   gulp.start('js');
});

gulp.task('clean', function() {
   return del([destination], {force: true});
});