<?php
namespace Tests\Acceptance\App\Http\Controllers\Backend\Manage;

use App\Providers\ChampionshipGameComposerProvider;
use \AcceptanceTester;
use App\Models\Championship\Game;
use App\Models\Championship\Player;
use App\Models\Championship\PlayerRelation;
use App\Models\Championship\Team;
use App\Models\Championship\Tournament;
use \BaseAcceptance;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use TwigBridge\Twig\Template;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Mockery;

class IndividualPlayersControllerCest extends BaseAcceptance
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    private $pl_name;
    private $pl_username;
    private $player;
    private $ga_name;
    private $game;
    private $to_name;
    private $tournament;
    private $te_name;
    private $team;
    private $relation;
    /**
     * @param AcceptanceTester $I
     */
    public function _before(AcceptanceTester $I)
    {
        parent::_before($I);

        $this->pl_name = $this->faker->name;
        $this->pl_username = $this->faker->name;
        $this->player = factory(Player::class,1)->create(['name'=>$this->pl_name, 'username'=>$this->pl_username]);

        $this->ga_name = $this->faker->name;
        $this->game = factory(Game::class,1)->create(['name' => $this->ga_name]);

        $this->to_name = $this->faker->name;
        $this->tournament = factory(Tournament::class,1)->create(['name' => $this->to_name]);

        $this->te_name = $this->faker->name;
        $this->team = factory(Team::class,1)->create(['name' => $this->te_name]);

        $this->relation = factory(PlayerRelation::class,1)->create([
            'player_id' => $this->player->id,
            'relation_id' => $this->game->id,
            'relation_type' => Game::class
        ]);
        $this->relation = factory(PlayerRelation::class)->create([
            'player_id' => $this->player->id,
            'relation_id' => $this->tournament->id,
            'relation_type' => Tournament::class
        ]);
        $this->loginWithAdminUser($I);
        $I->amOnPage('/app/manage/individualPlayer');

    }

    /**
     * @param AcceptanceTester $I
     */
    public function _after(AcceptanceTester $I)
    {
        $this->logoutOfWp($I);
        parent::_after($I);
    }

    public function tryToAttachIndividualPlayerToSomething(AcceptanceTester $I, $scenario)
    {
        /** @todo remove this once the individual controller is complete */
        $scenario->skip();
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
        $I->executeJS("".
            "var game_sort = document.getElementById('game_sort');".
            "game_sort = game_sort.getAttribute('disabled');".
            "if(game_sort == false || game_sort == undefined){".
            "document.getElementById('playerForm').appendChild(document.createTextNode('Game Sort Field is enabled".$i.".   '));".
            "}".
            "var tournament_sort = document.getElementById('tournament_sort');".
            "tournament_sort = tournament_sort.getAttribute('disabled');".
            "if(tournament_sort == true || tournament_sort == 'disabled'){".
            "document.getElementById('playerForm').appendChild(document.createTextNode('Tournament Sort Field is disabled".$i.".   '));".
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
        $I->see("Tournament Sort Field is disabled".$i."");
        $I->see("Team Sort Field is disabled".$i."");
        $I->see("Submit Button is disabled".$i."");
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
    public function checkIfPlayerIsSelectedIfFieldsAreFull(AcceptanceTester $I)
    {
        $I->wantTo('check enable and disable selects');
        $I->amOnPage('/app/manage/individualPlayer');
        $I->selectOption(['id' => 'game_sort'], $this->ga_name);
        sleep(3); // wait for js
        $I->selectOption(['id' => 'tournament_sort'], $this->to_name);
        sleep(3); // wait for js
        $I->selectOption(['id' => 'game_sort'], $this->te_name);
        sleep(3); // wait for js
        $I->click($this->pl_username);

        $I->seeInField(['id' => 'name'], $this->pl_name);
        $I->seeInField(['id' => 'username'], $this->pl_username);
        $I->seeInField(['id' => 'email'], $this->player->email);
        $I->seeInField(['id' => 'phone'], $this->player->phone);

    }



}
