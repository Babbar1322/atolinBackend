<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserTransactionsDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_stripe_transactions_details', function (Blueprint $table) {
            $table->id();
            $table->string('stripe_tid')->nullable();
            $table->string('stripe_uid')->nullable();
            $table->string('email')->nullable();
            $table->string('card_id')->nullable();
            $table->string('amount')->nullable();
            $table->string('currency')->nullable();
            $table->string('t_type')->nullable();
            $table->timestamp('request_at')->nullable();
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
        Schema::dropIfExists('user_stripe_transactions_details');
    }
}
