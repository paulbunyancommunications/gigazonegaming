<?php
/**
 * ${CLASS_NAME}
 *
 * Created 6/8/16 11:41 AM
 * Description of this file here....
 *
 * @author Nate Nolting <naten@paulbunyan.net>
 * @package Tests\Unit\App\Http\Middleware
 * @subpackage Subpackage
 */

namespace Tests\Unit\App\Http\Middleware;

use App\Http\Middleware\LolIndividualSignUpMiddleware;
use App\Http\Requests\LolIndividualSignUpRequest;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Request;

class LolIndividualSignUpMiddlewareTest extends \TestCase
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
        exec('php artisan migrate:refresh');
    }

    /**
     * If validation fails check that an error array was returned
     * @test
     */
    public function it_returns_a_json_array_of_errors_when_validation_fails()
    {
        $request = new Request();
        $rules = new LolIndividualSignUpRequest();
        $params = [];
        $request->replace($params);
        $middleware = new LolIndividualSignUpMiddleware();

        $handle = $middleware->handle($request, function () {
            return 'I ran the closure';
        });
        // check return
        $this->assertJson($handle->getContent());
        $response = json_decode($handle->getContent());
        $this->assertObjectHasAttribute('error', $response);
        $this->assertTrue(is_array($response->error));
    }

    /**
     * If validation fails check that an game error was returned
     * @test
     */
    public function it_returns_a_json_array_of_errors_when_game_not_found()
    {
        $faker = \Faker\Factory::create();
        $request = new Request();
        $params = [
            'name' => $faker->firstName . ' ' . $faker->lastName,
            'your-lol-summoner-name' => $faker->userName,
            'email' => $faker->email,
            'your-phone' => $faker->phoneNumber
        ];
        $request->replace($params);
        $middleware = new LolIndividualSignUpMiddleware();
        $middleware->setGame(implode(' ', $faker->words(3)));

        $handle = $middleware->handle($request, function () {
            return 'I ran the closure';
        });
        // check return
        $this->assertJson($handle->getContent());
        $response = json_decode($handle->getContent());
        $this->assertObjectHasAttribute('error', $response);
        $this->assertTrue(is_array($response->error));
        $this->assertTrue(in_array('Could not find game "' . $middleware->getGame() . '"', $response->error));
    }

    /**
     * If successful check for call back return
     * @test
     */
    public function it_returns_callback_when_successful()
    {
        $faker = \Faker\Factory::create();
        $request = new Request();
        $params = [
            'name' => $faker->firstName . ' ' . $faker->lastName,
            'your-lol-summoner-name' => $faker->userName,
            'email' => $faker->email,
            'your-phone' => $faker->phoneNumber
        ];
        $request->replace($params);
        $middleware = new LolIndividualSignUpMiddleware();

        $handle = $middleware->handle($request, function () {
            return 'I ran the closure';
        });
        $this->assertSame($handle, 'I ran the closure');
        
    }
    /**
     * If successful check for call back return
     * @test
     */
    public function it_sets_new_row_to_db_when_successful()
    {
        $faker = \Faker\Factory::create();
        $request = new Request();
        $params = [
            'name' => $faker->firstName . ' ' . $faker->lastName,
            'your-lol-summoner-name' => $faker->userName.'-'.time(),
            'email' => $faker->email,
            'your-phone' => $faker->phoneNumber
        ];
        $request->replace($params);
        $middleware = new LolIndividualSignUpMiddleware();

        $middleware->handle($request, function () {
            return 'I ran the closure';
        });
            
        // get the player
        $getPlayer = \App\Models\Championship\IndividualPlayer::where('username', $params['your-lol-summoner-name'])->first();

        $this->assertSame($params['email'], $getPlayer->email);
        $this->assertSame($params['your-lol-summoner-name'], $getPlayer->username);
        $this->assertSame($params['your-phone'], $getPlayer->phone);
        $this->assertSame($params['name'], $getPlayer->name);
        
        
    }

    /**
     * If validation fails check for required validation messages
     * @test
     */
    public function it_returns_a_array_of_errors_messages_when_validation_fails()
    {
        $request = new Request();
        $rules = new LolIndividualSignUpRequest();
        $params = [];
        $request->replace($params);
        $middleware = new LolIndividualSignUpMiddleware();

        $handle = $middleware->handle($request, function () {
            return 'I ran the closure';
        });

        $response = json_decode($handle->getContent());

        // check messages
        foreach ($rules->messages() as $key => $message) {
            if (strpos($key, 'required') !== false) {
                $this->assertTrue(in_array($message, $response->error));
            }
        }
    }

    /**
     * If bad email was passed in request then check for bad email message
     * @test
     */
    public function it_returns_an_error_if_bad_email_is_passed()
    {
        $request = new Request();
        $rules = new LolIndividualSignUpRequest();
        $params = ['email' => '12345'];
        $request->replace($params);
        $middleware = new LolIndividualSignUpMiddleware();

        $handle = $middleware->handle($request, function () {
            return 'I ran the closure';
        });

        $response = json_decode($handle->getContent());

        // check messages
        $this->assertTrue(in_array($rules->messages()['email.email'], $response->error));
    }

    /**
     * Check return from getGame
     * @test
     */
    public function it_returns_name_of_game()
    {
        $faker = \Faker\Factory::create();
        $middleware = new LolIndividualSignUpMiddleware();
        $game = implode(' ', $faker->words());
        $middleware->setGame($game);
        $this->assertSame($game, $middleware->getGame());
    }
    
}
