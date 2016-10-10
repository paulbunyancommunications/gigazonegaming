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
            'team_id' => 'required:mysql_champ.players,team_id',
        ], $mock->rules());

    }

    /**
     * @test
     */
    public function it_returns_an_array_of_rules_on_put_method()
    {
        $faker = \Faker\Factory::create();
        $mock = Mockery::mock('App\\Http\\Requests\\PlayerRequest[method,route]');
        $mock->shouldReceive('method')->once()->andReturn('PUT');
        $name = $faker->username;
        $teamId = $faker->numberBetween(1, 99);
        $email = $faker->email;
        $mock->shouldReceive('route')->twice()->andReturn((object)[
            'player_id' => (object)[
                'username' => $name,
                'team_id' => $teamId,
                'email' => $email
            ]
        ]);

        $this->assertSame('required|unique:mysql_champ.players,username,'.$name.',username', $mock->rules()['username']);
        $this->assertSame('required|email|unique:mysql_champ.players,email,'.$email.',email', $mock->rules()['email']);

    }

    /**
     * @test
     */
    public function it_returns_an_array_of_rules_on_patch_method()
    {
        $faker = \Faker\Factory::create();
        $mock = Mockery::mock('App\\Http\\Requests\\PlayerRequest[method,route]');
        $mock->shouldReceive('method')->zeroOrMoreTimes()->andReturn('PATCH');
        $name = $faker->username;
        $teamId = $faker->numberBetween(1, 99);
        $email = $faker->email;
        $mock->shouldReceive('route')->zeroOrMoreTimes()->andReturn((object)[
            'player_id' => (object)[
                'username' => $name,
                'team_id' => $teamId,
                'email' => $email,
            ]
        ]);

        $this->assertSame('required|unique:mysql_champ.players,username,'.$name.',username', $mock->rules()['username']);
        $this->assertSame('required|email|unique:mysql_champ.players,email,'.$email.',email', $mock->rules()['email']);

    }

    /**
     * @test
     */
    public function it_returns_an_array_of_messages()
    {
        $request = new \App\Http\Requests\PlayerRequest();
        $this->assertArrayHasKey('name.required', $request->messages());
        $this->assertArrayHasKey('name.unique', $request->messages());
        $this->assertArrayHasKey('email.required', $request->messages());
        $this->assertArrayHasKey('email.unique', $request->messages());

    }
}
