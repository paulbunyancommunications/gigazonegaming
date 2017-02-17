<?php
global $cachePool;
// add wordpress settings page action for this theme
require_once __DIR__ . '/theme-panel.php';
register_nav_menu('Main Navigation', __('Primary Menu'));
$bootstrap = new \GigaZone\GigaZoneGamingBootstrap();

/** @var Stash\Driver\FileSystem $cacheDriver */
$cachePath = realpath(dirname(__DIR__) . '/../../../cache/');
$cacheDriver = new Stash\Driver\FileSystem(['path' => $cachePath]);

add_shortcode('splash', ["\\Pbc\\GigaZoneGaming\\ShortCodes\\Splash", 'shortCode']);
add_shortcode('bloginfo', [$bootstrap, 'blogInfoShortCode']);
add_shortcode('gigazone-info', [$bootstrap, 'getGigazoneInfo']);
add_shortcode('get-info', [$bootstrap, 'getInfo']);
add_shortcode('update-sign-up', [$bootstrap, 'formFieldsShortCode']);
add_shortcode('contact-us', [$bootstrap, 'formFieldsShortCode']);
add_shortcode('lol-team-sign-up', [$bootstrap, 'formFieldsShortCode']);
add_shortcode('lol-individual-sign-up', [$bootstrap, 'formFieldsShortCode']);
add_shortcode('build-form', [$bootstrap, 'formFieldsShortCode']);
add_shortcode('env', [$bootstrap, 'getEnvShortCode']);
add_shortcode('user-profile', [$bootstrap, 'userProfileShortCode']);


// get image by id, usage [get-image 12345]
// this will output the image with height and width attributes and class of get-image
add_shortcode('get-image', [$bootstrap, 'getMediaImageShortCode']);

// get recent posts preview, usage [recent-post-preview]
// see https://codex.wordpress.org/Function_Reference/wp_get_recent_posts for all options
add_shortcode('recent-post-preview', ["\\Pbc\\GigaZoneGaming\\ShortCodes\\RecentPostPreview", 'shortCode']);


// if the WP_FRONT_PAGE_ONLY flat is true then relay all requests to the front page post
add_action('init', 'showSplashPageOnly', 1);

// add theme support for post thumbnails
add_theme_support('post-thumbnails');

/**
 * Enqueue css for site
 */
function enqueueCss()
{
    $themeDir = parse_url(get_template_directory_uri(), PHP_URL_PATH);
    $autoVersion = new \Pbc\AutoVersion();
    wp_enqueue_style('bootstrap', $autoVersion->file('/..'. $themeDir . '/libraries/bootstrap/css/bootstrap.min.css'));
    wp_enqueue_style('fontawesome',
        '/..' . $autoVersion->file('/bower_components/font-awesome/css/font-awesome.min.css'));
    wp_enqueue_style(
        'gigazone',
        '/..' . $autoVersion->file($themeDir . '/css/style.css'),
        ['bootstrap', 'fontawesome']
    );
}

/**
 * Enqueue js for loading typekit fonts
 */
function enqueueTypekit()
{
    wp_enqueue_script('typekit', 'https://use.typekit.net/bhh0sxx.js');
}

/**
 * enqueue Google reCaptcha Js
 */
function enqueueReCaptcha()
{
    wp_enqueue_script('google-recaptcha', 'https://www.google.com/recaptcha/api.js');
}

add_action('wp_head', 'enqueueCss', 1);
add_action('wp_head', 'enqueueReCaptcha', 1);
add_action('wp_head', 'enqueueTypekit', 1);

/**
 * Enqueue all js for site
 */
function loadJs()
{
    $themeDir = parse_url(get_template_directory_uri(), PHP_URL_PATH);
    $autoVersion = new \Pbc\AutoVersion();
    wp_enqueue_script('common-require', '/..' . $autoVersion->file($themeDir . '/js/common-require.js'));
    wp_enqueue_script('require-js', '/..' . $autoVersion->file('/bower_components/requirejs/require.js'));
    wp_enqueue_script(
        'main-require',
        '/..' . $autoVersion->file($themeDir . '/js/main-require.js'),
        ['common-require', 'require-js']
    );
}

add_action('wp_footer', 'loadJs');

/**
 * Show splash page if "WP_FRONT_PAGE_ONLY" is set to true
 */
function showSplashPageOnly()
{
    if (filter_var(getenv('WP_FRONT_PAGE_ONLY'), FILTER_VALIDATE_BOOLEAN) === true) {
        include(locate_template('get-context.php'));
        $homeId = get_option('page_on_front');
        $context['page'] = Timber::get_post(get_option('page_on_front'));
        $context['body_class'] = 'splash page page-id-' . $homeId . ' page-template-default';
        Timber::render('pages/splash.twig', $context);
        die();
    }
}

/**
 * Remove empty paragraphs created by wpautop()
 * @author Ryan Hamilton
 * @link https://gist.github.com/Fantikerz/5557617
 */
function remove_empty_p($content)
{
    $content = force_balance_tags($content);
    $content = preg_replace('#<p>\s*+(<br\s*/*>)?\s*</p>#i', '', $content);
    $content = preg_replace('~\s?<p>(\s|&nbsp;)+</p>\s?~', '', $content);
    return $content;
}

add_filter('the_content', 'remove_empty_p', 20, 1);

// extra fields dialog
add_action( 'show_user_profile', [$bootstrap, 'showExtraProfileFields'] );
add_action( 'edit_user_profile', [$bootstrap, 'showExtraProfileFields'] );

// saving extra fields
add_action( 'personal_options_update', [$bootstrap, 'saveExtraProfileFields'] );
add_action( 'edit_user_profile_update', [$bootstrap,'saveExtraProfileFields'] );

/**
 * Allow for svg files to be uploaded via media upload
 * https://css-tricks.com/snippets/wordpress/allow-svg-through-wordpress-media-uploader/
 * @param $mimes
 * @return mixed
 */
function cc_mime_types($mimes) {
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}
add_filter('upload_mimes', 'cc_mime_types');
