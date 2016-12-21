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
use App\Models\Championship\LeaderBoard;
use App\Models\Championship\Player;
use App\Models\Championship\Tournament;
use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Cocur\Slugify\Slugify;

/**
 * Class LeaderBoardTest
 * @package Tests\Integration\Model
 */
class LeaderBoardTest extends \TestCase
{

    use DatabaseTransactions, DatabaseMigrations;

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
        $this->resetEventListeners('App\Models\Championship\LeaderBoard');

    }

    public function tearDown()
    {
        parent::tearDown();
    }

    /**
     *
     * Test to see when getting a game we
     * get back the correct name attribute
     *
     * @test
     */
    public function it_has_a_game_attribute()
    {
        $name = $this->faker->name;
        $score = $this->faker->numberBetween(1000, 10000);
        $game = factory(Game::class)->create(['name' => $name]);
        $player = factory(Player::class)->create();
        $leaderBoard = factory(\App\Models\Championship\LeaderBoard::class)->create([
            'score' => $score, 'game' => $game->id, 'player' => $player->id
        ]);
        $slug = Slugify::create();
        $this->assertSame($slug->slugify($name), $leaderBoard->game->name);
    }

    /**
     *
     * Test to see when getting a game we
     * get back the correct name attribute
     *
     * @test
     */
    public function it_has_a_player_attribute()
    {
        $name = $this->faker->name;
        $score = $this->faker->numberBetween(1000, 10000);
        $game = factory(Game::class)->create();
        $player = factory(Player::class)->create(['name' => $name]);
        $leaderBoard = factory(\App\Models\Championship\LeaderBoard::class)->create([
            'score' => $score, 'game' => $game->id, 'player' => $player->id
        ]);
        $this->assertSame($name, $leaderBoard->player->name);
    }

   }
