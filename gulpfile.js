'use strict';

var path = require("path");
var rootDir = __dirname;
var themeFolder = path.join(rootDir,'public_html/wp-content/themes/gigazone-gaming');
var resourceRoot = path.join(rootDir, 'resources');
var themeResourceFolder = path.join(resourceRoot, 'wp-content/themes/gigazone-gaming');
var appFolder = path.join(rootDir,'public_html/app/content');
var appResourceFolder = path.join(resourceRoot,'assets');

var sass = require('gulp-sass'),
    autoprefixer = require('gulp-autoprefixer'),
    elixir = require('laravel-elixir'),
    gulp = require('gulp'),
    coffee = require('gulp-coffee'),
    gutil = require('gulp-util'),
    haml = require('gulp-ruby-haml'),
    rename = require("gulp-rename"),
    del = require('del'),
    livereload = require('gulp-livereload'),
    uglify = require('gulp-uglify'),
    cleanCSS = require('gulp-clean-css'),
    sourcemaps = require('gulp-sourcemaps');
require('gulp-util');
require('laravel-elixir-livereload');
require('dotenv').config();

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */
var Task = elixir.Task;

/**
 * Coffee Extension, needed due to the fact that the new coffee
 * task in elixir will always combine js files,
 */
elixir.extend('blueMountain', function (src, outputDir) {

    new Task('blueMountain', function () {
        return gulp.src(src + '/**/*.coffee')
            .pipe(coffee({bare: true}).on('error', gutil.log))
            .pipe(gulp.dest(outputDir));
    }).watch(path.join(src, '/**/*.coffee'));

});

/**
 * HAML task, take all haml files and parse to twig
 */
elixir.extend('hamlToTwig', function (src, outputDir) {

    new Task('hamlToTwig', function () {
        gulp.src(src + '/**/*.haml')
            .pipe(haml({format: 'html5', style: 'default'}).on('error', gutil.log))
            .pipe(rename(function (path) {
                path.extname = ".twig";
                return path;
            }))
            .pipe(gulp.dest(outputDir));
    }).watch(path.join(src, '**/*.haml'));
});

/**
 * Copy files from one destination to another
 */
elixir.extend('copyFiles', function(src, output){
    new Task('copyFiles', function() {
        return gulp.src(src)
            .pipe(gulp.dest(output))
    }).watch(src);
});


/**
 * Minify Js Files
 */
elixir.extend('minifyJs', function(src){
    new Task('minifyJs', function() {
        return gulp.src(src + '/**/*.js')
            .pipe(uglify().on('error', gutil.log))
            .pipe(gulp.dest(src))
    });
});



/**
 * Clean CSS
 */
elixir.extend('cleanCss', function(src){
   new Task('cleanCss', function() {
       gulp.src(src + '/style.css')
           .pipe(cleanCSS({}).on('error', gutil.log))
           .pipe(gulp.dest(src));
   })
});

/**
 * Compile sass file to css with eyeglass
 * https://github.com/sass-eyeglass/eyeglass/blob/master/site-src/docs/integrations/gulp.md
 */
elixir.extend('sassy', function(source, destination, options){
    new Task('sassy', function() {
        return gulp.src(source)
            .pipe(sourcemaps.init())
            .pipe(sass(options).on("error", sass.logError))
            .pipe(autoprefixer({
                browsers: ['last 2 versions'],
                cascade: false
            }))
            .pipe(sourcemaps.write('./maps'))
            .pipe(gulp.dest(destination));
    }).watch(source)
})

/**
 * Delete task to remove files/folders
 * https://github.com/gulpjs/gulp/blob/master/docs/recipes/delete-files-folder.md
 */
elixir.extend('delete', function(toClean){
    new Task('delete', function(){
        return del(toClean)
    })
})

/**
 * Main gulp elixir task
 */
elixir(function (mix) {

    /** ================================================
     * Copy Boostrap css and js files to themes and app folder
     ================================================ */

    mix
    /** ================================================
     * Compile Theme SASS -> CSS
     ================================================ */
       .sassy(path.join(themeResourceFolder, 'sass/**/*.scss'), path.join(themeFolder, 'css'), {
           outputStyle: (process.env.APP_ENV === 'production' ? 'compressed': 'nested'),
           indentWidth: 4,
           indentType: 'space',
           includePaths: [
               path.join(themeResourceFolder, 'sass/libraries')
           ]
       })
        // get rid of the left over sass folder
        .delete(path.join(themeFolder, 'css/sass'))

        /** ================================================
        * Compile App SASS -> CSS
        ================================================ */
        .sassy(path.join(appResourceFolder, 'sass/**/*.scss'), path.join(appFolder, 'css'), {
            outputStyle: (process.env.APP_ENV === 'production' ? 'compressed': 'nested'),
            indentWidth: 4,
            indentType: 'space',
            includePaths: [
                path.join(appResourceFolder, 'sass/libraries')
            ]
        })
        // get rid of the left over sass folder
        .delete(path.join(appFolder, 'css/sass'))

        // compile theme coffee files to js
        .blueMountain(themeResourceFolder + '/coffee', themeFolder + '/js')
        // compile app coffee files to js
        .blueMountain(appResourceFolder + '/coffee', appFolder + '/js')
        // convert theme haml to twig
        .hamlToTwig(themeResourceFolder + '/haml', themeFolder + '/views')
        // convert app haml to twig
        .hamlToTwig(appResourceFolder + '/haml', appFolder + '/views')
        // copy twig views from resources to theme folder
        .copyFiles(themeResourceFolder + '/twig/**/*.twig', themeFolder + '/views')
        // copy js files from resources to theme folder
        .copyFiles(appResourceFolder + '/js/**/*.js', appFolder + '/js');

    /** ================================================
     * If app it set to production then minimize things
     ================================================ */
        if(process.env.APP_ENV === 'local' || process.env.APP_ENV === 'production') {
            mix.cleanCss(themeFolder + '/css')
               .minifyJs(themeFolder + '/js')
                .minifyJs(appFolder + '/js')
                .cleanCss(appFolder + '/css');
        }
    //mix.livereload([themeFolder + '/**/*.*'], {options: {basePath: "/wp-content/themes/greater-bemidji"}});
    mix.livereload(['app/**/*', 'public_html/**/*', 'resources/views/**/*'], {});
});
