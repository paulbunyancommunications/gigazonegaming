var themeFolder = './public_html/wp-content/themes/gigazone-gaming',
    themeResourceFolder = './resources/wp-content/themes/gigazone-gaming',
    elixir = require('laravel-elixir'),
    gulp = require('gulp'),
    coffee = require('gulp-coffee'),
    gutil = require('gulp-util'),
    haml = require('gulp-ruby-haml'),
    rename = require("gulp-rename"),
    del = require('del'),
    livereload = require('gulp-livereload'),
    uglify = require('gulp-uglify'),
    CleanCSS = require('gulp-clean-css');
require('gulp-util');
require('laravel-elixir-sass-compass');
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
    }).watch(src + '/**/*.coffee');

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
    }).watch(src + '/**/*.haml');
});

/**
 * Copy files from one destination to another
 */
elixir.extend('copyFiles', function(src, output){
    new Task('copyFiles', function() {
        gulp.src(src)
            .pipe(gulp.dest(output))
    }).watch(src);
});


/**
 * Minify Js Files
 */
elixir.extend('minifyJs', function(src){
    new Task('minifyJs', function() {
        return gulp.src(src + '/**/*.js')
            .pipe(uglify())
            .pipe(gulp.dest(src))
    });
});



/**
 * Clean CSS
 */
elixir.extend('cleanCss', function(src){
   new Task('cleanCss', function() {
       gulp.src(src + '/style.css')
           .pipe(CleanCSS({}))
           .pipe(gulp.dest(src));
   })
});

/**
 * Main gulp elixir task
 */
elixir(function (mix) {
    mix.compass('**/*.scss', themeFolder + '/css', {
        sass: themeResourceFolder + '/sass',
        config_file: themeResourceFolder + "/config.rb",
        style: "compressed",
        font: themeFolder + "/fonts",
        image: themeFolder + "/images",
        javascript: themeFolder + "/js",
        comments: false,
    })
        .blueMountain(themeResourceFolder + '/coffee', themeFolder + '/js')
        .hamlToTwig(themeResourceFolder + '/haml', themeFolder + '/views')
        .copyFiles(themeResourceFolder + '/twig/**/*.twig', themeFolder + '/views');

        if(process.env.APP_ENV === 'local' || process.env.APP_ENV === 'production') {
            //mix.cleanCss(themeFolder + '/css')
              //  .minifyJs(themeFolder + '/js');
        }
        mix.livereload([themeFolder + '/**/*.*'], {options: {basePath: "/wp-content/themes/greater-bemidji"}});
});