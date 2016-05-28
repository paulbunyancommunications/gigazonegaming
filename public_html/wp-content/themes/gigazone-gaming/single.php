<?php
/**
 * The template for displaying pages
 *
 * @package WordPress
 * @subpackage gigazone-gaming
 * @since Gigazone Gaming 1.0
 */
include(locate_template('get-context.php'));
/**
 * Output page to browser
 */
$context['post'] = Timber::get_post(get_post()->ID);
$context['single'] = true;
Timber::render('pages/page.twig', $context);