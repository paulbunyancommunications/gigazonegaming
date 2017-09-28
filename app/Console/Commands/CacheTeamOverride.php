<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class CacheTeamOverride extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'override:team';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        #Ask user to specify what team is red and what team is blue
        $teamR = strtolower(str_replace(' ', '', $this->ask('Enter red team name')));
        $teamB = strtolower(str_replace(' ', '', $this->ask('Enter blue team name')));

        #grab the serialized file with the same team names
        $data = unserialize(file_get_contents('app/Console/Commands/SerializedTeams2017/R_'.$teamR.'_B_'.$teamB.'.bin'));
        $this->cacheContent($data['teamInfo'],$data['colors'],$data['teamName'],$data['players']);

        $this->info("Teams [$teamR, $teamB] successfully updated");
    }
    public function cacheContent($teamInfoArrays, $colorArray, $team, $players){
        Cache::put('Players', $players, 70);
        Cache::put('Team1Name', $team[0], 70);
        Cache::put('Team1Info', $teamInfoArrays[0], 70);
        Cache::put('Team1Color', $colorArray[0], 70);
        Cache::put('Team1TimeStamp', Carbon::now(), 70);
        Cache::put('Team2Name', $team[1], 70);
        Cache::put('Team2Info', $teamInfoArrays[1], 70);
        Cache::put('Team2Color', $colorArray[1], 70);
        Cache::put('Team2TimeStamp', Carbon::now(), 70);
    }
}
