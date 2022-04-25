<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsercontestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usercontests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('contest_id');
            $table->unsignedBigInteger('team_id');
            $table->unsignedBigInteger('transaction_id');
            $table->unsignedBigInteger('captain_id');
            $table->unsignedBigInteger('vicecaptain_id');
            $table->jsonb('team_stats')->nullable();
            $table->double('score')->default(0.0);
            $table->integer('ranking')->default(0);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('contest_id')->references('id')->on('contests')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('team_id')->references('id')->on('teams')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('captain_id')->references('id')->on('players')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('vicecaptain_id')->references('id')->on('players')
                ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('usercontests');
    }
}
