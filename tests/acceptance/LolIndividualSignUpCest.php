<?php
namespace Tests\Acceptance;

use AcceptanceTester;

class LolIndividualSignUpCest extends \BaseAcceptance
{

    public $messageContainerId = "lol-team-sign-up-message-container";
    public function __construct()
    {
        $this->faker = \Faker\Factory::create();
    }

    public function _before(AcceptanceTester $I)
    {
        parent::_before($I);
        $I->amOnPage('/tournament/lol-team-signup/');
        $I->waitForJs('return jQuery.active == 0', 10);
    }

    public function _after(AcceptanceTester $I)
    {
        parent::_after($I);
    }

    // tests
    public function seeTitleOnIndividualSignUpPage(AcceptanceTester $I)
    {
        $I->see('League of Legends Individual SignUp');
    }

    public function submitAnIndividualToTheSystem(AcceptanceTester $I)
    {
        $I->wantTo('Submit the LOL individual sign up form');

        $name = $this->faker->name;
        $summoner = $this->faker->userName;
        $email = $this->faker->email;
        $phone = $this->faker->phoneNumber;
        $I->fillField(['name' => 'name'], $name);
        $I->fillField(['name' => 'your-lol-summoner-name'], $summoner);
        $I->fillField(['name' => 'email'], $email);
        $I->fillField(['name' => 'your-phone'], $phone);

        $I->click(['id' => 'doFormSubmit']);
        $I->waitForElementVisible(['id' => $this->messageContainerId]);

        $I->see('Thanks for signing up to play League of Legends!');
        $I->see($name, ['id' => 'name-response-value']);
        $I->see($summoner, ['id' => 'your-lol-summoner-name-response-value']);
        $I->see($email, ['id' => 'email-response-value']);
        $I->see($phone, ['id' => 'your-phone-response-value']);
    }

    public function submitFailsWithBadEmail(AcceptanceTester $I)
    {
        $I->wantTo('Submit the LOL individual sign up form with a bad email address');

        $email = $this->faker->words(4);
        $I->fillField(['name' => 'email'], $email);

        $I->click(['id' => 'doFormSubmit']);
        $I->waitForElementVisible(['id' => $this->messageContainerId]);

        $I->see('Your email address must be a valid address');
    }

    public function submitFailsWithMissingEmail(AcceptanceTester $I)
    {
        $I->wantTo('Submit the LOL individual sign up form with a missing email address');

        $I->fillField(['name' => 'name'], $this->faker->name);

        $I->click(['id' => 'doFormSubmit']);
        $I->waitForElementVisible(['id' => $this->messageContainerId]);

        $I->see('Your email address is required');
    }

    public function submitFailsWithMissingSummonerName(AcceptanceTester $I)
    {
        $I->wantTo('Submit the LOL individual sign up form with a missing summoner name');

        $I->fillField(['name' => 'name'], $this->faker->name);

        $I->click(['id' => 'doFormSubmit']);
        $I->waitForElementVisible(['id' => $this->messageContainerId]);

        $I->see('Your League of Legends summoner name is required');
    }

    public function submitFailsWithMissingName(AcceptanceTester $I)
    {
        $I->wantTo('Submit the LOL individual sign up form with a missing name');

        $I->fillField(['name' => 'email'], $this->faker->email);

        $I->click(['id' => 'doFormSubmit']);
        $I->waitForElementVisible(['id' => $this->messageContainerId]);

        $I->see('Your name is required');
    }

    public function submitFailsWithMissingPhone(AcceptanceTester $I)
    {
        $I->wantTo('Submit the LOL individual sign up form with a missing phone');

        $I->fillField(['name' => 'email'], $this->faker->email);

        $I->click(['id' => 'doFormSubmit']);
        $I->waitForElementVisible(['id' => $this->messageContainerId]);

        $I->see('Your phone number is required');
    }
}
