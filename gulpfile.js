//
// gulpfile Chalet.nl new website
//
// usage:
//        - gulp watchify
//
//

var gulp       = require('gulp');
var gutil      = require('gulp-util');
var source     = require('vinyl-source-stream');
var buffer     = require('vinyl-buffer');
var watchify   = require('watchify');
var browserify = require('browserify');

var bundler = watchify(browserify('./src/AppBundle/Resources/assets/js/price_table/main.js', watchify.args));
gulp.task('watchify', browserifyBundle);

bundler.on('update', browserifyBundle);

function browserifyBundle() {
    var start = new Date().getTime();

    var b = bundler.bundle()
      .on('error', gutil.log.bind(gutil, 'Browserify Error'))
      .pipe(source('price_table.js'))
      .pipe(buffer())
      .pipe(gulp.dest('./web/bundles/app/js'));

    var end = new Date().getTime();
    var time = end - start;

    gutil.log('[browserify]', 'rebundle took ', gutil.colors.cyan(time + ' ms'));
    return b;
}

gulp.task('browsersync', function() {
    // Start a Browsersync proxy
    browserSync.init({
        proxy: "http://local.chalet.nl",
        port: 3000,
        open: false,
        notify: true
    });
});

gulp.task('default', function() {

});
