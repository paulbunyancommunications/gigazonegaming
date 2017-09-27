<?php

use App\Models\Championship\Game;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeMaddenNfl18GameIfItDoesNotExistAlready extends Migration
{
    protected $name = 'madden-nfl-18';
    protected $title = 'Madden NFL 18';
    protected $uri = "https://www.easports.com/madden-nfl";
    protected $description = "";


    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $exists = $this->exists();
        if(!$exists) {
            $overwatch = new Game();
            $overwatch->name = $this->name;
            $overwatch->title = $this->title;
            $overwatch->uri = $this->uri;
            $overwatch->description = $this->description;
            $overwatch->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $exists = $this->exists();

        if($exists) {
            $exists->delete();
        }

    }

    /**
     * @return mixed
     */
    protected function exists()
    {
        $exists = Game::where('name', $this->name)->orWhere('title', $this->title)->first();
        return $exists;
    }
}
