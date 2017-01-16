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

class GigaZoneFromPaulBunyan extends RemoteContent implements RemoteContentInterface
{
    const PBC_PATH = "https://www.paulbunyan.net";

    public function __construct($config = [])
    {
        parent::__construct($config);

    }

    public function getInfo()
    {
        return $this->getGigazoneInfo();
    }

    public function getGigazoneInfo()
    {
        $key = md5(__CLASS__ . __METHOD__);
        $item = $this->pool->getItem($key);
        if ($item->isMiss()) {

            $item->lock();

            $gigazoneInfo = $this->getSource($this->uri);
            $this->fixUrls($gigazoneInfo);

            $dom = $this->getDom($gigazoneInfo);

            $gigazoneContent = $dom->find('#gigazone-content-container', 0);
            $content = $gigazoneContent->innertext;

            $scripts = $this->getScripts($dom);
            foreach ($scripts as $script) {
                $this->addScripts($content, $script);
            }

            $styles = $this->getLinkedStyles($dom);
            foreach ($styles as $style) {
                if (strpos($style->outertext, 'gigazone') !== false) {
                    $content .= $style->outertext;
                    continue;
                }
                $this->addStyles($content, $style);
            }
            // Cache expires 5 minutes.
            $item->expiresAfter(RemoteContent::POOL_EXPIRE);

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
            'href="/gigazone/',
            'src="/gigazone/'
        ];
        $pathsReplace = [
            '\'' . GigaZoneFromPaulBunyan::PBC_PATH . '/images/',
            'src="' . GigaZoneFromPaulBunyan::PBC_PATH . '/images/',
            'src="' . GigaZoneFromPaulBunyan::PBC_PATH . '/Scripts/',
            'href="' . GigaZoneFromPaulBunyan::PBC_PATH . '/style.css"',
            'href="' . GigaZoneFromPaulBunyan::PBC_PATH . '/css/',
            'src="' . GigaZoneFromPaulBunyan::PBC_PATH . '/menu.js"',
            'src="' . GigaZoneFromPaulBunyan::PBC_PATH . '/conditions/',
            'href="' . GigaZoneFromPaulBunyan::PBC_PATH . '/gigazone/',
            'src="' . GigaZoneFromPaulBunyan::PBC_PATH . '/gigazone/'
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
        if (strpos($element->outertext, 'MM_swapImage') !== false) {
            return;
        }

        $string .= $element->outertext;

    }

    private function addStyles(&$content, $style)
    {
        $script = $style->href;
        $styles = $this->getSource($script);
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