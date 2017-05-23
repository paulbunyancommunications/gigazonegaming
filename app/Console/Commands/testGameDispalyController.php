<?php
namespace App\Console\Commands;


use Illuminate\Console\Command;
use App\Http\Controllers\GameDisplayController;

class testGameDispalyController extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:controller';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'testGameDispalyController';
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

        $controller = new GameDisplayController();
        $TeamName = "TestTeamSingle";
        $TournamentName = "gigazone-gaming-2016-league-of-legends";
        $controller->setTeam($TeamName, $TournamentName);

        $string ="";


        foreach($controller->team as $player){
            if($player->checkCurrentGameStatus() != false){
                $player->setChampion();
            }
            else{
                dd("Not Ready");
            }
            $string = "Summoner Name: " . $player->getSummonerName() . "\nSummoner Icon: " . $player->getIcon() . "\nSolo Rank: " . $player->getSoloRank() . "\nSolo Win|Loss: " . $player->getSoloRankedWinLoss() . "\nFlex Rank:" . $player->getFLEXRank() . "\nFlex Win|Loss:" . $player->getFLEXRankedWinLoss() . "\nChampion: " . $player->getChampion() . $string;
        }

        dd($string);

    }
}