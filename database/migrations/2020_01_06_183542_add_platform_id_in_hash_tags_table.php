<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPlatformIdInHashTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hash_tags', function (Blueprint $table) {
            $table->integer('platforms_id')->after('id')->index();
        });

        //updating existing hash tag data to instagram, since we were using it for adding instagram hash tags until now
        DB::table('hash_tags')->where('platforms_id', 0)->update(['platforms_id' => '1']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hash_tags', function (Blueprint $table) {
            $table->dropColumn(['platforms_id']);
        });
    }
}
