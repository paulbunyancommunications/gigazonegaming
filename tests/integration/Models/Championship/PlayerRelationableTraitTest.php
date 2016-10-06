<?php
/**
 * PlayerRelationTest
 *
 * Created 10/5/16 4:19 PM
 * Testing the Player relationship model
 *
 * @author Nate Nolting <naten@paulbunyan.net>
 * @package Tests\Integration\Models\Championship
 * @subpackage Subpackage
 */

namespace Tests\Integration\Models\Championship;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Faker\Factory;

class PlayerRelationableTraitTest extends \TestCase
{
    use DatabaseTransactions, DatabaseMigrations;

    /**
     *
     */
    public function setUp()
    {
        parent::setUp();
        $this->faker = Factory::create();
        $this->resetEventListeners('App\Models\Championship\PlayerRelation');

    }

    public function tearDown()
    {
        parent::tearDown();
    }




}
