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
use Ctct\ConstantContact;
use Enqueue\Test\RetryTrait;

/**
 * Class AddContactToConstantContactGigazoneGamingUpdatesMiddlewareTest
 * @package Tests\Unit\App\Http\Middleware
 */
class AddContactToConstantContactGigazoneGamingUpdatesMiddlewareTest extends \TestCase
{

    use DatabaseTransactions;

    /**
     * @var \Faker\Generator
     */
    protected $faker;

    /**
     *
     */
    public function setUp()
    {
        parent::setUp();
        $this->faker = \Faker\Factory::create();
    }

    /**
     *
     */
    public function tearDown()
    {
        \DB::table('jobs')->truncate();
        \DB::table('failed_jobs')->truncate();
        parent::tearDown();
        \Mockery::close();
    }

    /**
     * @test
     * @group ConstantContact
     * @retry 5
     */
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

    /**
     * Test that submitting to the job queue will show job submitted after an allotted time
     *
     * @test
     * @group ConstantContact
     * @retry 5
     */
    public function testThatSubmittingToTheJobQueueWillShowJobSubmittedAfterAnAllottedTime()
    {
        $middleware = new AddContactToConstantContactGigazoneGamingUpdatesMiddleware();
        $request = new Request();
        $params = ['email' => time() . $this->faker->email, 'update-recipient' => 'yes', 'name' => $this->faker->firstName . ' '. $this->faker->lastName];
        $request->replace($params);

        $middleware->handle($request, function () {
            return 'I ran the closure';
        });
        $sleep = config('form_mail.delay.send_message', 10);
        sleep(floor($sleep * 1.5));
        exec("php artisan queue:work");
        sleep(5);
        // I don't see these in the jobs table as they should have been pushed off by now.
        $queueEmail = \DB::table('jobs')->where('payload', 'LIKE', '%'.$params['email'].'%')->first();
        $queueName = \DB::table('jobs')->where('payload', 'LIKE', '%'.$params['name'].'%')->first();
        $this->assertNull($queueEmail);
        $this->assertNull($queueName);

    }

    /**
     * Test that the job that was supposed to send the address to Constant Contact didn't end up in the failed_jobs table
     * @test
     * @group ConstantContact
     * @retry 5
    */

    public function testThatTheJobThatWasSupposedToSendTheAddressToConstantContactDidnTEndUpInTheFailedJobsTable()
    {
      $middleware = new AddContactToConstantContactGigazoneGamingUpdatesMiddleware();
      $request = new Request();
      $params = ['email' => time() . $this->faker->email, 'update-recipient' => 'yes', 'name' => $this->faker->firstName . ' '. $this->faker->lastName];
      $request->replace($params);

      $middleware->handle($request, function () {
          return 'I ran the closure';
      });
      $sleep = config('form_mail.delay.send_message', 10);
      sleep(floor($sleep * 1.5));
      exec("php artisan queue:work");
      sleep(5);
      // I don't see anything int he failed jobs table
      $queueFailed = \DB::table('failed_jobs');
      $this->assertSame($queueFailed->count(), 0);
    }

    /**
     * Test that the contact ended up on the Constant Contact side
     * @test
     * @group ConstantContact
     * @group ConstantContactEndPoint
     * @retry 5
     */
    public function testThatTheContactEndedUpOnTheConstantContactSide()
    {
      $middleware = new AddContactToConstantContactGigazoneGamingUpdatesMiddleware();
      $request = new Request();
      $params = ['email' => time() . $this->faker->email, 'update-recipient' => 'yes', 'name' => $this->faker->firstName . ' '. $this->faker->lastName];
      $request->replace($params);

      $middleware->handle($request, function () {
          return 'I ran the closure';
      });
      $sleep = config('form_mail.delay.send_message', 10);
      sleep(floor($sleep * 1.5));
      exec("php artisan queue:work");
      // give the constant contact api some time to get the contact in place.
      sleep(20);

      // now get the contacts and make sure that the submitted person ended up in the contact contact list
        $ccConfig= [
            'apiKey' => config('constant_contact.api_key'),
            'apiToken' => config('constant_contact.api_token'),
            'apiSecret' => config('constant_contact.api_secret'),
            'listName' => config('constant_contact.list_name'),
        ];
        $connection = new ConstantContact($ccConfig['apiKey']);
        $contacts = $connection->contactService->getContacts($ccConfig['apiToken'], ['email' => $params['email']])->results;
        // Did the contact list return an array?
        $this->assertTrue(is_array($contacts));
        // Was the returned contact list empty? It shouldn't be if this contact ended up over on CC.
        $this->assertNotEmpty($contacts);
        $found = false;
        //(new \Illuminate\Support\Debug\Dumper)->dump($params);
        for($i=0, $iCount=count($contacts); $i < $iCount; $i++) {
            for($c=0, $cCount=count($contacts[$i]->email_addresses); $c < $cCount; $c++) {
                //(new \Illuminate\Support\Debug\Dumper)->dump($contacts[$i]->email_addresses[$c]->email_address);
                if($contacts[$i]->email_addresses[$c]->email_address == $params['email']) {
                    $found = true;
                    break;
                }
            }
        }
        // Was the submitted address in the CC list?
        $this->assertTrue($found);
    }
}
