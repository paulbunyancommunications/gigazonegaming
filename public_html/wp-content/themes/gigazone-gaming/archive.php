<?php

include(locate_template('get-context.php'));
$context['is_archive'] = is_archive();
$context['posts'] = Timber::get_posts();
$context['pagination'] = Timber::get_pagination();
Timber::render('pages/page.twig', $context);