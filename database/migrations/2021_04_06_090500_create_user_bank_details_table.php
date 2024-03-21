<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserBankDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_stripe_bank_details', function (Blueprint $table) {
            $table->id();
            $table->string('bank_id')->nullable();
            $table->string('stripe_uid')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('fingerprint')->nullable()->unique();
            $table->string('last4')->nullable();
            $table->string('country')->nullable();
            $table->string('routing_number')->nullable();
            $table->string('currency')->nullable();
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
        Schema::dropIfExists('user_stripe_bank_details');
    }
}
