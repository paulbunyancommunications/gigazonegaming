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
    public $faker;
    const DEFAULT_WAIT = 30;
    const TEAM_A_NAME = "Tester Team Unique Width A";//same as in the SignUpUniqueWithValidatorTesterSeeder
    const TEAM_B_NAME = "Tester Team Unique Width B";//same as in the SignUpUniqueWithValidatorTesterSeeder
    const TOURNAMENT_A_NAME = "Tester Tournament Unique Width A";//same as in the SignUpUniqueWithValidatorTesterSeeder
    const TOURNAMENT_B_NAME = "Tester Tournament Unique Width B";//same as in the SignUpUniqueWithValidatorTesterSeeder

    /**
     * Create the test admin user
     */
    protected function populateDB(AcceptanceTester $I)
    {
//        $I->runShellCommand('cd /var/www');
        exec('php artisan db:seed --class=SignUpUniqueWithValidatorTesterSeeder');
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
        $I->amOnPage('/app/manage/team');
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
    public function tryToCheckIfAfterCreatingATeamIfICanAssignAnotherTeamTheSameNameButHavingADifferentTournament(AcceptanceTester $I)
    {
        $I->am("admin");
        $I->wantTo("create a team");
        $I->fillField("#name", $this::TEAM_A_NAME);
        $I->selectOption("#tournament_id", array('text' => str_replace(' ', '_', $this::TOURNAMENT_B_NAME))); //gz2016
        $I->click(['id'=>"submit"]);
        $I->waitForText("The team ".$this::TEAM_A_NAME." was added", $this::DEFAULT_WAIT);
    }
    /**
     * Test the form with the participation flag
     * @param AcceptanceTester $I
     */
    public function tryToCheckIfAfterCreatingATeamIfICanAssignAnotherTeamTheSameNameInTheSameTournament(AcceptanceTester $I)
    {
        $I->am("admin");
        $I->wantTo("create a team");
        $I->fillField("#name", $this::TEAM_A_NAME);
        $I->selectOption("#tournament_id", array('text' => str_replace(' ', '_', $this::TOURNAMENT_A_NAME))); //gz2016
        $I->click(['id'=>"submit"]);
        $I->waitForText("A team with the exact same name already exists for this tournament, please select a different name.", $this::DEFAULT_WAIT);
    }
}
