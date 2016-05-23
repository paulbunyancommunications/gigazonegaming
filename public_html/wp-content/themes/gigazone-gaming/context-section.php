<?php
// get section
if(cnr_is_section()) {
    $context['section'] = $context['page'];
} elseif($getSection = cnr_get_the_section()) {
    $context['section'] = Timber::get_post($getSection);
} else {
    $context['section'] = false;
}

