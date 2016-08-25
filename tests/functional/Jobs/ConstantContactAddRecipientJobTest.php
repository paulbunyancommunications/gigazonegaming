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

    /**
     * @test
     */
    public function it_returns_true_if_the_message_from_CC_is_that_the_email_already_exists()
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
                'listName' => env('CONSTANT_CONTACT_LIST_NAME', 'Update List'),
                'email' => $email,
                'name' => $name
            ]
        );
        // run handle
        $job->handle();

        // Do the Constant Contact recipient job again
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
        $this->assertTrue($job->handle());

    }

    /**
     * Test that a single name, first name, will return
     * as the first name and the last name will be blank
     *
     * @test
     */
    public function it_returns_an_empty_last_name_if_no_space_is_found_in_name()
    {
        $faker = Factory::create();
        $name = $faker->firstName;
        $email = $faker->email;
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
        $handle = $job->handle();
        $this->assertSame($handle->first_name, $name);
        $this->assertEmpty($handle->last_name);
    }

    /**
     * Test that when the api key is named 'CONSTANT_CONTACT_API_KEY' and
     * not the actual value that the job script will go to config to
     * look for the value.
     *
     * @test
     */
    public function getApiKey_returns_from_the_config_if_set_to_env_name()
    {
        $faker = Factory::create();
        $name = $faker->firstName;
        $email = $faker->email;
        // initialize the Constant Contact recipient job
        $job = new ConstantContactAddRecipientJob(
            [
                'apiKey' => 'CONSTANT_CONTACT_API_KEY',
                'apiSecret' => env('CONSTANT_CONTACT_API_SECRET', 'CONSTANT_CONTACT_API_SECRET'),
                'apiToken' => env('CONSTANT_CONTACT_API_TOKEN', 'CONSTANT_CONTACT_API_TOKEN'),
                'listName' => env('CONSTANT_CONTACT_LIST_NAME', 'Update List'),
                'email' => $email,
                'name' => $name
            ]
        );
        $this->assertSame($job->getApiKey(), config('constant_contact.api_key'));
    }

    /**
     * Test that when the api token is named 'CONSTANT_CONTACT_API_TOKEN' and
     * not the actual value that the job script will go to config to
     * look for the value.
     *
     * @test
     */
    public function getApiToken_returns_from_the_config_if_set_to_env_name()
    {
        $faker = Factory::create();
        $name = $faker->firstName;
        $email = $faker->email;
        // initialize the Constant Contact recipient job
        $job = new ConstantContactAddRecipientJob(
            [
                'apiKey' => env('CONSTANT_CONTACT_API_KEY', 'CONSTANT_CONTACT_API_KEY'),
                'apiSecret' => env('CONSTANT_CONTACT_API_SECRET', 'CONSTANT_CONTACT_API_SECRET'),
                'apiToken' => 'CONSTANT_CONTACT_API_TOKEN',
                'listName' => env('CONSTANT_CONTACT_LIST_NAME', 'Update List'),
                'email' => $email,
                'name' => $name
            ]
        );
        $this->assertSame($job->getApiToken(), config('constant_contact.api_token'));
    }

    /**
     * Test that when the api secret is named 'CONSTANT_CONTACT_API_SECRET' and
     * not the actual value that the job script will go to config to
     * look for the value.
     *
     * @test
     */
    public function getApiSecret_returns_from_the_config_if_set_to_env_name()
    {
        $faker = Factory::create();
        $name = $faker->firstName;
        $email = $faker->email;
        // initialize the Constant Contact recipient job
        $job = new ConstantContactAddRecipientJob(
            [
                'apiKey' => env('CONSTANT_CONTACT_API_KEY', 'CONSTANT_CONTACT_API_KEY'),
                'apiSecret' => 'CONSTANT_CONTACT_API_SECRET',
                'apiToken' => env('CONSTANT_CONTACT_API_TOKEN', 'CONSTANT_CONTACT_API_TOKEN'),
                'listName' => env('CONSTANT_CONTACT_LIST_NAME', 'Update List'),
                'email' => $email,
                'name' => $name
            ]
        );
        $this->assertSame($job->getApiSecret(), config('constant_contact.api_secret'));
    }

    /**
     * Test that when the api secret set in the constructor
     * the getApiSecret method should return a string
     *
     * @test
     */
    public function getApiSecret_returns_string()
    {
        $faker = Factory::create();
        $name = $faker->firstName;
        $email = $faker->email;
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
        $this->assertSame($job->getApiSecret(), env('CONSTANT_CONTACT_API_SECRET', 'CONSTANT_CONTACT_API_SECRET'));
    }

    /**
     * Test that when the name set in the constructor
     * the getName method should return a string
     *
     * @test
     */
    public function getName_returns_string()
    {
        $faker = Factory::create();
        $name = $faker->firstName;
        $email = $faker->email;
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
        $this->assertSame($job->getName(), $name);
    }

    /**
     * Test that when the list name is named 'CONSTANT_CONTACT_LIST_NAME' and
     * not the actual value that the job script will go to config to
     * look for the value.
     *
     * @test
     */
    public function getListName_returns_from_the_config_if_set_to_env_name()
    {
        $faker = Factory::create();
        $name = $faker->firstName;
        $email = $faker->email;
        // initialize the Constant Contact recipient job
        $job = new ConstantContactAddRecipientJob(
            [
                'apiKey' => env('CONSTANT_CONTACT_API_KEY', 'CONSTANT_CONTACT_API_KEY'),
                'apiSecret' => env('CONSTANT_CONTACT_API_SECRET', 'CONSTANT_CONTACT_API_SECRET'),
                'apiToken' => env('CONSTANT_CONTACT_API_TOKEN', 'CONSTANT_CONTACT_API_TOKEN'),
                'listName' => 'CONSTANT_CONTACT_LIST_NAME',
                'email' => $email,
                'name' => $name
            ]
        );
        $this->assertSame($job->getListName(), config('constant_contact.list_name'));
    }
}
