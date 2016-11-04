<?php
namespace Tests\Acceptance\App\Http\Controllers\Backend\Manage;

use App\Providers\ChampionshipGameComposerProvider;
use Illuminate\Support\Facades\DB;
use \AcceptanceTester;
use App\Models\Championship\Game;
use App\Models\Championship\Player;
use App\Models\Championship\PlayerRelation;
use App\Models\Championship\Team;
use App\Models\Championship\Tournament;
use \BaseAcceptance;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Mockery;


class IndividualPlayersControllerIndividualPlayerViewCest extends BaseAcceptance
{

    /**
     * @param AcceptanceTester $I
     */
    public $faker;
    public function _before(AcceptanceTester $I)
    {
        parent::_before($I);
        $this->populateDB($I);
        $this->loginWithAdminUser($I);

    }

    /**
     * Create the test admin user
     */
    protected function populateDB(AcceptanceTester $I)
    {
        exec('php artisan db:seed --class=DatabaseSeeder');
        $this->faker = \Faker\Factory::create();
    }

    /**
     * @param AcceptanceTester $I
     */
    public function _after(AcceptanceTester $I)
    {
        $this->logoutOfWp($I);
        parent::_after($I);
    }
    /**
     * @param AcceptanceTester $I
     * Get to the team management page and check for the emblems
     */
    public function tryToGetToTheIndividualPlayerApp(AcceptanceTester $I)
    {
        $I->wantTo('get to the individual player management page');
        $I->amOnPage('/app/manage/individualPlayer');
        $I->see('Update Individual Player');
    }
    /**
     * Ensure before trying to attach an individual player to a team that the fields are disabled
     *
     * @param AcceptanceTester $I
     */
    public function seeIfFieldsAreDisableWhenNoPlayerIsSelected(AcceptanceTester $I)
    {

        $I->wantTo('check enable and disable inputs');
        $I->amOnPage('/app/manage/individualPlayer');
        $name = $I->waitForJS("return $('#name').getAttribute('disabled');", 10);
        $username = $I->waitForJS("return $('#username').getAttribute('disabled');", 10);
        $email = $I->waitForJS("return $('#email').getAttribute('disabled');", 10);
        $phone = $I->waitForJS("return $('#phone').getAttribute('disabled');", 10);
        $teamName = $I->waitForJS("return $('#team_name').getAttribute('disabled');", 10);
        $reset = $I->waitForJS("return $('#reset').getAttribute('disabled');", 10);
        list($game, $tournament, $team, $submit) = $this->ICheckFor_EnablesTrue_Or_DisablesFalse($I);
        $I->assertSame(true, $game, "Game Sort should be enabled");
        $I->assertSame(false, $tournament, "Tournament Sort should be disabled");
        $I->assertSame(false, $team, "Team Sort should be disabled");
        $I->assertSame(false, $submit, "Submit Button should be disabled");
        $I->assertSame(true, $reset, "Reset Button should be enabled");
        $I->assertSame(false, $submit, "Submit Button should be disabled");
        $I->assertSame(false, $name, "Name Field should be disabled");
        $I->assertSame(false, $username, "Username Field should be disabled");
        $I->assertSame(false, $email, "Email Field should be disabled");
        $I->assertSame(false, $phone, "Phone Field should be disabled");
        $I->assertSame(false, $teamName, "TeamName Field should be disabled");
    }


