<?php
namespace Tests\Acceptance\App\Http\Controllers\Backend\Manage;

use \AcceptanceTester;
use \BaseAcceptance;

/**
 * Class TournamentsControllerCest
 * @package Tests\Acceptance\App\Http\Controllers\Backend\Manage
 */
class TournamentsControllerCest extends BaseAcceptance
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
        $I->amOnPage('/app/manage/tournament');

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
     * Get to the tournament management page and check for the max_playerss
     */
    public function tryToGetToTheTournamentApp(AcceptanceTester $I)
    {
        $I->wantTo('get to the tournament management page');
        $I->see('Create a new Tournament');
        $I->see('Tournament List');
    }

    /**
     * @param AcceptanceTester $I
     */
    public function tryToCreateATournament(AcceptanceTester $I)
    {
        $I->wantTo('create a tournament on the management page');
        list($name, $max_players, $game_id) = $this->createATournament($I);
        $I->click(['id'=>'edit-'.str_replace(' ', '', $name)]);
        $I->seeInField(['id' => 'name'], $name);
        $I->seeInField(['id' => 'max_players'], $max_players);
        $I->seeOptionIsSelected(['id' => 'game_id'], $game_id);
        // check return, we should have a message and all the fields filled
    }

    /**
     * @param AcceptanceTester $I
     */
    public function tryToUpdateATournament(AcceptanceTester $I)
    {
        $I->wantTo('update a tournament on the management page');

        list($name, $max_players, $game_id) = $this->createATournament($I);

        $name2 = implode('-', $this->faker->words(3));
        $max_players2 = $this->faker->numberBetween(1, 10);
        $I->click(['id'=>'edit-'.str_replace(' ', '', $name)]);
        $I->seeInField(['id' => 'name'], $name);
        $I->seeInField(['id' => 'max_players'], $max_players);
        $I->seeOptionIsSelected(['id' => 'game_id'], $game_id);
        // fill fields with new values
        $I->fillField(['id' => 'name'], $name2);
        $I->fillField(['id' => 'max_players'], $max_players2);
        $I->selectOption(['id' => 'game_id'], $game_id);

        $I->click(['id' => 'submit']);

        // check that the fields are now all updated
        $I->see('The tournament '. $name2.' was updated');
        $I->seeInField(['id' => 'name'], $name2);
        $I->seeInField(['id' => 'max_players'], $max_players2);
        $I->seeOptionIsSelected(['id' => 'game_id'], $game_id);


    }

    /**
     * @param AcceptanceTester $I
     */
    public function tryAndUpdateATournamentKeepingEverythingTheSame(AcceptanceTester $I)
    {
        $I->wantTo('update a tournament, keeping the everything the same.');

        // make a tournament, then update
        list($name, $max_players, $game_id) = $this->createATournament($I);
        $I->click(['id'=>'edit-'.str_replace(' ', '', $name)]);
        $I->seeInField(['id' => 'name'], $name);
        $I->seeInField(['id' => 'max_players'], $max_players);
        $I->seeOptionIsSelected(['id' => 'game_id'], $game_id);
        // fill fields with new values
        $I->fillField(['id' => 'name'], $name);
        $I->fillField(['id' => 'max_players'], $max_players);
        $I->selectOption(['id' => 'game_id'], $game_id);

        $I->click(['id' => 'submit']);

        // check that the fields are now all updated
        $I->see('The tournament '. $name.' was updated');
        $I->seeInField(['id' => 'name'], $name);
        $I->seeInField(['id' => 'max_players'], $max_players);
        $I->seeOptionIsSelected(['id' => 'game_id'], $game_id);

    }

    /**
     * Ensure when creating a tournament that the name is present
     *
     * @param AcceptanceTester $I
     */
    public function seeErrorWhenNameIsMissing(AcceptanceTester $I)
    {
        $I->click(['id' => 'submit']);
        $I->see('The Tournament Name is required.');
    }
    /**
     * Ensure when creating a tournament that the name is present
     *
     * @param AcceptanceTester $I
     */
    public function seeErrorWhenGameIsntSelected(AcceptanceTester $I)
    {
        $I->click(['id' => 'submit']);
        $I->see('A Game must be selected');
    }
    /**
     * Ensure when creating a tournament that the name is present
     *
     * @param AcceptanceTester $I
     */
    public function seeErrorWhenMaxPlayersIsntInput(AcceptanceTester $I)
    {
        $I->click(['id' => 'submit']);
        $I->see('The number of players is a required field');
    }
    /**
     * Ensure when creating a tournament that the name is present
     *
     * @param AcceptanceTester $I
     */
    public function seeErrorWhenMaxPlayersIsntANumber(AcceptanceTester $I)
    {
        $I->executeJS(
            'document.getElementsByName("max_players")[0].type="text";'
        );
        $I->fillField(['id' => 'max_players'], "Not A Number");
        $I->click(['id' => 'submit']);
        $I->see('The NUMBER of players needs to be ... a number, LOL.');

    }

    /**
     * Ensure when creating a tournament that the name isn't already being used
     *
     * @param AcceptanceTester $I
     */
    public function seeErrorWhenNameIsAlreadyUsed(AcceptanceTester $I)
    {
        list($name, $max_players, $game_id) = $this->createATournament($I);

        $I->amOnPage('/app/manage/tournament');
        $I->fillField(['id' => 'name'], $name);
        $I->fillField(['id' => 'max_players'], $max_players);
        $I->selectOption(['id' => 'game_id'], $game_id);
        $I->waitForJS('return document.getElementById("submit").click();', BaseAcceptance::TEXT_WAIT_TIMEOUT);
        $I->see('The name has already been taken');
    }


    /**
     * @param AcceptanceTester $I
     * @return array
     */
    private function createATournament(AcceptanceTester $I, $attributes = [])
    {
        $name = "Tester Tournament";
        $max_players = 6;
        $game_id = "tester-game";

        return array($name, $max_players, $game_id);
    }

}
