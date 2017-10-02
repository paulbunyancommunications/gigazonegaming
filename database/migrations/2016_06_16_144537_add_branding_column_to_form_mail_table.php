<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBrandingColumnToFormMailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('form_mail') and !Schema::hasColumn('form_mail','branding' )) {
            Schema::table('form_mail', function (Blueprint $table) {
                $table->addColumn('text', 'branding');
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
        if (Schema::hasTable('form_mail') and Schema::hasColumn('form_mail','branding' )) {
            Schema::table('form_mail', function (Blueprint $table) {
                $table->dropColumn('branding');
            });
        }
    }
}
