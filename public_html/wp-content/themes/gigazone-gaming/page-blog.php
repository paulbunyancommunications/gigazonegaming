<?php
/**
 * The template for displaying blog page
 *
 * @package WordPress
 * @subpackage gigazone-gaming
 * @since Gigazone Gaming 1.0
 */
global $paged;
if (!isset($paged) || !$paged){
    $paged = 1;
}

include(locate_template('get-context.php'));

/**
 * Get the posts with pagination
 */
$args = array(
    'post_parent' => $context['section']->ID,
    'posts_per_page' => get_option('posts_per_page'),
    'paged' => $paged,
    'orderby' => 'post_date',
    'order' => 'DESC'
);
query_posts($args);
$context['posts'] = Timber::get_posts();
$context['pagination'] = Timber::get_pagination();
$context['paged'] = $paged;
/**
 * Output page to browser
 */
Timber::render('pages/page.twig', $context);
