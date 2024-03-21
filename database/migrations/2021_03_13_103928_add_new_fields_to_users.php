<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewFieldsToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('lastname', 15)->nullable()->after('name');
            $table->string('contact')->nullable()->after('email');
            $table->string('kyc_type')->nullable()->after('contact');
            $table->string('kyc_number')->nullable()->after('kyc_type');
            $table->date('expiry_date')->nullable($value = true)->after('kyc_number');
            $table->string('issuing_country')->nullable()->after('expiry_date');
            $table->tinyInteger('status')->default(1)->after('issuing_country');
            $table->string('utype')->default('user')->after('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
