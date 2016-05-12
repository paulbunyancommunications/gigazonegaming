<?php
global $cachePool;

register_nav_menu('Main Navigation', __('Primary Menu'));
$bootstrap = new \GigaZone\GigaZoneGamingBootstrap();

/** @var Stash\Driver\FileSystem $cacheDriver */
$cacheDriver = new Stash\Driver\FileSystem(['path' => dirname($_SERVER['DOCUMENT_ROOT']) . '/cache/']);

add_shortcode('update-sign-up', [$bootstrap, 'updateSignUpFormShortCode']);
add_shortcode('bloginfo', [$bootstrap, 'blogInfoShortCode']);
add_shortcode('gigazone-info', [$bootstrap, 'getGigazoneInfo']);
add_shortcode('contact-us', [$bootstrap, 'formFieldsShortCode']);
add_shortcode('team-sign-up', [$bootstrap, 'formFieldsShortCode']);
add_shortcode('individual-sign-up', [$bootstrap, 'formFieldsShortCode']);


// if the WP_FRONT_PAGE_ONLY flat is true then relay all requests to the front page post
add_action('init', 'showSplashPageOnly', 1);
function showSplashPageOnly()
{
    if(filter_var(getenv('WP_FRONT_PAGE_ONLY'), FILTER_VALIDATE_BOOLEAN) === true) {
        include(locate_template('get-context.php'));
        $homeId = get_option('page_on_front');
        $context['page'] = Timber::get_post(get_option('page_on_front'));
        $context['body_class'] = 'splash page page-id-'.$homeId.' page-template-default';
        Timber::render('pages/splash.twig', $context);
        die();
    }

}



