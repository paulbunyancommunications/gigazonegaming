<?php
namespace Tests\Functional;

use App\Models\Auth\Users\User;
use App\Models\Championship\Game;
use App\Models\Championship\IndividualPlayer;
use App\Models\Championship\Player;
use App\Models\Championship\Team;
use App\Models\Championship\Tournament;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Pbc\Bandolier\Type\Numbers;

class PlayerUpdateTest extends \TestCase
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
    public function it_returns_a_success_message_from_register_player_route_makes_sure_player_is_added_to_DB()
    {
        $faker = \Faker\Factory::create();
        $this->withoutMiddleware();
        $email = $faker->email;
        $username = $faker->userName;
        $parameters =['email'=>$email,
            'username'=>$username];
        $this->call('POST', '/player/register',$parameters);
        $this->assertRedirectedTo('/player/register', $with = ['success'=>'Check Your Email!']);

        // now check the db for this player
        $getIndividual = Player::where('email', '=', $parameters['email'])->first();
        $getUser = User::where('email', '=', $parameters['email'])->first();
        \Codeception\Util\Debug::debug($getIndividual);
        \Codeception\Util\Debug::debug($getUser);
        $this->assertInstanceOf(Player::class, $getIndividual);
        $this->assertInstanceOf(User::class, $getUser);
        $this->assertSame($getIndividual->email, $parameters['email']);
        $this->assertSame($getUser->email, $parameters['email']);
    }
}
