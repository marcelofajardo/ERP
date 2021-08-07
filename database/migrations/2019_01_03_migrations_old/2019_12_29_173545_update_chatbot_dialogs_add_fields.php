<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class UpdateChatbotDialogsAddFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('chatbot_dialogs', function ($table) {
            $table->string('metadata')->nullable()->after('match_condition');
            $table->string('previous_sibling')->nullable()->after('metadata');
            $table->string('response_type')->nullable()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('chatbot_dialogs', function ($table) {
            $table->dropColumn('metadata');
            $table->dropColumn('previous_sibling');
            $table->dropColumn('response_type');
        });
    }
}
