<?php
/**
 * ${CLASS_NAME}
 *
 * Created 6/8/16 11:41 AM
 * Description of this file here....
 *
 * @author Nelson Castillo
 * @package Tests\Unit\App\Http\Middleware
 * @subpackage Subpackage
 */

namespace Tests\Functional\Http\Middleware;

use App\Http\Middleware\LolTeamSignUpMiddleware;
use App\Http\Middleware\TournamentSignUpMiddleware;
use App\Http\Requests\LolTeamSignUpRequest;
use App\Models\Championship\Tournament;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Pbc\Bandolier\Type\Numbers;
use Closure;

class TournamentSignUpMiddlewareTest extends \TestCase
{
    use DatabaseTransactions, DatabaseMigrations;

    public $faker;
    public $yesterday;
    public $tomorrow;
    public $now;
    public $counter = 0;

    /**
     *
     */
    public function setUp()
    {
        parent::setUp();
        $this->clean();
        $this->faker = \Faker\Factory::create();
        $this->get_dates();

    }

    public function clean()
    {
        $dir = dirname(dirname(dirname(dirname(__DIR__))))."/database/dump/gigazone_wp.sql";
        exec('mysql -h "'.env('DB_HOST').'" -u "'.env('DB_USERNAME').'" "-p'.env('DB_PASSWORD').'" "'.env('DB_DATABASE').'" < '. $dir . ' 2> /dev/null');
        exec("php artisan migrate");
    }


    /**
     *
     */
    public function tearDown()
    {
        parent::tearDown();
        $this->clean();
        $this->teamInputs = [];
    }


//
    public function count()
    {
        echo $this->counter;
        $this->counter++;
        return $this->counter;
    }

    /**
     * If validation fails check that an error array was returned
     * @test
     */
    public function it_returns_a_json_array_of_errors_when_validation_fails()
    {
        $this->clean();
        $request = new Request();
        $params = [];
        $request->replace($params);
        $middleware = new TournamentSignUpMiddleware();
        $handle = $middleware->handle($request, function () {
            return 'I ran the closure';
        });
        $result = $handle->getContent();
        $this->assertJson($result);
        $response = json_decode($handle->getContent());
        $this->assertObjectHasAttribute('error', $response);
        $this->assertTrue(is_array($response->error));
        $this->assertEquals("{\"error\":[\"There was no real request here.... moving on!\"]}", $result);
    }

    /**
     * If validation fails check that an tournament error was returned
     * @test
     */
    public function it_returns_a_json_array_of_errors_when_tournament_not_found()
    {
        $this->clean();
        $request = Request::create("/gigazone-gaming-2017-league-sign-up", 'POST');
        $params = [
            'tournament' => "tournament-a",
        ];
        $request->replace($params);

        $middleware = new TournamentSignUpMiddleware();

        // this will set off the wrong tournament issue
        $handle = $middleware->handle($request, function () {
            return 'I ran the closure';
        });
        $find = 'There was no real tournament here.... moving on!';
        $this->find_and_check_in_returned_error($handle, $find);
    }

