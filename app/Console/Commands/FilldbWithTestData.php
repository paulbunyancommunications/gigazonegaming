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
     * @return mixed
     */
    public function handle()
    {

        $this->fillDB();
        dd("This is working");
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
        $team->name =  "SPARTANS";
        #add captain and save the team
        $team->captain = $Captian->id;
        $team->save();
        #relations
        $Captian::createRelation([
            'player' => $Captian,
            'game' => 2,
            'team' => $team,
        ]);
        $playerUserNameArray = array('YoBroSo', 'DumpsterDiver21', 'HelloMyBro90', 'ThatsAllFolksHa');
        $i = 0;
        #creat players for team
        foreach ($playerUserNameArray as $Player){
            $player = new Player();
            $player->username = $Player;
            $player->email = "ready_player_" . $i . "@gigazonegaming.com";
            $player->phone = "2182605085";
            $player->save();
            // attach player to team/tournament/game
            $player::createRelation([
                'player' => $player,
                'tournament' => 1,
                'game' => 2,
                'team' => $team,
            ]);
            $i++;
        }
    }
}