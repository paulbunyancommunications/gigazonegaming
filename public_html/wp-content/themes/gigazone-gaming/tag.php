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
if (is_tag()) {
    $term_id = get_query_var('tag_id');
    $taxonomy = 'post_tag';
    $args = 'include=' . $term_id;
    $terms = get_terms($taxonomy, $args);
    $context['tag'] = $terms[0];
    $tagPosts = get_posts('tag=' . $context['tag']->name);
    $context['posts'] = Timber::get_posts(
        array_map(function ($post) {
            return $post->ID;
        },
            $tagPosts)
    );
    $context['tag_label'] = "Tag";
}

Timber::render('pages/page.twig', $context);