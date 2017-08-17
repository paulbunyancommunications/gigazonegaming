<?php
namespace Tests\Acceptance;

use \AcceptanceTester;
use Pbc\Bandolier\Type\Numbers;
class LolTeamSignUpCest extends \BaseAcceptance
{
    const DEFAULT_WAIT = 15;

    public $nameList = ["ChaChing77", "SlySkeever", "DragonDefeater", "CacheMeOuside", "CrackAColdOne", "YouBetterNotComeAtMe"];

    public function _before(AcceptanceTester $I)
    {
        parent::_before($I);
        $tournament = 'gigazone-gaming-2016-league-of-legends';
        $I->amOnPage('/tournament/lol-team-signup/');
        $I->waitForJs('return jQuery.active == 0', $this::TEXT_WAIT_TIMEOUT);
        $I->waitForText('Team SignUp', $this::TEXT_WAIT_TIMEOUT);
        $I->executeJS("$('#hidden').val('$tournament');");
        $I->fillField(['name' => 'tournament'], $tournament);
    }

    public function _after(AcceptanceTester $I)
    {
        parent::_after($I);
    }

    // tests
    public function submitATeamToTheSystemFromForm(AcceptanceTester $I)
    {
        $faker = \Faker\Factory::create();
        $I->wantTo('Submit the LOL team sign up form');
        $I->waitForElementVisible("#app-lol-team-sign-up-form", $this::TEXT_WAIT_TIMEOUT);
        $I->seeElementInDOM(['name' => 'request_token']);
        $I->seeElementInDOM(['name' => 'team-name']);
        $teamName = $faker->company;
        $I->fillField(['name' => 'team-name'], $teamName);
        $teamCaptain = $faker->name;
        $I->fillField(['name' => 'name'], $teamCaptain);
        $teamCaptainLol = $this->nameList[0];
        $I->fillField(['name' => 'team-captain-lol-summoner-name'], $teamCaptainLol);
        $teamCaptainEmail = $faker->email;
        $I->fillField(['name' => 'email'], $teamCaptainEmail);
        $teamCaptainPhone = $faker->phoneNumber;
        $I->fillField(['name' => 'team-captain-phone'], $teamCaptainPhone);
        $teamMembers = [];

        for ($i = 1; $i <= 4; $i++) {
            $teamMembers[$i] = [
                'username' => $this->nameList[$i],
                'email' => $faker->email,
            ];
            $I->fillField(
                ['name' => 'teammate-' . Numbers::toWord($i) . '-email-address'],
                $teamMembers[$i]['email']
            );
            $I->fillField(
                ['name' => 'teammate-' . Numbers::toWord($i) . '-lol-summoner-name'],
                $teamMembers[$i]['username']
            );
        }

        $I->click(['id' => 'doFormSubmit']);
        $I->waitForElement(['id' => 'team-name-response-value'], $this::TEXT_WAIT_TIMEOUT * 1.5);

        $I->see('Thanks for signing up your team to play League of Legends!');
        $I->see($teamName, ['id' => 'team-name-response-value']);
        $I->see($teamCaptain, ['id' => 'name-response-value']);
        $I->see($teamCaptainLol, ['id' => 'team-captain-lol-summoner-name-response-value']);
        $I->see($teamCaptainEmail, ['id' => 'email-response-value']);
        $I->see($teamCaptainPhone, ['id' => 'team-captain-phone-response-value']);
        for ($i = 1; $i <= 4; $i++) {
            $I->see($teamMembers[$i]['username'], ['id' => 'teammate-'.Numbers::toWord($i).'-lol-summoner-name-response-value']);
            $I->see($teamMembers[$i]['email'], ['id' => 'teammate-'.Numbers::toWord($i).'-email-address-response-value']);
        }
    }
}
