<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSenderReceiverToHubstaffActivitySummariesTable extends Migration
{

    public function __construct()
    {
        \DB::getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('json', 'string');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hubstaff_activity_summaries', function (Blueprint $table) {
            $table->integer('sender');
            $table->integer('receiver');
            $table->string('forworded_person');
            $table->json('approved_ids')->nullable();
            $table->json('rejected_ids')->nullable();
            $table->boolean('final_approval')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hubstaff_activity_summaries', function (Blueprint $table) {
            //
        });
    }
}
