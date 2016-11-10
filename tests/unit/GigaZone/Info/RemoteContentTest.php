<?php
namespace Tests\Unit\GigaZone\Info;

use GigaZone\Info\GigaZoneFromPaulBunyan;
use GigaZone\Info\RemoteContent;
use Stash\Driver\FileSystem;
use Stash\Pool;

/**
 * GigaZoneFromPaulBunyanTest
 *
 * Created 10/18/16 10:04 PM
 * Tests for the GigaZoneFromPaulBunyan class
 *
 * @author Nate Nolting <naten@paulbunyan.net>
 * @package Tests\Unit\GigaZone\Info
 * @subpackage Subpackage
 */

class RemoteContentTest extends \TestCase
{

    protected $file;
    public function setUp()
    {
        parent::setUp();
    }

    public function tearDown()
    {
        parent::tearDown();
        \Mockery::close();

        if ($this->file && file_exists($this->file)) {
            unlink($this->file);
        }
    }


    /**
     * Test that the getSource method returns the content from getInfo
     * @test
     */
    public function getSourceReturnsGetInfo()
    {

        $this->file = __DIR__ . '/'.__FUNCTION__.'.html';
        file_put_contents($this->file, '<html><head><title>'. __METHOD__ .'</title></head><body><p>'. __METHOD__ .'</p></body></html>');
        $driver = \Mockery::mock();
        $pool = \Mockery::mock();
        $pool->shouldReceive('save');
        $item = \Mockery::mock();
        $item->shouldReceive('isMiss')->andReturn(true);
        $item->shouldReceive('lock');
        $item->shouldReceive('expiresAfter');
        $item->shouldReceive('set');
        $pool->shouldReceive('getItem')->andReturn($item);
        $content = new RemoteContent(['pool' => $pool, 'uri' => $this->file]);
        $this->assertContains('<p>' . __METHOD__ .'</p>', $content->getInfo());
        $this->assertSame($content->getInfo(), $content->getSource($this->file));
    }

    /**
     * @test
     */
    public function getEmbeddedStyleFromSource()
    {
        $this->file = __DIR__ . '/'.__FUNCTION__.'.html';
        $css = '<style>strong {font-size: 14px; font-weight: 700;}</style>';
        file_put_contents($this->file, '<html><head>'.$css.'<title>'. __METHOD__ .'</title></head><body><p>'. __METHOD__ .'</p></body></html>');
        $pool = \Mockery::mock();
        $item = \Mockery::mock();
        $pool->shouldReceive('getItem')->andReturn($item);
        $content = new RemoteContent(['pool' => $pool, 'uri' => $this->file]);

        $dom = \Mockery::mock();
        $dom->shouldReceive('find')->with('style')->once()->andReturn($css);
        $this->assertSame($css, $content->getStyles($dom));
    }
    /**
     * @test
     */
    public function getLinkedStylesFromSource()
    {
        $this->file = __DIR__ . '/'.__FUNCTION__.'.html';
        $css = '<link src="/some/css.css" type="text/css" />';
        file_put_contents($this->file, '<html><head>'.$css.'<title>'. __METHOD__ .'</title></head><body><p>'. __METHOD__ .'</p></body></html>');
        $pool = \Mockery::mock();
        $item = \Mockery::mock();
        $pool->shouldReceive('getItem')->andReturn($item);
        $content = new RemoteContent(['pool' => $pool, 'uri' => $this->file]);

        $dom = \Mockery::mock();
        $dom->shouldReceive('find')->with('link')->once()->andReturn($css);
        $this->assertSame($css, $content->getLinkedStyles($dom));
    }
    /**
     * @test
     */
    public function getLinkedScriptsFromSource()
    {
        $this->file = __DIR__ . '/'.__FUNCTION__.'.html';
        $js = '<script type="application/javascript"></script>';
        file_put_contents($this->file, '<html><head>'.$js.'<title>'. __METHOD__ .'</title></head><body><p>'. __METHOD__ .'</p></body></html>');
        $pool = \Mockery::mock();
        $item = \Mockery::mock();
        $pool->shouldReceive('getItem')->andReturn($item);
        $content = new RemoteContent(['pool' => $pool, 'uri' => $this->file]);

        $dom = \Mockery::mock();
        $dom->shouldReceive('find')->with('script')->once()->andReturn($js);
        $this->assertSame($js, $content->getScripts($dom));
    }
}
