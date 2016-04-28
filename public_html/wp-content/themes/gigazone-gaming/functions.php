<?php
global $cachePool;

register_nav_menu('Main Navigation', __('Primary Menu'));
$bootstrap = new \GigaZone\Timber\GigaZoneGamingBootstrap();

/** @var Stash\Driver\FileSystem $cacheDriver */
$cacheDriver = new Stash\Driver\FileSystem(['path' => dirname($_SERVER['DOCUMENT_ROOT']) . '/cache/']);

add_shortcode('update-sign-up', [$bootstrap, 'updateSignUpFormShortCode']);
add_shortcode('bloginfo', [$bootstrap, 'blogInfoShortCode']);

