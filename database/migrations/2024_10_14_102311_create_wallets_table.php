<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWalletsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallets', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('from_id');
            $table->integer('amount');
            $table->enum('status', ['PENDING', 'APPROVED' , 'FAILED'])->default('PENDING');
            $table->enum('type', ['DEPOSIT', 'WITHDRAWAL' , 'TRANSFER', 'TOKEN_SWAP', 'REFUND'])->default('DEPOSIT');
            $table->enum('t_type', ['credit', 'debit']);
            $table->string('transaction_id')->nullable();
            $table->integer('fee')->nullable();
            $table->string('remarks')->nullable();
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
        Schema::dropIfExists('wallets');
    }
}
