<?php
/**
 * ShortCodeInterface
 *
 * Created 1/30/17 8:58 AM
 * Interface for short code classes
 *
 * @author Nate Nolting <naten@paulbunyan.net>
 * @package Pbc\GigaZone\ShortCode
 * @subpackage Subpackage
 */

namespace Pbc\GigaZoneGaming\ShortCodes;


interface ShortCodeInterface
{

    public static function shortCode($attributes, $content = null, $tag = null);

}