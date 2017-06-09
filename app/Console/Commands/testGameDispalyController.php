<?php
namespace App\Console\Commands;


use Illuminate\Console\Command;
use App\Http\Controllers\GameDisplayController;
use GameDisplay\RiotDisplay\Summoner;

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
        $time_start = microtime(true);
        $summoner = new Summoner('KingMorpheus2131', 1);
        $time_end = microtime(true);
        dd($time_end - $time_start, $summoner->getIcon(), $summoner->getSummonerName());

    }
}