<?php
if (env('APP_ENV') === 'production') {
    $context['google_analytics'] = Timber::compile('partials/google-analytics.twig', ['key' => 'UA-76362808-1']);
}