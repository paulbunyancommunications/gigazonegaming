<?php
namespace Tests\Integration\Models\Championship;

use App\Models\Championship\Game;
use App\Models\Championship\Team;
use App\Models\Championship\Tournament;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TournamentTest extends \TestCase
{

    use DatabaseTransactions, DatabaseMigrations;

    /**
     * @var \Faker\Factory::create
     */
    protected $faker;

    public function setUp()
    {
        parent::setUp();
        $this->faker = \Faker\Factory::create();
        $this->resetEventListeners('App\Models\Championship\Tournament');
    }

    public function tearDown()
    {
        parent::tearDown();
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
     * @covers \App\Models\Championship\Tournament::getTeamsAttribute()
     */
    public function it_is_returns_a_team_collection()
    {
        $tournament = factory(Tournament::class)->create();
        factory(Team::class, 10)->create(['tournament_id' => $tournament->id]);

        $this->assertInstanceOf('\Illuminate\Database\Eloquent\Collection', $tournament->teams);
        foreach ($tournament->teams as $team) {
            $this->assertSame($team->tournament_id, $tournament->id);
        }
    }

    /**
     * @test
     * @covers \App\Models\Championship\Tournament::teams()
     */
    public function it_has_teams()
    {
        $tournament = factory(Tournament::class)->create();
        factory(Team::class, 10)->create(['tournament_id' => $tournament->id]);

        $this->assertInstanceOf('\Illuminate\Database\Eloquent\Relations\HasMany', $tournament->teams());
        foreach ($tournament->teams()->get() as $team) {
            $this->assertSame($team->tournament_id, $tournament->id);
        }
    }

    /**
     * @test
     * @covers \App\Models\Championship\Tournament::getGameAttribute()
     */
    public function it_is_returns_a_game_collection()
    {
        $game = factory(Game::class)->create();
        $tournament = factory(Tournament::class)->create(['game_id' => $game->id]);

        $this->assertInstanceOf('\App\Models\Championship\Game', $tournament->game);
        $this->assertSame($tournament->game->name, $game->name);
        $this->assertSame($tournament->game->description, $game->description);
        $this->assertSame($tournament->game->uri, $game->uri);
    }

    /**
     * @test
     * @covers \App\Models\Championship\Tournament::game()
     */
    public function it_belongs_to_a_game()
    {
        $game = factory(Game::class)->create();
        $tournament = factory(Tournament::class)->create(['game_id' => $game->id]);
        $this->assertInstanceOf('\Illuminate\Database\Eloquent\Relations\BelongsTo', $tournament->game());
        $this->assertSame($tournament->game()->first()->name, $game->name);
        $this->assertSame($tournament->game()->first()->description, $game->description);
        $this->assertSame($tournament->game()->first()->uri, $game->uri);
    }

    /**
     *
     * Test to see when getting a Tournament we
     * get back the correct updated by attribute
     *
     * @test
     */
    public function it_has_a_updated_by_attribute()
    {
        $user_name = $this->faker->userName;
        $user = factory(\App\Models\WpUser::class)->create(['user_login' => $user_name]);
        $tournament = factory(Tournament::class)->create(['updated_by' => $user->ID]);
        $getTournament = Tournament::find($tournament->id);
        $this->assertSame($user->ID, $getTournament->updated_by);
    }

    /**
     *
     * Test to see when getting a Tournament we
     * get back the correct updated on attribute
     *
     * @test
     */
    public function it_has_a_updated_on_attribute()
    {
        $time_stamp = Carbon::now("CMT");
        $tournament = factory(Tournament::class)->create(['updated_on' => $time_stamp]);
        $this->assertSame($time_stamp, $tournament->updated_on);
    }

    /**
     *
     * Test to see when getting a Tournament we
     * get back the correct sign_up_open attribute
     *
     * @test
     */
    public function it_has_a_sign_up_open_attribute()
    {
        $time_stamp = Carbon::now("CMT")->toDateTimeString();
        $tournament = factory(Tournament::class)->create(['sign_up_open' => $time_stamp]);
        $this->assertInstanceOf(Carbon::class, $tournament->sign_up_open);
        $this->assertSame($time_stamp, $tournament->sign_up_open->toDateTimeString());
    }

    /**
     *
     * Test to see when getting a Tournament we
     * get back the correct sign_up_close attribute
     *
     * @test
     */
    public function it_has_a_sign_up_close_attribute()
    {
        $time_stamp = Carbon::now("CMT")->toDateTimeString();
        $tournament = factory(Tournament::class)->create(['sign_up_close' => $time_stamp]);
        $this->assertInstanceOf(Carbon::class, $tournament->sign_up_close);
        $this->assertSame($time_stamp, $tournament->sign_up_close->toDateTimeString());
    }

    /**
     *
     * Test to see when getting a Tournament we
     * get back the correct occurring attribute
     *
     * @test
     */
    public function it_has_a_occurring_attribute()
    {
        $time_stamp = Carbon::now("CMT")->toDateTimeString();
        $tournament = factory(Tournament::class)->create(['occurring' => $time_stamp]);
        $this->assertInstanceOf(Carbon::class, $tournament->occurring);
        $this->assertSame($time_stamp, $tournament->occurring->toDateTimeString());
    }

    /**
     * @test
     */
    public function it_deletes_teams_when_it_is_deleted()
    {
        $tournament = factory(Tournament::class)->create();
        $teams = factory(Team::class, 10)->create(['tournament_id' => $tournament->id]);
        $tournament->delete();
        foreach ($teams as $team) {
            $this->assertNull(Team::find($team->id));
        }
    }
}
