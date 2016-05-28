<?php
// get section
if (cnr_is_section()) {
    $context['section'] = $context['page'];
} elseif ($getSection = cnr_get_the_section()) {
    $context['section'] = Timber::get_post($getSection);
} elseif (get_post()->post_type === 'page') {
    $context['section'] = Timber::get_post(get_post()->ID);
} else {
    $context['section'] = false;
}

