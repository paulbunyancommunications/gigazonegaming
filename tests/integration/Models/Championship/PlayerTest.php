<?php
/**
 * RoleTest
 *
 * Created 2/18/16 2:51 PM
 * Test roles model
 *
 * @author Nate Nolting <naten@paulbunyan.net>
 * @package Tests\Integration\Model
 * @subpackage Subpackage
 */

namespace Tests\Integration\Models\Championship;


use App\Models\Championship\Player;
use App\Models\Championship\Team;
use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;


/**
 * Class RoleTest
 * @package Tests\Integration\Model
 */
class PlayerTest extends \TestCase
{

    use DatabaseTransactions, DatabaseMigrations;


    /**
     * @var
     */
    protected $faker;

    /**
     *
     */
    public function setUp()
    {
        parent::setUp();
        $this->faker = Factory::create();
        $this->resetEventListeners('App\Models\Championship\Player');

    }

    public function tearDown()
    {
        parent::tearDown();
    }

    /**
     *
     * Test to see when getting a player we
     * get back the correct username attribute
     *
     * @test
     */
    public function it_has_a_username_attribute()
    {

        $name = $this->faker->username;
        $item = factory(Player::class)->create(['username' => $name]);
        $getPlayer = Player::find($item->id);

        $this->assertSame($name, $getPlayer->username);
    }
    /**
     *
     * Test to see when getting a player we
     * get back the correct email attribute
     *
     * @test
     */
    public function it_has_a_email_attribute()
    {
        $email = $this->faker->email;
        $item = factory(Player::class)->create(['email' => $email]);

        $getPlayer = Player::find($item->id);

        $this->assertSame($email, $getPlayer->email);
    }
    /**
     *
     * Test to see when getting a team we
     * get back the game the team is playing
     *
     * @test
     */
    public function it_has_a_phone_attribute()
    {
        $phone = $this->faker->phoneNumber;
        $item = factory(Player::class)->create(['phone' => $phone]);

        $getPlayer = Player::find($item->id);

        $this->assertSame($getPlayer->phone, $phone);
    }

    /**
     * Test that we get a Team instance when calling the team attribute
     *
     * @test
     */
    public function it_is_attached_to_a_team()
    {
        $player = factory(Player::class)->create([]);
        $team = factory(Team::class)->create([]);
        // this will attach the team to the player
        $player::createRelation(['player' => $player, 'team' => $team]);

        $getPlayer = Player::find($player->id);

        // now the first team should be the attached team to this player
        $this->assertInstanceOf(Team::class, $getPlayer->teams[0]);
    }
    /**
     *
     * Test to see when getting a Player we
     * get back the correct updated by attribute
     *
     * @test
     */
    public function it_has_a_updated_by_attribute()
    {
        $user_name = $this->faker->userName;
        $user = factory(\App\Models\WpUser::class)->create(['user_login' => $user_name]);
        $player = factory(Player::class)->create(['updated_by' => $user->ID]);
        $getPlayer = Player::find($player->id);
        $this->assertSame($user->ID, $getPlayer->updated_by);
    }

    /**
     *
     * Test to see when getting a game we
     * get back the correct updated on attribute
     *
     * @test
     */
    public function it_has_a_updated_on_attribute()
    {
        $time_stamp = Carbon::now("CMT");
        $player = factory(Player::class)->create(['updated_on' => $time_stamp]);
        $this->assertSame($time_stamp, $player->updated_on);
    }
}
