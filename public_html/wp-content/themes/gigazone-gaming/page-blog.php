<?php
/**
 * The template for displaying blog page
 *
 * @package WordPress
 * @subpackage gigazone-gaming
 * @since Gigazone Gaming 1.0
 */
include(locate_template('get-context.php'));
$context['posts'] = $wpdb->get_results('SELECT * FROM '. $wpdb->posts .' WHERE post_parent = '.$context['section']->ID .' AND post_status = "publish"');
/**
 * Output page to browser
 */
Timber::render('pages/page.twig', $context);
