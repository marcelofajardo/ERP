<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AdjustColumnsInHashTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hash_tags', function (Blueprint $table) {
            $table->dropColumn('username');
            $table->dropColumn('image_url');
            $table->dropColumn('description');
            $table->dropColumn('comments');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hash_tags', function (Blueprint $table) {
            $table->string('username');
            $table->text('image_url');
            $table->text('description');
            $table->longText('comments')->nullable();
        });
    }
}
