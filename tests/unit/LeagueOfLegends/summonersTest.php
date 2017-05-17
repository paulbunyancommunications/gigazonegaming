<?php

namespace LeagueOfLegends;

//use App\Models\Championship\Tournament;
//use App\Models\Championship\Team;
//use App\Models\Championship\Player;
//use Faker\Factory;

class summonersTest extends \TestCase
    {
    /**
     * @var \UnitTester
     */
    protected $tester;
    protected $faker;


    public function setUp()
    {
        parent::setUp();
    }

    public function tearDown()
    {
        parent::tearDown();
        \Mockery::close();
    }


    protected function _before()
    {
//        #Tournament
//        $tournament = new Tournament();
//        $tournament->game_id = 12;
//        $tournament->max_players = 10;
//        $tournament->name = "LOL";
//        $tournament->save();

//        #Team
//        $team = new Team();
//        $team->tournament_id = 0;
//        $team->name =  "Power Rangers";
//        $team->save();
//
//        #Captian
//        $Captian = new Player();
//        $Captian->setAttribute('username', "KingMorpheus2131");
//        $Captian->setAttribute('email', "simoncecer7@gmail.com");
//        $Captian->setAttribute('name', "Simon");
//        $Captian->setAttribute('phone', "6125596450");
//        $Captian->save();
//
//        #relations
//        $Captian::createRelation([
//            'player' => $captain,
//            'game' => $tournament->game,
//            'team' => $team,
//        ]);
//
//        // add captain and save the team
//        $team->captain = $captain->id;
//        $team->save();

        #players




    }

    // tests
    public function createdTournament()
    {
        $something = "Something";
        dd($something);
    }
}