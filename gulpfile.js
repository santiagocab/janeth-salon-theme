const gulp = require('gulp');
const sass = require('gulp-sass')(require('sass'));
const cleanCSS = require('gulp-clean-css');
const rename = require('gulp-rename');

const scssSrc = 'assets/scss/*.scss';
const cssDest = 'dist/css/';

gulp.task('scss', function () {
  return gulp.src(scssSrc)
    .pipe(sass().on('error', sass.logError))
    .pipe(cleanCSS())
    .pipe(rename({
      suffix: '.min',
      extname: '.css'
    }))
    .pipe(gulp.dest(cssDest));
});

gulp.task('default', gulp.series('scss'));