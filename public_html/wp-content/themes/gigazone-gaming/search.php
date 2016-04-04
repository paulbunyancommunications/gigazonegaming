<?php
include(locate_template('get-context.php'));
/**
 * Output page to browser
 */
Timber::render('pages/search.twig', $context);
