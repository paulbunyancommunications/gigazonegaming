<?php
/**
 * ${CLASS_NAME}
 *
 * Created 10/17/16 4:57 PM
 * Description of this file here....
 *
 * @author Nate Nolting <naten@paulbunyan.net>
 * @package GigaZone\Info
 * @subpackage Subpackage
 */

namespace GigaZone\Info;

use Sunra\PhpSimple\HtmlDomParser;

/**
 * Class remoteContent
 * @package GigaZone\Info
 */
class RemoteContent implements RemoteContentInterface
{

    /**
     *
     */
    const POOL_EXPIRE = 300;

    protected $config = [];
    /**
     * @var \Stash\Pool
     */
    protected $pool;

    protected $uri;

    /**
     * remoteContent constructor.
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->prepProperties($config);
    }

    public function getInfo() {
        return $this->getSource($this->uri);
    }

    /**
     * @param $config
     */
    private function prepProperties($config)
    {
        $this->config = $config;
        if ($config) {
            foreach ($config as $key => $value) {
                if (property_exists($this, $key)) {
                    $this->{$key} = $value;
                }
            }
        }
    }


    /**
     * Get Remote content and cache it
     *
     * @param $path
     * @return string
     */
    public function getSource($path)
    {
        $key = md5(__CLASS__ . __METHOD__ . $path);
        $item = $this->pool->getItem($key);
        if ($item->isMiss()) {
            $item->lock();

            // Cache expires 5 minutes.
            $item->expiresAfter(self::POOL_EXPIRE);
            $content = file_get_contents($path);
            $this->pool->save($item->set($content));
        } else {
            $content = $item->get();
        }

        return $content;
    }

    /**
     * Get the full dom content from string
     *
     * @param $string
     * @return mixed
     */
    public function getDom($string)
    {
        return HtmlDomParser::str_get_html($string, true, true, DEFAULT_TARGET_CHARSET, false);
    }

    /**
     * Get scripts from dom
     *
     * @param $dom
     * @return mixed
     */
    public function getScripts($dom)
    {
        return $dom->find('script');
    }

    /**
     * Get style links from dom
     *
     * @param $dom
     * @return mixed
     */
    public function getLinkedStyles($dom)
    {
        return $dom->find('link');
    }

    /**
     * Get styles from dom
     *
     * @param $dom
     * @return mixed
     */
    public function getStyles($dom)
    {
        return $dom->find('style');
    }
}