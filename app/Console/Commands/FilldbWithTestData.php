<?php
namespace App\Console\Commands;
use App\Models\Championship\Game;
use App\Models\Championship\Team;
use App\Models\Championship\Tournament;
use App\Models\Championship\Player;
use Illuminate\Console\Command;
class FilldbWithTestData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:fillDB';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'fill DB with test summoners';
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
        var_dump("This is working");
    }
    public function fillDB(){
        #Captian
        $Captian = new Player();
        $Captian->setAttribute('username', "Spartan7Warrior");
        $Captian->setAttribute('email', "martushev8@gmail.com");
        $Captian->setAttribute('name', "Roman");
        $Captian->setAttribute('phone', "2182605085");
        $Captian->save();
        #Team
        $team = new Team();
        $team->tournament_id = 1;
        $team->name =  "Team Awesome";
        #add captain and save the team
        $team->captain = $Captian->id;
        $team->save();
        #relations
        $Captian::createRelation([
            'player' => $Captian->id,
            'Game' => 2,
            'Tournament' => 1,
            'team' => $team->id,
        ]);
        $playerUserNameArray = array('CacheMeOuside', 'DragonDefeater1', 'SlySkeever', 'ChaChing77');
        $i = 0;
        #creat players for team
        for($i = 0; $i < count($playerUserNameArray); $i++) {
            $player = new Player();
            $player->username = $playerUserNameArray[$i];
            $player->email = "ready_player_" . $i . "@gigazonegaming.com";
            $player->phone = "2182605085";
            $player->save();
            // attach player to team/tournament/game
            $player::createRelation([
                'player' => $player->id,
                'Game' => 2,
                'Tournament' => 1,
                'team' => $team->id,
            ]);
        }
            #Captian
            $Captian = new Player();
            $Captian->setAttribute('username', "KingMorpheus2131");
            $Captian->setAttribute('email', "martushev8@gmail.com");
            $Captian->setAttribute('name', "Roman");
            $Captian->setAttribute('phone', "2182605085");
            $Captian->save();
            #Team
            $team = new Team();
            $team->tournament_id = 1;
            $team->name =  "That Team";
            #add captain and save the team
            $team->captain = $Captian->id;
            $team->save();
            #relations
            $Captian::createRelation([
                'player' => $Captian->id,
                'Game' => 2,
                'Tournament' => 1,
                'team' => $team->id,
            ]);
            $playerUserNameArray = array('Juanpablomontoya', 'manklar', 'ReckonStuff', 'ThatBoy18');
            $i = 0;
            #creat players for team
            for($i = 0; $i < count($playerUserNameArray); $i++){
                $player = new Player();
                $player->username = $playerUserNameArray[$i];
                $player->email = "ready_player_" . $i . "@gigazonegaming.com";
                $player->phone = "2182605085";
                $player->save();
                // attach player to team/tournament/game
                $player::createRelation([
                    'player' => $player->id,
                    'Game' => 2,
                    'Tournament' => 1,
                    'team' => $team->id,
                ]);
            }

    }
}