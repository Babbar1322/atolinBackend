<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToNotificationUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('notification_users', function (Blueprint $table) {
            $table->enum('status', ['READ', 'UNREAD' , 'DECLINED' , 'DELETED','ARCHIVED'])->default('UNREAD');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('notification_users', function (Blueprint $table) {
            //
        });
    }
}
