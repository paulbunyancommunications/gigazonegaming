<?php

use Illuminate\Database\Seeder;

class BasicTeamFillerSeed extends Seeder
{
    public $nelson_variables;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        $faker = \Faker\Factory::create();
//        $playerUserNameArray = array('kingmorpheus2131','ReckonStuff', 'DidYouSeeMe31', 'HowAboutThat123', 'YoBroSo');
//        $sp_team = \App\Models\Championship\Team::create(
//            [
//                'name' => "Tester Simon Team",
//                'emblem' => $faker->imageUrl(),
//                'tournament_id' => 1,
//                'captain' => 0
//            ])->toArray();
//        $first = true;
//        // create the tester user if not already created
//        for($i=0; $i<5; $i++) {
//            $player = \App\Models\Championship\Player::create(
//                [
//                    'name' => "Tester Player".str_pad($i, 3, '0', STR_PAD_LEFT),
//                    'username' => $playerUserNameArray[$i],
//                    'email' => "player".str_pad($i, 3, '0', STR_PAD_LEFT)."@test.com",
//                    'phone' => "(218)-444-0".str_pad($i, 3, '0', STR_PAD_LEFT),
//                    'user_id' => $faker->numberBetween(20, 2226000)
//                ])->toArray();
//            \App\Models\Championship\Relation\PlayerRelation::create([
//                'player_id' => $player['id'],
//                'relation_type' => \App\Models\Championship\Game::class,
//                'relation_id' => 2,
//            ]);
//            \App\Models\Championship\Relation\PlayerRelation::create([
//                'player_id' => $player['id'],
//                'relation_type' => \App\Models\Championship\Tournament::class,
//                'relation_id' => 1,
//            ]);
//            \App\Models\Championship\Relation\PlayerRelation::create([
//                'player_id' => $player['id'],
//                'relation_type' => \App\Models\Championship\Team::class,
//                'relation_id' => $sp_team['id'],
//            ]);
//            if($first){
//                $first=false;
//                \App\Models\Championship\Team::where('id', '=', $sp_team['id'])
//                    ->update(['captain'=>$player['id']]);
//            }
//
//        }

    }
}
