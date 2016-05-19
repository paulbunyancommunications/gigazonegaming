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
use App\Models\Championship\IndividualPlayer;
use App\Models\Championship\Tournament;
use Faker\Factory;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * Class RoleTest
 * @package Tests\Integration\Model
 */
class GameTest extends \TestCase
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
     * Test to see when getting a game we
     * get back the correct name attribute
     *
     * @test
     */
    public function it_has_a_name_attribute()
    {

        $name = $this->faker->name;
        $item = factory(Game::class)->create(['name' => $name]);
        $getName = Game::find($item->id);

        $this->assertSame($name, $getName->name);
    }

    /**
     *
     * Test to see when getting a game we
     * get back the correct title attribute
     *
     * @test
     */
    public function it_has_a_title_attribute()
    {
        $title = $this->faker->sentence;
        $item = factory(Game::class)->create(['title' => $title]);

        $getName = Game::find($item->id);

        $this->assertSame($title, $getName->title);
    }

    /**
     *
     * Test to see when getting a game we
     * get back the correct description attribute
     *
     * @test
     */
    public function it_has_a_description_attribute()
    {
        $description = $this->faker->sentence;
        $item = factory(Game::class)->create(['description' => $description]);

        $getName = Game::find($item->id);

        $this->assertSame($description, $getName->description);
    }

    /**
     *
     * Test to see when getting a game we
     * get back the correct uri attribute
     *
     * @test
     */
    public function it_has_a_uri_attribute()
    {
        $uri = $this->faker->url;
        $item = factory(Game::class)->create(['uri' => $uri]);

        $getName = Game::find($item->id);

        $this->assertSame($uri, $getName->uri);
    }

    /**
     * Get a game by name
     *
     * @test
     */
    public function it_can_get_a_game_by_name()
    {
        $name = $this->faker->sentence;
        factory(Game::class)->create(['name' => $name]);

        $getByName = Game::byName($name);

        $this->assertSame($getByName->name, $name);

    }

    /**
     * check that teams are attached to tournament
     *
     * @test
     */
    public function it_has_tournaments()
    {

        $game = Factory(Game::class)->create();
        factory(Tournament::class, 10)->create(['game_id' => $game->id]);


        $this->assertSame(count($game->tournaments()->get()->toArray()), 10);
    }

    /**
     * check that teams are attached to game
     *
     * @test
     */
    public function it_has_individual_players()
    {
        $name = $this->faker->sentence;
        $game = factory(Game::class)->create(['name' => $name]);
        for ($i = 0; $i < 10; $i++) {
            Factory(IndividualPlayer::class)->create(['game_id' => $game->id]);
        }

        $getGame = Game::find($game->id);
        $this->assertSame(count($getGame->individualPlayers->toArray()), 10);
    }
}
