<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlayerItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('player_items', function (Blueprint $table) {
            $table->integer('player_id')->unsinged();
            $table->integer('item_id')->unsinged();
            $table->integer('item_count');

            $table->primary(['player_id','item_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('player_items');
    }
}
