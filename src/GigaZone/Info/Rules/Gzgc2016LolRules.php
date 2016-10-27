<?php
/**
 * Gzgc2016LolRules
 *
 * Created 10/17/16 4:56 PM
 * Getter for the 2016 LoL tournament Rules from Google Docs.
 *
 * @author Nate Nolting <naten@paulbunyan.net>
 * @package GigaZone\Info\Rules
 */

namespace GigaZone\Info\Rules;

use GigaZone\Info\RemoteContent;
use GigaZone\Info\RemoteContentInterface;

class Gzgc2016LolRules extends RemoteContent implements RemoteContentInterface
{

    public function __construct(array $config)
    {
        parent::__construct($config);
    }

    public function getInfo()
    {
        $source = $this->getSource($this->uri);
        $dom = $this->getDom($source);
        $content = $dom->find('body', 0);
        return $content->innertext;
    }

}