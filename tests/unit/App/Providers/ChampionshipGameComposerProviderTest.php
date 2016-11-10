<?php
namespace Tests\Unit\App\Providers;

/**
 * ChampionshipGameComposerProviderTest
 *
 * Created 7/7/16 5:35 PM
 * Tests for class ChampionshipGameComposerProvider
 *
 * @author Nate Nolting <naten@paulbunyan.net>
 * @package Tests\Unit\App\Providers
 */

use App\Models\Championship\Player;
use App\Models\Championship\Team;
use App\Providers\ChampionshipGameComposerProvider;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Mockery;

class ChampionshipGameComposerProviderTest extends \TestCase
{

    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();
        exec('php artisan migrate:refresh');
    }

    public function tearDown()
    {
        parent::tearDown();
        Mockery::close();
    }

    public static function tearDownAfterClass()
    {
        exec('php artisan migrate:refresh');
    }

    /**
     * Test getting the result of the ChampionshipGameComposerProvider::teams method
     * @test
     */
    public function teams_will_return_an_array_of_teams()
    {
        factory(Team::class, 10)->create();
        $app = Mockery::mock('\Illuminate\Contracts\Foundation\Application');
        $provider = new ChampionshipGameComposerProvider($app);

        $teams = $provider->teams();
        $this->assertTrue(is_array($teams));
        $this->assertCount(10, $teams);
    }

    /**
     * Test that the team array that is returned has players in it
     * @todo create test for players coming with team, not sure what the "team_count" key is for
     */
    public function teams_have_players()
    {
        
        $team = factory(Team::class)->create();
        factory(Player::class)->create(['team_id' => $team->id]);
        $app = Mockery::mock('\Illuminate\Contracts\Foundation\Application');
        $provider = new ChampionshipGameComposerProvider($app);

        $teams = $provider->teams();
    }
}
