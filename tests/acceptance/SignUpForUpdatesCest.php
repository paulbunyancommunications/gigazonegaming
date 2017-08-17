<?php
namespace Tests\Acceptance;
use \AcceptanceTester;

/**
 * Class SignUpForUpdatesCest
 * @package Tests\Acceptance
 */
class SignUpForUpdatesCest extends \BaseAcceptance
{
    /**
     *
     */
    const DEFAULT_WAIT = 15;

    /**
     * @param AcceptanceTester $I
     */
    public function _before(AcceptanceTester $I)
    {
        parent::_before($I);
    }

    /**
     * @param AcceptanceTester $I
     */
    public function _after(AcceptanceTester $I)
    {
        parent::_after($I);
    }

    /**
     * Test the form with the participation flag
     * @param AcceptanceTester $I
     */
    public function tryToSubmitAnEmailToTheUpdatesListWithParticipate(AcceptanceTester $I, $scenario)
    {
        $scenario->incomplete("the tests aint going to work. once the link to sign up for updates is clicked, you are shoot to contactcontact.com in a new tab.");
        $faker = \Faker\Factory::create();
        $I->wantTo('Submit email address to the update controller with the participate flag');
        $I->amOnPage('/');
        // label might be hidden on this form, so just check for the string in the source
        $I->waitForElementVisible('Signup For Updates', $this::TEXT_WAIT_TIMEOUT);
        $I->click("Sign up for updates");
        $I->waitForElementVisible('Sign up to stay in touch!', $this::TEXT_WAIT_TIMEOUT);
        $email = $faker->companyEmail;
        $I->fillField(['id' => 'updateSignUpForm-email'], $email);

        // element might be hidden in the dom, detach and move it so it's visible for clicking
        \Helper\Acceptance::loadJQuery($I, 'ele = $("#updateSignUpForm-participate").detach(); $("#app-update-sign-up-form").prepend(ele);');
        $I->wait(2);
        $I->checkOption(['id' => 'updateSignUpForm-participate']);
        $I->click(['id' => 'updateSignUpFormSubmit']);
        $I->wait(self::DEFAULT_WAIT);
        $I->see('Thanks for signing up!');
        $I->seeInDatabase('update_recipients', array('email' => $email, 'participate' => 1));
    }

    /**
     * Submit a form without a participation flag
     * @param AcceptanceTester $I
     */
    public function tryToSubmitAnEmailToTheUpdatesListWithoutParticipate(AcceptanceTester $I, $scenario)
    {
        $scenario->incomplete("the tests aint going to work. once the link to sign up for updates is clicked, you are shoot to contactcontact.com in a new tab.");
        $faker = \Faker\Factory::create();
        $I->wantTo('Submit email address to the update controller without the participate flag');
        $I->amOnPage('/');

        // label might be hidden on this form, so just check for the string in the source
        $I->seeInSource('Signup For Updates');
        $I->click("Sign up for updates");
        $email = $faker->companyEmail;
        $I->waitForElementVisible('Sign up to stay in touch!', $this::TEXT_WAIT_TIMEOUT);
        $I->fillField(['id' => 'updateSignUpForm-email'], $email);

        // element might be hidden in the dom, detach and move it so it's visible for clicking
        \Helper\Acceptance::loadJQuery($I, 'ele = $("#updateSignUpForm-participate").detach(); $("#app-update-sign-up-form").prepend(ele);');
        $I->wait(2);
        $I->uncheckOption(['id' => 'updateSignUpForm-participate']);
        $I->click(['id' => 'updateSignUpFormSubmit']);
        $I->wait(self::DEFAULT_WAIT);
        $I->see('Thanks for signing up!');
        $I->seeInDatabase('update_recipients', array('email' => $email, 'participate' => 0));
    }

    /**
     * Check to see that a bad email address with fail submission of form
     *
     * @param AcceptanceTester $I
     */
    public function tryToSubmitAnBadEMailToTheUpdatesListFails(AcceptanceTester $I, $scenario)
    {
        $scenario->incomplete("the tests aint going to work. once the link to sign up for updates is clicked, you are shoot to contactcontact.com in a new tab.");
        $faker = \Faker\Factory::create();
        $I->wantTo('Submit a bad email address to the updates controller and see that I get an error');
        $I->amOnPage('/');

        // label might be hidden on this form, so just check for the string in the source
        $I->seeInSource('Signup For Updates');
        $email = $faker->md5();
        $I->click("Sign up for updates");
        $I->waitForElementVisible('Sign up to stay in touch!', $this::TEXT_WAIT_TIMEOUT);
        $I->fillField(['id' => 'updateSignUpForm-email'], $email);

        // element might be hidden in the dom, detach and move it so it's visible for clicking
        \Helper\Acceptance::loadJQuery($I, 'ele = $("#updateSignUpForm-participate").detach(); $("#app-update-sign-up-form").prepend(ele);');
        $I->wait(2);
        $I->checkOption(['id' => 'updateSignUpForm-participate']);
        $I->click(['id' => 'updateSignUpFormSubmit']);
        $I->wait(self::DEFAULT_WAIT);
        $I->see('The email must be a valid email address.');
        $I->dontSeeInDatabase('update_recipients', array('email' => $email));
    }

