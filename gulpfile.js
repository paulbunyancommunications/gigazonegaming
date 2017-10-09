/**
 * @todo figure out live reload
 */
var rootDir = __dirname;

var gulp          = require('gulp');
var plugins       = require('gulp-load-plugins');
var env           = require('gulp-env');
var util          = require('gulp-util');
var path          = require("path");
var debug         = require('gulp-debug');
var sass          = require('gulp-sass');
var autoprefixer  = require('gulp-autoprefixer');
var coffee        = require('gulp-coffee');
var gutil         = require('gulp-util');
var del           = require('del');
var livereload    = require('gulp-livereload');
var uglify        = require('gulp-uglify');
var cleanCSS      = require('gulp-clean-css');
var sourcemaps    = require('gulp-sourcemaps');
var webpack       = require('gulp-webpack');
var logger        = require('gulp-logger');
var shell         = require('gulp-shell');
var _             = require('underscore');
var watch         = require('gulp-watch');
var plumber       = require('gulp-plumber');
var batch         = require('gulp-batch');
var wait          = require('gulp-wait');
var browser       = require('browser-sync').create();
var beep          = require('beepbeep')
var themeFolder   = path.join(rootDir, 'public_html/wp-content/themes/gigazone-gaming');
var appFolder     = path.join(rootDir, 'public_html/app/content');
var resourceRoot  = path.join(rootDir, 'resources');
var bowerDirectory= path.join(rootDir, 'public_html/bower_components');
var npmDirectory  = path.join(rootDir, 'node_modules');
var themeResourceFolder = path.join(resourceRoot, 'wp-content/themes/gigazone-gaming');
var appResourceFolder   = path.join(resourceRoot, 'assets');
var composerDirectory   = path.join(rootDir, 'vendor');

// File to copy directly from the resource folder to the public folder
var sourceFiles = {
  'theme-twig': [themeResourceFolder + '/twig/**/*.twig', themeFolder + '/views'],
  'theme-js': [themeResourceFolder + '/js/**/*.js', themeFolder + '/js'],
  'app-js': [appResourceFolder + '/js/**/*.js', appFolder + '/js']
};
// Location of coffeescript files
var coffeeFiles = {
  'theme-coffee': [path.join(themeResourceFolder + '/coffee/**/*.coffee'), themeFolder + '/js/'],
  'app-coffee': [path.join(appResourceFolder + '/coffee/**/*.coffee'), appFolder + '/js/']
};
// Location of the distrbution js files
var distJs = {
  'theme-js': [path.join(themeFolder, '/js/**/*.js'), path.join(themeFolder, 'dist/js')],
  'app-js': [path.join(appFolder, '/js/**/*.js'), path.join(appFolder, 'dist/js')]
};

// Location of distrbution css files
var distCss = {
  'theme-css': [path.join(themeFolder + '/css/**/*.css'), path.join(themeFolder, 'dist/css')],
  'app-css': [path.join(appFolder + '/css/**/*.css'), path.join(appFolder, 'dist/css')]

};

// Sass file locations
var sassFiles = {
  'theme-sass': [path.join(themeResourceFolder, 'sass/*.scss'), path.join(themeFolder, 'css')],
  'app-sass': [path.join(appResourceFolder, 'sass/*.scss'), path.join(appFolder, 'css')]
};

// Sass config
var sassConfig = {
  outputStyle: (process.env.APP_ENV === 'production' ? 'compressed' : 'nested'),
  indentWidth: 4,
  indentType: 'space',
  includePaths: [
      path.join(themeResourceFolder, 'sass/vendor/')
  ]
}

require('dotenv').config();

// build task
gulp.task('build', gulp.series(compileSass, compileCoffee));

// copy task
gulp.task('copy', gulp.series(copyVendorFiles, copySourceFiles));

// Default task
gulp.task('default', gulp.series('build', server, watcher));

// Production task
gulp.task('production', gulp.series('build', latestCommitHash, uglifyJs, compileCleanCss));


// Latest git hash task
function latestCommitHash() {
    return gulp.src('./', {read: false})
        .pipe(shell(['chmod +x git_log.txt; echo $(git log -n 1 --pretty=format:"%H") > git_log.txt; echo $(head -n 1 git_log.txt) > ' + themeFolder + '/latest_git_commit_hash.txt']))
};

// Copy files from the copyFiles array
function copyVendorFiles() {
  return new Promise(function(resolve, reject) {
      gulp.src('./', {read:false})
          .pipe(shell('rm -rf '+bowerDirectory+' || true && bower update --allow-root --force --silent'))
          .pipe(shell('bash .npm_copy_libraries.sh 2>&1 /dev/null'))

      resolve();
    });
}

function copySourceFiles()
{
  return new Promise(function(resolve, reject) {
    _.mapObject(sourceFiles, function (val) {
        return gulp.src([val[0]])
            .pipe(gulp.dest(val[1]))
            .pipe(wait(1500));
    });
      resolve();
    });
}

// Compile sass to css
function compileSass() {

  return new Promise(function(resolve, reject) {

    _.mapObject(sassFiles, function (val) {
        return gulp.src([val[0]])
            .pipe(wait(1500))
            .pipe(sourcemaps.init())
            .pipe(sass(sassConfig).on("error", sass.logError))
            .pipe(autoprefixer())
            .pipe(sourcemaps.write('./maps'))
            .pipe(gulp.dest(val[1]));
    });
    resolve();
  });
};

// Compile sass to css
function compileCoffee() {

  return new Promise(function(resolve, reject) {
    _.mapObject(coffeeFiles, function (val) {
        return gulp.src([val[0]])
            .pipe(coffee({bare: true}).on('error', gutil.log))
            .pipe(gulp.dest(val[1]))
    });
    resolve();
  });
};


// cleanup css
function compileCleanCss() {
  return new Promise(function(resolve, reject) {
    _.mapObject(distCss, function (val) {
        return gulp.src([val[0]])
            .pipe(cleanCSS({}).on('error', gutil.log))
            .pipe(gulp.dest(val[1]))
    });
    resolve();
  });
};

// upglify js
function uglifyJs() {

    return new Promise(function(resolve, reject) {
    _.mapObject(distJs, function (val) {
        return gulp.src([val[0]])
            .pipe(uglify().on('error', gutil.log))
            .pipe(gulp.dest(val[1]))
    });
    resolve();
  });
};


function server(done) {
  browser.init({
    files: ['{src,app,public_html/wp-content}/**/*.php'],
    host: process.env.SERVER_NAME,
    proxy: 'https://' + process.env.SERVER_NAME,
    browser: 'google chrome',
    https: true,
    open: true,
    reloadDelay: 1000
  });
  done();
};

function watcher() {
    // Watch template files
    gulp.watch([resourceRoot+'/**/*.php', resourceRoot+'**/*.twig', themeFolder+'**/*.php']).on('all', gulp.series('copy', browser.reload));
    // watch js files
    gulp.watch([appResourceFolder+'/js/**/*.js',themeResourceFolder+'/js/**/*.js']).on('all', gulp.series('copy', browser.reload));
    // watch Sass files that are not a library
    gulp.watch([appResourceFolder+'/sass/**/*.scss',themeResourceFolder+'/sass/**/*.scss']).on('all', gulp.series(compileSass, browser.reload));
    // watch coffee files
    gulp.watch([appResourceFolder+'/coffee/**/*.coffee',themeResourceFolder+'/coffee/**/*.coffee']).on('all', gulp.series(compileCoffee, browser.reload));
}
