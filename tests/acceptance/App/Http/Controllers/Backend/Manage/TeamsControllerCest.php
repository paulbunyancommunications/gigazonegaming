<?php
namespace Tests\Acceptance\App\Http\Controllers\Backend\Manage;

use \AcceptanceTester;
use App\Models\Championship\Player;
use App\Models\Championship\Relation\PlayerRelation;
use App\Models\Championship\Team;
use \BaseAcceptance;

/**
 * Class TeamsControllerCest
 * @package Tests\Acceptance\App\Http\Controllers\Backend\Manage
 */
class TeamsControllerCest extends BaseAcceptance
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
        $I->amOnPage('/app/manage/team');
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
    public function tryToGetToTheTeamApp(AcceptanceTester $I)
    {
        $I->wantTo('get to the team management page');
        $I->amOnPage('/app/manage/team');
        $I->see('Create a new Team');
        $I->see('Team List');
    }

    /**
     * @param AcceptanceTester $I
     */
    public function tryToCreateATeam(AcceptanceTester $I)
    {
        $I->wantTo('create a team on the management page');
        list($name, $emblem, $tournament_id) = $this->createATeam($I);
        // check return, we should have a message and all the fields filled
        $I->waitForText('The team ' . $name . ' was added', $this::TEXT_WAIT_TIMEOUT);
        $I->see('The team ' . $name . ' was added');

    }

    /**
     * @param AcceptanceTester $I
     */
    public function tryToUpdateATeam(AcceptanceTester $I)
    {
        $I->wantTo('update a team on the management page');
        //create a team
        list($name, $emblem, $tournament_id) = $this->createATeam($I);
        //wait to see that it is there
        $I->waitForText('The team ' . $name . ' was added', $this::TEXT_WAIT_TIMEOUT);
        $I->see('The team ' . $name . ' was added');
        $I->click(['id' => 'edit-' . $name]);
        $I->waitForText("Update Team", $this::TEXT_WAIT_TIMEOUT);
        $I->see("Update Team");
        $I->fillField(['id' => 'name'], $name . "-edited");
        $I->click(['id' => 'submit']);
        $I->waitForText("The team " . $name . "-edited was updated", $this::TEXT_WAIT_TIMEOUT);
        $I->see("The team " . $name . "-edited was updated");
    }

    /**
     * @param AcceptanceTester $I
     */
    public function tryAndUpdateATeamKeepingEverythingTheSame(AcceptanceTester $I)
    {
        $I->wantTo('update a team, keeping the everything the same.');
        // make a team, then update
        list($name, $emblem, $tournament_id) = $this->createATeam($I);
        $I->waitForText('The team ' . $name . ' was added', $this::TEXT_WAIT_TIMEOUT);
        $I->see('The team ' . $name . ' was added');
        $I->click(['id' => 'edit-' . $name]);
        $I->waitForText("Update Team:", $this::TEXT_WAIT_TIMEOUT);
        $I->see("Update Team:");
        $I->seeInField(['id' => 'name'], $name);
        $I->seeInField(['id' => 'emblem'], $emblem);
        $I->seeOptionIsSelected(['id' => 'tournament_id'], $tournament_id);
        // fill fields with new values
        $name .= "-edited";
        $I->fillField(['id' => 'name'], $name);
        $I->fillField(['id' => 'emblem'], $emblem);
        $I->selectOption(['id' => 'tournament_id'], $tournament_id);
        $I->click(['id' => 'submit']);
        // check that the fields are now all updated
        $I->waitForText('The team ' . $name . ' was updated', $this::TEXT_WAIT_TIMEOUT);
        $I->see('The team ' . $name . ' was updated');
        $I->seeInField(['id' => 'name'], $name);
        $I->seeInField(['id' => 'emblem'], $emblem);
        $I->seeOptionIsSelected(['id' => 'tournament_id'], $tournament_id);
    }

    /**
     * Ensure when creating a team that the name is present
     *
     * @param AcceptanceTester $I
     */
    public function seeErrorWhenNameIsMissing(AcceptanceTester $I)
    {
        $I->click(['id' => 'submit']);
        $I->waitForText('The Team Name Field is required.', $this::TEXT_WAIT_TIMEOUT);
        $I->see('The Team Name Field is required.');
    }

    /**
     * @param AcceptanceTester $I
     */
    public function seeErrorWhenNameIsAlreadyUsed(AcceptanceTester $I)
    {
        $I->wantTo('update a team, keeping the everything the same.');
        // make a team, then update
        list($name, $emblem, $tournament_id) = $this->createATeam($I);
        $I->waitForText('The team ' . $name . ' was added', $this::TEXT_WAIT_TIMEOUT);
        $I->see('The team ' . $name . ' was added');
        $I->amOnPage('/app/manage/team');
        $I->fillField(['id' => 'name'], $name);
        $I->fillField(['id' => 'emblem'], $emblem);
        $I->selectOption(['id' => 'tournament_id'], $tournament_id);
        $I->click(['id' => 'submit']);
        // check that the fields are now all updated
        $I->waitForText('The Team Name is in use, pick a new one.', $this::TEXT_WAIT_TIMEOUT);
        $I->see('The Team Name is in use, pick a new one.');
    }

    /**
     * Ensure when creating a team that the name is present
     *
     * @param AcceptanceTester $I
     */
    public function seeErrorWhenTheTournamentIsntSelected(AcceptanceTester $I)
    {
        $I->executeJS('$("#tournament_id").remove();');
        $I->click(['id' => 'submit']);
        $I->waitForText('The Tournament field can not be empty.', $this::TEXT_WAIT_TIMEOUT);
        $I->see('The Tournament field can not be empty.');
    }

    /**
     * Ensure when creating a team that the name is present
     *
     * @param AcceptanceTester $I
     */
    public function seeErrorWhenTheTournamentIsntAnInteger(AcceptanceTester $I)
    {
        $I->executeJS("
            select = document.getElementById('tournament_id');
            var option = document.createElement('option');
            option.text = 'myFakeHackyOption';
            option.value = 'myHackyValue';
            select.appendChild(option);
                ");
        $I->selectOption(['id' => 'tournament_id'], "myFakeHackyOption");
        $I->click(['id' => 'submit']);
        $I->waitForText('The Tournament field must be an tournament ID.', $this::TEXT_WAIT_TIMEOUT);
        $I->see('The Tournament field must be an tournament ID.');
    }

    /**
     * @param AcceptanceTester $I
     * @return array
     */
    private function createATeam(AcceptanceTester $I, $attributes = [])
    {
        $name = array_key_exists('name', $attributes) ? $attributes['name'] : implode('-', $this->faker->words(3));
        $emblem = array_key_exists('emblem', $attributes) ? $attributes['emblem'] : $this->faker->url;
        $tournament = "Tester Tournament";
        //created a team
        $I->fillField(['id' => 'name'], $name);
        $I->fillField(['id' => 'emblem'], $emblem);
        $I->selectOption(['id' => 'tournament_id'], $tournament);
        $I->click(['id' => 'submit']);
        return array($name, $emblem, $tournament);
    }
}
