var gulp     = require('gulp'),
    concat   = require('gulp-concat'),
    uglify   = require('gulp-uglify'),
    rename   = require('gulp-rename'),
    sass     = require('gulp-sass'),
    sassGlob = require('gulp-sass-glob'),
    postcss  = require('gulp-postcss'),
    cssnext  = require('postcss-cssnext'),
    // autoprefixer = require('gulp-autoprefixer'),
    browserSync = require('browser-sync').create();

var DEST = 'build/';

gulp.task('scripts', function() {
    return gulp.src([
        'src/js/helpers/*.js',
        'src/js/*.js',
      ])
      .pipe(concat('custom.js'))
      .pipe(gulp.dest(DEST+'/js'))
      //.pipe(rename({suffix: '.min'}))
      //.pipe(uglify())
      //.pipe(gulp.dest(DEST+'/js'))
      .pipe(browserSync.stream());
});

// For icons sass
var compileICONS = function (filename, options) {
  var processors = [
      // autoprefixer('last 2 versions', '> 5%'),
      cssnext({ browsers: ['last 2 version', '> 5%'] })
  ];
  return gulp.src('src/icons/**/*.scss') // sass('src/icons/*.scss', options)
        .pipe(sassGlob())
        .pipe(sass(options))
        .pipe(postcss(processors))
        //.pipe(autoprefixer('last 2 versions', '> 5%'))
        .pipe(concat(filename))
        .pipe(gulp.dest(DEST+'/css'))
        .pipe(browserSync.stream());
};

gulp.task('icons', function() {
    return compileICONS('icons.css', {outputStyle: ':compact'});
});

gulp.task('icons-minify', function() {
    return compileICONS('icons.min.css', {outputStyle: ':compressed'});
});

// TODO: Maybe we can simplify how sass compile the minify and unminify version
var compileSASS = function (filename, options) {
  var processors = [
      // autoprefixer('last 2 versions', '> 5%'),
      cssnext({
          browsers: ['last 2 version', '> 5%'],
          cascade: false
      })
  ];
  return gulp.src('src/scss/main.scss') // sass('src/scss/*.scss', options)
        .pipe(sassGlob())
        .pipe(sass(options).on('error', sass.logError))
        .pipe(postcss(processors))
        //.pipe(autoprefixer('last 2 versions', '> 5%'))
        .pipe(concat(filename))
        .pipe(gulp.dest(DEST+'/css'))
        .pipe(browserSync.stream());
};

gulp.task('sass', function() {
    return compileSASS('custom.css', {outputStyle: 'expanded'});
});

gulp.task('sass-minify', function() {
    return compileSASS('custom.min.css', {outputStyle: 'compressed'});
});



gulp.task('browser-sync', function() {
    browserSync.init({
        server: {
            baseDir: './'
        },
        startPath: './production/index.html'
    });
});

gulp.task('watch', function() {
  // Watch .html files
  gulp.watch('production/*.html', browserSync.reload);
  // Watch .js files
  gulp.watch('src/js/*.js', ['scripts']);
  // Watch .scss files
  gulp.watch('src/scss/*.scss', ['sass', 'sass-minify']);
  // Watch .scss files for icons
  gulp.watch('src/icons/*.scss', ['icons', 'icons-minify']);
});

// Default Task
gulp.task('default', ['browser-sync', 'watch']);