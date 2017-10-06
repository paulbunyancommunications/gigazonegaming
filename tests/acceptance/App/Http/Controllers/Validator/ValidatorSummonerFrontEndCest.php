<?php
namespace Tests\Acceptance\App\Http\Controllers\Validator;
use \AcceptanceTester;
use App\Models\Championship\Tournament;
use \BaseAcceptance;

/**
 * Class ValidatorUniqueWidthFrontEndCest
 * @package Tests\Acceptance
 */
class ValidatorSummonerFrontEndCest extends BaseAcceptance
{
    /**
     *
     */
    public $faker;
    const DEFAULT_WAIT = 60;
    const TEAM_A_NAME = "Tester Team Unique Width A";//same as in the SignUpUniqueWithValidatorTesterSeeder
    const TEAM_B_NAME = "Tester Team Unique Width B";//same as in the SignUpUniqueWithValidatorTesterSeeder
    const TOURNAMENT_A_NAME = "Tester_Tournament_Unique_Width_A";//same as in the SignUpUniqueWithValidatorTesterSeeder
    const TOURNAMENT_B_NAME = "Tester_Tournament_Unique_Width_B";//same as in the SignUpUniqueWithValidatorTesterSeeder

    public $nameList = ["ChaChing77", "SlySkeever", "DragonDefeater", "CacheMeOuside", "CrackAColdOne", "YouBetterNotComeAtMe"];
    public $emailList = [];
    public $names = [
        "#team-captain-lol-summoner-name",
        '#teammate-one-lol-summoner-name',
        '#teammate-two-lol-summoner-name',
        '#teammate-three-lol-summoner-name',
        '#teammate-four-lol-summoner-name',
    ];
    public $emails = [
        "#team-captain-email-address",
        '#teammate-one-email-address',
        '#teammate-two-email-address',
        '#teammate-three-email-address',
        '#teammate-four-email-address',
    ];
    /**
     * @param AcceptanceTester $I
     */
    public function _before(AcceptanceTester $I)
    {
        parent::_before($I);
        $this->populateDBSignUp($I);
        $this->faker = \Faker\Factory::create();
        for($i=0; $i < 6; $i++) {
            $this->emailList[] = "xyz".$this->faker->email();
        }
        $I->amOnPage('/tournament/lol-team-signup/');
        $I->checkIfJQueryIsWorking($I,  $this::TEXT_WAIT_TIMEOUT);
    }
    /**
    * Create the test admin user
    */
    protected function populateDBSignUp(AcceptanceTester $I)
    {
        exec('php artisan db:seed --class=SignUpUniqueWithValidatorTesterSeeder');
    }
    /**
    * ResetDb
    */
    protected function resetDBSignUp(AcceptanceTester $I)
    {

    }
    /**
     * @param AcceptanceTester $I
     */
    public function _after(AcceptanceTester $I)
    {
        $this->resetDBSignUp($I);
    }
    /**
     * Test the form with the participation flag
     * @param AcceptanceTester $I
     */
    public function tryToCreateATeamIShouldntSeeAnyError(AcceptanceTester $I)
    {
        $I->executeJS("$('#hidden').val('".$this::TOURNAMENT_B_NAME."')
            .css({ 
                'display': 'block',
                'color':'#ff0000',
                'font-size':'20px',
                'width':'500px',
                'height':'40px'
                }).prop(
                'type', 'text'
                )");
        $I->fillField("#team-name", $this::TEAM_A_NAME);
        $I->fillField("#team-captain", $this->nameList[0]);
        $I->fillField("#team-captain-phone", "2184443131");

        for($i=0; $i < 5; $i++) {
            $I->fillField( $this->names[$i], $this->nameList[$i] );
            $I->fillField( $this->emails[$i], $this->emailList[$i] );
        }

        $I->click("Submit");

        $I->wait(2);
        $I->checkIfJQueryIsWorking($I, $this::TEXT_WAIT_TIMEOUT*2);
        $I->waitForElementVisible("#lol-team-sign-up-message-container",$this::DEFAULT_WAIT*2);
        $I->dontSee("not a valid summoner name");
        $I->dontSee("A team with the exact same name already exists for this tournament, please select a different name.");

    }
    /**
     * Test the form with the participation flag
     * @param AcceptanceTester $I
     */
    public function tryToCreateATeamIShouldSeeAnError(AcceptanceTester $I)
    {
        $I->executeJS("$('#hidden').val('".$this::TOURNAMENT_B_NAME."')
            .css({ 
                'display': 'block',
                'color':'#ff0000',
                'font-size':'20px',
                'width':'500px',
                'height':'40px'
                }).prop(
                'type', 'text'
                )");
        $I->fillField("#team-name", $this::TEAM_A_NAME);
        $I->fillField("#team-captain", "ThisNickNameShouldNotExist");
        $I->fillField("#team-captain-phone", "2184443131");
        $I->fillField("#team-captain-email-address", "captain@team.com");
        $I->fillField("#team-captain-lol-summoner-name", "ThisNickNameShouldNotExist");

        for($i=1; $i < 5; $i++) {
            $I->fillField( $this->names[$i], $this->nameList[$i] );
            $I->fillField( $this->emails[$i], $this->emailList[$i] );
        }

        $I->click("Submit");

        $I->wait(2);
        $I->checkIfJQueryIsWorking($I, $this::TEXT_WAIT_TIMEOUT*2);
        $I->waitForElementVisible("#lol-team-sign-up-message-container",$this::DEFAULT_WAIT*2);
        $I->waitForText("not a valid summoner name", $this::DEFAULT_WAIT);
        $I->dontSee("A team with the exact same name already exists for this tournament, please select a different name.");
    }
}
