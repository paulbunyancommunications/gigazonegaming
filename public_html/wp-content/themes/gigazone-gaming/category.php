<?php
/**
 * The template for displaying tags
 *
 * @package WordPress
 * @subpackage gigazone-gaming
 * @since Gigazone Gaming 1.0
 */
include(locate_template('get-context.php'));
/**
 * Output page to browser
 */
$context['section'] = false;
if (is_category()) {
    $terms = get_category(get_query_var('cat'));
    $context['category'] = $terms;
    $tagPosts = get_posts('category_name=' . $context['category']->name);
    $ids = array_map(function ($post) {
        return $post->ID;
    }, $tagPosts);
    $context['posts'] = Timber::get_posts(['post__in' => $ids]);
    $context['category_label'] = "Categories";
}

Timber::render('pages/page.twig', $context);