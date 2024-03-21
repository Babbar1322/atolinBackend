<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserTransactionDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_transaction_details', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_id')->nullable();
            $table->integer('user_id')->nullable();
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('source_id')->nullable();
            $table->string('amount')->nullable();
            $table->string('t_type')->nullable();
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
        Schema::dropIfExists('user_transaction_details');
    }
}
