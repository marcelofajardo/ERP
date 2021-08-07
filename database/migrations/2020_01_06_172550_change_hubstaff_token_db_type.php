<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeHubstaffTokenDbType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        DB::statement('ALTER TABLE users MODIFY COLUMN auth_token_hubstaff TEXT');

        DB::statement('ALTER TABLE users MODIFY COLUMN refresh_token_hubstaff TEXT');
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        DB::statement('ALTER TABLE users MODIFY COLUMN auth_token_hubstaff STRING');

        DB::statement('ALTER TABLE users MODIFY COLUMN refresh_token_hubstaff STRING');
    }
}
