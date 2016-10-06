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
        $this->goToViewAndCreateAGame($I);
        return $this->ga_name;
    }
    /**
     * @param \AcceptanceTester|\FunctionalTester $I
     */
    private function goToViewAndCreateAGame(\AcceptanceTester $I)
    {
        $this->ga_name = $this->faker->name;
        $this->ga_title = $this->faker->name;
        $I->amOnPage('/app/manage/game');
        $I->fillField('name', $this->ga_name);
        $I->fillField('title', $this->ga_title);
        $I->fillField('uri', $this->faker->url);
        $I->fillField('description', $this->faker->words(5));
        $I->click('submit');
        $I->see("The game " . $this->ga_title . " was added!");

    }

    /**
     * @param \AcceptanceTester|\FunctionalTester $I
     */
    public function getATournament(\AcceptanceTester $I){
        $this->goToViewAndCreateATournament($I);
        return $this->to_name;
    }
    /**
     * @param \AcceptanceTester|\FunctionalTester $I
     */
    private function goToViewAndCreateATournament(\AcceptanceTester $I)
    {
        $this->to_name = $this->faker->name;
        $I->amOnPage('/app/manage/tournament');
        $I->fillField('name', $this->to_name);
        $I->fillField('max_players', $this->faker->numberBetween(1, 10));
        $I->selectOption(['id' => 'game_id'], $this->ga_name);
        $I->click('submit');
        $I->see("The tournament " . $this->to_name . " was added");
    }

    /**
     * @param \AcceptanceTester|\FunctionalTester $I
     */
    public function getATeam(\AcceptanceTester $I){
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
