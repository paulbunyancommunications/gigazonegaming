<?php

namespace Tests\Acceptance\Frontend;

use AcceptanceTester;
use Carbon\Carbon;
use Cocur\Slugify\Slugify;
use Pbc\Bandolier\Type\Arrays;
use Pbc\Bandolier\Type\Strings;

class TournamentSignUpCest extends \BaseAcceptance
{
    public function _before(\AcceptanceTester $I)
    {
        parent::_before($I);
        $I->loginToWordpress($I, 'admin', 'password', 1);
    }

    public function _after(\AcceptanceTester $I)
    {
        parent::_after($I);
    }

    /**
     * Test that tournament sign up shortcode will return an error if not found.
     * @test
     * @group TournamentSignUp
     */
    public function testThatTournamentSignUpShortcodeWillReturnAnErrorIfNotFound(\AcceptanceTester $I)
    {
        $tournamentName = 'foo-bar-baz-12345';
        $I->createAPost($I, 'testing tournament error',
            '[tournament-signup-form tournament="' . $tournamentName . '"]');
        $I->waitForText('No tournament was found for “' . $tournamentName . '”', \BaseAcceptance::TEXT_WAIT_TIMEOUT);
    }

    /**
     * Test to the no open message gets returned from the tournament short code.
     * @test
     * @group TournamentSignUp
     */
    public function testToTheNoOpenMessageGetsReturnedFromTheTournamentShortCode(\AcceptanceTester $I)
    {
        $tournament = $this->createTournament($I, [
            'sign_up_open' => Carbon::now()->addDay(1)->toDateTimeString()
        ]);

        $I->createAPost($I, 'testing tournament error',
            '[tournament-signup-form tournament="' . $tournament['name'] . '"]');
        $I->waitForText('The tournament “' . $tournament['title'] . '” will start taking submissions on '. date('l, F jS \a\t g:iA', strtotime($tournament['sign_up_open'])), \BaseAcceptance::TEXT_WAIT_TIMEOUT);

    }


    /**
     * Test to the closed message gets returned from the tournament short code.
     * @test
     * @group TournamentSignUp
     */
    public function testToTheClosedMessageGetsReturnedFromTheTournamentShortCode(\AcceptanceTester $I)
    {
        $tournament = $this->createTournament($I, [
            'sign_up_open' => Carbon::now()->subMonth(2)->toDateTimeString(),
            'sign_up_close' => Carbon::now()->subWeek(1)->toDateTimeString()
        ]);

        $I->createAPost($I, 'testing tournament error',
            '[tournament-signup-form tournament="' . $tournament['name'] . '"]');
        $I->waitForText('The tournament “' . $tournament['title'] . '” is now closed to submissions', \BaseAcceptance::TEXT_WAIT_TIMEOUT);

    }

    /**
     * Create a tournament in the database
     * @param AcceptanceTester $I
     * @param array $attributes
     * @return array|bool
     */
    protected function createTournament(\AcceptanceTester $I, $attributes = [])
    {
        $faker = \Faker\Factory::create();
        $tournamentName = implode('-', $faker->words());
        $tournamentTitle = Strings::formatForTitle(implode('_', $faker->words()));
        $open = Carbon::now()->subDay(1)->toDateTimeString();
        $close = Carbon::now()->addMonth(1)->toDateTimeString();
        $occuring = Carbon::now()->addMonth(1)->addDay(1)->toDateTimeString();
        $attr = Arrays::defaultAttributes(
            [
                'name' => $tournamentName,
                'title' => $tournamentTitle,
                'max_players' => 5,
                'max_teams' => 5,
                'sign_up_open' => $open,
                'sign_up_close' => $close,
                'game_id' => $I->grabFromDatabase('champ_games', 'id', array('name' => 'league-of-legends')),
                'occurring' => $occuring,
                'sign_up_form' => '{"tournament":["Tournament Name","required|exists:mysql_champ.tournaments,name","hidden","' . $tournamentName . '"],"team-name":["Team Name","required|uniqueWidth:mysql_champ.teams,=name,tournament_id>{{tournament_id}}","text",""]}',
                'sign_up_form_shortcode' => '[build-form name="' . $tournamentName . '-sign-up" new_line="," delimiter="|" start="' . strtotime($open) . '" expires="' . strtotime($close) . '" questions="Tournament Name|hidden|' . $tournamentName . ',Team Name|text"]'
            ],
            $attributes);

        $query = 'INSERT INTO champ_tournaments (';
        foreach (array_keys($attr) as $key) {
            $query .= '`' . $key . '`,';
        }
        $query = rtrim($query, ',');
        $query .= ") VALUES (";
        foreach (array_values($attr) as $value) {
            $query .= "'$value',";
        }
        $query = rtrim($query, ',');
        $query .= ")";

        $I->runQuery($I,
            [
                'server' => env('DB_HOST'),
                'user' => env('DB_USERNAME'),
                'password' => env('DB_PASSWORD'),
                'database' => env('DB_DATABASE'),
            ],
            $query);

        return $attr;

    }

    /**
     * Test that tournament sign up will return the correct fields when displaying a tournament
     * @test
     * @group TournamentSignUp
     */
    public function testThatTournamentSignUpWillReturnTheCorrectFieldsWhenDisplayingATournament(\AcceptanceTester $I)
    {
        // we know that there is an overwatch 17 tournament from migrations, use that for the basis of this test.
        $tournamentName = 'gigazone-gaming-2017-overwatch';

        $form = $I->grabFromDatabase('champ_tournaments', 'sign_up_form', array('name' => $tournamentName));
        $shortCode = $I->grabFromDatabase('champ_tournaments', 'sign_up_form_shortcode', array('name' => $tournamentName));
        $fooTournament = $tournamentName . '-foo-bar';

        $this->createTournament($I, [
            'name' => $fooTournament,
            'sign_up_form' => $form,
            'sign_up_form_shortcode' => $shortCode,
        ]);

        $I->createAPost($I, 'testing tournament',
            '[tournament-signup-form tournament="' . $fooTournament . '"]');

        $decodeForm = json_decode($form, true);
        for($i=0, $iCount=count($decodeForm); $i < $iCount; $i++) {
            $field = array_keys($decodeForm)[$i];
            $I->seeElementInDOM('input[name="'.$field.'"]');
        }
    }
}
