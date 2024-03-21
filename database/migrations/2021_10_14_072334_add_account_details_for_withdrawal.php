<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAccountDetailsForWithdrawal extends Migration
{
    /**
     * amount,bank_name,account_number,ifsc
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('withdrawal_requests', function (Blueprint $table) {
            $table->string('account_holder_name')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('ifsc')->nullable();
            $table->string('phone_no')->nullable();
            $table->string('bank_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('withdrawal_requests', function (Blueprint $table) {
            $table->dropColumn('account_holder_name');
            $table->dropColumn('bank_name');
            $table->dropColumn('account_number');
            $table->dropColumn('ifsc');
            $table->dropColumn('phone_no');
        });
    }
}
