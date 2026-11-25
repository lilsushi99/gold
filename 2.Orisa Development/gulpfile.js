const gulp = require('gulp');
const sass = require('gulp-dart-sass');
const sourcemaps = require('gulp-sourcemaps');
const autoprefixer = require('gulp-autoprefixer');
const includeHTML = require('gulp-file-include');
const beautify = require('gulp-html-beautify');
const browserSync = require('browser-sync').create();
const once = require('gulp-once');
// Include HTML files
function includeHtml() {
    return gulp
        .src(['src/views/pages/*.html'])
        .pipe(
            includeHTML({
                prefix: '@@',
                basepath: '@file',
            }),
        )
        .pipe(gulp.dest('dist'))
        .pipe(browserSync.stream());
}
// Beautify HTML
function beautifyHtml() {
    return gulp
        .src('dist/**/*.html')
        .pipe(beautify({ indent_size: 4 }))
        .pipe(gulp.dest('dist'));
}
// Copy other resource files
function copyAssets() {
    return gulp.src(['src/assets/css/**/*', 'src/assets/fonts/**/*', 'src/assets/images/**/*', 'src/assets/imgs/**/*', 'src/assets/img/**/*', 'src/assets/js/**/*'], { base: 'src/assets' }).pipe(gulp.dest('dist/assets'));
}
// Copy other resource files
function copyAssetsChanged() {
    return gulp.src(['src/assets/css/**/*', 'src/assets/fonts/**/*', 'src/assets/images/**/*', 'src/assets/imgs/**/*', 'src/assets/img/**/*', 'src/assets/js/**/*'], { base: 'src/assets' }).pipe(once()).pipe(gulp.dest('dist/assets')).pipe(browserSync.stream());
}
// Sass
function buildStyles() {
    return gulp
        .src('src/assets/scss/main.scss')
        .pipe(sourcemaps.init())
        .pipe(
            sass({
                quietDeps: true,
                silenceDeprecations: ['legacy-js-api'],
            }).on('error', sass.logError),
        )
        .pipe(autoprefixer())
        .pipe(sourcemaps.write(''))
        .pipe(gulp.dest('src/assets/css/'));
}
// Build task
gulp.task('build', gulp.series(includeHtml, beautifyHtml, buildStyles, copyAssets));
// Initialize BrowserSync and track changes
gulp.task(
    'dev',
    gulp.series('build', function () {
        // Watch tasks
        gulp.watch('src/views/**/*.html', gulp.series(includeHtml));
        gulp.watch('src/assets/scss/**/**/*', gulp.series(buildStyles));
        gulp.watch(['src/assets/css/**/*', 'src/assets/fonts/**/*', 'src/assets/images/**/*', 'src/assets/imgs/**/*', 'src/assets/img/**/*', 'src/assets/js/**/*'], copyAssetsChanged);
        browserSync.init({
            server: {
                baseDir: 'dist',
            },
            hot: true,
            // Listen on all interfaces so other devices on the LAN can open
            // http://<this-pc-lan-ip>:3000 (e.g. http://192.168.1.16:3000).
            host: '0.0.0.0',
            port: 3000,
            open: 'local',
            notify: false,
        });
    }),
);
// Default action
gulp.task('default', gulp.series('dev'));
