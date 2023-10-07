const gulp = require('gulp');
const rename = require('gulp-rename');
const concat = require('gulp-concat');

const cssFiles = ['node_modules/bulma/css/bulma.css', 'public/assets/fontawesome/css/all.min.css', 'resources/style.css'];
const jsFiles = ['node_modules/jquery/dist/jquery.min.js', 'resources/scripts.js'];

function styles() {
    return gulp.src(cssFiles)
        .pipe(rename({ suffix: '.min' }))
        .pipe(concat('style.min.css'))
        .pipe(gulp.dest('public/portal2'));
}

function scripts() {
    return gulp.src(jsFiles)
        .pipe(rename({ suffix: '.min' }))
        .pipe(concat('scripts.min.js'))
        .pipe(gulp.dest('public/portal2'));
}

function watch() {
    gulp.watch(jsFiles, scripts);
    gulp.watch(cssFiles, styles);
}

const build = gulp.series(gulp.parallel(styles, scripts));

exports.watch = watch;
exports.default = build;