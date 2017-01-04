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
class ScoreTest extends \TestCase
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
        $this->resetEventListeners('App\Models\Championship\Score');

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
    public function it_has_a_tournament_attribute()
    {
        $slug = Slugify::create();
        $name = $slug->slugify($this->faker->name);
        $score = $this->faker->numberBetween(1000, 10000);
        $tournament = factory(Tournament::class)->create(['name' => $name]);
        $player = factory(Player::class)->create();
        $MakeScore = factory(\App\Models\Championship\Score::class)->create([
            'score' => $score, 'tournament' => $tournament->id, 'player' => $player->id
        ]);

        $this->assertSame($name, $MakeScore->tournament->name);
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
        $tournament = factory(Tournament::class)->create();
        $player = factory(Player::class)->create(['name' => $name]);
        $makeScore = factory(\App\Models\Championship\Score::class)->create([
            'score' => $score, 'tournament' => $tournament->id, 'player' => $player->id
        ]);
        $this->assertSame($name, $makeScore->player->name);
    }

   }
