<?php
/**
 * ${CLASS_NAME}
 *
 * Created 6/13/16 12:22 PM
 * Description of this file here....
 *
 * @author Nate Nolting <naten@paulbunyan.net>
 * @package App\Http\Requests
 * @subpackage Subpackage
 */

namespace App\Http\Requests;

use Mockery;

class PlayerRequestTest extends WpRequestsBase
{

    /**
     *
     */
    public function setUp()
    {
        parent::setUp();
        self::$functions = Mockery::mock();
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
        $request = new PlayerRequest();
        self::$functions->shouldReceive('is_user_logged_in')->once()->andReturn(true);
        self::$functions->shouldReceive('is_super_admin')->once()->andReturn(true);
        $this->assertTrue($request->authorize());
    }

    /**
     * @test
     */
    public function it_allows_user_if_logged_in_and_user_admin()
    {
        $request = new PlayerRequest();
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
        $request = new PlayerRequest();
        self::$functions->shouldReceive('is_user_logged_in')->once()->andReturn(false);
        $this->assertFalse($request->authorize());
    }

    /**
     * @test
     */
    public function it_does_not_allow_the_user_if_logged_in_but_not_a_super_admin_or_user_admin()
    {
        $request = new PlayerRequest();
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
        $mock = Mockery::mock('App\\Http\\Requests\\PlayerRequest[method]');
        $mock->shouldReceive('method')->once()->andReturn('');
        $this->assertEmpty($mock->rules());
    }

    /**
     * @test
     */
    public function it_returns_an_empty_array_on_get_method()
    {
        $mock = Mockery::mock('App\\Http\\Requests\\PlayerRequest[method]');
        $mock->shouldReceive('method')->once()->andReturn('GET');
        $this->assertEmpty($mock->rules());
    }

    /**
     * @test
     */
    public function it_returns_an_empty_array_on_delete_method()
    {
        $mock = Mockery::mock('App\\Http\\Requests\\PlayerRequest[method]');
        $mock->shouldReceive('method')->once()->andReturn('DELETE');
        $this->assertEmpty($mock->rules());
    }

    /**
     * @test
     */
    public function it_returns_an_array_of_rules_on_post_method()
    {
        $mock = Mockery::mock('App\\Http\\Requests\\PlayerRequest[method]');
        $mock->shouldReceive('method')->once()->andReturn('POST');
        $this->assertSame([
            'username' => 'required|unique:mysql_champ.players,username',
            'email' => 'required|email|unique:mysql_champ.players,email',
            'phone' => 'phone:US',
        ], $mock->rules());
    }

    /**
     * @test
     */
    public function it_returns_an_array_of_rules_on_put_method()
    {
        $faker = \Faker\Factory::create();
        $mock = Mockery::mock('App\\Http\\Requests\\PlayerRequest[method,route]');
        $mock->shouldReceive('method')->zeroOrMoreTimes()->andReturn('PUT');
        $username = $faker->username;
        $phone = "(218)-444-1234";
        $email = $faker->email;
        $mock->shouldReceive('route')->zeroOrMoreTimes()->andReturn((object)[
            'player_id' => (object)[
                'email' => $email,
                'username' => $username,
                'phone' => $phone
            ]
        ]);

        $this->assertSame('required|unique:mysql_champ.players,username,'.$username.',username', $mock->rules()['username']);
        $this->assertSame('required|email|unique:mysql_champ.players,email,'.$email.',email', $mock->rules()['email']);
        $this->assertSame('phone:US', $mock->rules()['phone']);
    }

    /**
     * @test
     */
    public function it_returns_an_array_of_rules_on_patch_method()
    {
        $faker = \Faker\Factory::create();
        $mock = Mockery::mock('App\\Http\\Requests\\PlayerRequest[method,route]');
        $mock->shouldReceive('method')->zeroOrMoreTimes()->andReturn('PATCH');
        $username = $faker->username;
        $phone = "(218)-444-1234";
        $email = $faker->email;
        $mock->shouldReceive('route')->zeroOrMoreTimes()->andReturn((object)[
            'player_id' => (object)[
                'email' => $email,
                'username' => $username,
                'phone' => $phone
            ]
        ]);

        $this->assertSame('required|unique:mysql_champ.players,username,'.$username.',username', $mock->rules()['username']);
        $this->assertSame('required|email|unique:mysql_champ.players,email,'.$email.',email', $mock->rules()['email']);
        $this->assertSame('phone:US', $mock->rules()['phone']);
    }

    /**
     * @test
     */
    public function it_returns_an_array_of_messages()
    {
        $request = new \App\Http\Requests\PlayerRequest();
        $this->assertArrayHasKey('username.required', $request->messages());
        $this->assertArrayHasKey('username.unique', $request->messages());
        $this->assertArrayHasKey('email.required', $request->messages());
        $this->assertArrayHasKey('email.unique', $request->messages());
        $this->assertArrayHasKey('phone.phone', $request->messages());
    }
}
