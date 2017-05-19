<?php
namespace App\Console\Commands;


use Illuminate\Console\Command;
use App\Http\Controllers\GameDisplay\GameDisplayController;

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
        $TeamName = "Power Rangers";
        $TournamentName = "gigazone-gaming-2016-league-of-legends";
        $controller->setTeam($TeamName, $TournamentName);
    }
}