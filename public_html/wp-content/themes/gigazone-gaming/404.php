<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package WordPress
 * @subpackage gigazone-gaming
 * @since Red Lake Electric Coop 1.0
 */

include(locate_template('get-context.php'));
/**
 * Output 404 to browser
 */
Timber::render('pages/404.twig', $context);