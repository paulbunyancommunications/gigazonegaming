<?php

use App\Models\Championship\Game;
use Illuminate\Database\Migrations\Migration;

class MakeLeagueOfLegendsGameEntry extends Migration
{

    protected $name = 'league-of-legends';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $game = Game::where('name', $this->name)->first();
        if (!$game) {
            $newGame = new Game();
            $newGame->setAttribute('name', $this->name);
            $newGame->setAttribute('title', 'League of Legends');
            $newGame->setAttribute('uri', 'http://leagueoflegends.com/');
            $newGame->save();

        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $game = Game::where('name', $this->name)->first();
        if ($game) {
            $game->delete();
        }
    }
}
