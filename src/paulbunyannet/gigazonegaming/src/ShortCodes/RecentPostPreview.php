<?php
/**
 * RecentPostPreview
 *
 * Created 2/16/17 10:24 AM
 * Handler for the shortcode [recent-post-preview]
 *
 * @author Nate Nolting <naten@paulbunyan.net>
 * @package Pbc\GigaZone\ShortCodes
 */

namespace Pbc\GigaZoneGaming\ShortCodes;


class RecentPostPreview implements ShortCodeInterface
{

    /**
     * Generate recent posts list with returned view
     * See attribute list at https://codex.wordpress.org/Function_Reference/wp_get_recent_posts
     *
     * @param array $attributes
     * @param null $content
     * @param null $tag
     */
    public static function shortCode($attributes, $content = null, $tag = null)
    {
        $attr = shortcode_atts(array(
            'wrapper_class' => '',
            'title' => '',
            'numberposts' => 5,
            'offset' => 0,
            'category' => 0,
            'orderby' => 'post_date',
            'order' => 'DESC',
            'include' => '',
            'exclude' => '',
            'meta_key' => '',
            'meta_value' =>'',
            'post_type' => 'post',
            'post_status' => 'draft, publish, future, pending, private',
            'suppress_filters' => true
        ), $attributes);

        $params = [
            'related' => wp_get_recent_posts($attr, OBJECT),
            'related_title' => $attr['title'],
            'related_count' => $attr['numberposts'],
            'wrapper_class' => $attr['wrapper_class'],
        ];

        return \Timber::compile(['partials/post/post-related.twig'], $params);



    }
}