    /**
     * Ensure before trying to attach an individual player to a team that the selectors are disabled but enabled after selecting parent selector
     *
     * @param AcceptanceTester $I
     */
    public function seeIfSelectsAreEnabledOrDisabledAfterSelectingParentSelector(AcceptanceTester $I)
    {
        $i = 0;
        $I->wantTo('check enable and disable selects');
        $I->amOnPage('/app/manage/individualPlayer');
        list($game, $tournament, $team, $submit) = $this->ICheckFor_EnablesTrue_Or_DisablesFalse($I);
        $I->assertSame(true, $game, "Game Sort should be enabled");
        $I->assertSame(false, $tournament, "Tournament Sort should be disabled");
        $I->assertSame(false, $team, "Team Sort should be disabled");
        $I->assertSame(false, $submit, "Submit Button should be disabled");

        $I->selectOption(['id' => 'game_sort'], "league-of-legends");
        list($game, $tournament, $team, $submit) = $this->ICheckFor_EnablesTrue_Or_DisablesFalse($I);
        $I->assertSame(true, $game, "Game Sort should be enabled");
        $I->assertSame(true, $tournament, "Tournament Sort should be enabled");
        $I->assertSame(false, $team, "Team Sort should be disabled");
        $I->assertSame(false, $submit, "Submit Button should be disabled");

        $I->selectOption(['id' => 'tournament_sort'], "gigazone-gaming-2016-league-of-legends");
        list($game, $tournament, $team, $submit) = $this->ICheckFor_EnablesTrue_Or_DisablesFalse($I);
        $I->assertSame(true, $game, "Game Sort should be enabled");
        $I->assertSame(true, $tournament, "Tournament Sort should be enabled");
        $I->assertSame(true, $team, "Team Sort should be enabled");
        $I->assertSame(false, $submit, "Submit Button should be disabled");
    }


    /**
     * Ensure before trying to attach an individual player to a team that the selectors are disabled but enabled after selecting parent selector
     *
     * @param AcceptanceTester $I
     */
    public function CheckIfThereIsPlayersAssignedToTheTournament(AcceptanceTester $I)
    {
        $I->wantTo('check if enable and disable selects');
        $I->amOnPage('/app/manage/individualPlayer');
        $I->selectOption(['id' => 'game_sort'], 'tester-game');//this will enable users under this game and the tournament select
        $I->selectOption(['id' => 'tournament_sort'], 'Tester Tournament');//this will enable the team select
        $I->selectOption(['id' => 'team_sort'], 'Tester Team');//this will pre-select the team
        $id = $I->waitForJS('return $(".btn.btn-default.aPlayer.playerName.list").first().attr("id")', 30);
        $name = $I->waitForJS('return $(".btn.btn-default.aPlayer.playerName.list").first().attr("player_name")', 30);
        $user = $I->waitForJS('return $(".btn.btn-default.aPlayer.playerName.list").first().attr("player_user")', 30);
        $phone = $I->waitForJS('return $(".btn.btn-default.aPlayer.playerName.list").first().attr("player_phone")', 30);
        $email = $I->waitForJS('return $(".btn.btn-default.aPlayer.playerName.list").first().attr("player_email")', 30);

        $I->wait(3);
        $this->CheckIfFieldsAreDisabledAfterTeamSelection($I);
        $I->click( "#".$id);
        $I->seeInField(['id' => 'name'], $name);
        $I->seeInField(['id' => 'username'], $user);
        $I->seeInField(['id' => 'email'], $email);
        $I->seeInField(['id' => 'phone'], $phone);


    }

    /**
     * @param AcceptanceTester $I
     * @param $i
     */
    private function CheckIfFieldsAreDisabledAfterTeamSelection(AcceptanceTester $I, $i=0)
    {
        list($game, $tournament, $team, $submit) = $this->ICheckFor_EnablesTrue_Or_DisablesFalse($I);
        $I->assertSame(true, $game, "Game Sort should be enabled");
        $I->assertSame(true, $tournament, "Tournament Sort should be enabled");
        $I->assertSame(true, $team, "Team Sort should be enabled");
        $I->assertSame(true, $submit, "Submit Button should be enabled");
    }

    /**
     * @param AcceptanceTester $I
     * @return array
     */
    private function ICheckFor_EnablesTrue_Or_DisablesFalse(AcceptanceTester $I)
    {
        $game = $I->waitForJS("return $('#game_sort').getAttribute('disabled');", 10);
        $tournament = $I->waitForJS("return $('#tournament_sort').getAttribute('disabled');", 10);
        $team = $I->waitForJS("return $('#team_sort').getAttribute('disabled');", 10);
        $submit = $I->waitForJS("return $('#submit_button').getAttribute('disabled');", 10);
        return array($game, $tournament, $team, $submit);
    }

}
