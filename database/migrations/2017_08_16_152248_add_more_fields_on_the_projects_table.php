<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMoreFieldsOnTheProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Schema::table('projects', function(Blueprint $table) {
            $table->integer('total_target_runs')->default(0);
            $table->integer('total_target_hits')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Schema::table('projects', function(Blueprint $table) {
            $table->dropColumn('total_target_runs');
            $table->dropColumn('total_target_hits');
        });
    }
}
