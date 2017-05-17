<?php
/**
 * Splash
 *
 * Created 2/17/17 11:55 AM
 * Handle the "splash" container
 *
 * @author Nate Nolting <naten@paulbunyan.net>
 * @package Pbc\GigaZoneGaming\ShortCodes
 */

namespace Pbc\GigaZoneGaming\ShortCodes;


class Splash implements ShortCodeInterface
{
    public static function shortCode($attributes, $content = null, $tag = null)
    {
        $attr = shortcode_atts(array(
            'footer' => '',
            'class' => 'splash'
        ), $attributes);

        $params = [
            'content' => $content,
            'footer' => $attr['footer'],
            'class' => $attr['class']
        ];
        return \Timber::compile(['partials/splash.twig'], $params);
    }

}