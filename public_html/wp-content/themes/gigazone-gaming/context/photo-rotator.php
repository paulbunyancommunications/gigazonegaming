<?php
$context['masthead_photo_rotator'] = [];
/**
 * Generate image rotator in context if both
 * media tags plugin is installed and there's
 * images in the current page
 */
if (function_exists('get_attachments_by_media_tags')
    && isset($context['page'])
    && is_object($context['page'])
    && property_exists($context['page'], 'slug')
) {
    $rotatorImages = get_attachments_by_media_tags('media_tags=' . $context['page']->slug . '-masthead-photo-rotator&media_types=jpeg,jpg,gif,png&orderby=post_title&order=DESC');

    if ($rotatorImages) {
        foreach ($rotatorImages as $key => $img) {
            $imgPath = parse_url($img->guid, PHP_URL_PATH);
            $rotatorImages[$key]->imager = '/bower_components/image.php/image.php/' . $img->ID . '.jpg?width={width}&amp;image=' . $imgPath;
        }
        $mediaKeyForAttachments = array_values(
            array_filter(get_mediatags(), function ($tag) use ($context) {
                return $tag->slug === $context['page']->slug . '-masthead-photo-rotator';
            }));

        // check the description field for a json array with config items in it
        $desc = @json_decode($mediaKeyForAttachments[0]->description);
        // if randomize if found then shuffle the rotator images
        if ($desc
            && property_exists($desc, 'randomize')
            && \utilphp\util::str_to_bool($desc->randomize) === true
        ) {
            shuffle($rotatorImages);
        }
        $context['masthead_photo_rotator'] = $rotatorImages;
    }
}
