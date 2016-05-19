<?php
namespace Tests\Acceptance;

use \AcceptanceTester;

class LolIndividualSignUpCest
{

    const DEFAULT_WAIT = 15;

    public function _before(AcceptanceTester $I)
    {
        $I->runMigration($I);
    }

    public function _after(AcceptanceTester $I)
    {
        $I->runMigration($I);
    }

    // tests
    public function submitAnIndividualToTheSystem(AcceptanceTester $I)
    {
        $faker = \Faker\Factory::create();
        $I->wantTo('Submit the LOL individual sign up form');
        $I->amOnPage('/sign-up/lol-individual-sign-up/');
        $I->see('League of Legends Individual Sign Up');

        $name = $faker->name;
        $summoner = $faker->userName;
        $email = $faker->email;
        $phone = $faker->phoneNumber;
        $I->fillField(['name' => 'name'], $name);
        $I->fillField(['name' => 'your-lol-summoner-name'], $summoner);
        $I->fillField(['name' => 'email'], $email);
        $I->fillField(['name' => 'your-phone'], $phone);

        $I->click(['id' => 'doFormSubmit']);
        $I->wait(self::DEFAULT_WAIT);

        $I->see('Thanks for signing up to play League of Legends!');
        $I->see($name, ['id' => 'name-response-value']);
        $I->see($summoner, ['id' => 'your-lol-summoner-name-response-value']);
        $I->see($email, ['id' => 'email-response-value']);
        $I->see($phone, ['id' => 'your-phone-response-value']);
    }

    public function submitFailsWithBadEmail (AcceptanceTester $I)
    {
        $faker = \Faker\Factory::create();
        $I->wantTo('Submit the LOL individual sign up form with a bad email address');
        $I->amOnPage('/sign-up/lol-individual-sign-up/');
        $email = $faker->words(4);
        $I->fillField(['name' => 'email'], $email);
        $I->click(['id' => 'doFormSubmit']);
        $I->wait(self::DEFAULT_WAIT);
        $I->see('Your email address must be a valid address');
    }

    public function submitFailsWithMissingEmail(AcceptanceTester $I)
    {
        $I->wantTo('Submit the LOL individual sign up form with a missing email address');
        $I->amOnPage('/sign-up/lol-individual-sign-up/');
        // wait for any js to finish, should not take this long
        $I->wait(self::DEFAULT_WAIT);
        $I->click(['id' => 'doFormSubmit']);
        $I->wait(self::DEFAULT_WAIT);
        $I->see('Your email address is required');
    }

    public function submitFailsWithMissingSummonerName(AcceptanceTester $I)
    {
        $I->wantTo('Submit the LOL individual sign up form with a missing summoner name');
        $I->amOnPage('/sign-up/lol-individual-sign-up/');
        // wait for any js to finish, should not take this long
        $I->wait(self::DEFAULT_WAIT);
        $I->click(['id' => 'doFormSubmit']);
        $I->wait(self::DEFAULT_WAIT);
        $I->see('Your League of Legends summoner name is required');
    }

    public function submitFailsWithMissingName(AcceptanceTester $I)
    {
        $I->wantTo('Submit the LOL individual sign up form with a missing name');
        $I->amOnPage('/sign-up/lol-individual-sign-up/');
        // wait for any js to finish, should not take this long
        $I->wait(self::DEFAULT_WAIT);
        $I->click(['id' => 'doFormSubmit']);
        $I->wait(self::DEFAULT_WAIT);
        $I->see('Your name is required');
    }

    public function submitFailsWithMissingPhone(AcceptanceTester $I)
    {
        $I->wantTo('Submit the LOL individual sign up form with a missing phone');
        $I->amOnPage('/sign-up/lol-individual-sign-up/');
        // wait for any js to finish, should not take this long
        $I->wait(self::DEFAULT_WAIT);
        $I->click(['id' => 'doFormSubmit']);
        $I->wait(self::DEFAULT_WAIT);
        $I->see('Your phone number is required');
    }
}
