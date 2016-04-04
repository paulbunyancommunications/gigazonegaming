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
Timber::render('pages/page.twig', $context);

var_dump($context);
//echo '<code><pre>' . print_r($context, true) . '</pre></code>';