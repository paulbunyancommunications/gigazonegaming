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
        /*gets the inbox_id from mail trap*/
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://mailtrap.io/api/v1/inboxes?api_token=".env("MAIL_TRAP_API", "122ed35b015da58276e95c8d8cb81fee"));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        $response = curl_exec($ch);
        curl_close($ch);
        $inbox = json_decode($response);
        $inbox_id = $inbox[0]->id;
        /*gets the password from the message sent*/
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://mailtrap.io/api/v1/inboxes/".$inbox_id."/messages?api_token=".env("MAIL_TRAP_API", "122ed35b015da58276e95c8d8cb81fee"));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        $response = curl_exec($ch);
        curl_close($ch);
        $inboxMessage = json_decode($response);
        $messageID = $inboxMessage[0]->id;
        $message = $inboxMessage[0]->html_body;
        $tokenString = explode('/',$message);
        $tokenArray = explode('">',$tokenString[8]);
        $token = $tokenArray[0];
        /*deletes the message sent*/
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://mailtrap.io/api/v1/inboxes/".$inbox_id."/messages/".$messageID);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        $response = curl_exec($ch);
        curl_close($ch);
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
        $I->wantTo('Register a user,Create a password for that user,Reset password,User creates a new password,see their info,Logout');
        $I->amOnPage('/app/player/register');
        $I->fillField('#username',$name);
        $I->fillField('#email',$email);
        $I->click('#register-submit');
        $I->waitForText('Check Your Email!',30,'li');
        /*gets the inbox_id from mail trap*/
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://mailtrap.io/api/v1/inboxes?api_token=".env("MAIL_TRAP_API", "122ed35b015da58276e95c8d8cb81fee"));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        $response = curl_exec($ch);
        curl_close($ch);
        $inbox = json_decode($response);
        $inbox_id = $inbox[0]->id;
        /*gets the password from the message sent*/
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://mailtrap.io/api/v1/inboxes/".$inbox_id."/messages?api_token=".env("MAIL_TRAP_API", "122ed35b015da58276e95c8d8cb81fee"));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        $response = curl_exec($ch);
        curl_close($ch);
        $inboxMessage = json_decode($response);
        $messageID = $inboxMessage[0]->id;
        $message = $inboxMessage[0]->html_body;
        $tokenString = explode('/',$message);
        $tokenArray = explode('">',$tokenString[8]);
        $token = $tokenArray[0];
        /*deletes the message sent*/
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://mailtrap.io/api/v1/inboxes/".$inbox_id."/messages/".$messageID);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        $response = curl_exec($ch);
        curl_close($ch);
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
        /*gets the inbox_id from mail trap*/
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://mailtrap.io/api/v1/inboxes?api_token=".env("MAIL_TRAP_API", "122ed35b015da58276e95c8d8cb81fee"));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        $response = curl_exec($ch);
        curl_close($ch);
        $inbox = json_decode($response);
        $inbox_id = $inbox[0]->id;
        /*gets the password from the message sent*/
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://mailtrap.io/api/v1/inboxes/".$inbox_id."/messages?api_token=".env("MAIL_TRAP_API", "122ed35b015da58276e95c8d8cb81fee"));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        $response = curl_exec($ch);
        curl_close($ch);
        $inboxMessage = json_decode($response);
        $messageID = $inboxMessage[0]->id;
        $message = $inboxMessage[0]->html_body;
        $tokenString = explode('/',$message);
        $tokenArray = explode('">',$tokenString[8]);
        $token = $tokenArray[0];
        /*deletes the message sent*/
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://mailtrap.io/api/v1/inboxes/".$inbox_id."/messages/".$messageID);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        $response = curl_exec($ch);
        curl_close($ch);
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
}
