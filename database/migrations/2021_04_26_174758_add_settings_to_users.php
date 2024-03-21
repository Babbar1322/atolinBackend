<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSettingsToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('notifications_settings', ['ENABLED', 'DISABLED'])->default('DISABLED')->after('profile_photo_path');
            $table->json('privacy_settings')->nullable()->after('notifications_settings');
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
            $table->dropColumn('notifications_settings');
            $table->dropColumn('privacy_settings');
        });
    }
}
