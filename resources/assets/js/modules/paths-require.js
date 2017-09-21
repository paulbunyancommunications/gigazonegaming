/**
 * Check for common already loaded libraries, if they
 * are already loaded then don't load them again
 */

/**
 * @type {{}} paths to libraries that
 */
paths = {}
shims = {}
/**
 * Check is jquery is already loaded and if not then add it to the config and require
 */
var jQuery = window.jQuery,
    // check for old versions of jQuery
    oldjQuery = jQuery && !!jQuery.fn.jquery.match(/^1\.[0-4](\.|$)/),
    // jquery.js load path from /app/content/js
    localJqueryPath = "../../../bower_components/jquery/dist/jquery.min",
    noConflict;

// check for jQuery
if (!jQuery || oldjQuery) {
    // load if it's not available or doesn't meet min standards
    paths.jquery = "https://code.jquery.com/jquery-3.2.1.min.js";
    noConflict = !!oldjQuery;
} else {
    // register the current jQuery
    define('jquery', [], function() { return jQuery; });
}

/**
 * Check if bootstrap 3 is loaded
 * @type {boolean}
 */
var bootstrap3Enabled = (typeof $().emulateTransitionEnd == 'function');
    // Bootstrap.js load path from /app/content/js
    localBoostrapPath = "../libraries/bootstrap/js/bootstrap.min";
if (!bootstrap3Enabled) {
    paths.bootstrap = "https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js";
    shims.bootstrap = ['jquery']
}

// add to config
if (paths.length > 0 ) {
    require.config({
        paths: paths,
        shim: shims
    });
}
