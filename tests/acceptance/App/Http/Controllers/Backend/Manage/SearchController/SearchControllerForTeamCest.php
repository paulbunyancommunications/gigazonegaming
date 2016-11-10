<?php
namespace Tests\Acceptance\App\Http\Controllers\Backend\Manage;

use \AcceptanceTester;
use \BaseAcceptance;

/**
 * Class SearchControllerForTeamCest
 * @package Tests\Acceptance\App\Http\Controllers\Backend\Manage
 */
class SearchControllerForTeamCest extends BaseAcceptance
{

    protected $teamName = 'SearchControllerSeeder';
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
     * @param AcceptanceTester $I
     */
    protected function populateDB(AcceptanceTester $I)
    {
//        $I->runShellCommand('cd /var/www');
        exec('php artisan db:seed --class=DatabaseSeeder');
        exec('php artisan db:seed --class=SearchControllerSeeder');
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
     * Check that we can get a team from the search bar by name
     * @test
     * @group SearchController
     * @group SearchControllerForTeam
     * @param AcceptanceTester $I
     */
    public function testSearchControllerGetATeamByNameSearch(AcceptanceTester $I)
    {
        $I->wantTo('Check that we can get a team from the search bar by name');
        $I->amOnPage('/app/manage/team');

        /** Do by team name */
        $I->fillField(['id' => 'searchText'], $this->teamName);
        $I->click(['id' => 'doSearchBoxSubmit']);
        $I->waitForElementVisible(['id' => 'searchResults'], BaseAcceptance::TEXT_WAIT_TIMEOUT);
        $I->wait(1);
        $I->see($this->teamName, 'a[title="Team '. $this->teamName .'"]');
    }

    /**
     * Check that we can get a team from the search bar by name
     * @test
     * @group SearchController
     * @group SearchControllerForTeam
     * @param AcceptanceTester $I
     */
    public function testSearchControllerGetATeamByPartialNameSearch(AcceptanceTester $I)
    {
        $I->wantTo('Check that we can get a team from the search bar by partial name');
        $I->amOnPage('/app/manage/team');
        $name = substr($this->teamName, 0, floor(strlen($this->teamName) / 2));
        /** Do by team name */
        $I->fillField(['id' => 'searchText'], $name);
        $I->click(['id' => 'doSearchBoxSubmit']);
        $I->waitForElementVisible(['id' => 'searchResults'], BaseAcceptance::TEXT_WAIT_TIMEOUT);
        $I->wait(1);
        $I->see($this->teamName, 'a[title="Team '. $this->teamName .'"]');
    }

    /**
     * Check that we can get a team from the search bar by emblem
     * @test
     * @group SearchController
     * @group SearchControllerForTeam
     * @param AcceptanceTester $I
     */
    public function testSearchControllerGetATeamByEmblemSearch(AcceptanceTester $I)
    {
        $I->wantTo('Check that we can get a team from the search bar by emblem');
        $I->amOnPage('/app/manage/team');

        /** Do by team name */
        $I->fillField(['id' => 'searchText'], 'http://example.com/image.jpg');
        $I->click(['id' => 'doSearchBoxSubmit']);
        $I->waitForElementVisible(['id' => 'searchResults'], BaseAcceptance::TEXT_WAIT_TIMEOUT);
        $I->wait(1);
        $I->see($this->teamName, 'a[title="Team '. $this->teamName .'"]');
    }
    /**
     * Check that we can get a team from the search bar by partial emblem
     * @test
     * @group SearchController
     * @group SearchControllerForTeam
     * @param AcceptanceTester $I
     */
    public function testSearchControllerGetATeamByPartialEmblemSearch(AcceptanceTester $I)
    {
        $I->wantTo('Check that we can get a team from the search bar by partial emblem');
        $I->amOnPage('/app/manage/team');

        /** Do by team name */
        $I->fillField(['id' => 'searchText'], 'image.jpg');
        $I->click(['id' => 'doSearchBoxSubmit']);
        $I->waitForElementVisible(['id' => 'searchResults'], BaseAcceptance::TEXT_WAIT_TIMEOUT);
        $I->wait(1);
        $I->see($this->teamName, 'a[title="Team '. $this->teamName .'"]');
    }
}
