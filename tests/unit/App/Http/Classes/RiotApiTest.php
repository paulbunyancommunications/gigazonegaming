<?php
namespace App\Http\Classes;

use GameDisplay\RiotDisplay\API\Api;

class RiotApiTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {

    }

    protected function _after()
    {
    }

    // tests
    public function testSomeFeature()
    {
        $API = new Api("KingMorpheus2131","1234-56782-12321-1232");
    }
}