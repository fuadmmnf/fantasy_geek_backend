<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('matches', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pointdistribution_id');
            $table->unsignedBigInteger('team1_id');
            $table->unsignedBigInteger('team2_id');
            $table->string('api_matchid');
            $table->integer('status')->default(0); // 0 => upcoming, 1 => running, 2 => completed, 3 => canceled
            $table->string('name');
            $table->dateTime('starting_time');
            $table->string('team1_monogram');
            $table->string('team2_monogram');
            $table->timestamps();

            $table->foreign('pointdistribution_id')->references('id')->on('pointdistributions')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('team1_id')->references('id')->on('teams')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('team2_id')->references('id')->on('teams')
                ->onUpdate('cascade')->onDelete('cascade');


            $table->index(['api_matchid',]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('matches');
    }
}
