<?php
namespace Tests\Functional;

use App\Models\Championship\Game;
use App\Models\Championship\IndividualPlayer;
use App\Models\Championship\Player;
use App\Models\Championship\Team;
use App\Models\Championship\Tournament;
use App\Models\UpdateRecipients;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Pbc\Bandolier\Type\Numbers;

/**
 * Class TeamSignUpTest
 * @package Tests\Functional
 */
class TeamLolSignUpTest extends \TestCase
{
    #use DatabaseTransactions;

    /**
     *
     */
    public function tearDown()
    {
        parent::tearDown();
        #exec('php artisan migrate:refresh');
    }

    /**
     * @test
     */
    public function it_returns_a_success_message_from_lol_team_registration_route()
    {

        $parameters = $this->teamParameters();
        $response = $this->call('POST', '/lol-team-sign-up', $parameters);
        $this->assertTrue($response->isOk());
        $this->assertJson($response->getContent());
        $decode = json_decode($response->getContent());
        $this->assertObjectHasAttribute('success', $decode);
        $this->assertNotFalse(
            strpos(implode(' ', $decode->success), 'Thanks for signing up your team to play League of Legends!')
        );

        $getTeam = \App\Models\Championship\Team::where('name', $parameters['team-name'])->first();
        $this->assertInstanceOf(Team::class, $getTeam);
        //assert the team name passed is the same as the name on the team object
        $this->assertSame($getTeam->name, $parameters['team-name']);

        // get players and assert that they are all posted, first one should be the captain
        $getPlayers = $getTeam->players()->get();

        $this->assertInstanceOf(Player::class, $getPlayers[0]);
        $this->assertSame($getPlayers[0]->name, $parameters['name']);
        $this->assertSame($getPlayers[0]->email, $parameters['email']);
        $this->assertSame($getPlayers[0]->phone, $parameters['team-captain-phone']);
        $this->assertSame($getPlayers[0]->username, $parameters['team-captain-lol-summoner-name']);

        // assert captain was set
        $this->assertSame($getTeam->captain, $getPlayers[0]->id);

        // assert all the other players were set for this team
        for ($i = 1; $i <= 4; $i++) {
            $this->assertInstanceOf(Player::class, $getPlayers[$i]);
            $this->assertSame(
                $getPlayers[$i]->email,
                $parameters['teammate-' . Numbers::toWord($i) . '-email-address']
            );
            $this->assertSame(
                $getPlayers[$i]->username,
                $parameters['teammate-' . Numbers::toWord($i) . '-lol-summoner-name']
            );
        }
    }

    /**
     * @test
     */
    public function it_sets_the_captain_as_an_update_recipient()
    {
        $parameters = $this->teamParameters();
        $parameters['update-recipient'] = 'yes';
        $this->call('POST', '/lol-team-sign-up', $parameters);
        $get = UpdateRecipients::where('email', $parameters['email'])->get();
        $this->assertCount(1, $get);
    }

    /**
     * @test
     */
    public function it_sets_the_captain_as_an_update_recipient_with_geo_location()
    {
        $faker = \Faker\Factory::create();
        $parameters = $this->teamParameters();
        $parameters['update-recipient'] = 'yes';
        $parameters['geo_lat'] = $faker->latitude;
        $parameters['geo_long'] = $faker->longitude;
        $this->call('POST', '/lol-team-sign-up', $parameters);

        $get = UpdateRecipients::where(
            [
                'email' => $parameters['email'],
                'geo_lat' => $parameters['geo_lat'],
                'geo_long' => $parameters['geo_long'],
            ]
        )->get();
        $this->assertCount(1, $get);
    }

    /**
     * @return array
     * @throws \Exception
     */
    private function teamParameters()
    {
        $faker = \Faker\Factory::create();
        $name = implode('-', $faker->words());
        $tournament = factory(Tournament::class)->create(['name' => $name]);
        $parameters = [
            'email' => $faker->email,
            'name' => $faker->name,
            'team-name' => $faker->company,
            'team-captain-lol-summoner-name' => $faker->userName,
            'team-captain-phone' => $faker->phoneNumber,
            'tournament' => $tournament->name,
            'fields' => ['email', 'name', 'team-name', 'team-captain-lol-summoner-name', 'team-captain-phone']
        ];
        $parameters['_token'] =  \Session::token();
        for ($i = 1; $i <= 4; $i++) {
            $parameters['teammate-' . Numbers::toWord($i) . '-email-address'] = $faker->email;
            $parameters['teammate-' . Numbers::toWord($i) . '-lol-summoner-name'] = $faker->userName;
        }
        return $parameters;
    }
}
