<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOverflowColumnToDatabase extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
      if (!Schema::connection('mysql_champ')->hasColumn('tournaments', 'overflow')) {
          Schema::connection('mysql_champ')->table('tournaments', function (Blueprint $table) {
              $table->boolean('overflow')->default(0);
          });
      }
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
      if (Schema::connection('mysql_champ')->hasColumn('tournaments','overflow')) {
          Schema::connection('mysql_champ')->table('tournaments', function (Blueprint $table) {
              $table->dropColumn('overflow');
          });
      }
  }
}