    /**
     * Check to see that submitting a duplicate email address with fail
     *
     * @param AcceptanceTester $I
     */
    public function tryToSubmitDuplicateEmailFails(AcceptanceTester $I, $scenario)
    {
        $scenario->incomplete("the tests aint going to work. once the link to sign up for updates is clicked, you are shoot to contactcontact.com in a new tab.");
        $faker = \Faker\Factory::create();
        $I->wantTo('Submit a duplicate email address to the updates controller and see that I get an error');
        $I->amOnPage('/');
        $I->wait(self::DEFAULT_WAIT);
        // label might be hidden on this form, so just check for the string in the source
        $I->seeInSource('Signup For Updates');
        $email = $faker->companyEmail;
        $I->click("Sign up for updates");
        $I->waitForElementVisible('Sign up to stay in touch!', $this::TEXT_WAIT_TIMEOUT);
        $I->fillField(['id' => 'updateSignUpForm-email'], $email);
        $I->click(['id' => 'updateSignUpFormSubmit']);
        $I->waitForElementVisible(['id' => 'update-sign-up-message-container'], (self::DEFAULT_WAIT * 2));
        $I->see('Thanks for signing up!');
        // do it a second time
        $I->seeInDatabase('update_recipients', array('email' => $email));
        $I->fillField(['id' => 'updateSignUpForm-email'], $email);
        $I->click(['id' => 'updateSignUpFormSubmit']);
        $I->waitForElementVisible(['id' => 'update-sign-up-message-container'], (self::DEFAULT_WAIT * 2));
        $I->see('That email address has already been submitted.');

    }

    /**
     * Submit email address to the update controller with geo location data
     */
    public function checkToSeeThatThereAreGeoLocationFieldsInForm(AcceptanceTester $I, $scenario)
    {
        $scenario->incomplete("the tests aint going to work. once the link to sign up for updates is clicked, you are shoot to contactcontact.com in a new tab.");
        $faker = \Faker\Factory::create();
        $I->wantTo('Submit email address to the update controller with geo location data');
        $I->amOnPage('/');

        // label might be hidden on this form, so just check for the string in the source
        $I->seeInSource('Signup For Updates');
        $email = $faker->companyEmail;
        $I->click("Sign up for updates");
        $I->waitForElementVisible('Sign up to stay in touch!', $this::TEXT_WAIT_TIMEOUT);
        $I->fillField(['id' => 'updateSignUpForm-email'], $email);

        // element might be hidden in the dom, detach and move it so it's visible for clicking
        \Helper\Acceptance::loadJQuery($I, 'ele = $("#updateSignUpForm-participate").detach(); $("#app-update-sign-up-form").prepend(ele);');
        $I->wait(2);
        $I->checkOption(['id' => 'updateSignUpForm-participate']);
        \Helper\Acceptance::loadJQuery(
            $I,
            "var glForm = $('form.doGeoLocate');
            glForm.prepend('<input type=\"hidden\" name=\"geo_lat\" value=\"' + ". $faker->latitude() ." + '\" />');
            glForm.prepend('<input type=\"hidden\" name=\"geo_long\" value=\"' + ". $faker->longitude() ." + '\" />');"
        );
        $I->wait(self::DEFAULT_WAIT);
        $I->canSeeElementInDOM(['name' => 'geo_lat']);
        $latitude = $I->grabValueFrom(['name' => 'geo_lat']);
        $I->canSeeElementInDOM(['name' => 'geo_long']);
        $longitude = $I->grabValueFrom(['name' => 'geo_long']);
        $I->click(['id' => 'updateSignUpFormSubmit']);
        $I->wait(self::DEFAULT_WAIT);
        $I->see('Thanks for signing up!');
        $I->seeInDatabase('update_recipients', array('email' => $email, 'participate' => 1, 'geo_lat' => $latitude, 'geo_long' => $longitude));
    }


}
