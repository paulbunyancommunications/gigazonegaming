<?php
/**
 * ConstantContactAddRecipientJobTest
 *
 * Created 8/8/16 10:20 AM
 * Tests for the ConstantContactAddRecipientJob Job class
 *
 * @author Nate Nolting <naten@paulbunyan.net>
 * @package Tests\Functional\Jobs
 */

namespace Tests\Functional\Jobs;

use App\Jobs\ConstantContactAddRecipientJob;
use Ctct\ConstantContact;
use Faker\Factory;

class ConstantContactAddRecipientJobTest extends \TestCase
{

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

    public function testHandle()
    {
        $faker = Factory::create();
        $name = $faker->firstName . ' ' . $faker->lastName;
        $email = time() . $faker->email;

        // initialize the Constant Contact recipient job
        $job = new ConstantContactAddRecipientJob(
            [
                'apiKey' => env('CONSTANT_CONTACT_API_KEY', 'CONSTANT_CONTACT_API_KEY'),
                'apiSecret' => env('CONSTANT_CONTACT_API_SECRET', 'CONSTANT_CONTACT_API_SECRET'),
                'apiToken' => env('CONSTANT_CONTACT_API_TOKEN', 'CONSTANT_CONTACT_API_TOKEN'),
                'listName' => env('CONSTANT_CONTACT_LIST_NAME', 'Update List'),
                'email' => $email,
                'name' => $name
            ]
        );
        // run handle
        $job->handle();

        // pause so let the job finish and send over to Constant Contact
        sleep(intval(config('form_mail.delay.send_message')) * 2);

        // get constant contact connection and check that this email has been added
        $connection = new ConstantContact($job->getApiKey());
        $response = $connection->contactService->getContacts($job->getApiToken(), array("email" => $email));
        $this->assertNotEmpty($response->results);
    }

    /**
     * Check that an invalid email address will throw and exception in handle
     *
     * @expectedException     \App\Exceptions\ConstantContactAddRecipientJobException
     * @expectedExceptionMessage The email must be a valid email address to add recipient.
     */
    public function testHandleThrowsExceptionIfInvalidEmailAddress()
    {
        $faker = Factory::create();
        $name = $faker->firstName . ' ' . $faker->lastName;
        $email = $faker->sentence;

        // initialize the Constant Contact recipient job
        $job = new ConstantContactAddRecipientJob(
            [
                'apiKey' => env('CONSTANT_CONTACT_API_KEY', 'CONSTANT_CONTACT_API_KEY'),
                'apiSecret' => env('CONSTANT_CONTACT_API_SECRET', 'CONSTANT_CONTACT_API_SECRET'),
                'apiToken' => env('CONSTANT_CONTACT_API_TOKEN', 'CONSTANT_CONTACT_API_TOKEN'),
                'listName' => env('CONSTANT_CONTACT_LIST_NAME', 'Update List'),
                'email' => $email,
                'name' => $name
            ]
        );
        // run handle
        $job->handle();
    }

    /**
     * Check that an invalid list will throw and exception in handle
     *
     * @expectedException     \App\Exceptions\ConstantContactAddRecipientJobException
     * @expectedExceptionMessage    List "not a valid list" does not exist.
     */
    public function testHandleThrowsExceptionIfInvalidList()
    {
        $faker = Factory::create();
        $name = $faker->firstName . ' ' . $faker->lastName;
        $email = $faker->email;

        // initialize the Constant Contact recipient job
        $job = new ConstantContactAddRecipientJob(
            [
                'apiKey' => env('CONSTANT_CONTACT_API_KEY', 'CONSTANT_CONTACT_API_KEY'),
                'apiSecret' => env('CONSTANT_CONTACT_API_SECRET', 'CONSTANT_CONTACT_API_SECRET'),
                'apiToken' => env('CONSTANT_CONTACT_API_TOKEN', 'CONSTANT_CONTACT_API_TOKEN'),
                'listName' => 'not a valid list',
                'email' => $email,
                'name' => $name
            ]
        );
        // run handle
        $job->handle();
    }

    /**
     * Check that an API key will throw an exception in handle
     *
     * @expectedException     \App\Exceptions\ConstantContactAddRecipientJobException
     * @expectedExceptionMessage     Forbidden
     */
    public function testHandleThrowsExceptionIfInvalidApiKey()
    {
        $faker = Factory::create();
        $name = $faker->firstName . ' ' . $faker->lastName;
        $email = $faker->email;

        // initialize the Constant Contact recipient job
        $job = new ConstantContactAddRecipientJob(
            [
                'apiKey' => 'abcd1234',
                'apiSecret' => env('CONSTANT_CONTACT_API_SECRET', 'CONSTANT_CONTACT_API_SECRET'),
                'apiToken' => env('CONSTANT_CONTACT_API_TOKEN', 'CONSTANT_CONTACT_API_TOKEN'),
                'listName' => env('CONSTANT_CONTACT_LIST_NAME', 'Update List'),
                'email' => $email,
                'name' => $name
            ]
        );
        // run handle
        $job->handle();
    }

    /**
     * Check that an invalid Api Token will throw an exception in handle
     *
     * @expectedException     \App\Exceptions\ConstantContactAddRecipientJobException
     * @expectedExceptionMessage     Unauthorized
     */
    public function testHandleThrowsExceptionIfInvalidApiToken()
    {
        $faker = Factory::create();
        $name = $faker->firstName . ' ' . $faker->lastName;
        $email = $faker->email;

        // initialize the Constant Contact recipient job
        $job = new ConstantContactAddRecipientJob(
            [
                'apiKey' => env('CONSTANT_CONTACT_API_KEY', 'CONSTANT_CONTACT_API_KEY'),
                'apiSecret' => env('CONSTANT_CONTACT_API_SECRET', 'CONSTANT_CONTACT_API_SECRET'),
                'apiToken' => '1234acbde',
                'listName' => env('CONSTANT_CONTACT_LIST_NAME', 'Update List'),
                'email' => $email,
                'name' => $name
            ]
        );
        // run handle
        $job->handle();
    }
}
