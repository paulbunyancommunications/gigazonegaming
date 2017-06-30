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
    public $faker;

    /**
     * @param AcceptanceTester $I
     */
    public function _before(AcceptanceTester $I)
    {
        parent::_before($I);
        $this->populateDB($I);
        $this->loginWithAdminUser($I);
        $I->amOnPage('/app/manage/player');

    }

    /**
     * Create the test admin user
     */
    protected function populateDB(AcceptanceTester $I)
    {
//        $I->runShellCommand('cd /var/www');
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
     * Get to the player management page and check for the max_playerss
     */
    public function tryToGetToThePlayerApp(AcceptanceTester $I)
    {
        $I->wantTo('get to the player management page');
        $I->amOnPage('/app/manage/player');
        $I->see('Create a new Player');
    }

    /**
     * @param AcceptanceTester $I
     */
    public function tryToCreateAPlayer(AcceptanceTester $I)
    {
        $I->wantTo('create a player with no team, no tournament and no game');
        $name = "Tester PlayerX";
        $username = "DummyUserA-Tester";
        $email = "DummyUserA@Tester.com";
        $phone = "(218) - 444 - 4444";
        $I->wantTo('create a player on the management page');
        $I->amOnPage('/app/manage/player');
        $I->fillField(['id' => 'name'], $name);
        $I->fillField(['id' => 'username'], $username);
        $I->fillField(['id' => 'email'], $email);
        $I->fillField(['id' => 'phone'], $phone);
        $I->click("Save");

        $I->waitForText('Update Player: “' . $name . '”');
        $I->dontSeeOptionIsSelected('select#game_id', 'Tester Game');
        $I->dontSeeOptionIsSelected('select#tournament_id', 'Tester Tournament');
        $I->dontSeeOptionIsSelected('select#team_id', 'Tester Team');

    }
    /**
     * @param AcceptanceTester $I
     */
    public function tryToCreateAndEditAPlayerByAddingAGame(AcceptanceTester $I)
    {
        $I->wantTo('create a player with no team, no tournament but a game attached');

        $name = "Tester PlayerX";
        $I->click(".edit-form-TheTesterPlayerX");
        $I->waitForText($name, $this::TEXT_WAIT_TIMEOUT, "#name");
        $I->selectOption(array("id" => "game_id"), 'tester-game');
        $I->executeJS("$('#game_id').select2({
                allowClear: true
            });");
        $I->click("Update");
        $I->waitForText('Update Player: “' . $name . '”', $this::TEXT_WAIT_TIMEOUT);
        $I->see('Update Player: “' . $name . '”');
        $I->seeOptionIsSelected('select#game_id', 'tester-game');
        $I->dontSeeOptionIsSelected('select#tournament_id', 'Tester Tournament');
        $I->dontSeeOptionIsSelected('select#team_id', 'Tester Team');

    }
    /**
     * @param AcceptanceTester $I
     */
    public function tryToCreateAndEditAPlayerByAddingATournament(AcceptanceTester $I)
    {
        $I->wantTo('create a player with no team but a tournament attached which will automatically attach a game');
        $I->click(".edit-form-TheTesterPlayerX");
        $name = "Tester PlayerX";
        $I->waitForText($name, $this::TEXT_WAIT_TIMEOUT, "#name");
        $I->selectOption(["id" => "tournament_id"], 'Tester Tournament');
        $I->executeJS("$('#tournament_id').select2({
                allowClear: true
            });");
        $I->click("Update");
        $I->waitForText('Update Player: “' . $name . '”', $this::TEXT_WAIT_TIMEOUT);
        $I->see('Update Player: “' . $name . '”');
        $I->seeOptionIsSelected(['id' => 'game_id'], 'tester-game');
        $I->seeOptionIsSelected(['id' => 'tournament_id'], 'Tester Tournament');
        $I->dontSeeOptionIsSelected(['id' => 'team_id'], 'Tester Team');

    }
    /**
     * @param AcceptanceTester $I
     */
    public function tryToCreateAndEditAPlayerByAddingATeam(AcceptanceTester $I)
    {
        $I->wantTo('create a player with a team attached which will automatically attach a tournament and a game');
        $I->click(".edit-form-TheTesterPlayerX");
        $name = "Tester PlayerX";
        $I->waitForText($name, $this::TEXT_WAIT_TIMEOUT, "#name");
        $I->selectOption(array("id" => "team_id"), 'Tester Team');
        $I->executeJS("$('#team_id').select2({
                allowClear: true
            });");
        $I->click("Update");
        $I->waitForText('Update Player: “' . $name . '”', $this::TEXT_WAIT_TIMEOUT);
        $I->see('Update Player: “' . $name . '”');
        $I->seeOptionIsSelected('select#game_id', 'tester-game');
        $I->seeOptionIsSelected('select#tournament_id', 'Tester Tournament');
        $I->seeOptionIsSelected('select#team_id', 'Tester Team');

    }

    /**
     * @param AcceptanceTester $I
     */
    public function tryToCreateAPlayerWithAnEmailMissing(AcceptanceTester $I)
    {
        $faker = \Faker\Factory::create();
        $this->playerCreation($I);
        $name = "Tester PlayerX";
        $username = "The Tester PlayerX";
        $username2 = "The Tester PlayerX2"; //username should be unique
        $email = "playerx@test.com";
        $email2 = "DummyUserA@bpaosd.com"; //email should be unique
        $phone = "(218) - 444 - 4444";
        $I->wantTo('create a player on the management page without an email');
        $I->amOnPage('/app/manage/player');
        $I->fillField(['id' => 'name'], $name);
        $I->fillField(['id' => 'username'], $username);
        $I->fillField(['id' => 'phone'], $phone);
        $I->click(['id' => 'submit']);
        $I->waitForText("A email address is required", $this::TEXT_WAIT_TIMEOUT);
        $I->see("A email address is required");
    }

    /**
     * @param AcceptanceTester $I
     */
    public function tryToCreateAPlayerWithAnEmailBadEmail(AcceptanceTester $I)
    {
        $faker = \Faker\Factory::create();
        $this->playerCreation($I);
        $name = "Tester PlayerX";
        $username = "The Tester PlayerX";
        $username2 = "The Tester PlayerX2"; //username should be unique
        $email = "playerx@test.com";
        $email2 = "DummyUserA@bpaosd.com"; //email should be unique
        $phone = "(218) - 444 - 4444";

        $I->wantTo('create a player on the management page with an email that is not valid');
        $I->fillField(['id' => 'email'], implode(' ', $faker->words(3)));
        $I->click(['id' => 'submit']);
        $I->waitForText("That doesn't look like an email, try again", $this::TEXT_WAIT_TIMEOUT);
        $I->see("That doesn't look like an email, try again");
    }

    /**
     * @param AcceptanceTester $I
     */
    public function tryToCreateAPlayerWithAnEmailDuplicateEmail(AcceptanceTester $I)
    {
        $faker = \Faker\Factory::create();
        $name = "Tester PlayerX";
        $username = "The Tester PlayerX";
        $username2 = "The Tester PlayerX2"; //username should be unique
        $email = "playerx@test.com";
        $email2 = "DummyUserA@bpaosd.com"; //email should be unique
        $phone = "(218) - 444 - 4444";

        $I->wantTo('create a player on the management page with an email that is already in the db');
        $I->fillField(['id' => 'email'], $email);
        $I->click(['id' => 'submit']);
        $I->waitForText("A email address is already been used, use your previously created account or create a new one", $this::TEXT_WAIT_TIMEOUT);
        $I->see("A email address is already been used, use your previously created account or create a new one");
    }

    /**
     * @param AcceptanceTester $I
     */
    public function tryToCreateAPlayerWithAnEmail(AcceptanceTester $I)
    {
        $faker = \Faker\Factory::create();
        $this->playerCreation($I);
        $name = "Tester PlayerX";
        $username = "The Tester PlayerX";
        $username2 = $faker->userName; //username should be unique
        $email = "playerx@test.com";
        $email2 = "DummyUserA@bpaosd.com"; //email should be unique
        $phone = "(218) - 444 - 4444";

        $I->wantTo('create a player on the management page with an email that is not in the db (correct)');
        $I->fillField(['id' => 'name'], $name);
        $I->fillField(['id' => 'username'], $username2);
        $I->fillField(['id' => 'email'], $email2);
        $I->click(['id' => 'submit']);
        $I->waitForText('Update Player: “' . $name . '”');
        $I->see('Update Player: “' . $name . '”');
        $I->dontSeeOptionIsSelected('select#game_id', 'Tester Game');
        $I->dontSeeOptionIsSelected('select#tournament_id', 'Tester Tournament');
        $I->dontSeeOptionIsSelected('select#team_id', 'Tester Team');
    }
    /**
     * @param AcceptanceTester $I
     */
    public function tryToCreateAPlayerWithAnUsernameProblemMissingUserName(AcceptanceTester $I)
    {
        $name = "Tester PlayerX";
        $username = "The Tester PlayerX";
        $username2 = "DummyUserA-Tester2"; //username should be unique
        $email = "playerx@test.com";
        $email2 = "DummyUserA@bpaosd.com"; //email should be unique
        $phone = "(218) - 444 - 4444";
        $I->wantTo('create a player on the management page without a username');
        $I->amOnPage('/app/manage/player');
        $I->fillField(['id' => 'name'], $name);
        $I->fillField(['id' => 'email'], $email2);
        $I->fillField(['id' => 'phone'], $phone);
        $I->click("Save");
        $I->waitForText("A Username is required",$this::TEXT_WAIT_TIMEOUT);
        $I->see("A Username is required");

    }

    /**
     * @param AcceptanceTester $I
     */
    public function tryToCreateAPlayerWithAnUsernameProblemAlreadyExists(AcceptanceTester $I)
    {
        $name = "Tester PlayerX";
        $username = "The Tester PlayerX";
        $username2 = "DummyUserA-Tester2"; //username should be unique
        $email = "playerx@test.com";
        $email2 = "DummyUserA@bpaosd.com"; //email should be unique
        $phone = "(218) - 444 - 4444";


        $I->wantTo('create a player on the management page with a username that is already in the db');
        $I->fillField(['id' => 'username'], $username);
        $I->click("Save");
        $I->waitForText("The Username is already in use, please select a new one",$this::TEXT_WAIT_TIMEOUT);
        $I->see("The Username is already in use, please select a new one");

    }

    /**
     * @param AcceptanceTester $I
     */
    public function tryToCreateAPlayerWithAnUsername(AcceptanceTester $I)
    {
        $faker = \Faker\Factory::create();
        $name = $faker->name;
        $username = "The Tester PlayerX";
        $username2 = "DummyUserA-Tester2"; //username should be unique
        $email = "playerx@test.com";
        $email2 = "DummyUserA@bpaosd.com"; //email should be unique
        $phone = "(218) - 444 - 4444";

        $I->wantTo('create a player on the management page with a username that is not in the db (correct)');
        $I->fillField(['id' => 'username'], $username2);
        $I->fillField(['id' => 'email'], $email2);
        $I->fillField(['id' => 'name'], $name);
        $I->click("Save");
        $I->waitForText('Update Player: “' . $name . '”', $this::TEXT_WAIT_TIMEOUT);
        $I->see('Update Player: “' . $name . '”');
        $I->dontSeeOptionIsSelected('select#game_id', 'Tester Game');
        $I->dontSeeOptionIsSelected('select#tournament_id', 'Tester Tournament');
        $I->dontSeeOptionIsSelected('select#team_id', 'Tester Team');
    }
    /**
     * @param AcceptanceTester $I
     */
    public function tryToCreateAPlayerWithManyPhoneNumberVariations(AcceptanceTester $I)
    {
        $I->click(".edit-form-TheTesterPlayerX");
        $name = "Tester PlayerX";
        $phone1 = "(218) - 444 - 4654";
        $phone2 = "+1 - (218) - 444 - 4784";
        $phone3 = "+1 (218) - 444 - 4094";
        $phone4 = "+1 218 - 444 - 9044";
        $phone5 = "+1218 444 4678";
        $phone6 = "+12184444254";
        $phone7 = "(218)-444-4441";
        $phone8 = "218-444-4241";
        $phone9 = "2184144241";
        $phone10 = "+12184144249";
        $phone11 = "4144249"; // failing number
        $I->wantTo('create a player on the management page with a valid number1');
        $I->fillField(['id' => 'phone'], $phone1);
        $I->click("Update");
        $I->waitForText('Update Player: “' . $name . '”', $this::TEXT_WAIT_TIMEOUT);
        $I->see('Update Player: “' . $name . '”');
        $I->click(".close");
        $I->wantTo('create a player on the management page with a valid number2');
        $I->fillField(['id' => 'phone'], $phone2);
        $I->click("Update");
        $I->waitForText('Update Player: “' . $name . '”', $this::TEXT_WAIT_TIMEOUT);
        $I->see('Update Player: “' . $name . '”');
        $I->click(".close");
        $I->wantTo('create a player on the management page with a valid number3');
        $I->fillField(['id' => 'phone'], $phone3);
        $I->click("Update");
        $I->waitForText('Update Player: “' . $name . '”', $this::TEXT_WAIT_TIMEOUT);
        $I->see('Update Player: “' . $name . '”');
        $I->click(".close");
        $I->wantTo('create a player on the management page with a valid number4');
        $I->fillField(['id' => 'phone'], $phone4);
        $I->click("Update");
        $I->waitForText('Update Player: “' . $name . '”', $this::TEXT_WAIT_TIMEOUT);
        $I->see('Update Player: “' . $name . '”');
        $I->click(".close");
        $I->wantTo('create a player on the management page with a valid number5');
        $I->fillField(['id' => 'phone'], $phone5);
        $I->click("Update");
        $I->waitForText('Update Player: “' . $name . '”', $this::TEXT_WAIT_TIMEOUT);
        $I->see('Update Player: “' . $name . '”');
        $I->click(".close");
        $I->wantTo('create a player on the management page with a valid number6');
        $I->fillField(['id' => 'phone'], $phone6);
        $I->click("Update");
        $I->waitForText('Update Player: “' . $name . '”', $this::TEXT_WAIT_TIMEOUT);
        $I->see('Update Player: “' . $name . '”');
        $I->click(".close");
        $I->wantTo('create a player on the management page with a valid number7');
        $I->fillField(['id' => 'phone'], $phone7);
        $I->click("Update");
        $I->waitForText('Update Player: “' . $name . '”', $this::TEXT_WAIT_TIMEOUT);
        $I->see('Update Player: “' . $name . '”');
        $I->click(".close");
        $I->wantTo('create a player on the management page with a valid number8');
        $I->fillField(['id' => 'phone'], $phone8);
        $I->click("Update");
        $I->waitForText('Update Player: “' . $name . '”', $this::TEXT_WAIT_TIMEOUT);
        $I->see('Update Player: “' . $name . '”');
        $I->click(".close");
        $I->wantTo('create a player on the management page with a valid number9');
        $I->fillField(['id' => 'phone'], $phone9);
        $I->click("Update");
        $I->waitForText('Update Player: “' . $name . '”', $this::TEXT_WAIT_TIMEOUT);
        $I->see('Update Player: “' . $name . '”');
        $I->click(".close");
        $I->wantTo('create a player on the management page with a valid number10');
        $I->fillField(['id' => 'phone'], $phone10);
        $I->click("Update");
        $I->waitForText('Update Player: “' . $name . '”', $this::TEXT_WAIT_TIMEOUT);
        $I->see('Update Player: “' . $name . '”');
        $I->click(".close");
        $I->wantTo('create a player on the management page with a valid number11');
        $I->fillField(['id' => 'phone'], $phone11);
        $I->click("Update");
        $I->see("The phone number isn't a valid one, or you forgot the area code");
        $I->dontSeeOptionIsSelected('select#game_id', 'Tester Game');
        $I->dontSeeOptionIsSelected('select#tournament_id', 'Tester Tournament');
        $I->dontSeeOptionIsSelected('select#team_id', 'Tester Team');
    }
    /**
     * @param AcceptanceTester $I
     * @return string
     */
    private function playerCreation(AcceptanceTester $I)
    {
        $I->click(".edit-form-TheTesterPlayerX");
    }
}
