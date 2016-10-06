<?php
/**
 * BackEndAcceptanceCreators
 *
 * Created by PhpStorm.
 * User: nelson_castillo
 * Date: 10/5/16
 * Time: 14:41
 *
 * Creates App parts on view
 *
 * @author Nelson Castillo
 * @package Codeception\Module
 */

namespace Codeception\Module;


/**
 * Class BackEndAcceptanceCreators
 * @package Codeception\Module
 */
class BackEndAcceptanceCreators extends \Codeception\Module
{

    private $pl_name;
    private $pl_email;
    private $pl_phone;
    private $pl_username;
    private $ga_name;
    private $ga_title;
    private $to_name;
    private $te_name;
    private $faker;

    public function _initialize()
    {
        $this->faker = \Faker\Factory::create();
    }
    /**
     * @param \AcceptanceTester|\FunctionalTester $I
     */
    public function getAGame(\AcceptanceTester $I){
        $I->amOnPage('/app/manage/game');
        $this->goToViewAndCreateAGame($I);
        return $this->ga_name;
    }
    /**
     * @param \AcceptanceTester|\FunctionalTester $I
     */
    private function goToViewAndCreateAGame(\AcceptanceTester $I)
    {
        $this->ga_name = $this->faker->name;
        $this->ga_title = $this->ga_name;
        $I->amOnPage('/app/manage/game');
        $I->see('Create a new game');
        $I->fillField('name', $this->ga_name);
        $I->fillField('title', $this->ga_name);
        $I->fillField('uri', $this->faker->url);
        $I->fillField('description', $this->faker->title);
        $I->click('Create');
        $I->see("The game " . $this->ga_title . " was added!");

    }

    /**
     * @param \AcceptanceTester|\FunctionalTester $I
     * @param string $name
     */
    public function getATournament(\AcceptanceTester $I, $name = ''){

        $I->amOnPage('/app/manage/tournament');
        $I->see("Create a new Tournament");
        if($name !=''){
            $name = array('game_name'=> $name);
            $this->goToViewAndCreateATournament($I, $name);
        }else{
            $this->goToViewAndCreateATournament($I);
        }
        return $this->to_name;
    }
    /**
     * @param \AcceptanceTester|\FunctionalTester $I
     * @param array $attributes
     */
    private function goToViewAndCreateATournament(\AcceptanceTester $I, $attributes = [])
    {
        $name = array_key_exists('name', $attributes) ? $attributes['name'] : implode('-', $this->faker->name);
        $max_players = array_key_exists('max_players', $attributes) ? $attributes['max_players'] : $this->faker->numberBetween(1, 10);
        $game_id = array_key_exists('game_name', $attributes) ? $attributes['game_name'] : (isset($this->ga_name) and $this->ga_name!=null and $this->ga_name!='') ? $this->ga_name : "league-of-legends";

        $I->amOnPage('/app/manage/tournament');
        $I->see("Create a new Tournament");
        $I->fillField(['id' => 'name'], $name);
        $I->fillField(['id' => 'max_players'], $max_players);
        $I->selectOption(['id' => 'game_id'], ['text'=>$game_id]);
        $I->click(['id' => 'submit']);
    }

    /**
     * @param \AcceptanceTester|\FunctionalTester $I
     */
    public function getATeam(\AcceptanceTester $I){
        $I->amOnPage('/app/manage/team');
        $this->goToViewAndCreateATeam($I);
        return $this->te_name;
    }
    /**
     * @param \AcceptanceTester|\FunctionalTester $I
     */
    private function goToViewAndCreateATeam(\AcceptanceTester $I)
    {
        $this->te_name = $this->faker->name;
        $I->amOnPage('/app/manage/team');
        $I->fillField('name', $this->te_name);
        $I->selectOption(['id' => 'tournament_id'], $this->to_name);
        $I->click('submit');
        $I->see("The team " . $this->te_name . " was added");
    }

