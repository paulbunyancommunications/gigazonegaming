<?php
/**
 * Svg
 *
 * Created 4/1/16 8:08 AM
 * SVG helper class
 *
 * @author Nate Nolting <naten@paulbunyan.net>
 * @package GigaZone\Svg
 * @subpackage Subpackage
 */

namespace GigaZone\Svg;


class Svg
{

    public static function header()
    {
        return header('Content-type: image/svg+xml');
    }
    
    public static function validateFillColor($value, $default=000000)
    {
        $background = isset($value) ? preg_replace("/[^A-Fa-f0-9]/", '', $value) : '';
        $background = (strlen($background) === 3 || strlen($background) === 6) ? $background : $default;
        return $background;
    }

    public static function fillColor($value, $default, &$content)
    {
        $value = self::validateFillColor($value, $default);
        $content = str_replace('#'.$default, '#'.$value, $content);
        return $content;
    }
    
}