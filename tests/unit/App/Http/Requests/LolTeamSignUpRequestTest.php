<?php
///**
// * LolTeamSignUpRequest
// *
// * Created 5/27/16 8:04 AM
// * Unit tests for LolTeamSignUpRequest class
// *
// * @author Nate Nolting <naten@paulbunyan.net>
// */
//namespace App\Http\Requests;
//
//use App\Http\Requests\LolTeamSignUpRequest;
//use Pbc\Bandolier\Type\Numbers;
//
//class LolTeamSignUpRequestTest extends \PHPUnit_Framework_TestCase
//{
//
//    /**
//     * @test
//     */
//    public function it_is_authorized()
//    {
//        $individualRequest = new LolTeamSignUpRequest();
//        $this->assertTrue($individualRequest->authorize());
//    }
//
//    /**
//     * @test
//     */
//    public function it_has_the_correct_input_rules()
//    {
//        $individualRequest = new LolTeamSignUpRequest();
//        // check email field exists and the rules are correct
//        $this->assertArrayHasKey('email', $individualRequest->rules());
//        $this->assertSame($individualRequest->rules()['email'], 'required|email');
//
//        // check for name input and check that the rules are correct
//        $this->assertArrayHasKey('name', $individualRequest->rules());
//        $this->assertSame($individualRequest->rules()['name'], 'required');
//
//        // check for team-captain-lol-summoner-name input and check that the rules are correct
//        $this->assertArrayHasKey('team-captain-lol-summoner-name', $individualRequest->rules());
//        $this->assertSame($individualRequest->rules()['team-captain-lol-summoner-name'], 'required');
//
//        // check for team-captain-phone input and check that the rules are correct
//        $this->assertArrayHasKey('team-captain-phone', $individualRequest->rules());
//        $this->assertSame($individualRequest->rules()['team-captain-phone'], 'required');
//
//        // check for tournament input and check that the rules are correct
//        $this->assertArrayHasKey('tournament', $individualRequest->rules());
//        $this->assertSame($individualRequest->rules()['tournament'], 'required|exists:mysql_champ.tournaments,name');
//
//        // check for team-name input and check that the rules are correct
//        $this->assertArrayHasKey('team-name', $individualRequest->rules());
//        $this->assertSame($individualRequest->rules()['team-name'], 'required|unique:mysql_champ.teams,name');
//
//        for ($i = 1; $i <= 2; $i++) {
//            // check for teammate-x-lol-summoner-name input and check that the rules are correct
//            $this->assertArrayHasKey(
//                'teammate-' . Numbers::toWord($i) . '-lol-summoner-name',
//                $individualRequest->rules()
//            );
//            $this->assertSame($individualRequest->rules()['teammate-' . Numbers::toWord($i) . '-lol-summoner-name'], 'required');
//
//            // check for teammate-x-email-address input and check that the rules are correct
//            $this->assertArrayHasKey(
//                'teammate-' . Numbers::toWord($i) . '-email-address',
//                $individualRequest->rules()
//            );
//            $this->assertSame($individualRequest->rules()['teammate-' . Numbers::toWord($i) . '-email-address'], 'required|email');
//
//        }
//    }
//
//    /**
//     * @test
//     */
//    public function it_has_messages()
//    {
//        $individualRequest = new LolTeamSignUpRequest();
//
//        $messages = [
//            'team-name.uniqueWidth' => 'A team with the exact same name already exists for this tournament, please select a different name.',
//            'team-name.unique_width' => 'A team with the exact same name already exists for this tournament, please select a different name.',
//            'email.required' => 'The team captain email address is required.',
//            'email.email' => 'The team captain email address myst be a valid email address (someone@somewhere.com for example).',
//            'name.required' => 'The name of the team captain is required.',
//            'team-captain-lol-summoner-name.required' => 'The team captain LOL summoner name is required.',
//            'team-captain-phone.required' => 'The team captain phone number is required.',
//            'team-name.required' => 'The team name is required.',
//        ];
//
//        for ($i = 1; $i <= 2; $i++) {
//            $messages['teammate-'. Numbers::toWord($i).'-lol-summoner-id.exists'] = 'The summoner selected for teammate '.Numbers::toWord($i). ' was not found.';
//            $messages['teammate-'. Numbers::toWord($i).'-lol-summoner-name.required'] = 'The summoner name for team member '.Numbers::toWord($i).' is required.';
//            $messages['teammate-'.Numbers::toWord($i).'-email-address.required'] = 'The email address for team member '.Numbers::toWord($i).' is required.';
//            $messages['teammate-'.Numbers::toWord($i).'-email-address.unique'] = 'The email address for team member '.Numbers::toWord($i).' is already in use by another player.';
//            $messages['teammate-'.Numbers::toWord($i).'-email-address.email'] = 'The email address for team member '.Numbers::toWord($i).' must be a valid email address (someone@somewhere.com for example).';
//        }
//
//        $this->assertSame($messages, $individualRequest->messages());
//    }
//}
