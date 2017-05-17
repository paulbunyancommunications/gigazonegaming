<?php
namespace App\Http\Controllers\Api\Championship;
use App\Models\Championship\Player;
use App\Models\Championship\Team;
use App\Models\Championship\Tournament;
use App\Models\Championship\Game;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Http\Requests\GameRequest;
use App\Models\Championship\Relation\PlayerRelation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;


use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;


class summonerTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */

    use DatabaseTransactions, DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();
    }

    public function tearDown()
    {
        parent::tearDown();
    }

    // tests
    public function testSomething()
    {
        #Tournament
        $faker = \Faker\Factory::create();
        $params = [
            'name' => implode('-', $faker->words(3)),
            'title' => $faker->sentence(),
            'description' => $faker->paragraph(),
            'uri' => $faker->url
        ];

        $game = factory(Game::class)->create($params);

        var_dump($game);
        dd("we saved it");
        $tournament = new Tournament([
        'game_id' => $game->id,
        'max_players' => 10,
        'name' => "LOL",
        'created_at' => Carbon::now("CST"),
        'updated_at' => Carbon::now("CST"),
        'updated_by' =>  11,
        'updated_on' => Carbon::now("CST")
        ]);
        $captian = new Player([
            'username' => "KingMorpheus2131",
            'email' => "simoncecer7@gmail.com",
            'name' => 'Simon',
            'phone' => '6125596450'
        ]);
        $team = new Team([
        'tournament_id' => $tournament->id,
        'name' => 'Power Rangers',
        'captain' => $captian->id
        ]);

        $captian1 = new Player([
            'username' => "spartan7warrior",
            'email' => "simoncecer7@gmail.com",
            'name' => 'Roman',
            'phone' => '6125596450'
        ]);
        $captian2 = new Player([
            'username' => "Something1",
            'email' => "simoncecer7@gmail.com",
            'name' => 'Simon',
            'phone' => '6125596450'
        ]);
        $captian3 = new Player([
            'username' => "Something2",
            'email' => "simoncecer7@gmail.com",
            'name' => 'Simon',
            'phone' => '6125596450'
        ]);
        $captian4 = new Player([
            'username' => "Something3",
            'email' => "simoncecer7@gmail.com",
            'name' => 'Simon',
            'phone' => '6125596450'
        ]);










        dd("something");

//        $tournament = new Tournament();
//        $tournament->game_id = $request['game_id'];
//        $tournament->max_players = $request['max_players'];
//        $tournament->name = $request['name'];
//        $tournament->updated_by =  $this->getUserId();
//        $tournament->updated_on = Carbon::now("CST");
//        $tournament->created_at = Carbon::now("CST");
//        $tournament->updated_at = Carbon::now("CST");
//        $tournament->save();
//        return redirect('manage/tournament')->with('success',"The tournament ".$request['name']." was added");
    }
}