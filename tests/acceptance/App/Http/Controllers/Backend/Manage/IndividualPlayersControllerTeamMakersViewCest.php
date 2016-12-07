<?php
namespace Tests\Acceptance\App\Http\Controllers\Backend\Manage;

use App\Providers\ChampionshipGameComposerProvider;
use Illuminate\Support\Facades\DB;
use \AcceptanceTester;
use App\Models\Championship\Game;
use App\Models\Championship\Player;
use App\Models\Championship\Relation\PlayerRelation;
use App\Models\Championship\Team;
use App\Models\Championship\Tournament;
use \BaseAcceptance;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Mockery;


class IndividualPlayersControllerTeamMakersViewCest extends BaseAcceptance
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
    public function tryToGetToTheTeamMakerApp(AcceptanceTester $I)
    {
        $I->wantTo('get to the individual player management page');
        $I->amOnPage('/app/manage/teamMaker');
        $I->see('Team Filler/Maker');
    }
    /**
     * Ensure before trying to attach an individual player to a team that the selectors are disabled but enabled after selecting parent selector
     *
     * @param AcceptanceTester $I
     */
    public function seeIfThereArePlayersShowingAfterSelectingATeam(AcceptanceTester $I)
    {
        $I->wantTo('check after selecting a team that there are players');
        $I->amOnPage('/app/manage/teamMaker');
        $I->see('Team Filler/Maker');

        $I->click("Create Team for Selected Tournament");
        $I->dontSee("Add These Players To A New Team");

        $I->click("Fill selected Team");
        $I->dontSee("Add These Players To The Selected Team");

        $I->selectOption(['id' => 'game_sort'], "tester-game");
        $I->selectOption(['id' => 'tournament_sort'], "Tester Tournament");
        $I->click("Create Team for Selected Tournament");
        $I->waitForElementVisible(['id' => 'submit_create_team'], 60);
        $I->click(['id' => 'submit_create_team']);
        $I->see("The Players had being added to the new team");

        $I->selectOption(['id' => 'game_sort'], "tester-game");
        $I->selectOption(['id' => 'tournament_sort'], "Tester Tournament");
        $I->selectOption(['id' => 'team_sort'], "Tester Team");
        $I->click("Fill selected Team");
        $I->waitForElementVisible(['id' => 'submit_fill_team'], 60);
        $I->click(['id' => 'submit_fill_team']);
        $I->see("The Players had being added to the team Tester Team");

    }

}
