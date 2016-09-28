<?php
namespace Tests\Acceptance\App\Http\Controllers\Backend\Manage;

use \AcceptanceTester;
use \BaseAcceptance;

class IndividualPlayersControllerCest extends BaseAcceptance
{
    protected $parameters = [];

    /**
     * @param AcceptanceTester $I
     */
    public function _before(AcceptanceTester $I)
    {
        $this->parameters = [];
        parent::_before($I);
        $this->loginWithAdminUser($I);
        $I->amOnPage('/app/manage/game');

    }

    /**
     * @param AcceptanceTester $I
     */
    public function _after(AcceptanceTester $I)
    {
        $this->logoutOfWp($I);
        parent::_after($I);
    }

    // tests
    public function tryToCreateAnIndividualPlayer(AcceptanceTester $I)
    {

        $I->wantTo('Create a new individual player from IndividualPlayersController');
        $I->amOnPage('/app/manage/individualPlayer');
        $I->see('Create a new individual player');

        $this->parameters['name'] = $this->faker->name;
        $I->executeJS('document.getElementById(\'name\').disabled = false;');
        $I->fillField(['id' => 'name'], $this->parameters['name']);

        $this->parameters['username'] = $this->faker->userName;
        $I->executeJS('document.getElementById(\'username\').disabled = false;');
        $I->fillField(['id' => 'username'], $this->parameters['username']);

        $this->parameters['email'] = time().'_'.$this->faker->email;
        $I->executeJS('document.getElementById(\'email\').disabled = false;');
        $I->fillField(['id' => 'email'], $this->parameters['email']);

        $this->parameters['phone'] = time().'_'.$this->faker->phoneNumber;
        $I->executeJS('document.getElementById(\'phone\').disabled = false;');
        $I->fillField(['id' => 'phone'], $this->parameters['phone']);

        $this->destroySelect2($I);
        $I->selectOption(['id' => 'game_sort'], 'unknown');

        $I->executeJS('document.getElementById(\'submit_button\').disabled = false;');
        $I->click(['id' => 'submit_button']);

        $I->see('Individual player created successfully');

    }
}
