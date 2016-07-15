<?php
/** @var array $context */
if(!isset($context)) {
    $context = [];
}
$context = array_merge($context, Timber::get_context());

// theme directory
$context['theme_dir'] = parse_url($context['theme']->uri, PHP_URL_PATH);

// APP_ENV
$context['APP_ENV'] = getenv('APP_ENV');