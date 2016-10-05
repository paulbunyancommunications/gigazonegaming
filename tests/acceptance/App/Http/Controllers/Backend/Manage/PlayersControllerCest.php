<?php
namespace Tests\Acceptance\App\Http\Controllers\Backend\Manage;

use \AcceptanceTester;
use \BaseAcceptance;

/**
 * Class PlayersControllerCest
 * @package Tests\Acceptance\App\Http\Controllers\Backend\Manage
 */
class PlayersControllerCest extends BaseAcceptance
{
    /**
     * @param AcceptanceTester $I
     */
    public function _before(AcceptanceTester $I)
    {
        parent::_before($I);
        $this->loginWithAdminUser($I);
        $I->amOnPage('/app/manage/player');

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
     * Get to the player management page and check for the max_playerss
     */
    public function tryToGetToThePlayerApp(AcceptanceTester $I)
    {
        $I->wantTo('get to the player management page');
        $I->amOnPage('/app/manage/player');
        $I->see('Create a new player');
    }

//    /**
//     * @param AcceptanceTester $I
//     */
//    public function tryToCreateAPlayer(AcceptanceTester $I)
//    {
//        $I->wantTo('create a player on the management page');
//        list($name, $max_players, $game_id) = $this->createAPlayer($I);
//
//        // check return, we should have a message and all the fields filled
//        $I->see('The player '.$name.' was added');
//
//    }
//
//    /**
//     * @param AcceptanceTester $I
//     */
//    public function tryToUpdateAPlayer(AcceptanceTester $I)
//    {
//        $I->wantTo('update a player on the management page');
//
//        list($name, $max_players, $game_id) = $this->createAPlayer($I);
//
//        $name2 = implode('-', $this->faker->words(3));
//        $max_players2 = $this->faker->numberBetween(1, 10);
//        $I->click(['id'=>'edit-'.$name]);
//        $I->seeInField(['id' => 'name'], $name);
//        $I->seeInField(['id' => 'max_players'], $max_players);
//        $I->seeOptionIsSelected(['id' => 'game_id'], $game_id);
//        // fill fields with new values
//        $I->fillField(['id' => 'name'], $name2);
//        $I->fillField(['id' => 'max_players'], $max_players2);
//        $I->selectOption(['id' => 'game_id'], $game_id);
//
//        $I->click(['id' => 'submit']);
//
//        // check that the fields are now all updated
//        $I->see('The player '. $name2.' was updated');
//        $I->seeInField(['id' => 'name'], $name2);
//        $I->seeInField(['id' => 'max_players'], $max_players2);
//        $I->seeOptionIsSelected(['id' => 'game_id'], $game_id);
//
//
//    }
//
//    /**
//     * @param AcceptanceTester $I
//     */
//    public function tryAndUpdateAPlayerKeepingEverythingTheSame(AcceptanceTester $I)
//    {
//        $I->wantTo('update a player, keeping the everything the same.');
//
//        // make a player, then update
//        list($name, $max_players, $game_id) = $this->createAPlayer($I);
//        $I->click(['id'=>'edit-'.$name]);
//        $I->seeInField(['id' => 'name'], $name);
//        $I->seeInField(['id' => 'max_players'], $max_players);
//        $I->seeOptionIsSelected(['id' => 'game_id'], $game_id);
//        // fill fields with new values
//        $I->fillField(['id' => 'name'], $name);
//        $I->fillField(['id' => 'max_players'], $max_players);
//        $I->selectOption(['id' => 'game_id'], $game_id);
//
//        $I->click(['id' => 'submit']);
//
//        // check that the fields are now all updated
//        $I->see('The player '. $name.' was updated');
//        $I->seeInField(['id' => 'name'], $name);
//        $I->seeInField(['id' => 'max_players'], $max_players);
//        $I->seeOptionIsSelected(['id' => 'game_id'], $game_id);
//
//    }
//
//    /**
//     * Ensure when creating a player that the name is present
//     *
//     * @param AcceptanceTester $I
//     */
//    public function seeErrorWhenNameIsMissing(AcceptanceTester $I)
//    {
//        $I->click(['id' => 'submit']);
//        $I->see('The Player Name is required.');
//    }
//    /**
//     * Ensure when creating a player that the name is present
//     *
//     * @param AcceptanceTester $I
//     */
//    public function seeErrorWhenGameIsntSelected(AcceptanceTester $I)
//    {
//        $I->click(['id' => 'submit']);
//        $I->see('A Game must be selected');
//    }
//    /**
//     * Ensure when creating a player that the name is present
//     *
//     * @param AcceptanceTester $I
//     */
//    public function seeErrorWhenMaxPlayersIsntInput(AcceptanceTester $I)
//    {
//        $I->click(['id' => 'submit']);
//        $I->see('The Number of players is a required field');
//    }
//    /**
//     * Ensure when creating a player that the name is present
//     *
//     * @param AcceptanceTester $I
//     */
//    public function seeErrorWhenMaxPlayersIsntANumber(AcceptanceTester $I)
//    {
//        $I->fillField(['id' => 'max_players'], "Not A Number");
//        $I->click(['id' => 'submit']);
//        $I->see('The Number of players needs to be a number, LOL.');
//    }
//
//    /**
//     * Ensure when creating a player that the name isn't already being used
//     *
//     * @param AcceptanceTester $I
//     */
//    public function seeErrorWhenNameIsAlreadyUsed(AcceptanceTester $I)
//    {
//        list($name, $max_players, $game_id) = $this->createAPlayer($I);
//        $I->amOnPage('/app/manage/player');
//
//        $I->fillField(['id' => 'name'], $name);
//        $I->fillField(['id' => 'max_players'], $max_players);
//        $I->selectOption(['id' => 'game_id'], $game_id);
//        $I->click(['id' => 'submit']);
//        $I->see('The name has already been taken');
//    }
//
//
//    /**
//     * @param AcceptanceTester $I
//     * @return array
//     */
//    private function createAPlayer(AcceptanceTester $I, $attributes = [])
//    {
//        $name = array_key_exists('name', $attributes) ? $attributes['name'] : implode('-', $this->faker->words(3));
//        $max_players = array_key_exists('max_players', $attributes) ? $attributes['max_players'] : $this->faker->numberBetween(1, 10);
//        $game_id = "league-of-legends";
//
//        $I->fillField(['id' => 'name'], $name);
//        $I->fillField(['id' => 'max_players'], $max_players);
//        $I->selectOption(['id' => 'game_id'], $game_id);
//        $I->click(['id' => 'submit']);
//        return array($name, $max_players, $game_id);
//    }

}
