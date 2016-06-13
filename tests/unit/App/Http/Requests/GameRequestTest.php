<?php
namespace App\Http\Requests;

/**
 * GameRequestTest
 *
 * Created 6/10/16 3:37 PM
 * Tests for the GameRequest class
 *
 * @author Nate Nolting <naten@paulbunyan.net>
 * @package Tests\Functional\Http\Requests;
 */

use Mockery;

/**
 * Mock is_user_logged_in
 * @return mixed
 */
function is_user_logged_in()
{
    return GameRequestTest::$functions->is_user_logged_in();
}

/**
 * Mock is_super_admin
 * @return mixed
 */
function is_super_admin()
{
    return GameRequestTest::$functions->is_super_admin();
}

/**
 * Mock is_user_admin
 * @return mixed
 */
function is_user_admin()
{
    return GameRequestTest::$functions->is_user_admin();
}

/**
 * Class GameRequestTest
 * @package Tests\Functional\Http\Requests
 */
class GameRequestTest extends \TestCase
{
    /**
     * @var \Mockery::mock;
     */
    public static $functions;


    /**
     *
     */
    public function setUp()
    {
        parent::setUp();
        self::$functions = Mockery::mock();
    }

    /**
     * @afterClass
     */
    public static function removeAuthFunctions()
    {
        $list = ['is_user_logged_in', 'is_super_admin', 'is_user_admin'];
        foreach ($list as $l) {
            if (function_exists($l) && function_exists('runkit_function_remove')) {
                runkit_function_remove($l);
            }
        }
    }


    /**
     *
     */
    public function tearDown()
    {
        parent::tearDown();
        Mockery::close();
    }

    /**
     * @test
     */
    public function it_allows_user_if_logged_in_and_super_user()
    {
        $request = new \App\Http\Requests\GameRequest();
        self::$functions->shouldReceive('is_user_logged_in')->once()->andReturn(true);
        self::$functions->shouldReceive('is_super_admin')->once()->andReturn(true);
        $this->assertTrue($request->authorize());
    }

    /**
     * @test
     */
    public function it_allows_user_if_logged_in_and_user_admin()
    {
        $request = new \App\Http\Requests\GameRequest();
        self::$functions->shouldReceive('is_user_logged_in')->once()->andReturn(true);
        self::$functions->shouldReceive('is_super_admin')->once()->andReturn(false);
        self::$functions->shouldReceive('is_user_admin')->once()->andReturn(true);
        $this->assertTrue($request->authorize());
    }

    /**
     * @test
     */
    public function it_does_not_allow_the_user_if_not_logged_in()
    {
        $request = new \App\Http\Requests\GameRequest();
        self::$functions->shouldReceive('is_user_logged_in')->once()->andReturn(false);
        $this->assertFalse($request->authorize());
    }

    /**
     * @test
     */
    public function it_does_not_allow_the_user_if_logged_in_but_not_a_super_admin_or_uer_admin()
    {
        $request = new \App\Http\Requests\GameRequest();
        self::$functions->shouldReceive('is_user_logged_in')->once()->andReturn(true);
        self::$functions->shouldReceive('is_super_admin')->once()->andReturn(false);
        self::$functions->shouldReceive('is_user_admin')->once()->andReturn(false);
        $this->assertFalse($request->authorize());
    }

    /**
     * @test
     */
    public function it_returns_an_empty_array_on_default_method()
    {
        $mock = Mockery::mock('App\\Http\\Requests\\GameRequest[method]');
        $mock->shouldReceive('method')->once()->andReturn('');
        $this->assertEmpty($mock->rules());
    }
    /**
     * @test
     */
    public function it_returns_an_empty_array_on_get_method()
    {
        $mock = Mockery::mock('App\\Http\\Requests\\GameRequest[method]');
        $mock->shouldReceive('method')->once()->andReturn('GET');
        $this->assertEmpty($mock->rules());
    }

    /**
     * @test
     */
    public function it_returns_an_empty_array_on_delete_method()
    {
        $mock = Mockery::mock('App\\Http\\Requests\\GameRequest[method]');
        $mock->shouldReceive('method')->once()->andReturn('DELETE');
        $this->assertEmpty($mock->rules());
    }

    /**
     * @test
     */
    public function it_returns_an_array_of_rules_on_post_method()
    {
        $mock = Mockery::mock('App\\Http\\Requests\\GameRequest[method]');
        $mock->shouldReceive('method')->once()->andReturn('POST');
        $this->assertSame(['name' => 'required', 'uri' => 'required',], $mock->rules());

    }

    /**
     * @test
     */
    public function it_returns_an_array_of_rules_on_put_method()
    {
        $faker = \Faker\Factory::create();
        $mock = Mockery::mock('App\\Http\\Requests\\GameRequest[method,route]');
        $mock->shouldReceive('method')->once()->andReturn('PUT');
        $name = $faker->username;
        $mock->shouldReceive('route')->once()->andReturn((object)['game_id' => (object)['name' => $name]]);
        $this->assertSame(
            [
                'name' => 'required|unique:mysql_champ.games,name,' . $name . ',name',
                'uri' => 'required',
            ],
            $mock->rules()
        );

    }

    /**
     * @test
     */
    public function it_returns_an_array_of_rules_on_patch_method()
    {
        $faker = \Faker\Factory::create();
        $mock = Mockery::mock('App\\Http\\Requests\\GameRequest[method,route]');
        $mock->shouldReceive('method')->once()->andReturn('PATCH');
        $name = $faker->username;
        $mock->shouldReceive('route')->once()->andReturn((object)['game_id' => (object)['name' => $name]]);
        $this->assertSame(
            [
                'name' => 'required|unique:mysql_champ.games,name,' . $name . ',name',
                'uri' => 'required',
            ],
            $mock->rules()
        );

    }

    /**
     * @test
     */
    public function it_returns_an_array_of_messages()
    {
        $request = new \App\Http\Requests\GameRequest();
        $this->assertSame(
            [
                'name.required' => 'Your name is required.',
                'uri.required' => 'The URI is required.',
            ],
            $request->messages()
        );

    }
}