    /**
     * If validation fails check that an tournament error was returned
     * @test
     */
    public function it_returns_a_json_array_of_errors_when_tournament_is_found_but_signing_is_before_sign_up()
    {
        $this->clean();
        $tournament_name = "gigazone-gaming-2017-league-of-legends";
        $tournament_uri = "/gigazone-gaming-2017-league-sign-up";
        $method = "POST";

        $params_edit = [ "sign_up_open"=>$this->tomorrow, "sign_up_close"=>$this->tomorrow ];
        $this->edit_tournament($tournament_name, $params_edit);

        $handle = $this->create_the_request($tournament_uri, $method, $tournament_name);
        $find = 'It is to early to register for the tournament';
        $this->find_and_check_in_returned_error($handle, $find);

    }
    /**
     * If validation fails check that an tournament error was returned
     * @test
     */
    public function it_returns_a_json_array_of_errors_when_tournament_is_found_but_signing_is_after_sign_up()
    {
        $this->clean();
        $tournament_name = "gigazone-gaming-2017-league-of-legends";
        $tournament_uri = "/gigazone-gaming-2017-league-sign-up";
        $method = "POST";
        list($this->yesterday, $this->tomorrow, $this->now) = $this->get_dates();


        $params_edit = ["sign_up_open"=>$this->yesterday, "sign_up_close"=>$this->yesterday];
        $this->edit_tournament($tournament_name, $params_edit);

        $handle = $this->create_the_request($tournament_uri, $method, $tournament_name);

        $find = 'It is to late to register for the tournament';
        $this->find_and_check_in_returned_error($handle, $find);
    }
    /**
     * If validation fails check that an tournament error was returned
     * @test
     */
    public function it_returns_a_json_array_of_errors_when_tournament_is_found_but_there_is_no_sign_up()
    {
        $this->clean();
        $tournament_name = "gigazone-gaming-2017-league-of-legends";
        $tournament_uri = "/gigazone-gaming-2017-league-sign-up";
        $method = "POST";
        $tournament_sign_up_form = '{"update-recipient":["update-recipient","","hidden","yes"],"participate":["participate","","hidden","yes"],"tournament":["tournament","required|exists:mysql_champ.tournaments,name","hidden","'.$tournament_name.'"],"team-name":["Team Name","required|uniqueWidth:mysql_champ.teams,=name,tournament_id>##id##","text",""],"name":["Team Captain","required","text",""],"email":["Team Captain Email","required|email","email",""],"phone":["Team Captain Phone","required","tel",""],"teammate-one-name":["Teammate One Name","required|different:name|different:teammate-two-name","text",""],"teammate-one-email":["Teammate One Email","required|email|different:email|different:teammate-two-email","email",""],"teammate-two-name":["Teammate Two Name","required|different:name|different:teammate-one-name","text",""],"teammate-two-email":["Teammate Two Email","required|email|different:email|different:teammate-one-email","email",""]}';

        list($this->yesterday, $this->tomorrow, $this->now) = $this->get_dates();

        $params_edit = ["sign_up_open"=>"0000-00-00 00:00:00", "sign_up_close"=>"0000-00-00 00:00:00", "sign_up_form" => $tournament_sign_up_form, "max_teams" => 50, "overflow" => 1, "occurring" => $this->tomorrow];
        $x = $this->edit_tournament($tournament_name, $params_edit);

        $handle = $this->create_the_request($tournament_uri, $method, $tournament_name);

        $find = 'Sorry, there is no registration day for this tournament';
        $this->find_and_check_in_returned_error($handle, $find);

    }
    /**
     * If validation fails check that an tournament error was returned
     * @test
     */
    public function it_returns_a_json_array_of_errors_when_tournament_request_doesnt_have_rules()
    {
        $this->clean();
        $tournament_name = "gigazone-gaming-2017-league-of-legends";
        $tournament_uri = "/gigazone-gaming-2017-league-sign-up";
        $tournament_sign_up_form = "";

        $method = "POST";
        list($this->yesterday, $this->tomorrow, $this->now) = $this->get_dates();


        $params_edit = ["sign_up_open"=>$this->yesterday, "sign_up_close"=>$this->tomorrow, "sign_up_form" => $tournament_sign_up_form, "max_teams" => 50, "overflow" => 1, "occurring" => $this->tomorrow];
        $this->edit_tournament($tournament_name, $params_edit);

        $handle = $this->create_the_request($tournament_uri, $method, $tournament_name);

        $find = 'The Tournament has no set of rules... no rules, no sign up.';
        $this->find_and_check_in_returned_error($handle, $find);

    }
    /**
     * If validation fails check that an tournament error was returned
     * @test
     */
    public function it_passes()
    {
        $this->clean();
        $faker = \Faker\Factory::create();
        $tournament_name = "gigazone-gaming-2017-league-of-legends";
        $tournament_uri = "/gigazone-gaming-2017-league-sign-up";
        $tournament_sign_up_form = '{"update-recipient":["update-recipient","","hidden","yes"],"participate":["participate","","hidden","yes"],"tournament":["tournament","required|exists:mysql_champ.tournaments,name","hidden","'.$tournament_name.'"],"team-name":["Team Name","required|uniqueWidth:mysql_champ.teams,=name,tournament_id>##id##","text",""],"name":["Team Captain","required","text",""],"email":["Team Captain Email","required|email","email",""],"phone":["Team Captain Phone","required","tel",""],"teammate-one-name":["Teammate One Name","required|different:name|different:teammate-two-name","text",""],"teammate-one-email":["Teammate One Email","required|email|different:email|different:teammate-two-email","email",""],"teammate-two-name":["Teammate Two Name","required|different:name|different:teammate-one-name","text",""],"teammate-two-email":["Teammate Two Email","required|email|different:email|different:teammate-one-email","email",""]}';

        $method = "POST";
        list($this->yesterday, $this->tomorrow, $this->now) = $this->get_dates();


        $params_edit = ["sign_up_open" => $this->yesterday, "sign_up_close" => $this->tomorrow, "sign_up_form" => $tournament_sign_up_form, "max_teams" => 50, "overflow" => 1, "occurring" => $this->tomorrow];
        $this->edit_tournament($tournament_name, $params_edit);

        $params = [
            "tournament" => $tournament_name,
            "phone" => $faker->phoneNumber,
            "name" => $faker->name("male").$faker->name("female"),
            "email" => $faker->safeEmail,
            "team-name" => "team $faker->name('male').$faker->name('female')",
            "teammate-one-name" => $faker->name("male"),
            "teammate-one-email" => $faker->safeEmail,
            "teammate-two-name" => $faker->name("female"),
            "teammate-two-email" => $faker->safeEmail

        ];
        $handle = $this->create_the_request($tournament_uri, $method, $tournament_name, $params);

        $this->assertEquals("$handle", "I ran the closure");

    }
    /**
     * If validation fails check that an tournament error was returned
     * @test
     */
    public function it_returns_a_json_array_of_errors_when_captain_name_is_missing()
    {
        $this->clean();
        $faker = \Faker\Factory::create();
        $tournament_name = "gigazone-gaming-2017-league-of-legends";
        $tournament_uri = "/gigazone-gaming-2017-league-sign-up";
        $tournament_sign_up_form = '{"update-recipient":["update-recipient","","hidden","yes"],"participate":["participate","","hidden","yes"],"tournament":["tournament","required|exists:mysql_champ.tournaments,name","hidden","'.$tournament_name.'"],"team-name":["Team Name","required|uniqueWidth:mysql_champ.teams,=name,tournament_id>##id##","text",""],"name":["Team Captain","required","text",""],"email":["Team Captain Email","required|email","email",""],"phone":["Team Captain Phone","required","tel",""],"teammate-one-name":["Teammate One Name","required|different:name|different:teammate-two-name","text",""],"teammate-one-email":["Teammate One Email","required|email|different:email|different:teammate-two-email","email",""],"teammate-two-name":["Teammate Two Name","required|different:name|different:teammate-one-name","text",""],"teammate-two-email":["Teammate Two Email","required|email|different:email|different:teammate-one-email","email",""]}';

        $method = "POST";
        list($this->yesterday, $this->tomorrow, $this->now) = $this->get_dates();


        $params_edit = ["sign_up_open"=>$this->yesterday, "sign_up_close"=>$this->tomorrow, "sign_up_form" => $tournament_sign_up_form, "max_teams" => 50, "overflow" => 1, "occurring" => $this->tomorrow];
        $this->edit_tournament($tournament_name, $params_edit);

        $params = [
            "tournament" => $tournament_name,
            "phone" => $faker->phoneNumber,
            "name" => "",
            "email" => $faker->safeEmail,
            "team-name" => "team $faker->name('male').$faker->name('female')",
            "teammate-one-name" => $faker->name("male"),
            "teammate-one-email" => $faker->safeEmail,
            "teammate-two-name" => $faker->name("female"),
            "teammate-two-email" => $faker->safeEmail

        ];
        $handle = $this->create_the_request($tournament_uri, $method, $tournament_name, $params);

        $find = 'The name field is required.';
        $this->find_and_check_in_returned_error($handle, $find);

    }

