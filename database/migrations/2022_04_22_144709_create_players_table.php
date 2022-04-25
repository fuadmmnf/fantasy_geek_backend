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
            $table->string('api_pid');
            $table->string('name');
            $table->double('rating');
            $table->string('code')->nullable(false);
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
