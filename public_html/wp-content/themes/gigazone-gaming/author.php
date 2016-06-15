<?php
include(locate_template('get-context.php'));
$context['author'] = get_user_by('slug', get_query_var('author_name'));
Timber::render('pages/page.twig', $context);