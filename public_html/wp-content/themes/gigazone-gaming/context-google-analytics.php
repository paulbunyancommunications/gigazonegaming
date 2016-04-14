<?php
if (stripos(TimberURLHelper::get_host(), 'gigazonegaming.com') && getenv('APP_ENV') === 'production') {
    $context['google_analytics'] = Timber::compile('partials/google-analytics.twig', ['key' => 'UA-76362808-1']);
}