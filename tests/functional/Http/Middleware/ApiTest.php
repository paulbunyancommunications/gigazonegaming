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
use App\Http\Middleware\Api;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Request;

/**
 * Class AddContactToConstantContactGigazoneGamingUpdatesMiddlewareTest
 * @package Tests\Unit\App\Http\Middleware
 */
class ApiTest extends \TestCase
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

    public function testCheckIfResponseIsOkIfIpAddressIsInFile()
    {
//        $okSites = [
//            '209.191.200.242', //pbnet.pbndev.net
//            '209.191.199.89', //paulbunyan.net
//            '209.191.200.106', //gigazonegaming
//            '192.168.56.1' //testing if you need another for testing, add a comma and add an extra string
//        ];
//        foreach ($okSites as $site) {
//            putenv('REMOTE_ADDR='.$site);
//            $middleware = new Api();
//            $request = new Request();
//
//            $params = [
//                'REMOTE_ADDR' => $site
//            ];
//            $_SERVER->set_remote_addr($params);
//            $request->replace($params);
//            dd($_SERVER);
//            $handle = $middleware->handle($request, function () {
//                return 'I ran the closure';
//            });
//            // check return
//            $this->assertJson($handle->getContent());
//            $response = json_decode($handle->getContent());
//            $this->assertObjectNotHasAttribute('false', $response);
//        }
//
//
    }
}
