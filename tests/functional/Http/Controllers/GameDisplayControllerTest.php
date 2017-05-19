<?php

use App\Http\Controllers\GameDisplay\GameDisplayController;
use Codeception\Util\Fixtures;
class GameDisplayControllerTest extends \Codeception\Test\Unit
{
    /**
     * @var \FunctionalTester
     */
    protected $tester;
    protected $controller;

    protected function _before()
    {
        $this->controller = new GameDisplayController();
    }

    protected function _after()
    {
    }

    // tests
    public function testSetTeam()
    {
        $TestArray = Fixtures::get('TeamTestArray');
        $TeamName = $TestArray['TeamName'];
        $TournamentName = $TestArray['TournamentName'];
        $this->controller->setTeam($TeamName, $TournamentName);
    }
}