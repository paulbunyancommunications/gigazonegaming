<?php
namespace Tests\Acceptance\App\Http\Controllers\Validator;
use \AcceptanceTester;
use App\Models\Championship\Tournament;
use \BaseAcceptance;

/**
 * Class ValidatorUniqueWidthFrontEndCest
 * @package Tests\Acceptance
 */
class ValidatorUniqueWidthFrontEndCest extends BaseAcceptance
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

    public $nameList = [];
    public $emailList = [];
    public $names = [
        "#team-captain-lol-summoner-name",
        '#teammate-one-lol-summoner-name',
        '#teammate-two-lol-summoner-name',
        '#teammate-three-lol-summoner-name',
        '#teammate-four-lol-summoner-name',
        '#alternate-one-summoner-name',
        '#alternate-two-summoner-name',
        '#alternate-three-summoner-name'
    ];
    public $emails = [
        "#team-captain-email-address",
        '#teammate-one-email-address',
        '#teammate-two-email-address',
        '#teammate-three-email-address',
        '#teammate-four-email-address',
        '#alternate-one-email-address',
        '#alternate-two-email-address',
        '#alternate-three-email-address'
    ];
    /**
     * @param AcceptanceTester $I
     */
    public function _before(AcceptanceTester $I)
    {
        parent::_before($I);
        $this->populateDBSignUp($I);
        $this->faker = \Faker\Factory::create();
        for($i=0; $i < 8; $i++) {
            $this->nameList[] = "xyz".$this->faker->name()."stu";
            $this->emailList[] = "xyz".$this->faker->email();
        }
        $I->amOnPage('/tournament/lol-team-signup/');
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
    public function tryToCreateATeam(AcceptanceTester $I)
    {
        $idA = $I->grabFromDatabase("champ_tournaments", "id", ['name'=>$this::TOURNAMENT_A_NAME]);
        $idB = $I->grabFromDatabase("champ_tournaments", "id", ['name'=>$this::TOURNAMENT_B_NAME]);

        $I->canSeeInDatabase("champ_teams", ['name'=>$this::TEAM_A_NAME,'tournament_id'=>$idA]);
        $I->cantSeeInDatabase("champ_teams", ['name'=>$this::TEAM_A_NAME,'tournament_id'=>$idB]);

        $I->amOnPage('/tournament/lol-team-signup/');
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
        $I->fillField("#team-captain", $this->faker->name()."asdasd");
        $I->fillField("#team-captain-phone", "2184443131");

        for($i=0; $i < 8; $i++) {
            $I->fillField( $this->names[$i], $this->nameList[$i] );
            $I->fillField( $this->emails[$i], $this->emailList[$i] );
        }

        $I->click("Submit");

        $I->waitForElementNotVisible("#lol-team-sign-up-message-container",$this::DEFAULT_WAIT);
        $I->waitForElementVisible(".message-outer-container",$this::DEFAULT_WAIT);
        $I->dontSee("A team with the exact same name already exists for this tournament, please select a different name.");

    }
    /**
     * Test the form with the participation flag
     * @param AcceptanceTester $I
     */
    public function tryToCreateATeamWithTheSameNameAsAnotherOneInTheSameTournanent(AcceptanceTester $I)
    {
        ///todo for some reason even when I can manually check that the tournanent isnt the same and that the code works, tests continues to fail
        $I->executeJS("$('#hidden').val('".$this::TOURNAMENT_A_NAME."')
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
        $I->fillField("#team-captain", $this->faker->name());
        $I->fillField("#team-captain-phone", "2184443132");

        for($i=0; $i < 8; $i++) {
            $I->fillField( $this->names[$i], $this->nameList[$i] );
            $I->fillField( $this->emails[$i], $this->emailList[$i] );
        }

        $I->click("Submit");
        $I->wait(10);
        $I->canSee("A team with the exact same name already exists for this tournament, please select a different name.");

    }
    /**
     * Test the form with the participation flag
     * @param AcceptanceTester $I
     */
    public function tryToCreateATeamWithTheSamePlayersInDifferentTournamentsWithTheSameTeamName(AcceptanceTester $I)
    {
        $I->executeJS("$('#hidden').val('".$this::TOURNAMENT_A_NAME."')
            .css({ 
                'display': 'block',
                'color':'#ff0000',
                'font-size':'20px',
                'width':'500px',
                'height':'40px'
                }).prop(
                'type', 'text'
                )");
        $I->fillField("#team-name", $this::TEAM_B_NAME."ll");
        $I->fillField("#team-captain", $this->faker->name());
        $I->fillField("#team-captain-phone", "2184443133");
        for($i=0; $i < 8; $i++) {
            $I->fillField( $this->names[$i], $this->nameList[$i] );
            $I->fillField( $this->emails[$i], $this->emailList[$i] );
        }

        $I->click("Submit");

        $I->waitForElementNotVisible("#lol-team-sign-up-message-container",$this::DEFAULT_WAIT);
        $I->waitForElementVisible(".message-outer-container",$this::DEFAULT_WAIT);
        $I->cantSee("A team with the exact same name already exists for this tournament, please select a different name.");

        /////////////////////////////////////////////////////////////////////////////////////////////////////
        /////////////////////////////////////////////////////////////////////////////////////////////////////
        $I->wantToTest("if it is now giving an error");
        /////////////////////////////////////////////////////////////////////////////////////////////////////
        /////////////////////////////////////////////////////////////////////////////////////////////////////

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

        $I->fillField("#team-name", $this::TEAM_B_NAME."ll");
        $I->fillField("#team-captain", $this->faker->name());
        $I->fillField("#team-captain-phone", "2184443135");

        for($i=0; $i < 8; $i++) {
            $I->fillField( $this->names[$i], $this->nameList[$i] );
            $I->fillField( $this->emails[$i], $this->emailList[$i] );
        }

        $I->click("Submit");
        $I->wait(10);

        $I->cantSee("A team with the exact same name already exists for this tournament, please select a different name.");

    }
}
