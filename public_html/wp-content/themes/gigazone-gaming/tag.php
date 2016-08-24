<?php
/**
 * The template for displaying tags
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
 * Output page to browser
 */
$context['is_tag'] = is_tag();
$context['section'] = false;
if ($context['is_tag']) {
    $term_id = get_query_var('tag_id');
    $taxonomy = 'post_tag';
    $args = 'include=' . $term_id;
    $terms = get_terms($taxonomy, $args);
    $context['tag'] = $terms[0];
    $tagPosts = get_posts('tag=' . $context['tag']->name);
    $ids = array_map(function ($post) {
        return $post->ID;
    }, $tagPosts);

    $args = array(
        'post__in' => $ids,
        'posts_per_page' => get_option('posts_per_page'),
        'paged' => $paged
    );

    query_posts($args);
    $context['posts'] = Timber::get_posts();
    $context['tag_label'] = "Tag";
    $context['pagination'] = Timber::get_pagination();
}

Timber::render('pages/page.twig', $context);