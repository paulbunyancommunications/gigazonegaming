<?php
namespace App\Http\Controllers;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Codeception\Util\Fixtures;

//todo:clear cache, get data and assert it
class GameDisplayControllerTest extends \TestCase
{
    use DatabaseTransactions, DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();
        exec('php artisan cache:clear');
    }

    public function tearDown()
    {
        parent::tearDown();
    }

    /**
     *Unserializes store fixture data then caches data for tested methods that need cache.
     */
    public function cacheTeam(){

        $data = unserialize(file_get_contents('tests/_data/PlayerInfoArray.bin'));

        $teamInfoArrays = $data['teamInfo'];
        $colorArray = $data['colors'];
        $team = $data['teamName'];
        $players = $data['players'];

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

    public function cacheChampions($teamNum){
        $champions = [
            "https://ddragon.leagueoflegends.com/cdn/img/champion/loading/Garen_0.jpg",
            "https://ddragon.leagueoflegends.com/cdn/img/champion/loading/Ashe_0.jpg",
            "https://ddragon.leagueoflegends.com/cdn/img/champion/loading/Darius_0.jpg",
            "https://ddragon.leagueoflegends.com/cdn/img/champion/loading/Morgana_0.jpg",
            "https://ddragon.leagueoflegends.com/cdn/img/champion/loading/Graves_0.jpg"
        ];
        Cache::put('Team'.$teamNum.'Champions', $champions, 70);
        Cache::put('Team'.$teamNum.'ChampionsPlayerId', [0,1,2,3,4], 70);
    }

    public function testTeamViewDisplayWithNoCacheForTeam1ShouldReturnAltPageWithSpecificTitleForTeam1(){
        $response = $this->call('GET', '/GameDisplay/team1');
        $this->assertSame($response->getStatusCode(),200);
        $title = explode("</Title>", explode('<Title>',$response->getContent())[1])[0];
        $this->assertSame($title, "Loading Team Display");
    }

    public function testTeamViewDisplayWithNoCacheForTeam2ShouldReturnAltPageWithSpecificTitleForTeam2(){
        $response = $this->call('GET', '/GameDisplay/team2');

        $this->assertSame($response->getStatusCode(),200);
        $title = explode("</Title>", explode('<Title>',$response->getContent())[1])[0];
        $this->assertSame($title, "Loading Team Display");
    }

    public function testTeamViewDisplayWithCacheForTeam1ShouldReturnViewWithSpecificDataForTeam1(){
        $this->cacheTeam();

        $response = $this->call('GET', '/GameDisplay/team1');
        $content = $response->getOriginalContent();
        $data = $content->getData();
        foreach($data as $key => $value){
            $this->assertSame($value, Fixtures::get('team1' . $key));
        }
        $this->assertTrue(Cache::has('Team1CacheLoadedTimeStamp'));
    }

    public function testTeamViewDisplayWithCacheForTeam2ShouldReturnViewWithSpecificDataForTeam2(){
        $this->cacheTeam();

        $response = $this->call('GET', '/GameDisplay/team2');
        $content = $response->getOriginalContent();
        $data = $content->getData();

        foreach($data as $key => $value){
            $this->assertSame($value, Fixtures::get('team2' . $key));
        }
        $this->assertTrue(Cache::has('Team2CacheLoadedTimeStamp'));
    }
    public function testGetDataShouldReturnFalseWithNoCacheForTeam1(){
        $response = $this->call('GET', '/GameDisplay/getData',['team' => 'team1']);
        $this->assertSame($response->getContent(), 'false');
    }
    public function testGetDataShouldReturnFalseWithNoCacheForTeam2(){
        $response = $this->call('GET', '/GameDisplay/getData',['team' => 'team2']);
        $this->assertSame($response->getContent(), 'false');
    }
    public function testGetDataShouldReturnTrueWithCacheForTeam1(){
        $this->cacheTeam();
        $response = $this->call('GET', '/GameDisplay/getData',['team' => 'team1']);
        $this->assertSame($response->getContent(), 'true');

    }
    public function testGetDataShouldReturnTrueWithCacheForTeam2(){
        $this->cacheTeam();
        $response = $this->call('GET', '/GameDisplay/getData',['team' => 'team2']);
        $this->assertSame($response->getContent(), 'true');
    }
    public function testUpdateDataShouldReturnFalseForPageReloadWithCacheAndNoUpdatedDataForTeam1(){
        $this->cacheTeam();
        $this->call('GET', '/GameDisplay/team1');
        $response = $this->call('GET', '/GameDisplay/Update',['team' => 'team1', 'checkChamp' => true]);
        $this->assertSame($response->getOriginalContent()[0], 'false');
    }
    public function testUpdateDataShouldReturnFalseWithNoCacheAndNoUpdatedDataForTeam2(){
        $this->cacheTeam();
        $this->call('GET', '/GameDisplay/team2');
        $response = $this->call('GET', '/GameDisplay/Update',['team' => 'team2', 'checkChamp' => true]);
        $this->assertSame($response->getOriginalContent()[0], 'false');
    }
    public function testUpdateDataShouldReturnTrueForPageReloadWithCacheAndUpdatedDataForTeam1(){
        $this->cacheTeam();
        $this->call('GET', '/GameDisplay/team1');
        $this->cacheTeam();
        $response = $this->call('GET', '/GameDisplay/Update',['team' => 'team1', 'checkChamp' => true]);
        $this->assertSame($response->getOriginalContent()[0], 'true');
    }
    public function testUpdateDataShouldReturnTrueForPageReloadWithCacheAndUpdatedDataForTeam2(){
        $this->cacheTeam();
        $this->call('GET', '/GameDisplay/team2');
        $this->cacheTeam();
        $response = $this->call('GET', '/GameDisplay/Update',['team' => 'team2', 'checkChamp' => true]);
        $this->assertSame($response->getOriginalContent()[0], 'true');
    }
    public function testUpdateDataShouldReturnTrueForPageReloadWithCacheClearedForTeam1(){
        $this->cacheTeam();
        $this->call('GET', '/GameDisplay/team1');
        exec('php artisan cache:clear');
        $response = $this->call('GET', '/GameDisplay/Update',['team' => 'team1', 'checkChamp' => true]);
        $this->assertSame($response->getOriginalContent()[0], 'true');
    }
    public function testUpdateDataShouldReturnTrueForPageReloadWithCacheClearedForTeam2(){
        $this->cacheTeam();
        $this->call('GET', '/GameDisplay/team2');
        exec('php artisan cache:clear');
        $response = $this->call('GET', '/GameDisplay/Update',['team' => 'team2', 'checkChamp' => true]);
        $this->assertSame($response->getOriginalContent()[0], 'true');
    }

    public function testUpdateDataShouldReturnFalseOnChampionWhenThereIsNoCacheForChampionsForTeam1(){
        $this->cacheTeam();
        $this->call('GET', '/GameDisplay/team1');
        $response = $this->call('GET', '/GameDisplay/Update',['team' => 'team1', 'checkChamp' => 'true']);
        $this->assertSame($response->getOriginalContent()[1], 'false');
        $this->assertSame($response->getOriginalContent()[2], 'false');
        $this->assertSame($response->getOriginalContent()[3], 'true');
    }
    public function testUpdateDataShouldReturnFalseOnChampionWhenThereIsNoCacheForChampionsForTeam2(){
        $this->cacheTeam();
        $this->call('GET', '/GameDisplay/team2');
        $response = $this->call('GET', '/GameDisplay/Update',['team' => 'team2', 'checkChamp' => 'true']);
        $this->assertSame($response->getOriginalContent()[1], 'false');
        $this->assertSame($response->getOriginalContent()[2], 'false');
        $this->assertSame($response->getOriginalContent()[3],'true');
    }
    public function testUpdateDataShouldReturnTrueOnChampionWhenThereIsCacheForChampionsForTeam1(){
        $this->cacheTeam();
        $this->cacheChampions('1');
        $this->call('GET', '/GameDisplay/team1');
        $response = $this->call('GET', '/GameDisplay/Update',['team' => 'team1', 'checkChamp' => 'true']);
        $this->assertSame($response->getOriginalContent()[1], [
            "https://ddragon.leagueoflegends.com/cdn/img/champion/loading/Garen_0.jpg",
            "https://ddragon.leagueoflegends.com/cdn/img/champion/loading/Ashe_0.jpg",
            "https://ddragon.leagueoflegends.com/cdn/img/champion/loading/Darius_0.jpg",
            "https://ddragon.leagueoflegends.com/cdn/img/champion/loading/Morgana_0.jpg",
            "https://ddragon.leagueoflegends.com/cdn/img/champion/loading/Graves_0.jpg"
        ]);
        $this->assertSame($response->getOriginalContent()[2], [0,1,2,3,4]);
    }
    public function testUpdateDataShouldReturnTrueOnChampionWhenThereIsCacheForChampionsForTeam2(){
        $this->cacheTeam();
        $this->cacheChampions('2');
        $this->call('GET', '/GameDisplay/team2');
        $response = $this->call('GET', '/GameDisplay/Update',['team' => 'team2', 'checkChamp' => 'true']);
        $this->assertSame($response->getOriginalContent()[1], [
            "https://ddragon.leagueoflegends.com/cdn/img/champion/loading/Garen_0.jpg",
            "https://ddragon.leagueoflegends.com/cdn/img/champion/loading/Ashe_0.jpg",
            "https://ddragon.leagueoflegends.com/cdn/img/champion/loading/Darius_0.jpg",
            "https://ddragon.leagueoflegends.com/cdn/img/champion/loading/Morgana_0.jpg",
            "https://ddragon.leagueoflegends.com/cdn/img/champion/loading/Graves_0.jpg"
        ]);
        $this->assertSame($response->getOriginalContent()[2], [0,1,2,3,4]);
    }

}