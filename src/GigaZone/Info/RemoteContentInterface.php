<?php
/**
 * RemoteContentInterface
 *
 * Created 10/19/16 7:57 AM
 * Interface for getting remote content.
 *
 * @author Nate Nolting <naten@paulbunyan.net>
 * @package GigaZone\Info
 */
namespace GigaZone\Info;

/**
 * Interface RemoteContentInterface
 * @package GigaZone\Info
 */
interface RemoteContentInterface
{

    /**
     * @return string
     */
    public function getInfo();

}