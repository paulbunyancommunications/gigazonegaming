<?php
$context['masthead_photo_rotator'] = [];
/**
 * Generate image rotator in context if both
 * media tags plugin is installed and there's
 * images in the current page
 */
if (function_exists('get_attachments_by_media_tags') && is_object($context['page']) && property_exists($context['page'],
        'slug')
) {
    $rotatorImages = get_attachments_by_media_tags('media_tags=' . $context['page']->slug . '-masthead-photo-rotator&media_types=jpeg,jpg,gif,png&orderby=post_title&order=DESC');
    if ($rotatorImages) {
        foreach ($rotatorImages as $key => $img) {
            $imgPath = substr($img->guid, strlen($context['http_host']), strlen($img->guid));
            $rotatorImages[$key]->imager = '/bower_components/image.php/image.php/' . $img->ID . '.jpg?width={width}&amp;image=' . $imgPath;
        }

        $context['masthead_photo_rotator'] = $rotatorImages;
    }
}
