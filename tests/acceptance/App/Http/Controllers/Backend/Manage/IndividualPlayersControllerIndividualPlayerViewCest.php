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
        $name = $I->executeJS("return $('#name').attr('disabled');");
        $username = $I->executeJS("return $('#username').attr('disabled');");
        $email = $I->executeJS("return $('#email').attr('disabled');");
        $phone = $I->executeJS("return $('#phone').attr('disabled');");
        $teamName = $I->executeJS("return $('#team_name').attr('disabled');");
        $reset = $I->executeJS("return $('#reset').attr('disabled');");
        list($game, $tournament, $team, $submit) = $this->ICheckFor_EnablesTrue_Or_DisablesFalse($I);
        $I->assertSame(null, $game, "Game Sort should be enabled");
        $I->assertSame('disabled', $tournament, "Tournament Sort should be disabled");
        $I->assertSame('disabled', $team, "Team Sort should be disabled");
        $I->assertSame('disabled', $submit, "Submit Button should be disabled");
        $I->assertSame(null, $reset, "Reset Button should be enabled");
        $I->assertSame('disabled', $submit, "Submit Button should be disabled");
        $I->assertSame('disabled', $name, "Name Field should be disabled");
        $I->assertSame('disabled', $username, "Username Field should be disabled");
        $I->assertSame('disabled', $email, "Email Field should be disabled");
        $I->assertSame('disabled', $phone, "Phone Field should be disabled");
        $I->assertSame('disabled', $teamName, "TeamName Field should be disabled");
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
        $I->assertSame(null, $game, "Game Sort should be enabled");
        $I->assertSame('disabled', $tournament, "Tournament Sort should be disabled");
        $I->assertSame('disabled', $team, "Team Sort should be disabled");
        $I->assertSame('disabled', $submit, "Submit Button should be disabled");

        $I->selectOption(['id' => 'game_sort'], "tester-game");
        list($game, $tournament, $team, $submit) = $this->ICheckFor_EnablesTrue_Or_DisablesFalse($I);
        $I->assertSame(null, $game, "Game Sort should be enabled");
        $I->assertSame(null, $tournament, "Tournament Sort should be enabled");
        $I->assertSame('disabled', $team, "Team Sort should be disabled");
        $I->assertSame('disabled', $submit, "Submit Button should be disabled");

        $I->selectOption(['id' => 'tournament_sort'], "Tester Tournament");
        list($game, $tournament, $team, $submit) = $this->ICheckFor_EnablesTrue_Or_DisablesFalse($I);
        $I->assertSame(null, $game, "Game Sort should be enabled");
        $I->assertSame(null, $tournament, "Tournament Sort should be enabled");
        $I->assertSame(null, $team, "Team Sort should be enabled");
        $I->assertSame('disabled', $submit, "Submit Button should be disabled");

        $I->selectOption(['id' => 'team_sort'], "Tester Team");
        list($game, $tournament, $team, $submit) = $this->ICheckFor_EnablesTrue_Or_DisablesFalse($I);
        $I->assertSame(null, $game, "Game Sort should be enabled");
        $I->assertSame(null, $tournament, "Tournament Sort should be enabled");
        $I->assertSame(null, $team, "Team Sort should be enabled");
        $I->assertSame(null, $submit, "Submit Button should be enabled");
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
        $id = $I->executeJS('return $(".btn.btn-default.aPlayer.playerName.list").first().attr("id")');
        $name = $I->executeJS('return $(".btn.btn-default.aPlayer.playerName.list").first().attr("player_name")');
        $user = $I->executeJS('return $(".btn.btn-default.aPlayer.playerName.list").first().attr("player_user")');
        $phone = $I->executeJS('return $(".btn.btn-default.aPlayer.playerName.list").first().attr("player_phone")');
        $email = $I->executeJS('return $(".btn.btn-default.aPlayer.playerName.list").first().attr("player_email")');
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
        $I->assertSame(null, $game, "Game Sort should be enabled");
        $I->assertSame(null, $tournament, "Tournament Sort should be enabled");
        $I->assertSame(null, $team, "Team Sort should be enabled");
        $I->assertSame(null, $submit, "Submit Button should be enabled");
    }

    /**
     * @param AcceptanceTester $I
     * @return array
     */
    private function ICheckFor_EnablesTrue_Or_DisablesFalse(AcceptanceTester $I)
    {
        $game = $I->executeJS("return $('#game_sort').attr('disabled');");
        $tournament = $I->executeJS("return $('#tournament_sort').attr('disabled');");
        $team = $I->executeJS("return $('#team_sort').attr('disabled');");
        $submit = $I->executeJS("return $('#submit_button').attr('disabled');");
        $I->wait(3);
//        var_dump($game)
        return array($game, $tournament, $team, $submit);
    }

}
