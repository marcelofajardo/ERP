<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateSentAtSentOnFieldsFcmNotifications extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('push_fcm_notifications', function (Blueprint $table) {
            \DB::statement("ALTER TABLE `push_fcm_notifications` MODIFY `sent_on` datetime NULL DEFAULT NULL;");
            \DB::statement("ALTER TABLE `push_fcm_notifications` MODIFY `sent_at` datetime NULL DEFAULT NULL;");

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('push_fcm_notifications', function (Blueprint $table) {
            $table->dropColumn('sent_on');
            $table->dropColumn('sent_at');
        });
    }
}
