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

use App\Models\Championship\Game;
use App\Models\Championship\Player;
use App\Models\Championship\Team;
use Faker\Factory;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * Class RoleTest
 * @package Tests\Integration\Model
 */
class PlayerTest extends \TestCase
{

    use DatabaseTransactions;

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

    }

    public function tearDown()
    {
        parent::tearDown();
        exec('php artisan migrate:refresh');
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
     *
     * Test to see when getting a player we
     * get back the game the captain attribute
     *
     * @test
     */
    public function it_has_a_captain_attribute()
    {
        $captain = true;
        $player = factory(Player::class)->create(['captain' => $captain]);

        $getPlayer = Player::find($player->id);

        $this->assertSame($getPlayer->captain, $captain);
    }

    /**
     * Test that we get a Team instance when calling the team attribute
     *
     * @test
     */
    public function it_is_attached_to_a_team()
    {
        $player = factory(Player::class)->create([]);

        $getPlayer = Player::find($player->id);

        $this->assertInstanceOf(Team::class, $getPlayer->team);
    }
}
