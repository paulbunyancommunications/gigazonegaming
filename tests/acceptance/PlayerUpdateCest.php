<?php
namespace Tests\Acceptance;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use AcceptanceTester;

class PlayerUpdateCest extends \BaseAcceptance
{
    /**
     *
     * @return void
     */
    public function _before(AcceptanceTester $I)
    {
        parent::_before($I);
    }

    public function _after(AcceptanceTester $I)
    {
        parent::_after($I);
        exec('php artisan migrate:refresh');
    }

#test
#-----------------------------------------------------------------------------
    public function addPlayerToDbSeePlayerInfo(AcceptanceTester $I){
        $faker = \Faker\Factory::create();
        $I->wantTo('See that a player was registered,See Login page and log the user in, See player info on Player Update page,Logout user, see success of Logout');
        $I->amOnPage('/app/player/register');
        $email = $faker->email;
        $I->fillField('#email',$email);
        $I->fillField('#username',$faker->userName);
        $I->fillField('#phone',$faker->phoneNumber);
        $password = $faker->password(6);
        $I->fillField('#password',$password);
        $I->fillField('#password_confirmation',$password);
        $I->click('#register-submit');
        $I->waitForText('Successfully Added! You can now Login.', 30, 'h4');
        $I->click('#Login');
        $I->waitForText('Please Sign In', 30, 'h2');
        $I->fillField('#email',$email);
        $I->fillField('#password',$password);
        $I->click('#login-submit');
        $I->waitForText('Player Update',30,'h1');
        $I->seeInField('#email',$email);
        $I->click('#logout');
        $I->waitForText('Successfully Logged Out!',30,'li');
    }

    public function signInWithInvalidEmail(AcceptanceTester $I){
        $faker = \Faker\Factory::create();
        $I->wantTo('See an Error message if I try to login with an Invalid Email');
        $I->amOnPage('/app/player/login');
        $I->fillField('#email',$faker->email);
        $I->fillField('#password',$faker->password(6));
        $I->click('#login-submit');
        $I->waitForText('The selected email is invalid.',30,'li');
    }

    public function tryToAccessPlayerUpdateWithoutLogin(AcceptanceTester $I){
        $I->wantTo('See that I cannot access Player Update page without credentials');
        $I->amOnPage('/app/player/playerUpdate');
        $I->waitForText('Authorization Needed',30,'li');
    }
}
