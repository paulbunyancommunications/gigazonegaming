<?php
/**
 * The template for displaying pages
 *
 * @package WordPress
 * @subpackage gigazone-gaming
 * @since Gigazone Gaming 1.0
 */
include(locate_template('get-context.php'));

$context['post'] = $context['section'] ? Timber::get_post($context['section']->ID) : Timber::get_post(get_post()->ID);
/**
 * Output page to browser
 */
Timber::render('pages/page.twig', $context);