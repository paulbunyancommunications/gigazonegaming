<?php

namespace App\Console\Commands;

use App\Models\Championship\Player;
use App\Models\Championship\Team;
use Illuminate\Console\Command;

class RealTestData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:realData';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This loads in the final 4 teams';

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
     */
    public function handle()
    {
        $this->createGoNext();
        $this->createProFreakz();
        $this->createIronRangeLegends();
        $this->createTeamIronRangeAssassins();
        var_dump("This worked");

    }

    public function createGoNext(){
        $Captain = Player::create([
            'username' => "kyle xo",
            'email' => "terre086@d.umn.edu",
            'name' => 'kyle terrell',
            'phone' => '6129132593']);
        $CaptainOfTeam = Player::where('username','kyle xo')->first();
        #Team
        $team = Team::create([
            'tournament_id' => 1,
            'name' => 'go next',
            'captain' => $CaptainOfTeam->id,
        ]);
        $team = Team::where('name', 'go next')->first();
        #relations
        $Captain::createRelation([
            'player' => $CaptainOfTeam->id,
            'Game' => 2,
            'Tournament' => 1,
            'team' => $team->id,
        ]);
        $playerUserNameArray = array('samˆºˆ', 'cuckerino', 'krypl', 'snowcane','you wont do it');
        $playerEmailsArray = array('lars5069@d.umn.edu','sweet126@d.umn.edu','wichm052@d.umn.edu','wongp003@d.umn.edu','menxx006@d.umn.edu');
        #creat players for team
        for($i = 0; $i < count($playerUserNameArray); $i++){
            $player = Player::create([
                'username' => $playerUserNameArray[$i],
                'email' => $playerEmailsArray[$i],
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

    public function createProFreakz(){
        $Captain = Player::create([
            'username' => "Belgardex",
            'email' => "belgardeiain@gmail.com",
            'name' => 'Iain Belgarde',
            'phone' => '2182443092']);
        $CaptainOfTeam = Player::where('username','Belgardex')->first();
        #Team
        $team = Team::create([
            'tournament_id' => 1,
            'name' => 'ProFreakz',
            'captain' => $CaptainOfTeam->id,
        ]);
        $team = Team::where('name', 'ProFreakz')->first();
        #relations
        $Captain::createRelation([
            'player' => $CaptainOfTeam->id,
            'Game' => 2,
            'Tournament' => 1,
            'team' => $team->id,
        ]);
        $playerUserNameArray = array('Isske', 'Tuckerz', 'bishe', 'oO Salsa Oo');
        $playerEmailsArray = array('ikrasmusson@gmail.com','alyisg19@hotmail.com','codyjmattews@hotmail.com','bigsalsa21@hotmail.com');
        #creat players for team
        for($i = 0; $i < count($playerUserNameArray); $i++){
            $player = Player::create([
                'username' => $playerUserNameArray[$i],
                'email' => $playerEmailsArray[$i],
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

    public function createIronRangeLegends(){
        $Captain = Player::create([
            'username' => "Is Trevor Strong",
            'email' => "benmonson@live.com",
            'name' => 'Ben Monson',
            'phone' => '2187504995']);
        $CaptainOfTeam = Player::where('username','Is Trevor Strong')->first();
        #Team
        $team = Team::create([
            'tournament_id' => 1,
            'name' => 'Iron Range Legends',
            'captain' => $CaptainOfTeam->id,
        ]);
        $team = Team::where('name', 'Iron Range Legends')->first();
        #relations
        $Captain::createRelation([
            'player' => $CaptainOfTeam->id,
            'Game' => 2,
            'Tournament' => 1,
            'team' => $team->id,
        ]);
        $playerUserNameArray = array('DenRaines', 'DJMixture', 'Pullz for dayz', 'Ravìolì');
        $playerEmailsArray = array('Prescott.weis@gmail.com','mull1sc@gmail.com','Bradmonson1@gmail.com','alabama47@live.com');
        #creat players for team
        for($i = 0; $i < count($playerUserNameArray); $i++){
            $player = Player::create([
                'username' => $playerUserNameArray[$i],
                'email' => $playerEmailsArray[$i],
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

    public function createTeamIronRangeAssassins(){
        $Captain = Player::create([
            'username' => "Renekto",
            'email' => "brandune1989@gmail.com",
            'name' => 'Brandon Olson',
            'phone' => '2184104911']);
        $CaptainOfTeam = Player::where('username','Renekto')->first();
        #Team
        $team = Team::create([
            'tournament_id' => 1,
            'name' => 'Team Iron Range Assassins',
            'captain' => $CaptainOfTeam->id,
        ]);
        $team = Team::where('name', 'Team Iron Range Assassins')->first();
        #relations
        $Captain::createRelation([
            'player' => $CaptainOfTeam->id,
            'Game' => 2,
            'Tournament' => 1,
            'team' => $team->id,
        ]);
        $playerUserNameArray = array('Xarxes Infinium', 'TheEggmO', 'Prazz', 'braven1234');
        $playerEmailsArray = array('kyletveiten@live.com','austinhautala@gmail.com','Resilientpraz@gmail.com','bravenfisher185@gmail.com');
        #creat players for team
        for($i = 0; $i < count($playerUserNameArray); $i++){
            $player = Player::create([
                'username' => $playerUserNameArray[$i],
                'email' => $playerEmailsArray[$i],
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
