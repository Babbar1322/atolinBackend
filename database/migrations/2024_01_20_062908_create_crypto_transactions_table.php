<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCryptoTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crypto_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('block_hash')->nullable();
            $table->string('contract_address')->nullable();
            $table->string('fee')->nullable();
            $table->string('amount')->nullable();
            $table->string('from')->nullable();
            $table->string('gas_price')->nullable();
            $table->string('hash')->nullable();
            $table->string('status')->nullable();
            $table->string('to')->nullable();
            $table->string('transaction_type')->nullable();
            $table->string('type')->nullable();
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
        Schema::dropIfExists('crypto_transactions');
    }
}
