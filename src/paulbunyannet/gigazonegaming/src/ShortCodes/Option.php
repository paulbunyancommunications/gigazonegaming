<?php
/**
 * Option
 *
 * Created 2/17/17 11:55 AM
 * Handle getting an option from the wp_options db
 *
 * @author Nate Nolting <naten@paulbunyan.net>
 * @package Pbc\GigaZoneGaming\ShortCodes
 */

namespace Pbc\GigaZoneGaming\ShortCodes;


class Option implements ShortCodeInterface
{
    public static function shortCode($attributes, $content = null, $tag = null)
    {
        $attr = shortcode_atts(array(
            'key' => 'blogname',
        ), $attributes);

        /** @var string $key */
        return get_option($attr['key']);
    }

}