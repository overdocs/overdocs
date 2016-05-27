/* jshint node: true    */

var gulp       = require('gulp');
var gutil      = require('gulp-util');
var less       = require('gulp-less');
var csso       = require('gulp-csso');
var spawn      = require('child_process').spawn;
var uglify     = require('gulp-uglify');
var concat     = require('gulp-concat-util');
var sourcemaps = require('gulp-sourcemaps');
var path       = require('path');

var ci = process.argv.indexOf('ci') > -1;
var scriptBundles = {
    index: [
        'node_modules/zepto/zepto.min',
        'node_modules/lodash/lodash.min',
        'node_modules/backbone/backbone-min',
        'node_modules/fuse.js/src/fuse.min',
        'resources/scripts/app',
        'resources/scripts/utils',
        'resources/scripts/models',
        'resources/scripts/views',
        'resources/scripts/search',
        'resources/scripts/search_views',
        'resources/scripts/main',
        'resources/scripts/index'
    ],
    sheet: [
        'node_modules/prismjs/prism',
        'node_modules/prismjs/components/prism-markup',
        'node_modules/prismjs/components/prism-css',
        'node_modules/prismjs/components/prism-clike',
        'node_modules/prismjs/components/prism-c',
        'node_modules/prismjs/components/prism-cpp',
        'node_modules/prismjs/components/prism-javascript',
        'node_modules/prismjs/components/prism-php',
        'node_modules/zepto/zepto.min',
        'resources/scripts/sheet'
    ]
};

gulp.task('default', ['server', 'watch']);

gulp.task('server', ['less', 'js', 'build'], function () {
    spawn('php', ['-S', '0.0.0.0:8000'], {
        cwd: 'public',
        stdio: 'inherit'
    });
});

gulp.task('ci', ['less', 'js']);

gulp.task('less', function () {
    return gulp.src('resources/styles/overdocs.less')
        .pipe(less({
            paths: [path.join(__dirname, 'resources/styles'), 'node_modules']
        }))
        .on('error', onError)
        .pipe(csso())
        .pipe(gulp.dest('public/styles'));
});

gulp.task('js', function () {
    Object.keys(scriptBundles).forEach(function (name) {
        gulp.src(scriptBundles[name].map(addSuffix('.js')))
            .pipe(concat(name + '.js', { separator: ';' }))
            .pipe(uglify())
            .pipe(gulp.dest('public/scripts'));
    });
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
    gulp.watch(['resources/styles/*.less'], ['less']);
    gulp.watch(['resources/scripts/*.js'], ['js']);
    gulp.watch(['sheets/**/*.xml', 'sheet.xsl'], ['lint', 'build']);
});

function onError(error) {
    gutil.log(error.toString());
    this.emit('end');

    if (ci) {
        throw error;
    }
}

function addSuffix(suffix) {
    return function (value) {
        return value + suffix;
    };
}
