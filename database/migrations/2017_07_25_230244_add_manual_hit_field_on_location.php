<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddManualHitFieldOnLocation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('project_locations', function(Blueprint $table) {
            $table->integer('category_id')->unsigned();
            $table->integer('manual_hits')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('project_locations', function(Blueprint $table) {
            $table->dropColumn('category_id')->unsigned();
            $table->dropColumn('manual_hits')->default(0);
        });
    }
}
