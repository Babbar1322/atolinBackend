<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailLoginOTPSTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_login_o_t_p_s', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('otp');
            $table->enum('status',['ACTIVE','SUCCESS','EXPIRED']);
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
        Schema::dropIfExists('email_login_o_t_p_s');
    }
}
