<?php
include(locate_template('get-context.php'));
Timber::render('pages/page.twig', $context);