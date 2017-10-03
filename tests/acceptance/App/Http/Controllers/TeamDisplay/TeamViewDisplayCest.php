<?php
namespace Test\Acceptance\App\Http\Controllers\TeamDisplay;


use AcceptanceTester;
use Illuminate\Support\Facades\Cache;

class TeamViewDisplayCest extends \BaseAcceptance
{
    public function _before(AcceptanceTester $I)
    {
        parent::_before($I);
        $I->loginToWordpress($I, "admin","password",3);
        $this->populateDB($I);
    }

    public function _after(AcceptanceTester $I)
    {
        parent::_after($I);

    }

#test
#-----------------------------------------------------------------------------

    public function _cacheChampionData($team){
        $championPlayerIdArray = [0,1,2,3,4];
        if ($team == 'Team 1') {
            $championArray = [
                "https://ddragon.leagueoflegends.com/cdn/img/champion/loading/Ashe_0.jpg",
                "https://ddragon.leagueoflegends.com/cdn/img/champion/loading/Morgana_0.jpg",
                "https://ddragon.leagueoflegends.com/cdn/img/champion/loading/Darius_0.jpg",
                "https://ddragon.leagueoflegends.com/cdn/img/champion/loading/Nidalee_0.jpg",
                "https://ddragon.leagueoflegends.com/cdn/img/champion/loading/Zed_0.jpg",
            ];
            Cache::put('Team1Champions', $championArray, 70);
            Cache::put('Team1ChampionsPlayerId', $championPlayerIdArray, 70);
        } else {
            $championArray = [
                "https://ddragon.leagueoflegends.com/cdn/img/champion/loading/Jinx_0.jpg",
                "https://ddragon.leagueoflegends.com/cdn/img/champion/loading/Thresh_0.jpg",
                "https://ddragon.leagueoflegends.com/cdn/img/champion/loading/Renekton_0.jpg",
                "https://ddragon.leagueoflegends.com/cdn/img/champion/loading/Kindred_0.jpg",
                "https://ddragon.leagueoflegends.com/cdn/img/champion/loading/Talon_0.jpg",
            ];
            Cache::put('Team2Champions', $championArray, 70);
            Cache::put('Team2ChampionsPlayerId', $championPlayerIdArray, 70);
        }
    }
    public function seeTeam1Display(AcceptanceTester $I){
        $I->wantTo('See that the default page is loaded for team1 since there is no cache for team1');

        $I->amOnPage('/app/gamedisplay/team1');
        $I->see('Please Wait For Game To Begin', 'h1');
    }
    public function seeTeam2Display(AcceptanceTester $I){
        $I->wantTo('See that the default page is loaded for team2 since there is no cache for team2');

        $I->amOnPage('/app/gamedisplay/team2');
        $I->see('Please Wait For Game To Begin', 'h1');
    }

    public function seeTeam1StatesDisplayed(AcceptanceTester $I)
    {
        $I->wantTo('Create Cache for team1 and team2 TeamDisplay and see the teams stats displayed. Also submit champion Override and see champions loaded into the page');

        //Cache team data
        $I->amOnPage('/app/gamedisplay/admin');
        $I->waitForJs('return jQuery.active == 0', 10);
        $I->executejs("$.ajaxSetup({headers: {\"X-CSRF-TOKEN\": $(\"#hiddenToken\").text(),'Testing': true}});");
        $I->selectOption('#Tournament', 'Tester Tournament');
        $I->selectOption('#Team', 'Tester Team');
        $I->selectOption('#Team-1', 'Tester Team');
        $I->selectOption('#Color', 'Red');
        $I->selectOption('#Color-1', 'Blue');
        $I->click('Submit');
        $I->waitForText('UPDATED', 10, '.console-header');

        //See team data on pages team1 and team2
        $I->amOnPage('/app/gamedisplay/team1');
        $I->waitForText('That Team', 15, 'h1');
        $I->amOnPage('/app/gamedisplay/team2');
        $I->waitForText('Team Awesome', 15, 'h1');

        //Select champs team 1
        $this->_cacheChampionData('Team 1');

        //Select champs team 2
        $this->_cacheChampionData('Team 2');

        //see champs on team 1
        $I->amOnPage('/app/gamedisplay/team1');
        $I->waitForText('Ashe', 15, 'h3');

        //see champs on team 2
        $I->amOnPage('/app/gamedisplay/team2');
        $I->waitForText('Jinx', 15, 'h3');
    }
    public function seeTeam1AndTeam2DisplayedOnCustomerPage(AcceptanceTester $I)
    {
        $I->wantTo('Create Cache for team1 and team2 TeamDisplay and see the teams names on the customer page');

        //Cache team data
        $I->amOnPage('/app/gamedisplay/admin');
        $I->waitForJs('return jQuery.active == 0', 10);
        $I->executejs("$.ajaxSetup({headers: {\"X-CSRF-TOKEN\": $(\"#hiddenToken\").text(),'Testing': true}});");
        $I->selectOption('#Tournament', 'Tester Tournament');
        $I->selectOption('#Team', 'Tester Team');
        $I->selectOption('#Team-1', 'Tester Team');
        $I->selectOption('#Color', 'Red');
        $I->selectOption('#Color-1', 'Blue');
        $I->click('Submit');
        $I->waitForText('UPDATED', 15, '.console-header');

        $I->amOnPage('/app/gamedisplay');
        $I->waitForText('That Team',15,'#team1');
        $I->see('Team Awesome','#team2');
    }
    public function seeThatTheCarouselWorks(AcceptanceTester $I)
    {
        $I->wantTo('Create Cache for team1 and team2 TeamDisplay and see the teams stats displayed in the mobile view');

        //Cache team data
        $I->amOnPage('/app/gamedisplay/admin');
        $I->waitForJs('return jQuery.active == 0', 10);
        $I->executejs("$.ajaxSetup({headers: {\"X-CSRF-TOKEN\": $(\"#hiddenToken\").text(),'Testing': true}});");
        $I->selectOption('#Tournament', 'Tester Tournament');
        $I->selectOption('#Team', 'Tester Team');
        $I->selectOption('#Team-1', 'Tester Team');
        $I->selectOption('#Color', 'Red');
        $I->selectOption('#Color-1', 'Blue');
        $I->click('Submit');
        $I->waitForText('UPDATED', 10, '.console-header');

        //See team data on pages team1 and team2
        //Know that in the future this could change
        $I->amOnPage('/app/gamedisplay/team1');
        $I->resizeWindow(360, 862);
        $I->see('KingMorpheus2131','.summonerName');
        $I->click('.carousel-control-next');
        $I->see('Juanpablomontoya','.summonerName');
        $I->click('.carousel-control-next');
        $I->see('ThatBoy18','.summonerName');
        $I->click('.carousel-control-next');
        $I->see('manklar','.summonerName');
        $I->click('.carousel-control-next');
        $I->see('ReckonStuff','.summonerName');
        $I->click('.carousel-control-next');
        $I->see('KingMorpheus2131','.summonerName');
        $I->click('.carousel-control-next');
        $I->see('Juanpablomontoya','.summonerName');
        $I->click('.carousel-control-prev');
        $I->see('KingMorpheus2131','.summonerName');
        $I->see('Diana', '.summonerName');
        $I->click('#topChampNav0Next');
        $I->see('Malzahar','.summonerName');
        $I->click('#topChampNav0Next');
        $I->see('Morgana','.summonerName');
        $I->click('#topChampNav0Prev');
        $I->see('Malzahar','.summonerName');
    }

}