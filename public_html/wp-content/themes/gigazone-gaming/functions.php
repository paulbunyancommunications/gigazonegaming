<?php
global $cachePool;

require_once __DIR__.'/../../../../vendor/autoload.php';
register_nav_menu('Main Navigation', __('Primary Menu'));
new \GigaZone\Timber\GigaZoneGamingTimber();

/** @var Stash\Driver\FileSystem $cacheDriver */
$cacheDriver = new Stash\Driver\FileSystem(['path' => dirname($_SERVER['DOCUMENT_ROOT']) . '/cache/']);

// [bartag foo="foo-value"]
function updateSignUpForm( $atts ) {
    $a = shortcode_atts( array(
        'foo' => 'something',
        'bar' => 'something else',
    ), $atts );

    return Timber::compile('forms/update-sign-up.twig', $a);
}
add_shortcode( 'update-sign-up', 'updateSignUpForm' );
