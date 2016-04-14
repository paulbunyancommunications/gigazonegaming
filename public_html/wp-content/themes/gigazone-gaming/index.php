<?php
/**
 * The template for displaying pages
 *
 * @package WordPress
 * @subpackage gigazone-gaming
 * @since Red Lake Electric Coop 1.0
 */


include(locate_template('get-context.php'));
/**
 * Output page to browser
 */
// if the WP_FRONT_PAGE_ONLY flat is true then relay all requests to the front page post
if(filter_var(getenv('WP_FRONT_PAGE_ONLY'), FILTER_VALIDATE_BOOLEAN) === true) {
    $homeId = get_option('page_on_front');
    $context['page'] = Timber::get_post(get_option('page_on_front'));
    $context['body_class'] = 'home page page-id-'.$homeId.' page-template-default';
    Timber::render('pages/splash.twig', $context);
    die();
}

if(is_front_page()) {
    Timber::render('pages/splash.twig', $context);   
} else {
    Timber::render('pages/page.twig', $context);
}

//echo '<code><pre>' . print_r(Timber::get_context(), true) . '</pre></code>';