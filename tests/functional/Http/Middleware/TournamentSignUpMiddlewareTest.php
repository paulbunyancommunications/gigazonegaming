<?php
///**
// * ${CLASS_NAME}
// *
// * Created 6/8/16 11:41 AM
// * Description of this file here....
// *
// * @author Nelson Castillo
// * @package Tests\Unit\App\Http\Middleware
// * @subpackage Subpackage
// */
//
//namespace Tests\Functional\Http\Middleware;
//
//use App\Http\Middleware\LolTeamSignUpMiddleware;
//use App\Http\Middleware\TournamentSignUpMiddleware;
//use App\Http\Requests\LolTeamSignUpRequest;
//use Illuminate\Foundation\Testing\DatabaseMigrations;
//use Illuminate\Foundation\Testing\DatabaseTransactions;
//use Illuminate\Http\Request;
//use Pbc\Bandolier\Type\Numbers;
//use Closure;
//
//class LolTeamSignUpMiddlewareTest extends \TestCase
//{
//    use DatabaseTransactions, DatabaseMigrations;
//
//    public $faker;
//    public $counter = 0;
//
//    /**
//     *
//     */
//    public function setUp()
//    {
//        parent::setUp();
//
//        $this->faker = \Faker\Factory::create();
//
//    }
//
//    /**
//     *
//     */
//    public function tearDown()
//    {
//        parent::tearDown();
//        $this->teamInputs = [];
//    }
////
//public function count(){
//    echo $this->counter;
//    $this->counter++;
//    return $this->counter;
//}
//    /**
//     * If validation fails check that an error array was returned
//     * @test
//     */
//    public function it_returns_a_json_array_of_errors_when_validation_fails()
//    {
//        $request = new Request();
//        $params = [];
//        codecept_debug($this->count());
//        $request->replace($params);
//        codecept_debug($this->count());
//        $middleware = new TournamentSignUpMiddleware();
//
////        return $this->error("There was no real request here.... moving on!");
//        codecept_debug($this->count());
//        $handle = $middleware->handle($request, function () {
//            return 'I ran the closure';
//        });
//        codecept_debug($this->count());
//        // check return
//        $result = $handle->getContent();
//        codecept_debug( $result );
//        $this->assertJson($result);
//        $response = json_decode($handle->getContent());
//        $this->assertObjectHasAttribute('error', $response);
//        $this->assertTrue(is_array($response->error));
//    }
////
////    /**
////     * If validation fails check that an tournament error was returned
////     * @test
////     */
////    public function it_returns_a_json_array_of_errors_when_tournament_not_found()
////    {
////        $faker = \Faker\Factory::create();
////        $request = new Request();
////        $request->replace($this->teamInputs);
////        $middleware = new LolTeamSignUpMiddleware();
////
////        // this will set off the wrong tournament issue
////        $middleware->setTournament(implode('-', $faker->words(4)));
////        $handle = $middleware->handle($request, function () {
////            return 'I ran the closure';
////        });
////
////        // check return
////        $this->assertJson($handle->getContent());
////        $response = json_decode($handle->getContent());
////        $this->assertObjectHasAttribute('error', $response);
////        $this->assertTrue(is_array($response->error));
////        $this->assertTrue(in_array('Could not find tournament "' . $middleware->getTournament() . '"',
////            $response->error));
////    }
////
////    /**
////     * If successful check for call back return
////     * @test
////     */
////    public function it_returns_callback_when_successful()
////    {
////        $request = new Request();
////        $request->replace($this->teamInputs);
////        $middleware = new LolTeamSignUpMiddleware();
////
////        $handle = $middleware->handle($request, function () {
////            return 'I ran the closure';
////        });
////        $this->assertSame($handle, 'I ran the closure');
////
////    }
////
////    /**
////     * If successful check for call back return
////     * @test
////     */
////    public function it_sets_new_row_with_captain_to_db_when_successful()
////    {
////        $request = new Request();
////        $request->replace($this->teamInputs);
////        $middleware = new LolTeamSignUpMiddleware();
////        $request->replace($this->teamInputs);
////        \Codeception\Util\Debug::debug(json_encode($request->all()));
////
////        $middleware->handle($request, function () {
////            return 'I ran the closure';
////        });
////
////        // get the captain
////        $getTeam = \App\Models\Championship\Team::where('name', '=', $this->teamInputs['team-name'])->first();
////
////        \Codeception\Util\Debug::debug($getTeam->toArray());
////        \Codeception\Util\Debug::debug('Count: ' . $getTeam->players()->count());
////
////        $getTournament = \App\Models\Championship\Tournament::where(
////            'name',
////            '=',
////            $this->teamInputs['tournament']
////        )->first();
////
////        $getCaptain = \App\Models\Championship\Player::where('email', $this->teamInputs['email'])->first();
////
////        $this->assertSame($getTeam->tournament_id, $getTournament->id);
////        $this->assertSame($this->teamInputs['team-name'], $getTeam->name);
////        $this->assertSame($getTeam->captain, $getCaptain->id);
////
////        // check captain
////        $this->assertSame($this->teamInputs['email'], $getCaptain->email);
////        $this->assertSame($this->teamInputs['team-captain-lol-summoner-name'], $getCaptain->username);
////        $this->assertSame($this->teamInputs['name'], $getCaptain->name);
////        $this->assertSame($this->teamInputs['team-captain-phone'], $getCaptain->phone);
////        $this->assertSame($getTeam->id, $getCaptain->teams[0]->id);
////    }
////
////    /**
////     * @test
////     */
////    public function it_sets_new_row_with_other_players_to_db_when_successful()
////    {
////        $request = new Request();
////        $request->replace($this->teamInputs);
////        $middleware = new LolTeamSignUpMiddleware();
////        $request->replace($this->teamInputs);
////
////        \Codeception\Util\Debug::debug(json_encode($request->all()));
////
////        $middleware->handle($request, function () {
////            return 'I ran the closure';
////        });
////
////        // get the captain
////        $getTeam = \App\Models\Championship\Team::where('name', '=', $this->teamInputs['team-name'])->first();
////
////        \Codeception\Util\Debug::debug($getTeam->toArray());
////        \Codeception\Util\Debug::debug('Count: ' . $getTeam->players()->count());
////
////        // check other players
////        for ($i = 1; $i < $getTeam->tournament->max_players; $i++) {
////
////            $getPlayer = \App\Models\Championship\Player::where(
////                'email',
////                '=',
////                $this->teamInputs['teammate-' . Numbers::toWord($i) . '-email-address']
////            );
////            \Codeception\Util\Debug::debug($getPlayer->first() ? '' : 'Missing player with email '. $this->teamInputs['teammate-' . Numbers::toWord($i) . '-email-address']);
////            $this->assertNotNull($getPlayer);
////
////            $this->assertSame(
////                $this->teamInputs['teammate-' . Numbers::toWord($i) . '-email-address'],
////                $getPlayer->first()->email
////            );
////            $this->assertSame(
////                $this->teamInputs['teammate-' . Numbers::toWord($i) . '-lol-summoner-name'],
////                $getPlayer->first()->username
////            );
////            $this->assertSame($getTeam->id, $getPlayer->first()->teams[0]->id);
////        }
////    }
////
////    /**
////     * If validation fails check for required validation messages
////     * @test
////     */
////    public function it_returns_a_array_of_errors_messages_when_validation_fails()
////    {
////        $request = new Request();
////        $rules = new LolTeamSignUpRequest();
////        $params = [];
////        $request->replace($params);
////        $middleware = new LolTeamSignUpMiddleware();
////
////        $handle = $middleware->handle($request, function () {
////            return 'I ran the closure';
////        });
////
////        $response = json_decode($handle->getContent());
////        // check messages
////        foreach ($rules->messages() as $key => $message) {
////            if (strpos($key, 'required') !== false) {
////                $this->assertTrue(in_array($message, $response->error));
////            }
////        }
////    }
////
////    /**
////     * If bad email was passed in request then check for bad email message
////     * @test
////     */
////    public function it_returns_an_error_if_bad_email_is_passed()
////    {
////        $request = new Request();
////        $rules = new LolTeamSignUpRequest();
////        $params = $this->teamInputs;
////        $params['email'] = 123456;
////        for ($i = 1; $i <= 2; $i++) {
////            $params['teammate-' . Numbers::toWord($i) . '-email-address'] = 123456;
////        }
////        $request->replace($params);
////        $middleware = new LolTeamSignUpMiddleware();
////
////        $handle = $middleware->handle($request, function () {
////            return 'I ran the closure';
////        });
////
////        $response = json_decode($handle->getContent());
////
////        // check messages
////        $this->assertTrue(in_array($rules->messages()['email.email'], $response->error));
////        for ($i = 1; $i <= 2; $i++) {
////            $this->assertTrue(in_array($rules->messages()['teammate-' . Numbers::toWord($i) . '-email-address.email'],
////                $response->error));
////
////        }
////        /**
////         * If bad email was passed in request then check for bad email message
////         * @test
////         */
////        public function it_checks_for_info_in_the_db()
////    {
////        $request = new Request();
////        $rules = new LolTeamSignUpRequest();
////        $params = $this->teamInputs;
////        $params['email'] = "poas12poa4sd5sd@ogmail.com";
////        for ($i = 1; $i <= 2; $i++) {
////            $params['teammate-' . Numbers::toWord($i) . '-email-address'] = "poas12po".$i."a4sd5".$i."sd@ogmail.com";
////        }
////        for ($i = 1; $i <= 2; $i++) {
////            $params['teammate-' . Numbers::toWord($i) . '-email-address'] = "poas12po".$i."a4sd5".$i."sd@ogmail.com";
////        }
////        $request->replace($params);
////        $middleware = new LolTeamSignUpMiddleware();
////
////        $handle = $middleware->handle($request, function () {
////            return 'I ran the closure';
////        });
////
////        $response = json_decode($handle->getContent());
////
////        // check messages
////        $this->assertTrue(in_array($rules->messages()['email.email'], $response->error));
////        for ($i = 1; $i <= 2; $i++) {
////            $this->assertTrue(in_array($rules->messages()['teammate-' . Numbers::toWord($i) . '-email-address.email'],
////                $response->error));
////
////        }
////    }
////
////    /**
////     * Check return from getTournament
////     * @test
////     */
////    public function it_returns_name_of_tournament()
////    {
////        $faker = \Faker\Factory::create();
////        $middleware = new TournamentSignUpMiddleware();
////        $tournament = implode(' ', $faker->words());
////        $middleware->setTournament($tournament);
////        $this->assertSame($tournament, $middleware->getTournament());
////    }
//}
