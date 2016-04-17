<?php
if (stripos(TimberURLHelper::get_host(), parse_url(getenv('APP_URL_PRODUCTION'), PHP_URL_HOST))
    && getenv('APP_ENV') === 'production'
) {
    $context['google_analytics'] = Timber::compile('partials/google-analytics.twig', ['key' => 'UA-76362808-1']);
}