    /**
     * @param \AcceptanceTester|\FunctionalTester $I
     */
    public function getAPlayerWithGaToTeAssociations(\AcceptanceTester $I){
        $this->createTeamTournamentGame($I);
        $this->goToViewAndCreateAPlayerWithGameAndTournamentAndTeamAssociations($I);
        return array(
            $this->pl_name,
            $this->pl_username,
            $this->pl_email,
            $this->pl_phone,
            $this->te_name,
            $this->to_name,
            $this->ga_name
            );
    }
    /**
     * @param \AcceptanceTester|\FunctionalTester $I
     */
    private function goToViewAndCreateAPlayerWithGameAndTournamentAndTeamAssociations(\AcceptanceTester $I)
    {
        $this->makePlayerFakeInfo();
        $I->amOnPage('/app/manage/player');
        $I->fillField('name', $this->pl_name);
        $I->fillField('username', $this->pl_username);
        $I->fillField('email', $this->pl_email);
        $I->fillField('phone', $this->pl_phone);
        $I->selectOption(['id' => 'team_id'], $this->te_name);
        $I->selectOption(['id' => 'tournament_id'], $this->to_name);
        $I->selectOption(['id' => 'game_id'], $this->ga_name);
        $I->click('submit');
    }
    /**
     * @param \AcceptanceTester|\FunctionalTester $I
     */
    public function getAPlayerWithGaToAssociations(\AcceptanceTester $I){
        $this->createTeamTournamentGame($I);
        $this->goToViewAndCreateAPlayerWithGameAndTournamentAssociations($I);
        return array(
            $this->pl_name,
            $this->pl_username,
            $this->pl_email,
            $this->pl_phone,
            $this->te_name,
            $this->to_name,
            $this->ga_name
        );
    }
    /**
     * @param \AcceptanceTester|\FunctionalTester $I
     */
    private function goToViewAndCreateAPlayerWithGameAndTournamentAssociations(\AcceptanceTester $I)
    {
        $this->makePlayerFakeInfo();
        $I->amOnPage('/app/manage/player');
        $I->fillField('name', $this->pl_name);
        $I->fillField('username', $this->pl_name);
        $I->fillField('email', $this->pl_email);
        $I->fillField('phone', $this->pl_phone);
        $I->selectOption(['id' => 'tournament_id'], $this->to_name);
        $I->selectOption(['id' => 'game_id'], $this->ga_name);
        $I->click('submit');
    }
    /**
     * @param \AcceptanceTester|\FunctionalTester $I
     */
    public function getAPlayerWithGaAssociations(\AcceptanceTester $I){
        $this->createTeamTournamentGame($I);
        $this->goToViewAndCreateAPlayerWithGameAssociations($I);
        return array(
            $this->pl_name,
            $this->pl_username,
            $this->pl_email,
            $this->pl_phone,
            $this->te_name,
            $this->to_name,
            $this->ga_name
        );
    }
    /**
     * @param \AcceptanceTester|\FunctionalTester $I
     */
    private function goToViewAndCreateAPlayerWithGameAssociations(\AcceptanceTester $I)
    {
        $this->makePlayerFakeInfo();
        $I->amOnPage('/app/manage/player');
        $I->fillField('name', $this->pl_name);
        $I->fillField('username', $this->pl_name);
        $I->fillField('email', $this->pl_email);
        $I->fillField('phone', $this->pl_phone);
        $I->selectOption(['id' => 'game_id'], $this->ga_name);
        $I->click('submit');
    }
    /**
     * @param \AcceptanceTester|\FunctionalTester $I
     */
    public function getAPlayerWithNoAssociations(\AcceptanceTester $I){
        $this->createTeamTournamentGame($I);
        $this->goToViewAndCreateAPlayerWithoutAssociations($I);
        return array(
            $this->pl_name,
            $this->pl_username,
            $this->pl_email,
            $this->pl_phone,
            $this->te_name,
            $this->to_name,
            $this->ga_name
        );
    }
    /**
     * @param \AcceptanceTester|\FunctionalTester $I
     */
    private function goToViewAndCreateAPlayerWithoutAssociations(\AcceptanceTester $I)
    {
        $this->makePlayerFakeInfo();
        $I->amOnPage('/app/manage/player');
        $I->fillField('name', $this->pl_name);
        $I->fillField('username', $this->pl_name);
        $I->fillField('email', $this->pl_email);
        $I->fillField('phone', $this->pl_phone);
        $I->click('submit');

    }

    /**
     * @param \AcceptanceTester|\FunctionalTester $I
     */
    private function createTeamTournamentGame(\AcceptanceTester $I)
    {
        $this->goToViewAndCreateAGame($I);
        $this->goToViewAndCreateATournament($I);
        $this->goToViewAndCreateATeam($I);
    }

    private function makePlayerFakeInfo()
    {
        $this->pl_name = $this->faker->name;
        $this->pl_username = $this->faker->userName;
        $this->pl_email = $this->faker->email;
        $this->pl_phone = $this->faker->phoneNumber;
    }
}
