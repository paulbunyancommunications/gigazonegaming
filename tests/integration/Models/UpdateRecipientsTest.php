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

namespace Tests\Integration\Models;

use App\Models\UpdateRecipients;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * Class RoleTest
 * @package Tests\Integration\Model
 */
class UpdateRecipientsTest extends \TestCase
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
        $this->faker = \Faker\Factory::create();
        $this->resetEventListeners('App\Models\UpdateRecipients');
    }

    public function tearDown()
    {
        parent::tearDown();
    }

    /**
     *
     * Test to see when getting a role by slug we
     * get back the correct name attribute
     *
     * @test
     */
    public function it_has_email_attribute()
    {

        $email = $this->faker->email;
        factory(UpdateRecipients::class)->create(['email' => $email]);

        $role = UpdateRecipients::findUpdateByEmail($email);

        $this->assertEquals($email, $role->email);
    }

    /**
     *
     * Test to see when getting a role by name
     * we get the correct name attribute
     *
     * @test
     */
    public function it_has_participate_attribute()
    {
        $email = $this->faker->email;
        factory(UpdateRecipients::class)->create(['participate' => true, 'email' => $email]);

        $update = UpdateRecipients::findUpdateByEmail($email);

        $this->assertTrue($update->participate);
    }
    /**
     *
     * Test to see when getting a role by name
     * we get the correct name attribute
     *
     * @test
     */
    public function it_has_geo_lat_attribute()
    {
        $latitude = $this->faker->latitude;
        $item = factory(UpdateRecipients::class)->create(['geo_lat' => $latitude]);

        $update = UpdateRecipients::find($item->id);

        $this->assertSame($update->geo_lat, round($latitude, 6, PHP_ROUND_HALF_UP));
    }
    /**
     *
     * Test to see when getting a role by name
     * we get the correct name attribute
     *
     * @test
     */
    public function it_has_geo_long_attribute()
    {
        $longitude = $this->faker->longitude;
        $item = factory(UpdateRecipients::class)->create(['geo_long' => $longitude]);

        $update = UpdateRecipients::find($item->id);

        $this->assertSame($update->geo_long, round($longitude, 6, PHP_ROUND_HALF_UP));
    }
}
