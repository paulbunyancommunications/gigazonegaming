<?php
/**
 * LolIndividualSignUpRequestTest
 *
 * Created 5/27/16 8:04 AM
 * Unit tests for LolIndividualSignUpRequest class
 *
 * @author Nate Nolting <naten@paulbunyan.net>
 */

namespace App\Http\Requests;

class LolIndividualSignUpRequestTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function it_is_authorized()
    {
        $individualRequest = new LolIndividualSignUpRequest();
        $this->assertTrue($individualRequest->authorize());
    }

    /**
     * @test
     */
    public function it_has_the_correct_input_rules()
    {
        $individualRequest = new LolIndividualSignUpRequest();

        // check email field exists and the rules are correct
        $this->assertArrayHasKey('email', $individualRequest->rules());
        $this->assertSame($individualRequest->rules()['email'], 'required|email');

        // check for name input and check that the rules are correct
        $this->assertArrayHasKey('your-lol-summoner-name', $individualRequest->rules());
        $this->assertSame($individualRequest->rules()['your-lol-summoner-name'], 'required');

        // check for team-captain-lol-summoner-name input and check that the rules are correct
        $this->assertArrayHasKey('name', $individualRequest->rules());
        $this->assertSame($individualRequest->rules()['name'], 'required');

        // check for team-captain-phone input and check that the rules are correct
        $this->assertArrayHasKey('your-phone', $individualRequest->rules());
        $this->assertSame($individualRequest->rules()['your-phone'], 'required');

    }

    /**
     * @test
     */
    public function it_has_messages()
    {
        $individualRequest = new LolIndividualSignUpRequest();

        $messages = [
            'name.required' => 'Your name is required.',
            'your-lol-summoner-name.required' => 'Your League of Legends summoner name is required.',
            'your-lol-summoner-name.unique' => 'Your League of Legends summoner name is already being used by someone else.',
            'email.required' => 'Your email address is required.',
            'email.unique' => 'Your email in use by another player',
            'email.email' => 'Your email address must be a valid address (someone@somewhere.com for example).',
            'your-phone.required' => 'Your phone number is required.',
        ];

        foreach ($messages as $m) {
            $this->assertArrayHasKey($m, $individualRequest->messages());
        }
    }
}
