<?php

use App\Models\Championship\Game;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeOverwatchGameIfItDoesNotExistAlready extends Migration
{
    protected $name = 'overwatch';
    protected $title = 'Overwatch';
    protected $uri = "https://playoverwatch.com/en-us/";
    protected $description = "";


    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::connection('mysql_champ')->hasTable('games')) {
            $exists = $this->exists();
            if (!$exists) {
                $overwatch = new Game();
                $overwatch->name = $this->name;
                $overwatch->title = $this->title;
                $overwatch->uri = $this->uri;
                $overwatch->description = $this->description;
                $overwatch->save();
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if(Schema::connection('mysql_champ')->hasTable('games')) {
            $exists = $this->exists();
            if($exists) {
                $exists->delete();
            }

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
