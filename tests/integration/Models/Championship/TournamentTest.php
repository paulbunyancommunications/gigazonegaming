<?php
namespace Tests\Integration\Models\Championship;

use App\Models\Championship\Game;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\Championship\Tournament;
use Illuminate\View\Factory;

class TournamentTest extends \TestCase
{

    use DatabaseTransactions;

    /**
     * @var \Faker\Factory::create
     */
    protected $faker;

    public function setUp()
    {
        parent::setUp();
        $this->faker = \Faker\Factory::create();
    }

    public function tearDown()
    {
        parent::tearDown();
        exec('php artisan migrate:refresh');
    }

    /**
     * @test
     */
    public function it_has_a_name_attribute()
    {
        $name = implode('-', $this->faker->words());
        $tournament = factory(Tournament::class)->create(['name' => $name]);
        $getTournament = Tournament::find($tournament->id);

        $this->assertSame($getTournament->name, $name);
    }

    /**
     * @test
     */
    public function it_is_attached_to_a_game()
    {
        $game = factory(Game::class)->create();
        $tournament = factory(Tournament::class)->create(['game_id' => $game->id]);

        $getTournament = Tournament::find($tournament->id);
        $this->assertSame($getTournament->game->name, $game->name);
        $this->assertSame($getTournament->game->description, $game->description);
        $this->assertSame($getTournament->game->uri, $game->uri);
    }
}
