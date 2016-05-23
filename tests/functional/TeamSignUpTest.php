<?php
namespace Tests\Functional;

use App\Models\Championship\Tournament;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Pbc\Bandolier\Type\Numbers;

class TeamSignUpTest extends \TestCase
{
    use DatabaseTransactions;

    public function tearDown()
    {
        parent::tearDown();
        exec('php artisan migrate:refresh');
    }

    /**
     * @test
     */
    public function it_returns_a_success_message_from_lol_team_registration_route()
    {

        $faker = \Faker\Factory::create();
        $name = implode('-',$faker->words());
        $tournament = factory(Tournament::class)->create(['name' => $name]);
        $parameters = [
            'email' => $faker->email,
            'name' => $faker->name,
            'team-name' => $faker->company,
            'team-captain-lol-summoner-name' => $faker->userName,
            'team-captain-phone' => $faker->phoneNumber,
            'tournament' => $name,
            'fields' => ['email','name','team-name','team-captain-lol-summoner-name','team-captain-phone']
        ];
        for ($i = 1; $i <= 4; $i++) {
            $parameters['teammate-' . Numbers::toWord($i) . '-email-address'] = $faker->email;
            $parameters['teammate-' . Numbers::toWord($i) . '-lol-summoner-name'] = $faker->userName;
        }
        $response = $this->call('POST', '/lol-team-sign-up', $parameters);
        $this->assertTrue($response->isOk());
        $this->assertJson($response->getContent());
        $decode = json_decode($response->getContent());
        $this->assertObjectHasAttribute('success', $decode);
        $this->assertNotFalse(strpos(implode(' ', $decode->success), 'Thanks for signing up your team to play League of Legends!'));

    }

    /*
     * @test
     */
    public function it_returns_a_success_message_from_lol_individual_player_registration_route()
    {
        $faker = \Faker\Factory::create();
        $parameters = [
            'email' => $faker->email,
            'name' => $faker->name,
            'your-lol-summoner-name' => $faker->userName,
            'your-phone' => $faker->phoneNumber,
            'fields' => ['email','name','team-name','your-lol-summoner-name','your-phone']
        ];
        $response = $this->call('POST', '/lol-individual-sign-up', $parameters);
        $this->assertTrue($response->isOk());
        $this->assertJson($response->getContent());
        $decode = json_decode($response->getContent());
        $this->assertObjectHasAttribute('success', $decode);
        $this->assertNotFalse(strpos(implode(' ', $decode->success), 'Thanks for signing up to play League of Legends!'));

    }
}
