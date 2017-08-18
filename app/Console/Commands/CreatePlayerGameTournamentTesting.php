<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 8/18/17
 * Time: 10:39 AM
 */

namespace App\Console\Commands;


use App\Models\Championship\Game;
use App\Models\Championship\Player;
use App\Models\Championship\Team;
use App\Models\Championship\Tournament;
use Faker\Factory;
use Illuminate\Console\Command;

class CreatePlayerGameTournamentTesting extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:fillTesting';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'fill DB with test summoner with multiple games and tournaments';
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {

        $this->fillDB();
        var_dump("It Worked");
    }

    /**
     * If you run this you must delete it manually,
     * First: delete player,
     * Second: Teams,
     * Third: Tournament,
     * Fourth: Game,
     * Last: Player Relations.
     */
    public function fillDB(){
        #Captian
        $Captain = Player::create([
            'username' => "Spartan7Warrior",
            'email' => "martushev8@gmail.com",
            'name' => 'Roman',
            'phone' => '2182805085']);
        $CaptainOfTeam = Player::where('username','Spartan7Warrior')->first();
        #Team
        Team::create([
            'tournament_id' => 1,
            'name' => 'Team Awesome',
            'captain' => $CaptainOfTeam->id,
        ]);
        Game::create([
            'name' => "Testing Game",
            'title' => "The Tester Game",
            'description' => "For Testing",
            'uri' => "http://leagueoflegends.com/"
        ]);
        $game = Game::where('name','testing-game')->first();
        Tournament::create([
            'name' => "League-Of-Legends-Testing",
            'game_id' => $game->id,
            'sign_up_open' => '0000-00-00 00:00:00',
            'sign_up_close' => '0000-00-00 00:00:00',
            'occurring' => '0000-00-00 00:00:00',
            'max_players' => 6,
        ]);
        $tournament = Tournament::where('name',"League-Of-Legends-Testing")->first();
        Team::create([
            'tournament_id' => $tournament->id,
            'name' => 'Spartans',
            'captain' => $CaptainOfTeam->id,
        ]);
        $team = Team::where('tournament_id',1)->first();
        $team2 = Team::where('tournament_id',$tournament->id)->first();
        #relations
        $Captain::createRelation([
            'player' => $CaptainOfTeam->id,
            'Game' => $game->id,
            'Tournament' => $tournament->id,
            'team' => $team2->id,
        ]);
        $Captain::createRelation([
            'player' => $CaptainOfTeam->id,
            'Game' => 2,
            'Tournament' => 1,
            'team' => $team->id,
        ]);

    }
}