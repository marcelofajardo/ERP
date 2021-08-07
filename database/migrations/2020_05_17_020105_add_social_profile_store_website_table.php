<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSocialProfileStoreWebsiteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('store_websites', function (Blueprint $table) {
            $table->string('instagram')->nullable()->after('magento_password');
            $table->text('instagram_remarks')->nullable()->after('instagram');
            $table->string('facebook')->nullable()->after('instagram_remarks');
            $table->text('facebook_remarks')->nullable()->after('facebook');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('store_websites', function (Blueprint $table) {
            $table->dropColumn('instagram');
            $table->dropColumn('instagram_remarks');
            $table->dropColumn('facebook');
            $table->dropColumn('facebook_remarks');
        });
    }
}
