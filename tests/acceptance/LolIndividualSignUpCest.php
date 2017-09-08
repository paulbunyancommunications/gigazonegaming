<?php
//namespace Tests\Acceptance;
//
//use AcceptanceTester;
//
//class LolIndividualSignUpCest extends \BaseAcceptance
//{
//
//    public $messageContainerId = "lol-team-sign-up-message-container";
//    public $url = "lol-individual-signup";
//    public $content = "*REGISTRATION IS OPEN ONLY TO THOSE WHO RESIDE WITHIN THE 218 AREA CODE IN THE STATE OF MINNESOTA*  Proof of residency will be required prior to tournament.
//        Participants must be 13 years of age or older.
//        By signing up as an independent player, you'll be entering a pool from which we will draw players to fill openings in registered teams. Once those openings are filled, we'll begin assigning independent players to teams. SIGNING UP AS AN INDEPENDENT PLAYER DOES NOT GUARANTEE YOU AN ENTRY IN THE TOURNAMENT! Though we want as many players to have the opportunity to play as possible, there may not be enough independent players remaining to create a 5 person team after openings on registered teams have been filled.
//        Please remember, the goal of this tournament is for everyone to have a great time playing a game that they love! If you are a team who has been assigned an independent player, don't be shy in including them in any other team activities or practices you might have planned. Conversely, if you're an independent player assigned to a team, get in there and make it known that you're willing to work for the team!
//        [lol-individual-sign-up new_line=\",\" delimiter=\"|\" questions=\"game|hidden|league-of-legends,update-recipient|hidden|yes,participate|hidden|yes,Your Name,Your LOL Summoner Name,Your Email Address|email,Your Phone|tel\" inputs=\"your-name|name,your-email-address|email\"]";
//    public function __construct()
//    {
//        $this->faker = \Faker\Factory::create();
//    }
//
//    public function _before(AcceptanceTester $I)
//    {
//        parent::_before($I);
//        $I->loginToWordpress($I,"admin", "password", 3);
//        $I->waitForText("Dashboard", $this::TEXT_WAIT_TIMEOUT);
//        $I->click("Pages");
//        $I->waitForText("Pages", $this::TEXT_WAIT_TIMEOUT, "h1");
//        $I->click("Add New");
//        $I->waitForText("Add New Page", $this::TEXT_WAIT_TIMEOUT, "h1");
//        $I->click("Text");
//        $I->fillField(['id' => 'title-prompt-text'], $this->url);
//        $I->fillField(['id' => 'content'], $this->content);
//        $I->click("Publish");
//        $I->waitForText("Update", $this::TEXT_WAIT_TIMEOUT, "input");
//        $I->amOnPage($this->url);
//        $I->waitForText("LOL INDIVIDUAL SIGN UP", $this::TEXT_WAIT_TIMEOUT);
//        $I->checkIfJQueryIsWorking($I,  $this::TEXT_WAIT_TIMEOUT);
//    }
//
//    public function _after(AcceptanceTester $I)
//    {
//        parent::_after($I);
//    }
//
//    // tests
//    public function seeTitleOnIndividualSignUpPage(AcceptanceTester $I)
//    {
//        $I->see('League of Legends Individual SignUp');
//    }
//
//    public function submitAnIndividualToTheSystem(AcceptanceTester $I)
//    {
//        $I->wantTo('Submit the LOL individual sign up form');
//
//        $name = $this->faker->name;
//        $summoner = $this->faker->userName;
//        $email = $this->faker->email;
//        $phone = $this->faker->phoneNumber;
//        $I->fillField(['name' => 'name'], $name);
//        $I->fillField(['name' => 'your-lol-summoner-name'], $summoner);
//        $I->fillField(['name' => 'your-email-address'], $email);
//        $I->fillField(['name' => 'your-phone'], $phone);
//
//        $I->click(['id' => 'doFormSubmit']);
//        $I->waitForElementVisible(['id' => $this->messageContainerId]);
//
//        $I->see('Thanks for signing up to play League of Legends!');
//        $I->see($name, ['id' => 'name-response-value']);
//        $I->see($summoner, ['id' => 'your-lol-summoner-name-response-value']);
//        $I->see($email, ['id' => 'email-response-value']);
//        $I->see($phone, ['id' => 'your-phone-response-value']);
//    }
//
//    public function submitFailsWithBadEmail(AcceptanceTester $I)
//    {
//        $I->wantTo('Submit the LOL individual sign up form with a bad email address');
//
//        $email = $this->faker->words(4);
//        $I->fillField(['name' => 'email'], $email);
//
//        $I->click(['id' => 'doFormSubmit']);
//        $I->waitForElementVisible(['id' => $this->messageContainerId]);
//
//        $I->see('email address must be a valid email address');
//    }
//
//    public function submitFailsWithMissingEmail(AcceptanceTester $I)
//    {
//        $I->wantTo('Submit the LOL individual sign up form with a missing email address');
//
//        $I->fillField(['name' => 'name'], $this->faker->name);
//
//        $I->click(['id' => 'doFormSubmit']);
//        $I->waitForElementVisible(['id' => $this->messageContainerId]);
//
//        $I->see('Your email address is required');
//    }
//
//    public function submitFailsWithMissingSummonerName(AcceptanceTester $I)
//    {
//        $I->wantTo('Submit the LOL individual sign up form with a missing summoner name');
//
//        $I->fillField(['name' => 'name'], $this->faker->name);
//
//        $I->click(['id' => 'doFormSubmit']);
//        $I->waitForElementVisible(['id' => $this->messageContainerId]);
//
//        $I->see('Your League of Legends summoner name is required');
//    }
//
//    public function submitFailsWithMissingName(AcceptanceTester $I)
//    {
//        $I->wantTo('Submit the LOL individual sign up form with a missing name');
//
//        $I->fillField(['name' => 'email'], $this->faker->email);
//
//        $I->click(['id' => 'doFormSubmit']);
//        $I->waitForElementVisible(['id' => $this->messageContainerId]);
//
//        $I->see('Your name is required');
//    }
//
//    public function submitFailsWithMissingPhone(AcceptanceTester $I)
//    {
//        $I->wantTo('Submit the LOL individual sign up form with a missing phone');
//
//        $I->fillField(['name' => 'email'], $this->faker->email);
//
//        $I->click(['id' => 'doFormSubmit']);
//        $I->waitForElementVisible(['id' => $this->messageContainerId]);
//
//        $I->see('Your phone number is required');
//    }
//}
