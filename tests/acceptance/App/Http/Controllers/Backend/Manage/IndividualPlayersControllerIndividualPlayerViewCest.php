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
        $I->executeJS("".
            "var submit_button = document.getElementById('submit_button');".
            "submit_button = submit_button.getAttribute('disabled');".
            "if(submit_button == true || submit_button == 'disabled'){".
            "document.getElementById('playerForm').appendChild(document.createTextNode('Submit Button is disabled.   '));".
            "}".
            "var p_name = document.getElementById('name');".
            "p_name = p_name.getAttribute('disabled');".
            "if(p_name == true || p_name == 'disabled'){".
            "document.getElementById('playerForm').appendChild(document.createTextNode('Name Field is disabled.   '));".
            "}".
            "var username = document.getElementById('username');".
            "username = username.getAttribute('disabled');".
            "if(username == true || username == 'disabled'){".
            "document.getElementById('playerForm').appendChild(document.createTextNode('Username Field is disabled.   '));".
            "}".
            "var email_ = document.getElementById('email');".
            "email_ = email_.getAttribute('disabled');".
            "if(email_ == true || email_ == 'disabled'){".
            "document.getElementById('playerForm').appendChild(document.createTextNode('Email Field is disabled.   '));".
            "}".
            "var phone_ = document.getElementById('phone');".
            "phone_ = phone_.getAttribute('disabled');".
            "if(phone_ == true || phone_ == 'disabled'){".
            "document.getElementById('playerForm').appendChild(document.createTextNode('Phone Field is disabled.   '));".
            "}".
            "var team_name = document.getElementById('team_name');".
            "team_name = team_name.getAttribute('disabled');".
            "if(team_name == true || team_name == 'disabled'){".
            "document.getElementById('playerForm').appendChild(document.createTextNode('Team Name Field is disabled.   '));".
            "}".
            "var game_sort = document.getElementById('game_sort');".
            "game_sort = game_sort.getAttribute('disabled');".
            "if(game_sort == false || game_sort == undefined){".
            "document.getElementById('playerForm').appendChild(document.createTextNode('Game Sort Field is enabled.   '));".
            "}".
            "var tournament_sort = document.getElementById('tournament_sort');".
            "tournament_sort = tournament_sort.getAttribute('disabled');".
            "if(tournament_sort == true || tournament_sort == 'disabled'){".
            "document.getElementById('playerForm').appendChild(document.createTextNode('Tournament Sort Field is disabled.   '));".
            "}".
            "var team_sort = document.getElementById('team_sort');".
            "team_sort = team_sort.getAttribute('disabled');".
            "if(team_sort == true || team_sort == 'disabled'){".
            "document.getElementById('playerForm').appendChild(document.createTextNode('Team Sort Field is disabled.   '));".
            "}".
            "var reset_ = document.getElementById('reset');".
            "reset_ = reset_.getAttribute('disabled');".
            "if(reset_ == false || reset_ == undefined){".
            "document.getElementById('playerForm').appendChild(document.createTextNode('Reset Button is enabled.   '));".
            "}"
        );
        sleep(3); // wait for js
        $I->see("Submit Button is disabled");
        $I->see("Name Field is disabled");
        $I->see("Username Field is disabled");
        $I->see("Email Field is disabled");
        $I->see("Phone Field is disabled");
        $I->see("Team Name Field is disabled");
        $I->see("Game Sort Field is enabled");
        $I->see("Tournament Sort Field is disabled");
        $I->see("Team Sort Field is disabled");
        $I->see("Reset Button is enabled");
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
        $this->CheckIfFieldsAreDisabled($I, $i);
        //new iteration
        $i++;
        $I->selectOption(['id' => 'game_sort'], "league-of-legends");

        $I->executeJS("".
            "var game_sort = document.getElementById('game_sort');".
            "game_sort = game_sort.getAttribute('disabled');".
            "if(game_sort == false || game_sort == undefined){".
            "document.getElementById('playerForm').appendChild(document.createTextNode('Game Sort Field is enabled".$i.".   '));".
            "}".
            "var tournament_sort = document.getElementById('tournament_sort');".
            "tournament_sort = tournament_sort.getAttribute('disabled');".
            "if(tournament_sort == false || tournament_sort == undefined){".
            "document.getElementById('playerForm').appendChild(document.createTextNode('Tournament Sort Field is enabled".$i.".   '));".
            "}".
            "var team_sort = document.getElementById('team_sort');".
            "team_sort = team_sort.getAttribute('disabled');".
            "if(team_sort == true || team_sort == 'disabled'){".
            "document.getElementById('playerForm').appendChild(document.createTextNode('Team Sort Field is disabled".$i.".   '));".
            "}".
            "var submit_button = document.getElementById('submit_button');".
            "submit_button = submit_button.getAttribute('disabled');".
            "if(submit_button == true || submit_button == 'disabled'){".
            "document.getElementById('playerForm').appendChild(document.createTextNode('Submit Button is disabled".$i.".   '));".
            "}"
        );
        sleep(3); // wait for js
        $I->see("Game Sort Field is enabled".$i."");
        $I->see("Tournament Sort Field is enabled".$i."");
        $I->see("Team Sort Field is disabled".$i."");
        $I->see("Submit Button is disabled".$i."");

        //new iteration
        $i++;
        $I->selectOption(['id' => 'tournament_sort'], "gigazone-gaming-2016-league-of-legends");

        $I->executeJS("".
            "var game_sort = document.getElementById('game_sort');".
            "game_sort = game_sort.getAttribute('disabled');".
            "if(game_sort == false || game_sort == undefined){".
            "document.getElementById('playerForm').appendChild(document.createTextNode('Game Sort Field is enabled".$i.".   '));".
            "}".
            "var tournament_sort = document.getElementById('tournament_sort');".
            "tournament_sort = tournament_sort.getAttribute('disabled');".
            "if(tournament_sort == false || tournament_sort == undefined){".
            "document.getElementById('playerForm').appendChild(document.createTextNode('Tournament Sort Field is enabled".$i.".   '));".
            "}".
            "var team_sort = document.getElementById('team_sort');".
            "team_sort = team_sort.getAttribute('disabled');".
            "if(team_sort == false || team_sort == undefined){".
            "document.getElementById('playerForm').appendChild(document.createTextNode('Team Sort Field is enabled".$i.".   '));".
            "}".
            "var submit_button = document.getElementById('submit_button');".
            "submit_button = submit_button.getAttribute('disabled');".
            "if(submit_button == true || submit_button == 'disabled'){".
            "document.getElementById('playerForm').appendChild(document.createTextNode('Submit Button is disabled".$i.".   '));".
            "}"
        );
        sleep(3); // wait for js
        $I->see("Game Sort Field is enabled".$i."");
        $I->see("Tournament Sort Field is enabled".$i."");
        $I->see("Team Sort Field is enabled".$i."");
        $I->see("Submit Button is disabled".$i."");
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
        $I->wait(3);
        $I->seeInField(['id' => 'name'], $name);
        $I->seeInField(['id' => 'username'], $user);
        $I->seeInField(['id' => 'email'], $email);
        $I->seeInField(['id' => 'phone'], $phone);


    }

    /**
     * @param AcceptanceTester $I
     * @param $i
     */
    private function CheckIfFieldsAreDisabled(AcceptanceTester $I, $i=0)
    {
        $I->executeJS("" .
            "var game_sort = document.getElementById('game_sort');" .
            "game_sort = game_sort.getAttribute('disabled');" .
            "if(game_sort == false || game_sort == undefined){" .
            "document.getElementById('playerForm').appendChild(document.createTextNode('Game Sort Field is enabled" . $i . ".   '));" .
            "}" .
            "var tournament_sort = document.getElementById('tournament_sort');" .
            "tournament_sort = tournament_sort.getAttribute('disabled');" .
            "if(tournament_sort == true || tournament_sort == 'disabled'){" .
            "document.getElementById('playerForm').appendChild(document.createTextNode('Tournament Sort Field is disabled" . $i . ".   '));" .
            "}" .
            "var team_sort = document.getElementById('team_sort');" .
            "team_sort = team_sort.getAttribute('disabled');" .
            "if(team_sort == true || team_sort == 'disabled'){" .
            "document.getElementById('playerForm').appendChild(document.createTextNode('Team Sort Field is disabled" . $i . ".   '));" .
            "}" .
            "var submit_button = document.getElementById('submit_button');" .
            "submit_button = submit_button.getAttribute('disabled');" .
            "if(submit_button == true || submit_button == 'disabled'){" .
            "document.getElementById('playerForm').appendChild(document.createTextNode('Submit Button is disabled" . $i . ".   '));" .
            "}"
        );
        sleep(3); // wait for js
        $I->see("Game Sort Field is enabled" . $i . "");
        $I->see("Tournament Sort Field is disabled" . $i . "");
        $I->see("Team Sort Field is disabled" . $i . "");
        $I->see("Submit Button is disabled" . $i . "");
    }
    /**
     * @param AcceptanceTester $I
     * @param $i
     */
    private function CheckIfFieldsAreDisabledAfterTeamSelection(AcceptanceTester $I, $i=0)
    {
        $I->executeJS("" .
            "var game_sort = document.getElementById('game_sort');" .
            "game_sort = game_sort.getAttribute('disabled');" .
            "if(game_sort == false || game_sort == undefined){" .
            "document.getElementById('playerForm').appendChild(document.createTextNode('Game Sort Field is enabled double check.   '));" .
            "}" .
            "var tournament_sort = document.getElementById('tournament_sort');" .
            "tournament_sort = tournament_sort.getAttribute('disabled');" .
            "if(tournament_sort == false || tournament_sort == undefined){" .
            "document.getElementById('playerForm').appendChild(document.createTextNode('Tournament Sort Field is enabled double check.   '));" .
            "}" .
            "var team_sort = document.getElementById('team_sort');" .
            "team_sort = team_sort.getAttribute('disabled');" .
            "if(team_sort == false || team_sort == undefined){" .
            "document.getElementById('playerForm').appendChild(document.createTextNode('Team Sort Field is enabled double check.  '));" .
            "}" .
            "var submit_button = document.getElementById('submit_button');" .
            "submit_button = submit_button.getAttribute('disabled');" .
            "if(submit_button == false || submit_button == undefined){" .
            "document.getElementById('playerForm').appendChild(document.createTextNode('Submit Button is enabled double check.  '));" .
            "}"
        );
        sleep(3); // wait for js
        $I->see("Game Sort Field is enabled double check.");
        $I->see("Tournament Sort Field is enabled double check.");
        $I->see("Team Sort Field is enabled double check.");
        $I->see("Submit Button is enabled double check.");
    }

}
