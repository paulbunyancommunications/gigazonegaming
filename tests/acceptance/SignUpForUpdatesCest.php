<?php
namespace Tests\Acceptance;
use \AcceptanceTester;

class SignUpForUpdatesCest
{
    const DEFAULT_WAIT = 5;

    public function _before(AcceptanceTester $I)
    {
        $I->runMigration($I);
    }

    public function _after(AcceptanceTester $I)
    {
        $I->runMigration($I);
    }

    // tests
    public function tryToSubmitAnEmailToTheUpdatesListWithParticipate(AcceptanceTester $I)
    {
        $faker = \Faker\Factory::create();
        $I->wantTo('Submit email address to the update controller with the participate flag');
        $I->amOnPage('/');
        $I->see('Sign up for updates');
        $email = $faker->companyEmail;
        $I->fillField(['id' => 'updateSignUpForm-email'], $email);
        $I->checkOption(['id' => 'updateSignUpForm-participate']);
        $I->click(['id' => 'updateSignUpFormSubmit']);
        $I->wait(self::DEFAULT_WAIT);
        $I->see('Thanks for signing up!');
        $I->seeInDatabase('update_recipients', array('email' => $email, 'participate' => 1));
    }
    public function tryToSubmitAnEmailToTheUpdatesListWithoutParticipate(AcceptanceTester $I)
    {
        $faker = \Faker\Factory::create();
        $I->wantTo('Submit email address to the update controller without the participate flag');
        $I->amOnPage('/');
        $I->see('Sign up for updates');
        $email = $faker->companyEmail;
        $I->fillField(['id' => 'updateSignUpForm-email'], $email);
        $I->uncheckOption(['id' => 'updateSignUpForm-participate']);
        $I->click(['id' => 'updateSignUpFormSubmit']);
        $I->wait(self::DEFAULT_WAIT);
        $I->see('Thanks for signing up!');
        $I->seeInDatabase('update_recipients', array('email' => $email, 'participate' => 0));
    }

    public function tryToSubmitAnBadEMailToTheUpdatesListFails(AcceptanceTester $I)
    {
        $faker = \Faker\Factory::create();
        $I->wantTo('Submit a bad email address to the updates controller and see that I get an error');
        $I->amOnPage('/');
        $I->see('Sign up for updates');
        $email = $faker->md5();
        $I->fillField(['id' => 'updateSignUpForm-email'], $email);
        $I->checkOption(['id' => 'updateSignUpForm-participate']);
        $I->click(['id' => 'updateSignUpFormSubmit']);
        $I->wait(self::DEFAULT_WAIT);
        $I->see('The email must be a valid email address.');
        $I->dontSeeInDatabase('update_recipients', array('email' => $email));
    }

    public function tryToSubmitDuplicateEmailFails(AcceptanceTester $I)
    {
        $faker = \Faker\Factory::create();
        $I->wantTo('Submit a duplicate email address to the updates controller and see that I get an error');
        $I->amOnPage('/');
        $I->see('Sign up for updates');
        $email = $faker->companyEmail;
        $I->fillField(['id' => 'updateSignUpForm-email'], $email);
        $I->click(['id' => 'updateSignUpFormSubmit']);
        $I->wait(self::DEFAULT_WAIT);
        $I->see('Thanks for signing up!');
        // do it a second time
        $I->seeInDatabase('update_recipients', array('email' => $email));
        $I->fillField(['id' => 'updateSignUpForm-email'], $email);
        $I->click(['id' => 'updateSignUpFormSubmit']);
        $I->wait(self::DEFAULT_WAIT);
        $I->see('That email address has already been submitted.');


    }


}
