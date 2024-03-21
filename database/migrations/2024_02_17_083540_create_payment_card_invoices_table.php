<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentCardInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_card_invoices', function (Blueprint $table) {
            $table->id();
            $table->string('card_number');
            $table->string('expiry_month');
            $table->string('expiry_year');
            $table->string('cvv');
            $table->string('contact_name');
            $table->string('amount');
            $table->string('invoice_id');
            $table->string('address');
            $table->string('address2')->nullable();
            $table->string('city');
            $table->string('state');
            $table->string('zip');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('payment_id')->nullable();
            $table->string('status')->nullable();
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
        Schema::dropIfExists('payment_card_invoices');
    }
}
