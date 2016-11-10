<?php
namespace Tests\Acceptance\App\Http\Controllers\Backend\Manage;

use \AcceptanceTester;
use \BaseAcceptance;

/**
 * Class SearchControllerForGameCest
 * @package Tests\Acceptance\App\Http\Controllers\Backend\Manage
 */
class SearchControllerForGameCest extends BaseAcceptance
{

    protected $gameName = 'SearchControllerSeeder';
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
     * Check that we can get a game from the search bar by name
     * @test
     * @group SearchController
     * @group SearchControllerForGame
     */
    public function testSearchControllerGetAGameByNameSearch(AcceptanceTester $I)
    {
        $I->wantTo('Check that we can get a game from the search bar by name');
        $I->amOnPage('/app/manage/player');

        /** DO by full first name and last name */
        $I->fillField(['id' => 'searchText'], strtolower($this->gameName));
        $I->click(['id' => 'doSearchBoxSubmit']);
        $I->waitForElementVisible(['id' => 'searchResults'], BaseAcceptance::TEXT_WAIT_TIMEOUT);
        $I->wait(1);
        $I->see($this->gameName.' Game', 'a[title="Game '. strtolower($this->gameName) . '-game"]');
    }

    /**
     * Check that we can get a game from the search bar by partial name
     * @test
     * @group SearchController
     * @group SearchControllerForGame
     */
    public function testSearchControllerGetAGameByPartialNameSearch(AcceptanceTester $I)
    {
        $I->wantTo('Check that we can get a game from the search bar by  partial name');
        $I->amOnPage('/app/manage/player');
        $name = substr($this->gameName, 0, floor(strlen($this->gameName) / 2));
        /** DO by full first name and last name */
        $I->fillField(['id' => 'searchText'], $name);
        $I->click(['id' => 'doSearchBoxSubmit']);
        $I->waitForElementVisible(['id' => 'searchResults'], BaseAcceptance::TEXT_WAIT_TIMEOUT);
        $I->wait(1);
        $I->see($this->gameName.' Game', 'a[title="Game '. strtolower($this->gameName) . '-game"]');
    }

    /**
     * Check that we can get a game from the search bar by title
     * @test
     * @group SearchController
     * @group SearchControllerForGame
     */
    public function testSearchControllerGetAGameByTitleSearch(AcceptanceTester $I)
    {
        $I->wantTo('Check that we can get a game from the search bar by title');
        $I->amOnPage('/app/manage/player');
        /** Do search by email */
        $I->fillField(['id' => 'searchText'], $this->gameName);
        $I->click(['id' => 'doSearchBoxSubmit']);
        $I->waitForElementVisible(['id' => 'searchResults'], BaseAcceptance::TEXT_WAIT_TIMEOUT);
        $I->wait(1);
        $I->see($this->gameName.' Game', 'a[title="Game '. strtolower($this->gameName) . '-game"]');
    }
}
