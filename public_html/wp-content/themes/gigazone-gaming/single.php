<?php
use Underscore\Types\Arrays;

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

// find related posts that are of a similar tag and the tag is higher than the average
$termIds = Arrays::pluck($context['post']->terms, 'term_id');
$terms = get_terms('post_tag', ['include' => $termIds]);
$average = count($terms) ? Arrays::average(Arrays::pluck($terms, 'count')) : 0;
$lessThanAverageTags = Arrays::filter($terms, function ($value) use ($average) {
    return $value->count <= $average;
});
$context['related'] = Timber::get_posts(['tag__in' => Arrays::pluck($lessThanAverageTags, 'term_id'), 'post__not_in' => [get_post()->ID]]);

// return output
Timber::render('pages/page.twig', $context);