<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFaceIdOnRawVideoResults extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('raw_video_results', function(Blueprint $table) {
            $table->string('face_id');
        });

        Schema::table('raw_videos', function(Blueprint $table) {
            $table->dropColumn('processed');
            $table->timestamp('processing_time')->nullable();
            $table->timestamp('completed_time')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('raw_video_results', function(Blueprint $table) {
            $table->dropColumn('face_id');
        });

        Schema::table('raw_videos', function(Blueprint $table) {
            $table->tinyInteger('processed');
        });
    }
}
