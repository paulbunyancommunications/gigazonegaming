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
    const TOURNAMENT_A_NAME = "Tester Tournament Unique Width A";//same as in the SignUpUniqueWithValidatorTesterSeeder
    const TOURNAMENT_B_NAME = "Tester Tournament Unique Width B";//same as in the SignUpUniqueWithValidatorTesterSeeder

    public $names = [
    "#team-captain-email-address",
    '#teammate-one-lol-summoner-name',
    '#teammate-two-lol-summoner-name',
    '#teammate-three-lol-summoner-name',
    '#teammate-four-lol-summoner-name',
    '#alternate-one-summoner-name',
    '#alternate-two-summoner-name',
    '#alternate-three-summoner-name'
    ];
    public $emails = [
    "#team-captain-phone",
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
        $this->populateDB($I);
        $this->loginWithAdminUser($I);
        $I->amOnPage('/tournament/lol-team-signup/');
    }
    /**
    * Create the test admin user
    */
    protected function populateDB(AcceptanceTester $I)
    {
        exec('php artisan db:seed --class=SignUpUniqueWithValidatorTesterSeeder');
        $this->faker = \Faker\Factory::create();
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
    public function tryToCreateATeam(AcceptanceTester $I)
    {
        $I->executeJS("$('hidden').val('".$this::TOURNAMENT_B_NAME."');");
        $I->fillField("#team-name", $this::TEAM_A_NAME);
        $I->fillField("#team-captain", $this->faker->name());
        $I->fillField("#team-captain-lol-summoner-name", $this->faker->name());

        $id = $I->grabFromDatabase("champ_teams", "id", ['name'=>$this::TOURNAMENT_A_NAME]);
        dd($id);
        for($i=0; $i < 8; $i++) {
            $I->fillField($this->names[$i], $this->faker->email());
            $I->fillField($this->emails[$i], "(218)-444-".$i."9".$i."0");
        }
        $I->canSeeInDatabase("champ_teams", ['name'=>$this::TEAM_A_NAME,'tournament_id'=>$id]);
        $I->cantSeeInDatabase("champ_teams", ['name'=>$this::TEAM_B_NAME,'tournament_id'=>$id]);

        $I->click("#doFormSubmit");

        $I->wait(2);

        $I->canSeeInDatabase("champ_teams", ['name'=>$this::TEAM_A_NAME,'tournament_id'=>$id]);
        $I->canSeeInDatabase("champ_teams", ['name'=>$this::TEAM_B_NAME,'tournament_id'=>$id]);

    }
    /**
     * Test the form with the participation flag
     * @param AcceptanceTester $I
     */
    public function tryToCreateATeamWithTheSameNameAsAnotherOne(AcceptanceTester $I)
    {
        $I->executeJS("$('hidden').val('".$this::TOURNAMENT_A_NAME."');");
        $I->fillField("#team-name", $this::TEAM_A_NAME);
        $I->fillField("#team-captain", $this->faker->name());
        $I->fillField("#team-captain-lol-summoner-name", $this->faker->name());
        for($i=0; $i < 8; $i++) {
            $I->fillField($this->names[$i], $this->faker->email());
            $I->fillField($this->emails[$i], "(218)-444-".$i."9".$i."0");
        }
        $I->click("#doFormSubmit");
        $I->waitForText("The team-name has already been taken.", $this::DEFAULT_WAIT);
    }
}
