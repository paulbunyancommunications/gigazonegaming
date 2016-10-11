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
 * Class GameRequestTest
 * @package Tests\Functional\Http\Requests
 */
class GameRequestTest extends WpRequestsBase
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
    public function it_does_not_allow_the_user_if_logged_in_but_not_a_super_admin_or_user_admin()
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
        $this->assertSame([
            'name' => 'required|unique:mysql_champ.games,name',
            'title' => 'required|unique:mysql_champ.games,title',
            'uri' => 'required|url',
        ], $mock->rules());

    }

    /**
     * @test
     */
    public function it_returns_an_array_of_rules_on_put_method()
    {
        $faker = \Faker\Factory::create();
        $mock = Mockery::mock('App\\Http\\Requests\\GameRequest[method,route]');
        $mock->shouldReceive('method')->zeroOrMoreTimes()->andReturn('PUT');
        $name = $faker->username;
        $title = $faker->sentence;
        $url = $faker->url;

        $mock->shouldReceive('route')->zeroOrMoreTimes()->andReturn((object)[
            'game_id' => (object)['name' => $name, 'title' => $title, 'uri' => $url]]
        );
        $this->assertSame($mock->rules()['name'], 'required|unique:mysql_champ.games,name,'.$name.',name');
        $this->assertSame($mock->rules()['title'], 'required|unique:mysql_champ.games,title,'.$title.',title');
        $this->assertSame($mock->rules()['uri'], 'required|url');
    }

    /**
     * @test
     */
    public function it_returns_an_array_of_rules_on_patch_method()
    {
        $faker = \Faker\Factory::create();
        $mock = Mockery::mock('App\\Http\\Requests\\GameRequest[method,route]');
        $mock->shouldReceive('method')->zeroOrMoreTimes()->andReturn('PATCH');
        $name = $faker->username;
        $title = $faker->sentence;
        $mock->shouldReceive('route')->zeroOrMoreTimes()->andReturn((object)['game_id' => (object)['name' => $name, 'title' => $title]]);
        $this->assertSame(
            [
                'name' => 'required|unique:mysql_champ.games,name,' . $name . ',name',
                'title' => 'required|unique:mysql_champ.games,title,'. $title .',title',
                'uri' => 'required|url',
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
                'name.required' => 'The game :attribute is required.',
                'name.unique' => 'The game :attribute is is already being used.',
                'title.required' => 'The game :attribute is required.',
                'title.unique' => 'The game :attribute is already being used.',
                'uri.required' => 'The game :attribute is required.',
                'uri.url' => 'The game :attribute must be a valid URL.',
            ],
            $request->messages()
        );

    }
}
