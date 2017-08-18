<?php
namespace Tests\Acceptance\App\Http\Controllers\Backend\Manage\SearchController;

use \AcceptanceTester;
use \BaseAcceptance;

/**
 * Class SearchControllerForTournamentCest
 * @package Tests\Acceptance\App\Http\Controllers\Backend\Manage
 */
class SearchControllerForTournamentCest extends BaseAcceptance
{

    protected $tournamentName = 'SearchControllerSeeder';
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
     * Check that we can get a tournament from the search bar by name
     * @test
     * @group SearchController
     * @group SearchControllerForTournament
     */
    public function testSearchControllerGetATournamentByNameSearch(AcceptanceTester $I)
    {
        $I->wantTo('Check that we can get a tournament from the search bar by name');
        $I->amOnPage('/app/manage/player');

        /** DO by full first name and last name */
        $I->fillField(['id' => 'searchText'], $this->tournamentName);
        $I->click(['id' => 'doSearchBoxSubmit']);
        $I->waitForElementVisible(['id' => 'searchResults'], BaseAcceptance::TEXT_WAIT_TIMEOUT);
        $I->wait(1);
        $I->see(strtolower($this->tournamentName).'-tournament', 'a[title="Tournament '. strtolower($this->tournamentName) . '-tournament"]');

    }

    /**
     * Check that we can get a tournament from the search bar by partial name
     * @test
     * @group SearchController
     * @group SearchControllerForTournament
     */
    public function testSearchControllerGetAGameByPartialNameSearch(AcceptanceTester $I)
    {
        $I->wantTo('Check that we can get a tournament from the search bar by partial name');
        $I->amOnPage('/app/manage/player');
        $name = substr($this->tournamentName, 0, floor(strlen($this->tournamentName) / 2));
        /** DO by full first name and last name */
        $I->fillField(['id' => 'searchText'], $name);
        $I->click(['id' => 'doSearchBoxSubmit']);
        $I->waitForElementVisible(['id' => 'searchResults'], BaseAcceptance::TEXT_WAIT_TIMEOUT);
        $I->wait(1);
        $I->see(strtolower($this->tournamentName).'-tournament', 'a[title="Tournament '. strtolower($this->tournamentName) . '-tournament"]');

    }
}
