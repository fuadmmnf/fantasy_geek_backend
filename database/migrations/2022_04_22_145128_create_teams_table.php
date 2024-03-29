<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeamsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->integer('type'); // 0 => match_rooster, 1 => user_team,
            $table->string('name');
            $table->string('code');
            $table->jsonb('key_members')->nullable(); // 2 length array, index 0 captain id, index 1 vice captain id
            $table->jsonb('team_members')->nullable();
//            $table->jsonb('combination')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('teams');
    }
}
