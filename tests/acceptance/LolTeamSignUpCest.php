<?php
namespace Tests\Acceptance;

use \AcceptanceTester;
use Pbc\Bandolier\Type\Numbers;
class LolTeamSignUpCest extends \BaseAcceptance
{
    const DEFAULT_WAIT = 15;
    public $pageAddress = "LOL16_test";
    public $nameList = ["ChaChing77", "SlySkeever", "DragonDefeater", "CacheMeOuside", "CrackAColdOne", "YouBetterNotComeAtMe"];
    public $post = "*REGISTRATION IS OPEN ONLY TO THOSE WHO RESIDE WITHIN THE 218 AREA CODE IN THE STATE OF MINNESOTA*  Proof of residency will be required prior to tournament.

        Participants must be 13 years of age or older.
        
        A team is required to have a team captain who will serve as the team organizer as well as primary contact. To register as a team you must have at least 5 players on your team. Teams are encouraged to find possible alternates to play should a player fall ill or not be able to make a match for some other reason. Should a registered player need to be replaced by an alternate, it will be the team captain's responsibility to notify PBC of the change prior to the match.
        
        [lol-team-sign-up new_line=\",\" delimiter=\"|\", expires=\"\", questions=\"tournament|hidden|gigazone-gaming-2016-league-of-legends,Team Name,update-recipient|hidden|yes,participate|hidden|yes,Team Captain,Team Captain LOL Summoner Name,Team Captain Email Address|email,Team Captain Phone|tel,Teammate One LOL Summoner Name,Teammate One Email Address|email,Teammate Two LOL Summoner Name,Teammate Two Email Address|email,Teammate Three LOL Summoner Name,Teammate Three Email Address|email,Teammate Four LOL Summoner Name,Teammate Four Email Address|email,Alternate One Summoner Name,Alternate One Email Address|email,Alternate Two Summoner Name,Alternate Two Email Address|email,Alternate Three Summoner Name,Alternate Three Email Address|email\" inputs=\"team-captain|name,team-captain-email-address|email\" headings=\"Team Info|team-name,Team Captain|team-captain,Team Members|teammate-one-lol-summoner-name,Team Alternates|alternate-one-summoner-name\"]
        
        Heads up
        
        If you get an error saying your team is already signed up or a summoner name already exists please let us know. Send us your team name and your summoners and we'll get you registered manually. We're working on the issue. Thanks!";
    public function _before(AcceptanceTester $I)
    {
        parent::_before($I);
        $this->loginWithAdminUser($I);
        $I->createAPost($I, $this->pageAddress, $this->post);
        $this->logoutOfWp($I);

        $tournament = 'gigazone-gaming-2016-league-of-legends';
        $I->amOnPage($this->pageAddress);
        $I->waitForJs('return jQuery.active == 0', $this::TEXT_WAIT_TIMEOUT);
        $I->waitForElementVisible("#app-lol-team-sign-up-form", $this::TEXT_WAIT_TIMEOUT);
        $I->executeJS("$('#hidden').val('$this->pageAddress');");
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
