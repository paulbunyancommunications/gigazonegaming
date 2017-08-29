<?php
namespace App\Http\Controllers;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Http\Controllers\GameDisplay\SimonCacheController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;


class SimonCacheControllerTest extends \TestCase
{
    use DatabaseTransactions, DatabaseMigrations;

    protected $controller;

    public function setUp()
    {
        parent::setUp();
        exec('php artisan cache:clear');
        $this->controller = new SimonCacheController();
    }

    public function tearDown()
    {
        parent::tearDown();
    }

    // tests
    public function testCacheContent()
    {
        $data = unserialize(file_get_contents('tests/_data/PlayerInfoArray.bin'));

        $teamInfoArrays = $data['teamInfo'];
        $colorArray = $data['colors'];
        $team = $data['teamName'];
        $players = $data['players'];


        $this->controller->cacheContent($teamInfoArrays,$colorArray,$team,$players);
        $this->assertNotNull(Cache::get('Players'));
        $this->assertSame(Cache::get('Team1Name'),$team[0]);
        $this->assertSame(Cache::get('Team1Info'),$teamInfoArrays[0]);
        $this->assertSame(Cache::get('Team1Color'),$colorArray[0]);
        $this->assertNotNull(Cache::get('Team1TimeStamp'));
        $this->assertSame(Cache::get('Team2Name'),$team[1]);
        $this->assertSame(Cache::get('Team2Info'),$teamInfoArrays[1]);
        $this->assertSame(Cache::get('Team2Color'),$colorArray[1]);
        $this->assertNotNull(Cache::get('Team2TimeStamp'));
    }

    public function testSetTeamColor(){
        $color = $this->controller->setTeamColor('Red');
        $this->assertSame($color, 'background-size:cover; box-shadow:inset 0 0 0 2000px rgba(255,0,0,0.2); width:100%; height:auto; min-height:100%');
        $color = $this->controller->setTeamColor('Blue');
        $this->assertSame($color, 'background-size:cover; box-shadow:inset 0 0 0 2000px rgba(0,0,255,0.2); width:100%; height:auto; min-height:100%');
    }

    public function testCacheChampionOverrideWithChampionsArray(){
        $req = new Request();
        $champions = ["https://ddragon.leagueoflegends.com/cdn/img/champion/loading/Garen_0.jpg",
            "https://ddragon.leagueoflegends.com/cdn/img/champion/loading/Ashe_0.jpg",
            "https://ddragon.leagueoflegends.com/cdn/img/champion/loading/Darius_0.jpg",
            "https://ddragon.leagueoflegends.com/cdn/img/champion/loading/Morgana_0.jpg",
            "https://ddragon.leagueoflegends.com/cdn/img/champion/loading/Graves_0.jpg"];
        #Team 1
        $req->replace(['team' => 'Team 1', 'championArray' => $champions]);
        $this->controller->cacheChampionOverride($req);
        $this->assertSame(Cache::get('Team1Champions'), $champions);
        $this->assertSame(Cache::get('Team1ChampionsPlayerId'),[0,1,2,3,4]);

        #Team 2
        $req->replace(['team' => 'Team 2', 'championArray' => $champions]);
        $this->controller->cacheChampionOverride($req);
        $this->assertSame(Cache::get('Team2Champions'), $champions);
        $this->assertSame(Cache::get('Team2ChampionsPlayerId'),[0,1,2,3,4]);
    }
}