<?php
/**
 * Gigazone
 *
 * Created 5/4/16 8:51 AM
 * Get info on Gigazone from main pbc site
 *
 * @author Nate Nolting <naten@paulbunyan.net>
 * @package App\Http\Controllers\Frontend\Info
 */

namespace GigaZone\Info;

use Sunra\PhpSimple\HtmlDomParser;


class GigaZoneFromPaulBunyan
{
    const PBC_PATH = "https://www.paulbunyan.net";
    protected $pool;

    public function __construct()
    {
        $options = ['path' => dirname(dirname(dirname(__DIR__))) . '/storage/framework/cache'];
        $driver = new \Stash\Driver\FileSystem($options);
        $this->pool = new \Stash\Pool($driver);
    }

    public function getGigazoneInfo()
    {
        $key = md5(__CLASS__ . __METHOD__);
        $item = $this->pool->getItem($key);
        if ($item->isMiss()) {

            $item->lock();

            $gigazoneInfo = $this->getContent();
            $this->fixUrls($gigazoneInfo);

            $dom = HtmlDomParser::str_get_html($gigazoneInfo, true, true, DEFAULT_TARGET_CHARSET, false);

            $gigazoneContent = $dom->find('#gigazone-content-container', 0);
            $content = $gigazoneContent->innertext;

            $scripts = $dom->find('script');
            foreach ($scripts as $script) {
                $this->addScripts($content, $script);
            }

            $styles = $scripts = $dom->find('link');
            foreach ($styles as $style) {
                if (strpos($style->outertext, 'gigazone') !== false) {
                    $content .= $style->outertext;
                    continue;
                }
                $this->addStyles($content, $style);
            }
            // Cache expires 5 minutes.
            $item->expiresAfter(300);

            $this->pool->save($item->set($content));

        } else {
            $content = $item->get();
        }
        return $content;
    }

    protected function getContent($path = null)
    {
        if (!$path) {
            $path = GigaZoneFromPaulBunyan::PBC_PATH . '/gigazone/index.html';
        }

        $key = md5(__CLASS__ . __METHOD__ . $path);
        $item = $this->pool->getItem($key);
        if ($item->isMiss()) {
            $item->lock();

            // Cache expires 5 minutes.
            $item->expiresAfter(300);
            $content = file_get_contents($path);
            $this->pool->save($item->set($content));
        } else {
            $content = $item->get();
        }

        return $content;
    }

    private function fixUrls(&$html)
    {
        $pathsFind = [
            '\'/images/',
            'src="/images/',
            'src="/Scripts/',
            'href="/style.css"',
            'href="/css/',
            'src="/menu.js"',
            'src="/conditions/',
            'href="/gigazone/'
        ];
        $pathsReplace = [
            '\'' . GigaZoneFromPaulBunyan::PBC_PATH . '/images/',
            'src="' . GigaZoneFromPaulBunyan::PBC_PATH . '/images/',
            'src="' . GigaZoneFromPaulBunyan::PBC_PATH . '/Scripts/',
            'href="' . GigaZoneFromPaulBunyan::PBC_PATH . '/style.css"',
            'href="' . GigaZoneFromPaulBunyan::PBC_PATH . '/css/',
            'src="' . GigaZoneFromPaulBunyan::PBC_PATH . '/menu.js"',
            'src="' . GigaZoneFromPaulBunyan::PBC_PATH . '/conditions/',
            'href="' . GigaZoneFromPaulBunyan::PBC_PATH . '/gigazone/'
        ];
        $html = str_replace($pathsFind, $pathsReplace, $html);

    }

    private function addScripts(&$string, $element)
    {

        if (strpos($element->outertext, 'google-analytics') !== false) {
            return;
        }
        if (strpos($element->outertext, 'AllWebMenus') !== false) {
            return;
        }
        if (strpos($element->outertext, 'ddtabcontent') !== false) {
            return;
        }

        $string .= $element->outertext;

    }

    private function addStyles(&$content, $style)
    {
        $script = $style->href;
        $styles = $this->getContent($script);
        // match things like p.gz {}
        if ($c = preg_match_all("/((?:[a-z][a-z0-9_]*))(\\.)(gz)(\\s+)(\\{.*?\\})/is", $styles, $matches)) {
            $content .= '<style type="text/css">';
            foreach ($matches[0] as $match) {
                $content .= $match;
            }
            $content .= '</style>';
        }
        // match things like .gz-something {}
        if ($c = preg_match_all("/(.)(gz).*?(\\s+)(\\{.*?\\})/is", $styles, $matches)) {
            $content .= '<style type="text/css">';
            foreach ($matches[0] as $match) {
                $content .= $match;
            }
            $content .= '</style>';
        }
    }
}