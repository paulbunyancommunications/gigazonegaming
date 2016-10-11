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
        $this->assertSame($individualRequest->rules()['email'], 'required|email|unique:mysql_champ.players,email');

        // check for name input and check that the rules are correct
        $this->assertArrayHasKey('your-lol-summoner-name', $individualRequest->rules());
        $this->assertSame($individualRequest->rules()['your-lol-summoner-name'], 'required|unique:mysql_champ.players,username');

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
            'name.required',
            'your-lol-summoner-name.required',
            'your-lol-summoner-name.unique',
            'email.required',
            'email.email',
            'your-phone.required'
        ];

        foreach ($messages as $m) {
            $this->assertArrayHasKey($m, $individualRequest->messages());
        }
    }
}
