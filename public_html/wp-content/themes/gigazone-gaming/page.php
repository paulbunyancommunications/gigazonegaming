<?php
/**
 * The template for displaying pages
 *
 * @package WordPress
 * @subpackage gigazone-gaming
 * @since Gigazone Gaming 1.0
 */
include(locate_template('get-context.php'));

if (is_front_page()) {
    $context['post'] = get_post(get_option('page_on_front'));

    // get recent posts for "related"
    $context['related'] = Timber::get_posts([
        'numberpost' => 4,
        'orderby' => 'post_date',
        'order' => 'DESC'
    ]);
    $context['related_title'] = "Recent posts";

} else {
    $context['post'] = $context['section'] ? Timber::get_post($context['section']->ID) : Timber::get_post(get_post()->ID);
}

/**
 * Output page to browser
 */
Timber::render('pages/page.twig', $context);