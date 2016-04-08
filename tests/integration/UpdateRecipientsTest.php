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

namespace Tests\Integration\Model;

use App\Models\UpdateRecipients;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\DatabaseMigrations;

/**
 * Class RoleTest
 * @package Tests\Integration\Model
 */
class UpdateRecipientsTest extends \TestCase
{

    use DatabaseTransactions;
    use DatabaseMigrations;

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


}
