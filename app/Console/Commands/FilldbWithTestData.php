<?php
namespace App\Console\Commands;
use App\Models\Championship\Game;
use App\Models\Championship\Team;
use App\Models\Championship\Tournament;
use App\Models\Championship\Player;
use Illuminate\Console\Command;
class FilldbWithTestData extends Command
{
    //todo: fix team assign


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
        $Captain = Player::create([
            'username' => "KingMorpheus2131",
            'email' => "martushev8@gmail.com",
            'name' => 'Roman',
            'phone' => '2182805085']);
        $CaptainOfTeam = Player::where('username','KingMorpheus2131')->first();
        #Team
        $team = Team::create([
            'tournament_id' => 1,
            'name' => 'That Team',
            'captain' => $CaptainOfTeam->id,
        ]);
        $team = Team::where('name', 'That Team')->first();
        #relations
        $Captain::createRelation([
            'player' => $CaptainOfTeam->id,
            'Game' => 2,
            'Tournament' => 1,
            'team' => $team->id,
        ]);
        $playerUserNameArray = array('Juanpablomontoya', 'ThatBoy18', 'manklar', 'ReckonStuff');
        #creat players for team
        for($i = 0; $i < count($playerUserNameArray); $i++){
            $player = Player::create([
                'username' => $playerUserNameArray[$i],
                'email' => "ready_player_" . $i . "@gigazonegaming.com",
                'phone' => "2182605085"
            ]);
            // attach player to team/tournament/game
            $playerOnTeam = Player::where('username',$playerUserNameArray[$i])->first();
            $player::createRelation([
                'player' => $playerOnTeam->id,
                'Game' => 2,
                'Tournament' => 1,
                'team' => $team->id,
            ]);
        }

    }
}