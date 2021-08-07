<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexToAutoRefreshPages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('auto_refresh_pages', function(Blueprint $table)
        {
            $table->index(['page']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('auto_refresh_pages', function(Blueprint $table)
        {
            $table->dropIndex(['page']);
        });
    }
}
