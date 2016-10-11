<?php
namespace Tests\Acceptance;

use AcceptanceTester;

class ContactFormCest extends \BaseAcceptance
{


    public function _before(AcceptanceTester $I)
    {
        parent::_before($I);
        $I->amOnPage('/about/contact-us/');
        $I->waitForJs('return jQuery.active == 0', 10);
    }

    public function _after(AcceptanceTester $I)
    {
        parent::_after($I);
    }

    public function __construct()
    {
        $this->faker = \Faker\Factory::create();
    }

    // tests

    public function seeTitleOnContactPage(AcceptanceTester $I)
    {
        $I->wantTo('See the contact page title');
        $I->see('Contact Us', ['class' => 'title-page']);
    }

    public function tryToSubmitTheContactForm(AcceptanceTester $I)
    {
        $I->wantTo('Submit the contact form');
        $comment = $this->faker->paragraph;
        $email = $this->faker->email;
        $name = $this->faker->name;
        $I->fillField(['name' => 'comment'], $comment);
        $I->fillField(['name' => 'email'], $email);
        $I->fillField(['name' => 'name'], $name);
        $I->click(['id' => 'doFormSubmit']);
        $I->waitForElementVisible(['id' => 'contact-us-message-container']);
        $I->see('Thanks for filling out the Contact Us form');
        $I->see($comment, ['id' => 'comment-response-value']);
        $I->see($name, ['id' => 'name-response-value']);
        $I->see($email, ['id' => 'email-response-value']);

    }

    public function tryToSubmitTheContactFormWithUpdateAndCheckDatabase(AcceptanceTester $I)
    {
        $I->wantTo('Submit the contact form and check to see that this user was signed up for updates');
        $email = $this->faker->email;
        $name = $this->faker->name;
        $I->fillField(['name' => 'email'], $email);
        $I->fillField(['name' => 'name'], $name);
        $I->executeJS('$(\'input[name="update-recipient"]\').prop("checked", true ).val("yes")');

        $I->click(['id' => 'doFormSubmit']);
        $I->waitForElementVisible(['id' => 'contact-us-message-container']);

        $I->see('Thanks for filling out the Contact Us form');
        $I->seeInDatabase('update_recipients', array('email' => $email));

    }

    public function tryAndSubmitContactFormWithMissingName(AcceptanceTester $I)
    {
        $I->wantTo('Submit the contact form and check that an error will return if name is missing');

        $email = $this->faker->email;
        $I->fillField(['name' => 'email'], $email);

        $I->click(['id' => 'doFormSubmit']);
        $I->waitForElementVisible(['id' => 'contact-us-message-container']);

        $I->see('The name field is required.');
    }

    public function tryAndSubmitContactFormWithMissingEmail(AcceptanceTester $I)
    {
        $I->wantTo('Submit the contact form and check that an error will return if email missing');
        $name = $this->faker->name;
        $I->fillField(['name' => 'name'], $name);

        $I->click(['id' => 'doFormSubmit']);
        $I->waitForElementVisible(['id' => 'contact-us-message-container']);

        $I->see('The email field is required.');
    }

    public function tryAndSubmitContactFormWithInvalidEmail(AcceptanceTester $I)
    {
        $I->wantTo('Submit the contact form and check that an error will return if email submitted is not valid');
        $name = $this->faker->name;
        $email = $this->faker->md5;
        $I->fillField(['name' => 'name'], $name);
        $I->fillField(['name' => 'email'], $email);

        $I->click(['id' => 'doFormSubmit']);
        $I->waitForElementVisible(['id' => 'contact-us-message-container']);

        $I->see('The email must be a valid email address.');
    }
}
