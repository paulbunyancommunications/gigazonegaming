<?php
// get section
$getPost = get_post();
if (function_exists('cnr_is_section') && cnr_is_section()) {
    $context['section'] = $context['page'];
} elseif (function_exists('cnr_get_the_section') && $getSection = cnr_get_the_section()) {
    $context['section'] = Timber::get_post($getSection);
} elseif ($getPost && $getPost->post_type === 'page') {
    $context['section'] = Timber::get_post(get_post()->ID);
} else {
    $context['section'] = false;
}
