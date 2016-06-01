<?php
/**
 * The template for displaying home page
 *
 * @package WordPress
 * @subpackage gigazone-gaming
 * @since Gigazone Gaming 1.0
 */
include(locate_template('get-context.php'));
/**
 * Output page to browser
 */
Timber::render('pages/page.twig', $context);