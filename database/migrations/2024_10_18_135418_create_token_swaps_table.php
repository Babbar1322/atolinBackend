<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTokenSwapsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('token_swaps', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->enum('from', ["ATOLIN", "TOKEN"]);
            $table->enum('to', ["ATOLIN", "TOKEN"]);
            $table->integer('atolin_amount');
            $table->integer('token_amount');
            $table->integer('fee');
            $table->string('token_symbol');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('token_swaps');
    }
}
