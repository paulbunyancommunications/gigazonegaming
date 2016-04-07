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
if(is_front_page()) {
    Timber::render('pages/home.twig', $context);   
} else {
    Timber::render('pages/page.twig', $context);
}