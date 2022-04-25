<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('match_id');
            $table->string('name');
            $table->double('entry_fee')->nullable(false);
            $table->integer('winner_count')->nullable(false);
            $table->double('award_amount')->nullable(false);
//            $table->double('award_decrease_rate')->nullable(false);
            $table->json('prize_list')->nullable();
            $table->double('total_award_amount')->nullable(false);
            $table->integer('entry_capacity')->nullable(false);
            $table->integer('entry_count')->default(0);
            $table->jsonb('user_standings')->nullable();

            $table->timestamps();

            $table->foreign('match_id')->references('id')->on('matches')
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
        Schema::dropIfExists('contests');
    }
}
