<?php
/**
 * The template for displaying category
 *
 * @package WordPress
 * @subpackage gigazone-gaming
 * @since Gigazone Gaming 1.0
 */

global $paged;
if (!isset($paged) || !$paged){
    $paged = 1;
}

/**
 * Get page context
 */
include(locate_template('get-context.php'));

$context['section'] = false;
$context['is_category'] = is_category();
if ($context['is_category']) {
    $terms = get_category(get_query_var('cat'));
    $context['category'] = $terms;

    $args = array(
        'category_name' => $context['category']->name,
        'posts_per_page' => get_option('posts_per_page'),
        'paged' => $paged
    );

    query_posts($args);
    $context['posts'] = Timber::get_posts();
    $context['category_label'] = "Categories";
    $context['pagination'] = Timber::get_pagination();
    $context['paged'] = $paged;
}

/**
 * Output page to browser
 */
Timber::render('pages/page.twig', $context);