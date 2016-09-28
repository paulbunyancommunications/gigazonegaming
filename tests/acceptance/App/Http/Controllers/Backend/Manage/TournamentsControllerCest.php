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
    public function _before(AcceptanceTester $I)
    {
        parent::_before($I);
        $this->loginWithAdminUser($I);
        $I->amOnPage('/app/manage/tournament');

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
        $I->amOnPage('/app/manage/tournament');
        $I->see('Create a new tournament');
        $I->see('Tournament List');
    }

    /**
     * @param AcceptanceTester $I
     */
    public function tryToCreateATournament(AcceptanceTester $I)
    {
        $I->wantTo('create a tournament on the management page');
        list($name, $max_players, $game_id) = $this->createATournament($I);

        // check return, we should have a message and all the fields filled
        $I->see('The tournament '.$max_players.' was added');
        $I->seeInField(['id' => 'name'], $name);
        $I->seeInField(['id' => 'max_players'], $max_players);
        $I->seeInField(['id' => 'game_id'], $game_id);
        
    }

    /**
     * @param AcceptanceTester $I
     */
    public function tryToUpdateATournament(AcceptanceTester $I)
    {
        $I->wantTo('update a tournament on the management page');

        $this->createATournament($I);

        $name2 = implode('-', $this->faker->words(3));
        $max_players2 = $this->faker->numberBetween(1, 10);
        $game_id = "league-of-legends";
        // fill fields with new values
        $I->fillField(['id' => 'name'], $name2);
        $I->fillField(['id' => 'max_players'], $max_players2);
        $I->selectOption(['id' => 'game_id'], $game_id);
        
        $I->click(['id' => 'submit']);

        // check that the fields are now all updated
        $I->see('Tournament '. $max_players2.' was updated');
        $I->seeInField(['id' => 'name'], $name2);
        $I->seeInField(['id' => 'max_players'], $max_players2);

        
    }

    /**
     * @param AcceptanceTester $I
     */
    public function tryAndUpdateATournamentKeepingTheSameTournamentName(AcceptanceTester $I)
    {
        $I->wantTo('update a tournament ,keeping the same name and max_players but changing everything else.');

        // make a tournament, then update
        list($name, $max_players, $game_id) = $this->createATournament($I);
        $this->FillAndClick($I, $name, $max_players, $game_id);

        // I don't see dupe errors
        $I->dontSee('The tournament name is is already being used.');

        // I see the right message and values in the fields
        $I->see('Tournament '. $max_players.' was updated');
        $I->seeInField(['id' => 'name'], $name);
        $I->seeInField(['id' => 'max_players'], $max_players);
        $I->seeInField(['id' => 'game_id'], $game_id);

    }

    /**
     * Ensure when creating a tournament that the name is present
     *
     * @param AcceptanceTester $I
     */
    public function seeErrorWhenNameIsMissing(AcceptanceTester $I)
    {
        $I->click(['id' => 'submit']);
        $I->see('The tournament name is required.');
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
        $I->click(['id' => 'submit']);

        $I->click(['id' => 'submit']);
        $I->see('The tournament name is is already being used.');
    }


    /**
     * @param AcceptanceTester $I
     * @return array
     */
    private function createATournament(AcceptanceTester $I, $attributes = [])
    {
        $name = array_key_exists('name', $attributes) ? $attributes['name'] : implode('-', $this->faker->words(3));
        $max_players = array_key_exists('max_players', $attributes) ? $attributes['max_players'] : $this->faker->numberBetween(1, 10);
        $game_id = "league-of-legends";

        $I->fillField(['id' => 'name'], $name);
        $I->fillField(['id' => 'max_players'], $max_players);
        $I->selectOption(['id' => 'game_id'], $game_id);
        $I->click(['id' => 'submit']);
        return array($name, $max_players, $game_id);
    }

    /**
     * @param AcceptanceTester $I
     * @param $name
     * @param $max_players
     * @param $game_id
     * @param $desc2
     */
    public function FillAndClick(AcceptanceTester $I, $name, $max_players, $game_id)
    {
        $I->fillField(['id' => 'name'], $name);
        $I->fillField(['id' => 'max_players'], $max_players);
        $I->selectOption(['id' => 'game_id'], $game_id);
        $I->click(['id' => 'submit']);
    }
}
