<?php
/**
 * UpdatesControllerTest
 *
 * Created 4/7/16 8:29 AM
 * Test updates controller
 *
 * @author Nate Nolting <naten@paulbunyan.net>
 * @package Test\Functional
 */

namespace Test\Functional\Http\Controllers\Updates;

use App\Models\UpdateRecipients;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UpdatesControllerTest extends \TestCase
{

    use DatabaseTransactions;

    public function tearDown()
    {
        parent::tearDown();

    }

    /**
     * @test
     * @covers \App\Http\Controllers\Frontend\Updates\UpdatesController::store
     */
    public function it_returns_a_success_when_email_is_sent()
    {
        $faker = \Faker\Factory::create();

        $response = $this->call('POST', '/updates/signup', ['email' => $faker->email]);
        $this->assertTrue($response->isOk());
        $this->assertJson($response->getContent());
        $decode = json_decode($response->getContent());
        $this->assertObjectHasAttribute('success', $decode);
        $this->assertNotFalse(strpos(implode(' ', $decode->success), 'Thanks for signing up!'));
    }

    /**
     * @test
     * @covers \App\Http\Controllers\Frontend\Updates\UpdatesController::store
     */
    public function it_has_a_participate_flag()
    {
        $faker = \Faker\Factory::create();

        $email = $faker->email;
        $response = $this->call('POST', '/updates/signup', ['email' => $email, 'participate' => true]);
        $this->assertTrue($response->isOk());

        $update = UpdateRecipients::findUpdateByEmail($email);
        $this->assertTrue($update->participate);

    }
    /**
     * @test
     * @covers \App\Http\Controllers\Frontend\Updates\UpdatesController::store
     */
    public function it_has_geo_location_data()
    {
        $faker = \Faker\Factory::create();

        $email = $faker->email;
        $latitude = $faker->latitude();
        $longitude = $faker->longitude();
        $response = $this->call('POST', '/updates/signup', ['email' => $email, 'participate' => true, 'geo_lat' => $latitude, 'geo_long' => $longitude]);
        $this->assertTrue($response->isOk());

        $update = UpdateRecipients::findUpdateByEmail($email);
        $this->assertEquals($update->geo_lat, $latitude, 'the stored value of "'.$update->geo_lat.'" equals  "'. $latitude .'"');
        $this->assertEquals($update->geo_long, $longitude, 'the stored value of "'.$update->geo_long.'" equals  "'. $longitude .'"');

    }

    /**
     * @test
     * @covers \App\Http\Controllers\Frontend\Updates\UpdatesController::store
     */
    public function it_fails_without_email()
    {
        $faker = \Faker\Factory::create();
        $response = $this->call('POST', '/updates/signup', []);
        $this->assertTrue($response->isOk());
        $this->assertJson($response->getContent());
        $decode = json_decode($response->getContent());
        $this->assertObjectHasAttribute('error', $decode);
        $this->assertNotFalse(strpos(implode(', ', $decode->error), 'The email field is required.'));

    }

    /**
     * @test
     * @covers \App\Http\Controllers\Frontend\Updates\UpdatesController::store
     */
    public function it_fails_with_bad_email()
    {

        $response = $this->call('POST', '/updates/signup', ['email' => 'this_is_a_invalid_email']);
        $this->assertTrue($response->isOk());
        $this->assertJson($response->getContent());
        $decode = json_decode($response->getContent());
        $this->assertObjectHasAttribute('error', $decode);
        $this->assertNotFalse(strpos(implode(', ', $decode->error), 'The email must be a valid email address.'));

    }

    /**
     * @test
     * @covers \App\Http\Controllers\Frontend\Updates\UpdatesController::store
     */
    public function it_fails_if_duplicate_email_present()
    {
        $faker = \Faker\Factory::create();

        $email = $faker->email;
        $response = $this->call('POST', '/updates/signup', ['email' => $email]);
        $this->assertTrue($response->isOk());
        $this->assertJson($response->getContent());
        $decode = json_decode($response->getContent());
        $this->assertObjectHasAttribute('success', $decode);
        $this->assertNotFalse(strpos(implode(' ', $decode->success), 'Thanks for signing up!'));

        // the second one will fail
        $response2 = $this->call('POST', '/updates/signup', ['email' => $email]);
        $this->assertTrue($response2->isOk());
        $this->assertJson($response2->getContent());
        $decode2 = json_decode($response2->getContent());
        $this->assertObjectHasAttribute('error', $decode2);
        $this->assertNotFalse(strpos(implode(', ', $decode2->error), 'That email address has already been submitted.'));

    }
}