    /**
     * If validation fails check that an tournament error was returned
     * @test
     */
    public function it_returns_a_json_array_of_errors_when_captain_email_is_missing()
    {
        $this->clean();
        $faker = \Faker\Factory::create();
        $tournament_name = "gigazone-gaming-2017-league-of-legends";
        $tournament_uri = "/gigazone-gaming-2017-league-sign-up";
        $tournament_sign_up_form = '{"update-recipient":["update-recipient","","hidden","yes"],"participate":["participate","","hidden","yes"],"tournament":["tournament","required|exists:mysql_champ.tournaments,name","hidden","'.$tournament_name.'"],"team-name":["Team Name","required|uniqueWidth:mysql_champ.teams,=name,tournament_id>##id##","text",""],"name":["Team Captain","required","text",""],"email":["Team Captain Email","required|email","email",""],"phone":["Team Captain Phone","required","tel",""],"teammate-one-name":["Teammate One Name","required|different:name|different:teammate-two-name","text",""],"teammate-one-email":["Teammate One Email","required|email|different:email|different:teammate-two-email","email",""],"teammate-two-name":["Teammate Two Name","required|different:name|different:teammate-one-name","text",""],"teammate-two-email":["Teammate Two Email","required|email|different:email|different:teammate-one-email","email",""]}';

        $method = "POST";
        list($this->yesterday, $this->tomorrow, $this->now) = $this->get_dates();


        $params_edit = ["sign_up_open"=>$this->yesterday, "sign_up_close"=>$this->tomorrow, "sign_up_form" => $tournament_sign_up_form, "max_teams" => 50, "overflow" => 1, "occurring" => $this->tomorrow];
        $this->edit_tournament($tournament_name, $params_edit);

        $params = [
            "tournament" => $tournament_name,
            "phone" => $faker->phoneNumber,
            "name" => "$faker->name('male').$faker->name('female')",
            "email" => "",
            "team-name" => "team $faker->name('male').$faker->name('female')",
            "teammate-one-name" => $faker->name("male"),
            "teammate-one-email" => $faker->safeEmail,
            "teammate-two-name" => $faker->name("female"),
            "teammate-two-email" => $faker->safeEmail

        ];
        $handle = $this->create_the_request($tournament_uri, $method, $tournament_name, $params);

        $find = 'The email field is required.';
        $this->find_and_check_in_returned_error($handle, $find);
    }
    /**
     * If validation fails check that an tournament error was returned
     * @test
     */
    public function it_returns_a_json_array_of_errors_when_team_name_is_missing()
    {
        $this->clean();
        $faker = \Faker\Factory::create();
        $tournament_name = "gigazone-gaming-2017-league-of-legends";
        $tournament_uri = "/gigazone-gaming-2017-league-sign-up";
        $tournament_sign_up_form = '{"update-recipient":["update-recipient","","hidden","yes"],"participate":["participate","","hidden","yes"],"tournament":["tournament","required|exists:mysql_champ.tournaments,name","hidden","'.$tournament_name.'"],"team-name":["Team Name","required|uniqueWidth:mysql_champ.teams,=name,tournament_id>##id##","text",""],"name":["Team Captain","required","text",""],"email":["Team Captain Email","required|email","email",""],"phone":["Team Captain Phone","required","tel",""],"teammate-one-name":["Teammate One Name","required|different:name|different:teammate-two-name","text",""],"teammate-one-email":["Teammate One Email","required|email|different:email|different:teammate-two-email","email",""],"teammate-two-name":["Teammate Two Name","required|different:name|different:teammate-one-name","text",""],"teammate-two-email":["Teammate Two Email","required|email|different:email|different:teammate-one-email","email",""]}';

        $method = "POST";
        list($this->yesterday, $this->tomorrow, $this->now) = $this->get_dates();


        $params_edit = ["sign_up_open"=>$this->yesterday, "sign_up_close"=>$this->tomorrow, "sign_up_form" => $tournament_sign_up_form, "max_teams" => 50, "overflow" => 1, "occurring" => $this->tomorrow];
        $this->edit_tournament($tournament_name, $params_edit);

        $params = [
            "tournament" => $tournament_name,
            "phone" => $faker->phoneNumber,
            "name" => "$faker->name('male').$faker->name('female')",
            "email" => $faker->safeEmail,
            "team-name" => "",
            "teammate-one-name" => $faker->name("male"),
            "teammate-one-email" => $faker->safeEmail,
            "teammate-two-name" => $faker->name("female"),
            "teammate-two-email" => $faker->safeEmail

        ];
        $handle = $this->create_the_request($tournament_uri, $method, $tournament_name, $params);

        $find = 'The team-name field is required.';
        $this->find_and_check_in_returned_error($handle, $find);

    }
    /**
     * If validation fails check that an tournament error was returned
     * @test
     */
    public function it_returns_a_json_array_of_errors_when_teammate_one_name_is_missing()
    {
        $this->clean();
        $faker = \Faker\Factory::create();
        $tournament_name = "gigazone-gaming-2017-league-of-legends";
        $tournament_uri = "/gigazone-gaming-2017-league-sign-up";
        $tournament_sign_up_form = '{"update-recipient":["update-recipient","","hidden","yes"],"participate":["participate","","hidden","yes"],"tournament":["tournament","required|exists:mysql_champ.tournaments,name","hidden","'.$tournament_name.'"],"team-name":["Team Name","required|uniqueWidth:mysql_champ.teams,=name,tournament_id>##id##","text",""],"name":["Team Captain","required","text",""],"email":["Team Captain Email","required|email","email",""],"phone":["Team Captain Phone","required","tel",""],"teammate-one-name":["Teammate One Name","required|different:name|different:teammate-two-name","text",""],"teammate-one-email":["Teammate One Email","required|email|different:email|different:teammate-two-email","email",""],"teammate-two-name":["Teammate Two Name","required|different:name|different:teammate-one-name","text",""],"teammate-two-email":["Teammate Two Email","required|email|different:email|different:teammate-one-email","email",""]}';

        $method = "POST";
        list($this->yesterday, $this->tomorrow, $this->now) = $this->get_dates();

        $params_edit = ["sign_up_open"=>$this->yesterday, "sign_up_close"=>$this->tomorrow, "sign_up_form" => $tournament_sign_up_form, "max_teams" => 50, "overflow" => 1, "occurring" => $this->tomorrow];
        $this->edit_tournament($tournament_name, $params_edit);

        $params = [
            "tournament" => $tournament_name,
            "phone" => $faker->phoneNumber,
            "name" => "$faker->name('male') $faker->name('female')",
            "email" => $faker->safeEmail,
            "team-name" => "team $faker->name('female') $faker->name('male')",
            "teammate-one-name" => "",
            "teammate-one-email" => $faker->safeEmail,
            "teammate-two-name" => $faker->name("female"),
            "teammate-two-email" => $faker->safeEmail

        ];
        $handle = $this->create_the_request($tournament_uri, $method, $tournament_name, $params);

        $find = 'The teammate-one-name field is required.';
        $this->find_and_check_in_returned_error($handle, $find);

    }
    /**
     * If validation fails check that an tournament error was returned
     * @test
     */
    public function it_returns_a_json_array_of_errors_when_teammate_one_email_is_missing()
    {
        $this->clean();
        $faker = \Faker\Factory::create();
        $tournament_name = "gigazone-gaming-2017-league-of-legends";
        $tournament_uri = "/gigazone-gaming-2017-league-sign-up";
        $tournament_sign_up_form = '{"update-recipient":["update-recipient","","hidden","yes"],"participate":["participate","","hidden","yes"],"tournament":["tournament","required|exists:mysql_champ.tournaments,name","hidden","'.$tournament_name.'"],"team-name":["Team Name","required|uniqueWidth:mysql_champ.teams,=name,tournament_id>##id##","text",""],"name":["Team Captain","required","text",""],"email":["Team Captain Email","required|email","email",""],"phone":["Team Captain Phone","required","tel",""],"teammate-one-name":["Teammate One Name","required|different:name|different:teammate-two-name","text",""],"teammate-one-email":["Teammate One Email","required|email|different:email|different:teammate-two-email","email",""],"teammate-two-name":["Teammate Two Name","required|different:name|different:teammate-one-name","text",""],"teammate-two-email":["Teammate Two Email","required|email|different:email|different:teammate-one-email","email",""]}';

        $method = "POST";
        list($this->yesterday, $this->tomorrow, $this->now) = $this->get_dates();


        $params_edit = ["sign_up_open"=>$this->yesterday, "sign_up_close"=>$this->tomorrow, "sign_up_form" => $tournament_sign_up_form, "max_teams" => 50, "overflow" => 1, "occurring" => $this->tomorrow];
        $this->edit_tournament($tournament_name, $params_edit);

        $params = [
            "tournament" => $tournament_name,
            "phone" => $faker->phoneNumber,
            "name" => "$faker->name('male') $faker->name('female')",
            "email" => $faker->safeEmail,
            "team-name" => "team $faker->name('female') $faker->name('male')",
            "teammate-one-name" => $faker->name("male"),
            "teammate-one-email" => "",
            "teammate-two-name" => $faker->name("female"),
            "teammate-two-email" => $faker->safeEmail

        ];
        $handle = $this->create_the_request($tournament_uri, $method, $tournament_name, $params);

        $find = 'The teammate-one-email field is required.';
        $this->find_and_check_in_returned_error($handle, $find);

    }
    /**
     * If validation fails check that an tournament error was returned
     * @test
     */
    public function it_returns_a_json_array_of_errors_when_teammate_two_name_is_missing()
    {
        $this->clean();
        $faker = \Faker\Factory::create();
        $tournament_name = "gigazone-gaming-2017-league-of-legends";
        $tournament_uri = "/gigazone-gaming-2017-league-sign-up";
        $tournament_sign_up_form = '{"update-recipient":["update-recipient","","hidden","yes"],"participate":["participate","","hidden","yes"],"tournament":["tournament","required|exists:mysql_champ.tournaments,name","hidden","'.$tournament_name.'"],"team-name":["Team Name","required|uniqueWidth:mysql_champ.teams,=name,tournament_id>##id##","text",""],"name":["Team Captain","required","text",""],"email":["Team Captain Email","required|email","email",""],"phone":["Team Captain Phone","required","tel",""],"teammate-one-name":["Teammate One Name","required|different:name|different:teammate-two-name","text",""],"teammate-one-email":["Teammate One Email","required|email|different:email|different:teammate-two-email","email",""],"teammate-two-name":["Teammate Two Name","required|different:name|different:teammate-one-name","text",""],"teammate-two-email":["Teammate Two Email","required|email|different:email|different:teammate-one-email","email",""]}';

        $method = "POST";
        list($this->yesterday, $this->tomorrow, $this->now) = $this->get_dates();


        $params_edit = ["sign_up_open"=>$this->yesterday, "sign_up_close"=>$this->tomorrow, "sign_up_form" => $tournament_sign_up_form, "max_teams" => 50, "overflow" => 1, "occurring" => $this->tomorrow];
        $this->edit_tournament($tournament_name, $params_edit);

        $params = [
            "tournament" => $tournament_name,
            "phone" => $faker->phoneNumber,
            "name" => "$faker->name('male') $faker->name('female')",
            "email" => $faker->safeEmail,
            "team-name" => "team $faker->name('female') $faker->name('male')",
            "teammate-one-name" => $faker->name("male"),
            "teammate-one-email" => $faker->safeEmail,
            "teammate-two-name" => "",
            "teammate-two-email" => $faker->safeEmail

        ];
        $handle = $this->create_the_request($tournament_uri, $method, $tournament_name, $params);

        $find = 'The teammate-two-name field is required.';
        $this->find_and_check_in_returned_error($handle, $find);
    }
    /**
     * If validation fails check that an tournament error was returned
     * @test
     */
    public function it_returns_a_json_array_of_errors_when_teammate_two_email_is_missing()
    {
        $this->clean();
        $faker = \Faker\Factory::create();
        $tournament_name = "gigazone-gaming-2017-league-of-legends";
        $tournament_uri = "/gigazone-gaming-2017-league-sign-up";
        $tournament_sign_up_form = '{"update-recipient":["update-recipient","","hidden","yes"],"participate":["participate","","hidden","yes"],"tournament":["tournament","required|exists:mysql_champ.tournaments,name","hidden","'.$tournament_name.'"],"team-name":["Team Name","required|uniqueWidth:mysql_champ.teams,=name,tournament_id>##id##","text",""],"name":["Team Captain","required","text",""],"email":["Team Captain Email","required|email","email",""],"phone":["Team Captain Phone","required","tel",""],"teammate-one-name":["Teammate One Name","required|different:name|different:teammate-two-name","text",""],"teammate-one-email":["Teammate One Email","required|email|different:email|different:teammate-two-email","email",""],"teammate-two-name":["Teammate Two Name","required|different:name|different:teammate-one-name","text",""],"teammate-two-email":["Teammate Two Email","required|email|different:email|different:teammate-one-email","email",""]}';

        $method = "POST";

        $params_edit = ["sign_up_open"=>$this->yesterday, "sign_up_close"=>$this->tomorrow, "sign_up_form" => $tournament_sign_up_form, "max_teams" => 50, "overflow" => 1, "occurring" => $this->tomorrow];
        $this->edit_tournament($tournament_name, $params_edit);

        $params = [
            "tournament" => $tournament_name,
            "phone" => $faker->phoneNumber,
            "name" => "$faker->name('male') $faker->name('female')",
            "email" => $faker->safeEmail,
            "team-name" => "team $faker->name('female') $faker->name('male')",
            "teammate-one-name" => $faker->name("male"),
            "teammate-one-email" => $faker->safeEmail,
            "teammate-two-name" => $faker->name("female"),
            "teammate-two-email" => ""

        ];
        $handle = $this->create_the_request($tournament_uri, $method, $tournament_name, $params);

        $find = 'The teammate-two-email field is required.';
        $this->find_and_check_in_returned_error($handle, $find);
    }















