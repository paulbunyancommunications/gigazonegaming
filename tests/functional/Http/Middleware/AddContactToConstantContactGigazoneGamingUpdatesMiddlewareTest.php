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

use App\Http\Middleware\AddContactToConstantContactGigazoneGamingUpdatesMiddleware as CcMiddleware;
use Ctct\ConstantContact;
use Faker\Factory;
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
    }

    /**
     * @test
     */
    public function it_adds_email_to_default_list()
    {
        $faker = Factory::create();
        $request = new Request();
        $params = ['email' => time() . $faker->email, 'update-recipient' => 'yes'];
        $request->replace($params);
        $middleware = new CcMiddleware();
        $constantContact = new ConstantContact($middleware->getApiKey());
        $handle = $middleware->handle($request, function () {
            return 'I ran the closure';
        });

        $this->assertSame($handle, 'I ran the closure');

        // get the newly created contact from Constant Contact
        $response = $constantContact->contactService->getContacts(
            $middleware->getApiToken(),
            array("email" => $middleware->email($request))
        );

        // check that user is in default list
        $this->assertNotEmpty($response->results);
        $this->assertSame($response->results[0]->email_addresses[0]->email_address, $params['email']);

        // delete test recipient
        $constantContact->contactService->unsubscribeContact($middleware->getApiToken(), $response->results[0]->id);
    }

    /**
     * @test
     */
    public function it_adds_email_to_a_list_if_already_exists()
    {
        $faker = Factory::create();
        $request = new Request();
        $params = ['email' => time() . $faker->email, 'update-recipient' => 'yes'];
        $request->replace($params);
        $middleware = new CcMiddleware();
        $listName = implode(' ', $faker->words(3));
        $middleware->setListName($listName);
        $constantContact = new ConstantContact($middleware->getApiKey());
        $handle = $middleware->handle($request, function () {
            return 'I ran the closure';
        });

        $this->assertSame($handle, 'I ran the closure');

        // get rid of the updates user so the middleware can be run again
        \App\Models\UpdateRecipients::where('email', '=', $params['email'])->delete();

        $listName2 = implode(' ', $faker->words(3));
        $middleware->setListName($listName2);

        $middleware->handle($request, function () {
            return 'I ran the closure again';
        });

        // get the newly created contact from Constant Contact
        $response2 = $constantContact->contactService->getContacts(
            $middleware->getApiToken(),
            array("email" => $middleware->email($request))
        );

        // check that user is in default list
        $this->assertNotEmpty($response2->results);
        $this->assertSame($response2->results[0]->email_addresses[0]->email_address, $params['email']);

        // delete test recipient
        $constantContact->contactService->unsubscribeContact($middleware->getApiToken(), $response2->results[0]->id);
        // delete the two test lists
        $lists = $constantContact->listService->getLists($middleware->getApiToken());
        foreach ($lists as $list) {
            if (in_array($list->name, [$listName, $listName2])) {
                $constantContact->listService->deleteList($middleware->getApiToken(), $list->id);
            }
        }
    }

    /**
     * @test
     */
    public function creating_a_list_returns_null_if_exception_is_thrown_from_constant_contact()
    {
        $middleware = new CcMiddleware();
        $constantContact = new ConstantContact($middleware->getApiKey());
        $middleware->setApiToken(1234567890);
        $r = new \ReflectionMethod('App\\Http\\Middleware\\AddContactToConstantContactGigazoneGamingUpdatesMiddleware',
            'createList');
        $r->setAccessible(true);
        $output = $r->invoke($middleware, $constantContact);
        $this->assertNull($output);
    }

    /**
     * @test
     * @expectedException \Exception
     */
    public function connecting_to_constant_contact_will_throw_error_with_bad_api_key()
    {
        $faker = Factory::create();
        $request = new Request();
        $params = ['email' => time() . $faker->email, 'update-recipient' => 'yes'];
        $request->replace($params);
        $middleware = new CcMiddleware();
        $middleware->setApiKey(1234567890);
        $middleware->handle($request, function () {
            return 'I ran the closure';
        });
    }

    /**
     * @test
     */
    public function it_will_return_the_list_name()
    {
        $faker = Factory::create();
        $listName = implode('-', $faker->words(3));
        $middleware = new CcMiddleware();
        $middleware->setListName($listName);
        $this->assertSame($listName, $middleware->getListName());
    }

    /**
     * @test
     */
    public function it_will_return_the_api_secret()
    {
        $faker = Factory::create();
        $secret = implode('-', $faker->words(3));
        $middleware = new CcMiddleware();
        $middleware->setApiSecret($secret);
        $this->assertSame($secret, $middleware->getApiSecret());
    }

    /**
     * @test
     */
    public function it_will_return_env_the_api_secret_if_not_set_already()
    {
        $middleware = new CcMiddleware();
        $secret = env('CONSTANT_CONTACT_API_SECRET', 'CONSTANT_CONTACT_API_SECRET');
        $this->assertSame($secret, $middleware->getApiSecret());
    }


    /**
     * @test
     */
    public function it_will_return_the_api_key()
    {
        $faker = Factory::create();
        $key = implode('-', $faker->words(3));
        $middleware = new CcMiddleware();
        $middleware->setApiKey($key);
        $this->assertSame($key, $middleware->getApiKey());
    }

    /**
     * @test
     */
    public function it_will_return_env_for_the_api_key_if_not_set_already()
    {
        $middleware = new CcMiddleware();
        $key = env('CONSTANT_CONTACT_API_KEY', 'CONSTANT_CONTACT_API_KEY');
        $this->assertSame($key, $middleware->getApiKey());
    }

    /**
     * @test
     */
    public function it_will_return_the_api_token()
    {
        $faker = Factory::create();
        $token = implode('-', $faker->words(3));
        $middleware = new CcMiddleware();
        $middleware->setApiToken($token);
        $this->assertSame($token, $middleware->getApiToken());
    }

    /**
     * @test
     */
    public function it_will_return_env_for_the_api_token_if_not_set_already()
    {
        $middleware = new CcMiddleware();
        $token = env('CONSTANT_CONTACT_API_TOKEN', 'CONSTANT_CONTACT_API_TOKEN');
        $this->assertSame($token, $middleware->getApiToken());
    }
}
