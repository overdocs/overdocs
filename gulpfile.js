/* jshint node: true    */

var gulp       = require('gulp');
var gutil      = require('gulp-util');
var less       = require('gulp-less');
var csso       = require('gulp-csso');
var spawn      = require('child_process').spawn;
var uglify     = require('gulp-uglify');
var concat     = require('gulp-concat-util');
var sourcemaps = require('gulp-sourcemaps');

var ci = process.argv.indexOf('ci') > -1;

gulp.task('default', ['server', 'watch']);

gulp.task('server', ['less', 'build'], function () {
    spawn('php', ['-S', '0.0.0.0:8000'], {
        cwd: 'public',
        stdio: 'inherit'
    });
});

gulp.task('ci', ['less', 'js']);

gulp.task('less', function () {
    return gulp.src('public/styles/overdocs.less')
        .pipe(less())
        .on('error', onError)
        .pipe(csso())
        .pipe(gulp.dest('public/styles'));
});

gulp.task('js', function () {
    return gulp.src([
        'vendor/zepto',
        'vendor/lodash',
        'vendor/backbone',
        'vendor/fuse',
        'vendor/prism',
        'js/app',
        'js/utils',
        'js/models',
        'js/views',
        'js/search',
        'js/search_views',
        'js/main',
        'js/index'
    ].map(function (path) {
        return 'public/' + path + '.js';
    })).pipe(concat('bundle.js', { separator: ';' }))
       .pipe(uglify())
       .pipe(gulp.dest('public/js'));
});

gulp.task('lint', function () {
    spawn('php', ['lint.php'], {
        stdio: 'inherit'
    });
});

gulp.task('build', function () {
    spawn('php', ['build.php'], {
        stdio: 'inherit'
    });
});

gulp.task('watch', function () {
    gulp.watch(['public/less/*.less'], ['less']);
    gulp.watch(['sheets/**/*.xml', 'sheet.xsl'], ['lint', 'build']);
});

function onError(error) {
    gutil.log(error.toString());
    this.emit('end');

    if (ci) {
        throw error;
    }
}
