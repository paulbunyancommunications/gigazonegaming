<?php
namespace App\Http\Classes;

use App\Models\Championship\Game;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use GameDisplay\RiotDisplay\API\Api;
use GameDisplay\RiotDisplay\Summoner;
use Mockery\Exception;

class SummonerTest extends \TestCase
{
    use DatabaseTransactions, DatabaseMigrations;

    protected $summoner;

    public function setUp()
    {
        $api = new RiotApiMock();
        $this->summoner = new Summoner("KingMorpheus2131", $api);
        parent::setUp();
    }

    public function tearDown()
    {
        parent::tearDown();
    }



    // tests
    public function testConstructionOfSummonerObjectWithApiObject()
    {
        $this->assertEquals("KingMorpheus2131", $this->summoner->getSummonerName());
    }

    public function testConstructionWithAFalseNameThrowsException(){
        $summonerName = "adhiasldhaidhsal";
        $api = new RiotApiMock();
        try{
            $this->summoner = new Summoner($summonerName, $api);
        }catch (\Exception $e){

            $this->assertEquals("Summoner '$summonerName' is not a valid name in North America", $e->getMessage());
        }
    }

    public function testSummonerObjectHasCorrectIconIdSoloRankAndFlexRank(){
        $this->assertEquals("67902786", $this->summoner->getSummonerID());
        $this->assertEquals("http://ddragon.leagueoflegends.com/cdn/7.16.1/img/profileicon/1665.png", $this->summoner->getIcon());
        $this->assertEquals("http://ddragon.leagueoflegends.com/cdn/7.16.1/img/profileicon/1665.png", $this->summoner->getIcon());
        $this->assertEquals("Unranked", $this->summoner->getFLEXRank());
        $this->assertEquals("Unranked", $this->summoner->getFLEXRankedWinLoss());
        $this->assertEquals("BRONZE I", $this->summoner->getSoloRank());
        $this->assertEquals("9 | 15", $this->summoner->getSoloRankedWinLoss());
    }

    public function testCallGetChampionBeforeCheckingSummonerGameStatusShouldThrowException(){
        $testPass = true;
        try{
            $this->summoner->setChampion();
            $testPass = false;
        }catch (\Exception $e){
            $this->assertEquals("Call checkCurrentGameStatus before calling this method. " . $this->summoner->getSummonerName() . " is not in game", $e->getMessage());
        }
        $this->assertTrue($testPass);
    }

    public function testGetGameStatusOfSummoner(){
        $this->assertEquals(false, $this->summoner->checkCurrentGameStatus());
    }

    public function testGetGameStatusIsTrueChampionImageShouldBeAvailable(){
        $api = new RiotApiMock_2();
        $this->summoner = new Summoner("KingMorpheus2131", $api);

        $this->assertTrue($this->summoner->checkCurrentGameStatus());
        $this->summoner->setChampion();
        $this->assertEquals("https://ddragon.leagueoflegends.com/cdn/img/champion/loading/Ashe_0.jpg",$this->summoner->getChampion());
    }
}
//Summoner not in game
class RiotApiMock{
    Public $summoner;
    Public $summonerID = "67902786";
    Public $currentGameStatus = false;
    Public $IconId = "1665";
    Public $DDragonVersion = "7.16.1";
    Public $summonerIcon = "http://ddragon.leagueoflegends.com/cdn/7.16.1/img/profileicon/1665.png";

    function __construct()
    {

    }

    public function getSummonerId(){
        return $this->summonerID;
    }
    public function getSummonerIcon(){
        return $this->summonerIcon;
    }
    public function getSoloRankedWinLoss(){
        return "9 | 15";
    }

    public function getSoloRank()
    {
        return "BRONZE I";
    }

    public function getFLEXRank()
    {
        return "Unranked";
    }

    public function getFLEXRankedWinLoss(){
        return "Unranked";
    }

    public function checkCurrentGameStatus(){
        return $this->currentGameStatus;
    }

    public function injectSummoner($summoner)
    {
        if ($summoner == 'KingMorpheus2131') {
            $this->summoner = $summoner;
        } else {
            throw new Exception("Summoner '$summoner' is not a valid name in North America");
        }
    }

    public function getChampion(){
        if($this->currentGameStatus === true){
            return "https://ddragon.leagueoflegends.com/cdn/img/champion/loading/Ashe_0.jpg";
        }
        throw new Exception("Call checkCurrentGameStatus before calling this method. $this->summoner is not in game");
    }
    public function getTop3Champions(){
        return [false,false,false,false];
    }
}
//Summoner in game
class RiotApiMock_2
{
    Public $summoner;
    Public $summonerID = "67902786";
    Public $currentGameStatus = true;
    Public $IconId = "1665";
    Public $DDragonVersion = "7.16.1";
    Public $summonerIcon = "http://ddragon.leagueoflegends.com/cdn/7.16.1/img/profileicon/1665.png";

    function __construct()
    {

    }

    public function getSummonerId(){
        return $this->summonerID;
    }
    public function getSummonerIcon(){
        return $this->summonerIcon;
    }
    public function getSoloRankedWinLoss(){
        return "9 | 15";
    }

    public function getSoloRank()
    {
        return "BRONZE I";
    }

    public function getFLEXRank()
    {
        return "Unranked";
    }

    public function getFLEXRankedWinLoss(){
        return "Unranked";
    }

    public function checkCurrentGameStatus(){
        return $this->currentGameStatus;
    }

    public function injectSummoner($summoner)
    {
        if ($summoner == 'KingMorpheus2131') {
            $this->summoner = $summoner;
        } else {
            throw new Exception("Summoner '$summoner' is not a valid name in North America");
        }
    }

    public function getChampion(){
        if($this->currentGameStatus === true){
            return "https://ddragon.leagueoflegends.com/cdn/img/champion/loading/Ashe_0.jpg";
        }
        throw new Exception("Call checkCurrentGameStatus before calling this method. $this->summoner is not in game");
    }

    public function getTop3Champions(){
        return [false,false,false,false];
    }
}