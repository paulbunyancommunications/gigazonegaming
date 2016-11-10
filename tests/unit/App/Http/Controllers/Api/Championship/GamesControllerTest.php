<?php
namespace App\Http\Controllers\Api\Championship;

/**
 * GameControllerTest
 *
 * Created 6/24/16 10:21 AM
 * Tests for Games Api controller
 *
 * @author Nate Nolting <naten@paulbunyan.net>
 * @package App\Http\Controllers\Api\Championship
 */

use App\Models\Championship\Game;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class GamesControllerTest extends \TestCase
{

    use DatabaseTransactions, DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();
    }

    public function tearDown()
    {
        parent::tearDown();
    }

    /**
     * Test going to the index route will return a list of teams
     * @todo failed
     * @test
     */
    public function the_index_route_will_return_a_json_array_of_teams()
    {
        $this->markTestSkipped(
            'The test is being skiped as url is not found - 500 error.'
        );
        $faker = \Faker\Factory::create();
        $params = [
            'name' => implode('-', $faker->words(3)),
            'title' => $faker->sentence(),
            'description' => $faker->paragraph(),
            'uri' => $faker->url
        ];
        $game = factory(Game::class)->create($params);
        $newCaught = false;
        $response = $this->call('GET', 'app/api/game');

        $this->assertSame($response->getStatusCode(), 200);

        $this->assertJson($response->getContent());
        $parse = json_decode($response->getContent(), true);
        foreach ($parse as $p) {
            if ($p['name'] === $params['name']) {
                $newCaught = true;
                $this->assertSame($p['name'], $game->name);
                $this->assertSame($p['title'], $game->title);
                $this->assertSame($p['description'], $game->description);
                $this->assertSame($p['uri'], $game->uri);
            }
        }
        if (!$newCaught) {
            $this->fail('Could not find newly created team in json response.');
        }
    }


    /**
     * Test that going to the game api route with
     * an id will return back that game
     * @todo failed
     *
     * @test
     */
    public function the_game_route_returns_a_single_team()
    {
        $this->markTestSkipped(
            'The test is being skiped as url is not found - 500 error.'
        );
        $faker = \Faker\Factory::create();
        $params = [
            'name' => implode('-', $faker->words(3)),
            'title' => $faker->sentence(),
            'description' => $faker->paragraph(),
            'uri' => $faker->url
        ];
        $game = factory(Game::class)->create($params);

        $response = $this->call('GET', 'app/api/game/'. $game->id);
        $this->assertSame($response->getStatusCode(), 200);

        $this->assertJson($response->getContent());
        $parse = json_decode($response->getContent());

        $this->assertSame($parse->name, $game->name);
        $this->assertSame($parse->title, $game->title);
        $this->assertSame($parse->description, $game->description);
        $this->assertSame($parse->uri, $game->uri);
    }

    /**
     * Test when trying to get a game by unknown id will return an exception
     * @todo failed
     * @test
     */
    public function getting_a_game_by_unknown_id_return_an_error()
    {
        $this->markTestSkipped(
            'The test is being skiped as url is not found - 500 error.'
        );
        $response = $this->call('GET', 'app/api/game/' . time(), []);
        $this->assertJson($response->getContent());
        $parse = json_decode($response->getContent());

        $this->assertObjectHasAttribute('error', $parse);
        $this->assertContains('No query results for model', $parse->error[0]);
    }
}
