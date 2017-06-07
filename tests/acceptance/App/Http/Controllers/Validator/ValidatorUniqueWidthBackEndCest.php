<?php
namespace Tests\Acceptance\App\Http\Controllers\Validator;
use \AcceptanceTester;
use \BaseAcceptance;
/**
 * Class ValidatorUniqueWidthBackEndCest
 * @package Tests\Acceptance
 */
class ValidatorUniqueWidthBackEndCest extends BaseAcceptance
{
    /**
     *
     */
    const DEFAULT_WAIT = 15;
    const TEAM_A_NAME = "sdgkldR34 4fjglkjahL KDNFLKDFJG3245ERGRPIOS2JGLNSD23LKVNS KDJ0FN SK234D3JGNDFDFSFD23SD";
    const TEAM_B_NAME = "FLKFJG245ERGRPIOkldR34 KDJ0NFLKD 4glkahL KD345NS2JGLD23LKVNS FJ3245ERPIO D3JGNFFSFD2SD";

    public $faker;

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
    public function _before(AcceptanceTester $I)
    {
        parent::_before($I);
        $this->populateDB($I);
        $this->loginWithAdminUser($I);
        $I->am("admin");
        $I->wantTo("A Admin User and I will create a team");
        $I->amOnPage('/app/manage/team');
        $I->fillField("#name", $this::TEAM_A_NAME);
        $I->selectOption("#tournament_id", "1"); //gz2016
        $I->click(['id'=>"submit"]);
    }

    /**
     * @param AcceptanceTester $I
     */
    public function _after(AcceptanceTester $I)
    {
        parent::_after($I);
        $this->logoutOfWp($I);
    }

    /**
     * Test the form with the participation flag
     * @param AcceptanceTester $I
     */
    public function tryToCheckIfAfterCreatingATeamICanAssignItTheSameName(AcceptanceTester $I)
    {

    }
}
