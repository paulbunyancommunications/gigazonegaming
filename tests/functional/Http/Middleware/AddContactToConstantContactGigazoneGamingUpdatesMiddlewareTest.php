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

use App\Http\Middleware\AddContactToConstantContactGigazoneGamingUpdatesMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Request;

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
        \Mockery::close();
    }

    public function testHandlePutsJobInDatabase()
    {
        $faker = \Faker\Factory::create();
        $middleware = new AddContactToConstantContactGigazoneGamingUpdatesMiddleware();
        $request = new Request();
        $params = ['email' => time() . $faker->email, 'update-recipient' => 'yes', 'name' => $faker->firstName . ' '. $faker->lastName];
        $request->replace($params);

        $middleware->handle($request, function () {
            return 'I ran the closure';
        });

        // verify that there's an entry in the jobs
        // table for this email to be added
        // to constant contact

        $queueEmail = \DB::table('jobs')->where('payload', 'LIKE', '%'.$params['email'].'%')->first();
        $queueName = \DB::table('jobs')->where('payload', 'LIKE', '%'.$params['name'].'%')->first();
        $this->assertSame($queueEmail->id, $queueName->id);
    }
}
