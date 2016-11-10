<?php
namespace Tests\Functional;

use App\Models\Championship\Game;
use App\Models\Championship\IndividualPlayer;
use App\Models\Championship\Player;
use App\Models\Championship\Team;
use App\Models\Championship\Tournament;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Pbc\Bandolier\Type\Numbers;

/**
 * IndividualLolSignUpTest
 *
 * Created 5/26/16 11:12 AM
 * Functional tests for individual League of Legends players
 *
 * @author Nate Nolting <naten@paulbunyan.net>
 * @package Tests\Functional
 */
class IndividualLolSignUpTest extends \TestCase
{
    use DatabaseTransactions, DatabaseMigrations;

    /**
     *
     */
    public function tearDown()
    {
        parent::tearDown();
        exec('php artisan migrate:refresh');
    }

    /**
     * @test
     */
    public function it_returns_a_success_message_from_lol_individual_player_registration_route()
    {
        $faker = \Faker\Factory::create();
        $parameters = [
            'email' => time() . $faker->email,
            'name' => $faker->name,
            'your-lol-summoner-name' => $faker->userName,
            'your-phone' => $faker->phoneNumber,
            'fields' => ['email', 'name', 'team-name', 'your-lol-summoner-name', 'your-phone']
        ];
        $response = $this->call('POST', '/lol-individual-sign-up', $parameters);
        $this->assertTrue($response->isOk());
        $this->assertJson($response->getContent());
        $decode = json_decode($response->getContent());
        $this->assertObjectHasAttribute('success', $decode);
        $this->assertNotFalse(
            strpos(
                implode(' ', $decode->success),
                'Thanks for signing up to play League of Legends!'
            )
        );

        // now check the db for this player
        $getIndividual = Player::where('email', '=', $parameters['email'])->first();
        \Codeception\Util\Debug::debug($getIndividual);
        $this->assertInstanceOf(Player::class, $getIndividual);

        $this->assertSame($getIndividual->email, $parameters['email']);
        $this->assertSame($getIndividual->name, $parameters['name']);
        $this->assertSame($getIndividual->username, $parameters['your-lol-summoner-name']);
        $this->assertSame($getIndividual->phone, $parameters['your-phone']);

        $this->assertInstanceOf(Game::class, $getIndividual->games[0]);
    }
}
