<?php
namespace Tests\Acceptance;

use AcceptanceTester;

class ContactFormCest
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
    public function tryToSubmitTheContactForm(AcceptanceTester $I)
    {
        $faker = \Faker\Factory::create();
        $I->wantTo('Submit the contact form');
        $I->amOnPage('/about/contact-us/');
        $I->see('Contact Us', ['class' => 'title-page']);
        $comment = $faker->paragraph;
        $email = $faker->email;
        $name = $faker->name;
        $I->fillField(['name' => 'comment'], $comment);
        $I->fillField(['name' => 'email'], $email);
        $I->fillField(['name' => 'name'], $name);
        $I->click(['id' => 'doFormSubmit']);
        $I->wait(self::DEFAULT_WAIT);
        $I->see('Thanks for filling out the Contact Us form');
        $I->see($comment, ['id' => 'comment-response-value']);
        $I->see($name, ['id' => 'name-response-value']);
        $I->see($email, ['id' => 'email-response-value']);

    }

    public function tryToSubmitTheContactFormWithUpdateAndCheckDatabase(AcceptanceTester $I)
    {
        $faker = \Faker\Factory::create();
        $I->wantTo('Submit the contact form and check to see that this user was signed up for updates');
        $I->amOnPage('/about/contact-us/');
        $I->see('Contact Us', ['class' => 'title-page']);
        $email = $faker->email;
        $name = $faker->name;
        $I->fillField(['name' => 'email'], $email);
        $I->fillField(['name' => 'name'], $name);
        $I->executeJS('$(\'input[name="update-recipient"]\').prop("checked", true ).val("yes")');
        $I->click(['id' => 'doFormSubmit']);
        $I->wait(self::DEFAULT_WAIT);
        $I->see('Thanks for filling out the Contact Us form');
        $I->seeInDatabase('update_recipients', array('email' => $email));

    }

    public function tryAndSubmitContactFormWithMissingName(AcceptanceTester $I)
    {
        $faker = \Faker\Factory::create();
        $I->wantTo('Submit the contact form and check that an error will return if name is missing');
        $I->amOnPage('/about/contact-us/');
        $I->see('Contact Us', ['class' => 'title-page']);
        $email = $faker->email;
        $I->fillField(['name' => 'email'], $email);
        $I->click(['id' => 'doFormSubmit']);
        $I->wait(self::DEFAULT_WAIT);
        $I->see('The name field is required.');
    }

    public function tryAndSubmitContactFormWithMissingEmail(AcceptanceTester $I)
    {
        $faker = \Faker\Factory::create();
        $I->wantTo('Submit the contact form and check that an error will return if email missing');
        $I->amOnPage('/about/contact-us/');
        $I->see('Contact Us', ['class' => 'title-page']);
        $name = $faker->name;
        $I->fillField(['name' => 'name'], $name);
        $I->click(['id' => 'doFormSubmit']);
        $I->wait(self::DEFAULT_WAIT);
        $I->see('The email field is required.');
    }

    public function tryAndSubmitContactFormWithInvalidEmail(AcceptanceTester $I)
    {
        $faker = \Faker\Factory::create();
        $I->wantTo('Submit the contact form and check that an error will return if email submitted is not valid');
        $I->amOnPage('/about/contact-us/');
        $I->see('Contact Us', ['class' => 'title-page']);
        $name = $faker->name;
        $email = $faker->md5;
        $I->fillField(['name' => 'name'], $name);
        $I->fillField(['name' => 'email'], $email);
        $I->click(['id' => 'doFormSubmit']);
        $I->wait(self::DEFAULT_WAIT);
        $I->see('The email must be a valid email address.');
    }
}
