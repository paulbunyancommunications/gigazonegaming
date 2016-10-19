<?php
namespace Tests\Acceptance\App\Http\Controllers\Backend\Manage;

use \AcceptanceTester;
use \BaseAcceptance;

/**
 * Class GamesControllerCest
 * @package Tests\Acceptance\App\Http\Controllers\Backend\Manage
 */
class EmailFilterCest extends BaseAcceptance
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
        $I->amOnPage('/app/manage/email');
        $I->see('Email Filter');
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
     * Get to the game management page and check for the titles
     */
    public function tryToCheckFilterForGameWorks(AcceptanceTester $I)
    {
        $I->wantTo('click on a game and see all the email addresses attached');
        $I->seeOptionIsSelected('#separator', ',');
        $I->seeOptionIsSelected('#space', 'Yes');
        $I->selectOption(['id' => 'game_sort'], 'tester-game');
        $I->executeJS('$("#get_game").click()');
        $I->wait(3);
        $I->see('player000@test.com, player001@test.com, player002@test.com, player003@test.com, player004@test.com, player005@test.com, player006@test.com, player007@test.com, player008@test.com, player009@test.com, player010@test.com, player011@test.com, player012@test.com, player013@test.com, player014@test.com, player015@test.com, player016@test.com, player017@test.com, player018@test.com, player019@test.com, player020@test.com, player021@test.com, player022@test.com, player023@test.com, player024@test.com, player025@test.com, player026@test.com, player027@test.com, player028@test.com, player029@test.com, player030@test.com, player031@test.com, player032@test.com, player033@test.com, player034@test.com');
//
        $I->selectOption(['id' => 'separator'], ';');
        $I->selectOption(['id' => 'tournament_sort'], 'Tester Tournament');
        $I->executeJS('$("#get_tournament").click()');
        $I->wait(3);
        $I->see('player000@test.com; player001@test.com; player002@test.com; player003@test.com; player004@test.com; player005@test.com; player006@test.com; player007@test.com; player008@test.com; player009@test.com; player010@test.com; player011@test.com; player012@test.com; player013@test.com; player014@test.com; player015@test.com; player016@test.com; player017@test.com; player018@test.com; player019@test.com; player020@test.com; player021@test.com; player022@test.com; player023@test.com; player024@test.com; player025@test.com; player026@test.com; player027@test.com; player028@test.com; player029@test.com; player030@test.com; player031@test.com; player032@test.com; player033@test.com; player034@test.com');
//
        $I->selectOption(['id' => 'space'], 'No');
        $I->selectOption(['id' => 'separator'], ',');
        $I->selectOption(['id' => 'team_sort'], 'Tester Team');
        $I->executeJS('$("#get_team").click()');
        $I->wait(3);
        $I->see('player000@test.com,player001@test.com,player002@test.com,player003@test.com,player004@test.com');
//
        $I->selectOption(['id' => 'player_sort'], 'Tester Player000');
        $I->executeJS('$("#get_player").click()');
        $I->wait(3);
        $I->see('player000@test.com');

    }

}
