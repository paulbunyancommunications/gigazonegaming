<?php
/**
 * AddContactToConstantContactGigazoneGamingUpdatesMiddlewareTest
 *
 * Created 6/7/16 10:47 AM
 * Tests for AddContactToConstantContactGigazoneGamingUpdatesMiddleware
 *
 * @author Nate Nolting <naten@paulbunyan.net>
 * @package Tests\Unit\App\Http\Middleware
 */

namespace Tests\Functional\Http\Middleware;

use Illuminate\Foundation\Testing\DatabaseTransactions;


/**
 * Class AddContactToConstantContactGigazoneGamingUpdatesMiddlewareTest
 * @package Tests\Unit\App\Http\Middleware
 */
class AddContactToConstantContactGigazoneGamingUpdatesMiddlewareTest extends \TestCase
{

    use DatabaseTransactions;

    /**
     *
     */
    public function setUp()
    {
        parent::setUp();
    }

    /**
     *
     */
    public function tearDown()
    {
        parent::tearDown();
    }

    public function testHandle()
    {
        /** @todo add test to check that job was added to the queue from middleware */
        $this->markTestSkipped('Skip until job written');

    }
}
