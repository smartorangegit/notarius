
var gulp = require('gulp'),
    sass = require('gulp-sass'),
    browserSync = require('browser-sync'),
    useref = require('gulp-useref'),
    concatJS = require('gulp-concat'),
    cleanCSS = require('gulp-clean-css'),
    concatCSS = require('gulp-concat-css'),
    imagemin = require('gulp-imagemin'),
    autoprefixer = require('gulp-autoprefixer'),
    uglify = require('gulp-uglify');


// SASS
gulp.task('sass', function(){
  return gulp.src('app/sass/style.scss')
    .pipe(sass())
    .pipe(gulp.dest('app/css'))
    .pipe(browserSync.reload({
        stream: true
      }))
});
// SASS


// browserSynk
gulp.task('browserSync', function(){
  browserSync({
    server: {
      baseDir: 'app'
    }
  })
});
// browserSynk


// CSS
gulp.task('css', function() {
  return gulp.src('app/css/*.css')
    .pipe(autoprefixer({
            browsers: ['last 10 versions'],
            cascade: true
        }))
    .pipe(cleanCSS({compatibility: 'ie8'}))
    .pipe(concatCSS("style.css"))
    .pipe(gulp.dest('dist/css'));
});
// CSS


// JS
gulp.task('js', function () {
    return gulp.src(['app/js/libraries/datapicker/picker.js',
     'app/js/libraries/datapicker/picker.date.js', 
     'app/js/libraries/datapicker/picker.time.js',
     'app/js/libraries/datapicker/ru_RU.js',
     'app/js/libraries/slick.min.js', 
     'app/js/libraries/jquery.maskedinput.min.js', 
     'app/js/shares.js',
     'app/js/card.js',
     'app/js/client-page.js',
     'app/js/notary-page.js',
     'app/js/filter.js',
     'app/js/pagination.js',
     'app/js/main.js'])
    .pipe(concatJS('all.js'))
    .pipe(gulp.dest('dist/js'))
});
// JS


// image
gulp.task('image', function(){ 
    gulp.src('app/img/*')
        .pipe(imagemin())
        .pipe(gulp.dest('dist/img'))
});
// image


gulp.task('watch',['browserSync', 'sass', 'css', 'js'], function(){
  gulp.watch('app/sass/**/*.scss', ['sass']);
  gulp.watch('app/css/*.css', ['css']);
  gulp.watch('app/js/*.js', ['js']);
  gulp.watch('app/*.html', browserSync.reload);
  gulp.watch('app/js/*.js', browserSync.reload);
});