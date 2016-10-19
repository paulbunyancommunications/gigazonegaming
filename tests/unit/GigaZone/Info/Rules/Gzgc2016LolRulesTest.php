<?php
namespace Tests\Unit\GigaZone\Info;

use GigaZone\Info\Rules\Gzgc2016LolRules;
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

class Gzgc2016LolRulesTest extends \TestCase
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
    public function getRulesThatIsNotCached()
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
        $info = new Gzgc2016LolRules(['pool' => $pool, 'uri' => 'https://docs.google.com/feeds/download/documents/export/Export?id=1sQA2OrOn0txoKcxPZMGZX_GjJNjQAyShb18l60IkilY&exportFormat=html']);

        $get = $info->getInfo();
        $this->assertContains('GIGAZONE GAMING TOURNAMENT 2016', $get, '', true);
        $this->assertContains('LEAGUE OF LEGENDS OFFICIAL RULES', $get, '', true);
    }

    /**
     * @test
     */
    public function getGigazoneInfoThatIsCached()
    {
        $pool = \Mockery::mock();
        $pool->shouldReceive('save');
        $item = \Mockery::mock();
        $item->shouldReceive('isMiss')->andReturn(false);
        $pool->shouldReceive('getItem')->andReturn($item);
        $item->shouldReceive('get')->andReturn(file_get_contents('https://docs.google.com/feeds/download/documents/export/Export?id=1sQA2OrOn0txoKcxPZMGZX_GjJNjQAyShb18l60IkilY&exportFormat=html'));
        $info = new Gzgc2016LolRules(['pool' => $pool]);

        $get = $info->getInfo();
        $this->assertContains('GIGAZONE GAMING TOURNAMENT 2016', $get, '', true);
        $this->assertContains('LEAGUE OF LEGENDS OFFICIAL RULES', $get, '', true);
    }
}
