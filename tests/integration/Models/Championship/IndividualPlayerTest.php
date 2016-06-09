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
use App\Models\Championship\IndividualPlayer;
use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * Class RoleTest
 * @package Tests\Integration\Model
 */
class IndividualPlayerTest extends \TestCase
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
        $this->resetEventListeners('App\Models\Championship\IndividualPlayer');

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
        $item = factory(IndividualPlayer::class)->create(['username' => $name]);
        $getPlayer = IndividualPlayer::find($item->id);

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
        $item = factory(IndividualPlayer::class)->create(['email' => $email]);

        $getPlayer = IndividualPlayer::find($item->id);

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
        $item = factory(IndividualPlayer::class)->create(['phone' => $phone]);

        $getPlayer = IndividualPlayer::find($item->id);

        $this->assertSame($getPlayer->phone, $phone);
    }
    /**
     * Test that we get a Team instance when calling the team attribute
     *
     * @test
     */
    public function it_is_attached_to_a_game()
    {
        $player = factory(IndividualPlayer::class)->create([]);

        $getPlayer = IndividualPlayer::find($player->id);

        $this->assertInstanceOf(Game::class, $getPlayer->game);
    }
    /**
     *
     * Test to see when getting an Individual Player we
     * get back the correct updated by attribute
     *
     * @test
     */
    public function it_has_a_updated_by_attribute()
    {
        $user_name = $this->faker->userName;
        $user = factory(\App\Models\WpUser::class)->create(['user_login' => $user_name]);
        $individualPlayer = factory(IndividualPlayer::class)->create(['updated_by' => $user->ID]);
        $getIndividualPlayer = IndividualPlayer::find($individualPlayer->id);
        $this->assertSame($user->ID, $getIndividualPlayer->updated_by);
    }

    /**
     *
     * Test to see when getting an Individual Player we
     * get back the correct updated on attribute
     *
     * @test
     */
    public function it_has_a_updated_on_attribute()
    {
        $time_stamp = Carbon::now("CMT");
        $individualPlayer = factory(IndividualPlayer::class)->create(['updated_on' => $time_stamp]);
        $this->assertSame($time_stamp, $individualPlayer->updated_on);
    }
}