    /**
     * @param $handle
     * @param $find
     * @return bool
     */
    public function find_and_check_in_returned_error($handle, $find)
    {
        $response = json_decode($handle->getContent());
        $this->assertObjectHasAttribute('error', $response);
        $this->assertTrue(is_array($response->error));
        $errorExist = false;
        foreach ($response->error as $key => $d_error) {
            $is_array = json_decode($d_error, True);
            if(is_array($is_array)){
                foreach ($is_array as $k => $d_err) {
                    if (trim(trim(trim($d_err) ,".")) == trim(trim(trim($find) ,"."))) {
                        $errorExist = true;
                        break;
                    }
                }
            }else {
                if ($d_error == $find) {
                    $errorExist = true;
                    break;
                }
            }
        }
        $this->assertTrue($errorExist);
    }

    /**
     * @return array
     */
    private function get_dates()
    {
        $this->yesterday = Carbon::now("America/Chicago")->subDays(random_int(1, 6))->toDateTimeString();
        $this->tomorrow = Carbon::now("America/Chicago")->addDays(random_int(1, 6))->toDateTimeString();
        $this->now = Carbon::now("America/Chicago")->toDateTimeString();
        return array($this->yesterday, $this->tomorrow, $this->now);
    }

    /**
     * @param $tournament_name
     * @param array $params
     * @return array Tournament
     */
    private function edit_tournament($tournament_name, $params = [])
    {
        unset($params["id"]);
        unset($params["game_id"]);
        unset($params["created_at"]);
        unset($params["updated_at"]);
        unset($params["updated_by"]);
        unset($params["updated_on"]);
        $db_tournament = Tournament::where("name", '=', $tournament_name)->first();
        foreach ($params as $k => $v){
            if($v=="-0001-11-30 00:00:00") {
                $params[$k] = $this->yesterday;
            }
        }
        $db_tournament->update($params);
    }

    /**
     * @param $tournament_uri
     * @param $method
     * @param $tournament_name
     * @param $params
     * @return mixed
     */
    private function create_the_request($tournament_uri, $method, $tournament_name, $params = [])
    {
        $request = Request::create($tournament_uri, $method);

        $params['tournament']= $tournament_name;

        $request->replace($params);

        $middleware = new TournamentSignUpMiddleware();

        // this will set off the wrong tournament issue
        $handle = $middleware->handle($request, function () {
            return 'I ran the closure';
        });

        return $handle;
    }
}
