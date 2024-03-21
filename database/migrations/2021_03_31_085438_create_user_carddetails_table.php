<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserCarddetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_stripe_carddetails', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('stripe_uid')->nullable();
            $table->string('card_id')->nullable();
            $table->string('fingerprint')->nullable()->unique();
            $table->string('last4')->nullable();
            $table->string('brand')->nullable();
            $table->string('country')->nullable();
            $table->string('exp_month')->nullable();
            $table->string('exp_year')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_stripe_carddetails');
    }
}
