<?php
/**
 * Current page content
 */
$posts = Timber::get_posts();
if (count($posts) === 1) {
    $context['page'] = array_shift($posts);
}