<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVerificationCodeToTeamTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::connection('mysql_champ')->hasColumn('teams', 'verification_code')) {
            Schema::connection('mysql_champ')->table('teams', function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->string('verification_code')->default("abcdefgh")->after('name');
            });
        }
        $teamUpdated = \App\Models\Championship\Team::all()->toArray();
        foreach ($teamUpdated as $team){
            \App\Models\Championship\Team::where('id','=',$team['id'])->update(['verification_code'=> str_random(8)]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::connection('mysql_champ')->hasColumn('teams','verification_code')) {
            Schema::connection('mysql_champ')->table('teams', function (Blueprint $table) {
                $table->dropColumn('verification_code');
            });
        }
    }
}
