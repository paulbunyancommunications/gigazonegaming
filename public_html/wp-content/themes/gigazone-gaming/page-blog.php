<?php
/**
 * The template for displaying blog page
 *
 * @package WordPress
 * @subpackage gigazone-gaming
 * @since Gigazone Gaming 1.0
 */
include(locate_template('get-context.php'));

$context['posts'] = Timber::get_posts('post_parent=' . $context['section']->id.'&limit=99');
/**
 * Output page to browser
 */
Timber::render('pages/page.twig', $context);
