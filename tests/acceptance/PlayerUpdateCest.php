<?php
namespace Tests\Acceptance;
use Faker\Factory;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use AcceptanceTester;

class PlayerUpdateCest extends \BaseAcceptance

{
    public function _before(AcceptanceTester $I)
    {
        parent::_before($I);
    }

    public function _after(AcceptanceTester $I)
    {
        parent::_after($I);
        exec('php artisan migrate:refresh');
    }

    public function PlayerUpdate(AcceptanceTester $I)
    {
        $faker = \Faker\Factory::create();
        $name = $faker->userName;
        $email = $faker->email;
        $password = $faker->password;
        $I->wantTo('Register a user,Create a password for that user, login,see their info,Logout');
        $I->amOnPage('/app/player/register');
        $I->fillField('#username',$name);
        $I->fillField('#email',$email);
        $I->click('#register-submit');
        $I->waitForText('Check Your Email!',30,'li');
        $token = $this->GetToken();
        $I->amOnPage('/app/player/createPassword/'.$token);
        $I->fillField('#password',$password);
        $I->fillField('#password_confirmation',$password);
        $I->click('#password-submit');
        $I->waitForText('Please Sign In',30, 'h2');
        $I->see('Password Created! You Can Now Login!','li');
        $I->fillField('#email',$email);
        $I->fillField('#password',$password);
        $I->click('#login-submit');
        $I->waitForText('Player Update',30,'h1');
        $I->seeInField('#email',$email);
        $I->click('#logout');
        $I->waitForText('Successfully Logged Out!',30,'li');
    }

    public function PlayerCantRecover(AcceptanceTester $I)
    {
        $faker = \Faker\Factory::create();
        $email = $faker->email;
        $I->wantTo('Make sure a user cannot recover a password if the user does not exist');
        $I->amOnPage('/app/player/login');
        $I->click("#recover");
        $I->waitForText('Password Recovery',30,'h2');
        $I->fillField('#email',$email);
        $I->click('#reset');
        $I->waitForText('User does not exist!',30,'li');
    }
    public function RegisterChangePassword(AcceptanceTester $I)
    {
        $faker = \Faker\Factory::create();
        $name = $faker->userName;
        $email = $faker->email;
        $password = $faker->password;
        $I->wantTo('Register a user,Create a password for that user,Reset password,User creates a new password');
        $I->amOnPage('/app/player/register');
        $I->fillField('#username',$name);
        $I->fillField('#email',$email);
        $I->click('#register-submit');
        $I->waitForText('Check Your Email!',30,'li');
        $token = $this->GetToken();
        $I->amOnPage('/app/player/createPassword/'.$token);
        $I->fillField('#password',$password);
        $I->fillField('#password_confirmation',$password);
        $I->click('#password-submit');
        $I->waitForText('Please Sign In',30, 'h2');
        $I->see('Password Created! You Can Now Login!','li');
        $I->click('#recover');
        $I->waitForText('Password Recovery',30,'h2');
        $I->fillField('#email',$email);
        $I->click('#reset');
        $I->waitForText('Check Your Email!',30,'li');
        $token = $this->GetToken();
        $I->amOnPage('/app/player/createPassword/'.$token);
        $I->fillField('#password',$password);
        $I->fillField('#password_confirmation',$password);
        $I->click('#password-submit');
        $I->waitForText('Please Sign In',30, 'h2');
        $I->see('Password Created! You Can Now Login!','li');
    }

    public function NoAccess(AcceptanceTester $I)
    {
        $I->wantTo('Make sure a guest user cannot access the player update page unless they login');
        $I->amOnPage('app/player/playerUpdate');
        $I->waitForText('Authorization Needed',30,'li');
    }

}
