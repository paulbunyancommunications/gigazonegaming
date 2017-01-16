<?php
/** @var array $context */
if(!isset($context)) {
    $context = [];
}
$context = array_merge($context, Timber::get_context());

// theme directory
$context['theme_dir'] = parse_url($context['theme']->uri, PHP_URL_PATH);
/** @todo temp fix for scheme, remove once fixed on the Timber side */
$context['http_host'] = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') .'://'. parse_url($context['http_host'], PHP_URL_HOST);

// use if needing first post
$context['first_post'] = get_posts('numberposts=1&order=ASC')[0];
