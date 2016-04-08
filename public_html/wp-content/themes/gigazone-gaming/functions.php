<?php
global $cachePool;

require_once __DIR__.'/../../../../vendor/autoload.php';
register_nav_menu('Main Navigation', __('Primary Menu'));
new \GigaZone\Timber\GigaZoneGamingTimber();

/** @var Stash\Driver\FileSystem $cacheDriver */
$cacheDriver = new Stash\Driver\FileSystem(['path' => dirname($_SERVER['DOCUMENT_ROOT']) . '/cache/']);

// [update-sign-up]
function gzUpdateSignUpForm( $atts ) {
    $a = shortcode_atts( array(
        'foo' => 'something',
        'bar' => 'something else',
    ), $atts );

    return Timber::compile('forms/update-sign-up.twig', $a);
}
add_shortcode( 'update-sign-up', 'gzUpdateSignUpForm' );

function gzBlogInfoShortcode( $atts ) {
    extract(shortcode_atts(array(
        'key' => '',
        'filter' => ''
    ), $atts));
    /** @var string $key */
    /** @var string $filter */
    return get_bloginfo($key, $filter);
}
add_shortcode('bloginfo', 'gzBlogInfoShortcode');