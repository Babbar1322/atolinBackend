<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserRefundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_refunds', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_id');
            $table->integer('amount')->default(0);
            $table->integer('user_id')->nullable();
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('destination');
            $table->enum('status', ['pending','succeeded','failed'])->default('pending');
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
        Schema::dropIfExists('user_refunds');
    }
}
