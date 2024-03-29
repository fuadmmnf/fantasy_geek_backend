<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlayersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('players', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('playerposition_id');
            $table->unsignedBigInteger('api_pid');
            $table->string('name');
            $table->double('rating')->default(0.0);
            $table->string('code');
            $table->string('bowlingstyle')->nullable();
            $table->string('battingstyle')->nullable();
            $table->string('image')->nullable();
            // strike rate and players profile
            $table->timestamps();

            $table->foreign('playerposition_id')->references('id')->on('playerpositions')
                ->onUpdate('cascade')->onDelete('cascade');


            $table->index(['api_pid']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('players');
    }
}
