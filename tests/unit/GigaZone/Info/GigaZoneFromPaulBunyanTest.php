<?php
namespace Tests\Unit\GigaZone\Info;

use GigaZone\Info\GigaZoneFromPaulBunyan;
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

class GigaZoneFromPaulBunyanTest extends \TestCase
{

    public function setUp()
    {
        parent::setUp();
    }

    public function tearDown()
    {
        parent::tearDown();
        \Mockery::close();
    }

    /**
     * Test getting Gigazone content from remote source
     * @test
     */
    public function getGigazoneInfoThatIsNotCached()
    {
        $driver = \Mockery::mock();
        $pool = \Mockery::mock();
        $pool->shouldReceive('save');
        $item = \Mockery::mock();
        $item->shouldReceive('isMiss')->andReturn(true);
        $item->shouldReceive('lock');
        $item->shouldReceive('expiresAfter');
        $item->shouldReceive('set');
        $pool->shouldReceive('getItem')->andReturn($item);
        $info = new GigaZoneFromPaulBunyan(['pool' => $pool, 'uri' => GigaZoneFromPaulBunyan::PBC_PATH . '/gigazone/index.html']);

        $get = $info->getGigazoneInfo();
        $this->assertContains('GigaZone', $get);
    }

    /**
     * Test getting Gigazone content from remote source on the getInfo method
     * @test
     */
    public function getGigazoneInfoThatIsNotCachedFromGetInfo()
    {
        $driver = \Mockery::mock();
        $pool = \Mockery::mock();
        $pool->shouldReceive('save');
        $item = \Mockery::mock();
        $item->shouldReceive('isMiss')->andReturn(true);
        $item->shouldReceive('lock');
        $item->shouldReceive('expiresAfter');
        $item->shouldReceive('set');
        $pool->shouldReceive('getItem')->andReturn($item);
        $info = new GigaZoneFromPaulBunyan(['pool' => $pool, 'uri' => GigaZoneFromPaulBunyan::PBC_PATH . '/gigazone/index.html']);

        $get = $info->getInfo();
        $this->assertContains('GigaZone', $get);
    }

    /**
     * @test
     */
    public function getGigazoneInfoThatIsCached()
    {
        $driver = \Mockery::mock();
        $pool = \Mockery::mock();
        $pool->shouldReceive('save');
        $item = \Mockery::mock();
        $item->shouldReceive('isMiss')->andReturn(false);
        $pool->shouldReceive('getItem')->andReturn($item);
        $item->shouldReceive('get')->andReturn(file_get_contents(GigaZoneFromPaulBunyan::PBC_PATH . '/gigazone/index.html'));
        $info = new GigaZoneFromPaulBunyan(['pool' => $pool]);

        $get = $info->getGigazoneInfo();
        $this->assertContains('GigaZone', $get);
    }
}
