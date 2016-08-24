<?php

global $paged;
if (!isset($paged) || !$paged){
    $paged = 1;
}

include(locate_template('get-context.php'));
$context['is_author'] = is_author();
$context['author'] = get_user_by('slug', get_query_var('author_name'));

$args = array(
    'post_author' => $context['author']->ID,
    'posts_per_page' => get_option('posts_per_page'),
    'paged' => $paged
);

query_posts($args);
$context['posts'] = Timber::get_posts();
$context['pagination'] = Timber::get_pagination();

Timber::render('pages/page.twig', $context);