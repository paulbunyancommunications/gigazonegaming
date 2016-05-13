<?php
/**
 * RoleTest
 *
 * Created 2/18/16 2:51 PM
 * Test roles model
 *
 * @author Nate Nolting <naten@paulbunyan.net>
 * @package Tests\Integration\Model
 * @subpackage Subpackage
 */

namespace Tests\Integration\Models\Championship;

use App\Models\Championship\Game;
use App\Models\Championship\Player;
use App\Models\Championship\Team;
use Faker\Factory;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * Class RoleTest
 * @package Tests\Integration\Model
 */
class TeamTest extends \TestCase
{

    use DatabaseTransactions;

    /**
     * @var
     */
    protected $faker;

    /**
     *
     */
    public function setUp()
    {
        parent::setUp();
        $this->faker = Factory::create();

    }

    public function tearDown()
    {
        parent::tearDown();
        exec('php artisan migrate:refresh');
    }

    /**
     *
     * Test to see when getting a team we
     * get back the correct name attribute
     *
     * @test
     */
    public function it_has_a_name_attribute()
    {

        $name = $this->faker->name;
        $item = factory(Team::class)->create(['name' => $name, 'game_id' => factory(Game::class)->create()->id]);
        $getName = Team::find($item->id);

        $this->assertSame($name, $getName->name);
    }
    /**
     *
     * Test to see when getting a team we
     * get back the correct emblem attribute
     *
     * @test
     */
    public function it_has_a_emblem_attribute()
    {
        $imageUrl = $this->faker->imageUrl();
        $item = factory(Team::class)->create(['emblem' => $imageUrl, 'game_id' => factory(Game::class)->create()->id]);

        $getName = Team::find($item->id);

        $this->assertSame($imageUrl, $getName->emblem);
    }
    /**
     *
     * Test to see when getting a team we
     * get back the game the team is playing
     *
     * @test
     */
    public function it_has_a_game_attribute()
    {
        $game = factory(Game::class)->create();
        $item = factory(Team::class)->create(['game_id' => $game->id]);

        $getGame = Team::find($item->id);

        $this->assertSame($getGame->game->id, $game->id);
    }
    /**
     *
     * Test to see when getting a team we
     * get back the game the captain of the team
     *
     * @test
     */
    public function it_has_a_captain_attribute()
    {
        $game = factory(Game::class)->create();
        $item = factory(Team::class)->create(['game_id' => $game->id]);
        $player = factory(Player::class)->create(['team_id' => $item->id, 'captain' => true]);

        $getTeam = Team::find($item->id);

        $this->assertSame($getTeam->captain->first()->id, $player->id);
    }
    /**
     *
     * Test to see when getting a team we
     * get back the game the captain of the team
     *
     * @test
     */
    public function it_has_a_players_attribute()
    {
        $game = factory(Game::class)->create();
        $team = factory(Team::class)->create(['game_id' => $game->id]);
        for ($i=0; $i < 10; $i++) {
            factory(Player::class)->create(['team_id' => $team->id]);
        }

        $getTeam = Team::find($team->id);

        $this->assertSame(count($getTeam->players->toArray()), 10);
        $this->assertInstanceOf(Player::class, $getTeam->players[0]);
    }
}
