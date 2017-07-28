<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTypeFieldOnProjectLocation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('project_locations', function(Blueprint $table) {
            $table->string('project_type')->nullable();
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
            $table->dropColumn('project_type')->nullable();
            $table->dropColumn('category_id')->nullable();
        });
    }
}
