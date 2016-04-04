//
// gulpfile Chalet.nl new website
//
//
// usage:
//
// - watch during development, combined with browserSync:
//      - gulp
//
// - generate files for development:
//      - gulp dev
//
// - generate files for deployment:
//      - gulp build
//
// - generate spritesheet:
//      - gulp spritesheet
//
// - generate styles (dev-version):
//      - gulp styles
//
// - generate styles (deployment-version):
//      - gulp build-styles
//

var gulp         = require('gulp');
var gutil        = require('gulp-util');
var source       = require('vinyl-source-stream');
var buffer       = require('vinyl-buffer');
var watchify     = require('watchify');
var browserify   = require('browserify');

var sass         = require('gulp-sass');
var sourcemaps   = require('gulp-sourcemaps');
var autoprefixer = require('gulp-autoprefixer');
var rename       = require('gulp-rename');
var shell        = require('gulp-shell')
var spritesmith  = require('gulp.spritesmith');

var del          = require('del');
var merge        = require('merge-stream');

var browserSync  = require('browser-sync');
var reload       = browserSync.reload;

function getFileListHash(dir, regexp) {
    var fs   = require("fs"),
        path = require('path'),
    files = fs.readdirSync(dir)

    string = '';

    for (i = 0; i < files.length; i++) {

        if (regexp.test(files[i]) == false) {
            continue;
        }

        fmtime = fs.statSync(path.join(dir, files[i])).mtime.getTime()
        string += files[i] + fmtime;
    }

    if (string != '')
        return require('crypto').createHash('md5').update(string).digest("hex").substring(0, 6)
    return null
}


//
// Browserify
//
var browserify_source    = './src/AppBundle/Resources/assets/js/price_table/main.js';
var browserify_dest_dir  = './web/bundles/app/js';
var browserify_dest_name = 'price_table.js';

// watchify
var bundler = watchify(browserify(browserify_source, watchify.args));
gulp.task('watchify', function() {
    browserifyBundle();
    bundler.on('update', browserifyBundle);
});

function browserifyBundle() {
    var start = new Date().getTime();

    var b = bundler.bundle()
        .on('error', gutil.log.bind(gutil, 'Browserify Error'))
        .pipe(source(browserify_dest_name))
        .pipe(buffer())
        .pipe(gulp.dest(browserify_dest_dir));

    var end = new Date().getTime();
    var time = end - start;

    gutil.log('[browserify]', 'rebundle took ', gutil.colors.cyan(time + ' ms'));
    return b;
}

gulp.task('browserify', function() {
    return browserify({ entries: [browserify_source] })
            .bundle()
            .pipe(source(browserify_dest_name))
            .pipe(gulp.dest(browserify_dest_dir));
});

// browsersync
gulp.task('browsersync', function() {
    // Start a Browsersync proxy
    browserSync.init({
        proxy: "http://local.chalet.nl",
        port: 3000,
        open: true,
        notify: true
    });
});

// convert SASS to CSS (with sourcemaps)
gulp.task('styles', function () {
    return gulp
        .src('./src/AppBundle/Resources/assets/css/main.scss')
        .pipe(sourcemaps.init())
        .pipe(sass({
            errLogToConsole: true,
            outputStyle: 'expanded',
            precision: 10,
            includePaths: ['.']
        }).on('error', sass.logError))
        .pipe(autoprefixer({
             browsers: ['last 2 versions', '> 1%', 'Firefox ESR']
         }))
        .pipe(sourcemaps.write())
        .pipe(rename('dev.css'))
        .pipe(gulp.dest('./web/bundles/app/css/build/'))
        .pipe(reload({stream: true}));
});

// convert SASS to CSS for production (minified, without sourcemaps)
gulp.task('build-styles', function () {
    return gulp
        .src('./src/AppBundle/Resources/assets/css/main.scss')
        .pipe(sass({
            errLogToConsole: true,
            outputStyle: 'compressed',
            precision: 10,
            includePaths: ['.']
        }).on('error', sass.logError))
        .pipe(autoprefixer({
             browsers: ['last 2 versions', '> 1%', 'Firefox ESR']
         }))
        .pipe(rename('prod.css'))
        .pipe(gulp.dest('./web/bundles/app/css/build/'));
});

// watch style changes
gulp.task('watch-styles', function() {
    gulp.watch([
            './src/AppBundle/Resources/assets/css/*.scss',
            './web/bundles/app/css/*.css'
    ],['styles']);
});

// generate spritesheet
gulp.task('gen-spritesheet', function() {

    del([
        'web/bundles/app/img/spritesheets/*.png',
        'src/AppBundle/Resources/assets/css/sprites.css'
    ]);

    var hash = getFileListHash('src/AppBundle/Resources/assets/img/sprite-images/', new RegExp('.*\.png'));
    var spriteData = gulp.src('src/AppBundle/Resources/assets/img/sprite-images/*.png')
        .pipe(spritesmith({
            imgName: '../../img/spritesheets/spritesheet-' + hash + '.png',
            retinaImgName: '../../img/spritesheets/spritesheet-' + hash + '@2x.png',
            retinaSrcFilter: 'src/AppBundle/Resources/assets/img/sprite-images/*@2x.png',
            cssName: 'sprites.css',
            padding: 5,
            cssOpts: {
              cssSelector: function (sprite) {
                return '.sprite-' + sprite.name;
              }
            }
        }));
    var imgStream = spriteData.img.pipe(gulp.dest('web/bundles/app/img/spritesheets'));
    var cssStream = spriteData.css.pipe(gulp.dest('src/AppBundle/Resources/assets/css'));
    return merge(imgStream, cssStream);
});

// spritesheet
gulp.task('spritesheet', ['gen-spritesheet'], function() {
    gulp.start('styles');
});

// build: generate files for development
gulp.task('dev', ['gen-spritesheet'], function() {
    gulp.start(['styles', 'browserify']);
});

// build: generate files for deployment
gulp.task('build', ['gen-spritesheet'], function() {
    gulp.start(['build-styles', 'browserify']);
});

// default development task
gulp.task('default', ['gen-spritesheet'], function() {
    gulp.start(['styles', 'watchify', 'watch-styles', 'browsersync']);
});
