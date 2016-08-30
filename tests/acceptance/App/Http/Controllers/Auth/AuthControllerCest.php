<?php
namespace Tests\Acceptance\App\Http\Controllers;

use \AcceptanceTester;
use \BaseAcceptanceCest;

class AuthControllerCest extends BaseAcceptanceCest
{

    public function _before(AcceptanceTester $I)
    {

    }

    public function _after(AcceptanceTester $I)
    {
        $I->runMigration($I);
    }

    /**
     * Check that without logged in state I see a message to login first
     * @param AcceptanceTester $I
     */
    public function the_auth_route_shows_me_a_message_if_not_logged_in(AcceptanceTester $I)
    {

        $I->wantTo('Check that without logged in state I see a message to login first');
        $I->amOnPage('/app/auth');
        $I->waitForText('You must login first.', BaseAcceptanceCest::TEXT_WAIT_TIMEOUT);
        $I->amOnPage('/app/auth/');
        $I->waitForText('You must login first.', BaseAcceptanceCest::TEXT_WAIT_TIMEOUT);
    }

    /**
     * Check that a user can log into the app from the login page
     *
     * @param AcceptanceTester $I
     */
    public function i_am_able_to_login_to_the_app_from_login_page(AcceptanceTester $I)
    {

        $I->wantTo('Check that a user can log into the app from the login page');
        $I->amOnPage('/app/auth/login');
        $I->see('Log In', ['id' => "page-title"]);
        $I->fillField(['id' => 'email'], '');
        $I->fillField(['id' => 'password'], '');
        $I->click(['id' => 'doSubmit']);
        $I->see('Dashboard', ['id' => "page-title"]);
        $I->see('Logged in successfully!');
    }

    /**
     * Check that if I send the form without the email field that I was get an error back
     */
    public function i_get_an_error_if_email_field_is_missing(AcceptanceTester $I)
    {
        $I->wantTo('Check that if I send the form without the email field that I was get an error back');
        $I->amOnPage('/app/auth/login');
        $I->click(['id' => 'doSubmit']);
        $I->waitForElementVisible(['id' => 'message-container'], BaseAcceptanceCest::TEXT_WAIT_TIMEOUT);
        $I->see('The email field is required.');
    }
    /**
     * Check that if I send the form without the password field that I was get an error back
     */
    public function i_get_an_error_if_password_field_is_missing(AcceptanceTester $I)
    {
        $I->wantTo('Check that if I send the form without the email field that I was get an error back');
        $I->amOnPage('/app/auth/login');
        $I->click(['id' => 'doSubmit']);
        $I->waitForElementVisible(['id' => 'message-container'], BaseAcceptanceCest::TEXT_WAIT_TIMEOUT);
        $I->see('The password field is required.');
    }
}
