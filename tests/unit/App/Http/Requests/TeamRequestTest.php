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

class TeamRequestTest extends WpRequestsBase
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
        $request = new TeamRequest();
        self::$functions->shouldReceive('is_user_logged_in')->once()->andReturn(true);
        self::$functions->shouldReceive('is_super_admin')->once()->andReturn(true);
        $this->assertTrue($request->authorize());
    }

    /**
     * @test
     */
    public function it_allows_user_if_logged_in_and_user_admin()
    {
        $request = new TeamRequest();
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
        $request = new TeamRequest();
        self::$functions->shouldReceive('is_user_logged_in')->once()->andReturn(false);
        $this->assertFalse($request->authorize());
    }

    /**
     * @test
     */
    public function it_does_not_allow_the_user_if_logged_in_but_not_a_super_admin_or_user_admin()
    {
        $request = new TeamRequest();
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
        $mock = Mockery::mock('App\\Http\\Requests\\TeamRequest[method]');
        $mock->shouldReceive('method')->once()->andReturn('');
        $this->assertEmpty($mock->rules());
    }

    /**
     * @test
     */
    public function it_returns_an_empty_array_on_get_method()
    {
        $mock = Mockery::mock('App\\Http\\Requests\\TeamRequest[method]');
        $mock->shouldReceive('method')->once()->andReturn('GET');
        $this->assertEmpty($mock->rules());
    }

    /**
     * @test
     */
    public function it_returns_an_empty_array_on_delete_method()
    {
        $mock = Mockery::mock('App\\Http\\Requests\\TeamRequest[method]');
        $mock->shouldReceive('method')->once()->andReturn('DELETE');
        $this->assertEmpty($mock->rules());
    }

    /**
     * @test
     */
    public function it_returns_an_array_of_rules_on_post_method()
    {
        $mock = Mockery::mock('App\\Http\\Requests\\TeamRequest[method]');
        $mock->shouldReceive('method')->once()->andReturn('POST');
        $this->assertSame([
            'name' => 'required|unique:mysql_champ.teams,name',
            'tournament_id' => 'required|numeric:mysql_champ.tournament,tournament_id',
        ], $mock->rules());

    }

    /**
     * @test
     */
    public function it_returns_an_array_of_rules_on_put_method()
    {
        $faker = \Faker\Factory::create();
        $mock = Mockery::mock('App\\Http\\Requests\\TeamRequest[method,route]');
        $mock->shouldReceive('method')->zeroOrMoreTimes()->andReturn('PUT');
        $name = $faker->username;
        $tournamentId = $faker->numberBetween(1, 99);
        $mock->shouldReceive('route')->zeroOrMoreTimes()->andReturn((object)[
            'team_id' => (object)[
                'name' => $name,
                'tournament_id' => $tournamentId
            ]
        ]);
        $this->assertSame($mock->rules()['name'], 'required|unique:mysql_champ.teams,name,'.$name.',name');
        $this->assertSame($mock->rules()['tournament_id'], 'required|numeric:mysql_champ.tournament,tournament_id'.$tournamentId.',tournament_id');


    }

    /**
     * @test
     */
    public function it_returns_an_array_of_rules_on_patch_method()
    {
        $faker = \Faker\Factory::create();
        $mock = Mockery::mock('App\\Http\\Requests\\TeamRequest[method,route]');
        $mock->shouldReceive('method')->zeroOrMoreTimes()->andReturn('PATCH');
        $name = $faker->username;
        $tournamentId = $faker->numberBetween(1, 99);
        $mock->shouldReceive('route')->zeroOrMoreTimes()->andReturn((object)[
            'team_id' => (object)[
                'name' => $name,
                'tournament_id' => $tournamentId
            ]
        ]);
        $this->assertSame($mock->rules()['name'], 'required|unique:mysql_champ.teams,name,'.$name.',name');
        $this->assertSame($mock->rules()['tournament_id'], 'required|numeric:mysql_champ.tournament,tournament_id'.$tournamentId.',tournament_id');
    }


    /**
     * @test
     */
    public function it_returns_an_array_of_messages()
    {
        $request = new \App\Http\Requests\TeamRequest();
        $this->assertSame($request->messages()['name.required'], 'The Team Name Field is required.');
        $this->assertSame($request->messages()['name.unique'], 'The Team Name is in use, pick a new one.');
        $this->assertSame($request->messages()['tournament_id.required'], 'The Tournament field can not be empty.');
        $this->assertSame($request->messages()['tournament_id.numeric'], 'The Tournament field must be an tournament ID.');

    }
}
