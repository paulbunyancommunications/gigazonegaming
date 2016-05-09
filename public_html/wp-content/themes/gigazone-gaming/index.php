<?php
/**
 * The template for displaying pages
 *
 * @package WordPress
 * @subpackage gigazone-gaming
 * @since Red Lake Electric Coop 1.0
 */
include(locate_template('get-context.php'));
/**
 * Output page to browser
 */
Timber::render('pages/front-page.twig', $context);