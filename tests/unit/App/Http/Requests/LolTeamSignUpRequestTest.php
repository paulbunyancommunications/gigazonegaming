<?php
/**
 * LolIndividualSignUpRequestTest
 *
 * Created 5/27/16 8:04 AM
 * Unit tests for LolIndividualSignUpRequest class
 *
 * @author Nate Nolting <naten@paulbunyan.net>
 */

namespace Tests\Unit\App\Http\Requests;

use App\Http\Requests\LolTeamSignUpRequest;
use Pbc\Bandolier\Type\Numbers;

class LolTeamSignUpRequestTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function it_is_authorized()
    {
        $individualRequest = new LolTeamSignUpRequest();
        $this->assertTrue($individualRequest->authorize());
    }

    /**
     * @test
     */
    public function it_has_the_correct_input_rules()
    {
        $individualRequest = new LolTeamSignUpRequest();
        
        // check email field exists and the rules are correct
        $this->assertArrayHasKey('email', $individualRequest->rules());
        $this->assertSame($individualRequest->rules()['email'], 'required|email');
        
        // check for name input and check that the rules are correct
        $this->assertArrayHasKey('name', $individualRequest->rules());
        $this->assertSame($individualRequest->rules()['name'], 'required');

        // check for team-captain-lol-summoner-name input and check that the rules are correct
        $this->assertArrayHasKey('team-captain-lol-summoner-name', $individualRequest->rules());
        $this->assertSame($individualRequest->rules()['team-captain-lol-summoner-name'], 'required');

        // check for team-captain-phone input and check that the rules are correct
        $this->assertArrayHasKey('team-captain-phone', $individualRequest->rules());
        $this->assertSame($individualRequest->rules()['team-captain-phone'], 'required');

        // check for tournament input and check that the rules are correct
        $this->assertArrayHasKey('tournament', $individualRequest->rules());
        $this->assertSame($individualRequest->rules()['tournament'], 'required|exists:mysql_champ.tournaments,name');

        // check for team-name input and check that the rules are correct
        $this->assertArrayHasKey('team-name', $individualRequest->rules());
        $this->assertSame($individualRequest->rules()['team-name'], 'required');

        for ($i = 1; $i <= 2; $i++) {
            // check for teammate-x-lol-summoner-name input and check that the rules are correct
            $this->assertArrayHasKey(
                'teammate-' . Numbers::toWord($i) . '-lol-summoner-name',
                $individualRequest->rules()
            );
            $this->assertSame($individualRequest->rules()['teammate-' . Numbers::toWord($i) . '-lol-summoner-name'], 'required');

            // check for teammate-x-email-address input and check that the rules are correct
            $this->assertArrayHasKey(
                'teammate-' . Numbers::toWord($i) . '-email-address',
                $individualRequest->rules()
            );
            $this->assertSame($individualRequest->rules()['teammate-' . Numbers::toWord($i) . '-email-address'], 'required|email');
        
        }
    }
}
