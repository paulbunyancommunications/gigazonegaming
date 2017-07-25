<?php

namespace Tests\Acceptance;

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
    }

#Tests
#-------------------------------------------------------------------------
    public function registerLoginLogoutPlayerUpdate(AcceptanceTester $I)
    {
        $faker = \Faker\Factory::create();
        $email = $faker->email;
        $I->wantTo('Register a user then get their password, log them in, see their profile, then log them out');
        $I->amOnPage('app/player/register');
        $I->fillField('#email',$email);
        $I->click('#register-submit');
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://mailtrap.io/api/v1/inboxes?api_token=122ed35b015da58276e95c8d8cb81fee");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);

        $response = curl_exec($ch);
        curl_close($ch);

        $inbox = json_decode($response);
        $inbox_id = $inbox[0]->id;
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://mailtrap.io/api/v1/inboxes/".$inbox_id."/messages?api_token=122ed35b015da58276e95c8d8cb81fee");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);

        $response = curl_exec($ch);
        curl_close($ch);

        $inboxMessage = json_decode($response);
        $messageID = $inboxMessage[0]->id;
        $messagePassword = $inboxMessage[0]->text_body;

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://mailtrap.io/api/v1/inboxes/".$inbox_id."/messages/".$messageID);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");

        $response = curl_exec($ch);
        curl_close($ch);
        $password = '';
        $I->waitForText('Please Sign In','h2');
        $I->fillField('#email',$email);
        $I->fillField('#password',$password);
        $I->click('#login-submit');
        $I->waitForText('Player Update','h1');
        $I->see($email,'#email');
        $I->click('#logout');
        $I->waitForText('Please Sign In',"h2");
        $I->see('Successfully Logged Out!','li');
    }
}