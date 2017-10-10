<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRawVideoResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('raw_video_results', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('raw_video_id')->unsigned();
            $table->string('file');
            $table->json('result')->nullable();
            $table->timestamps();

            $table->foreign('raw_video_id')
                ->references('id')->on('raw_videos')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('raw_video_results');
    }
}
