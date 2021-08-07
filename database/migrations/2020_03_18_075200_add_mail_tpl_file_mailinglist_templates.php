<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMailTplFileMailingListTemplates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mailinglist_templates', function (Blueprint $table) {
            $table->string('mail_class')->nullable()->after('name');
            $table->string('mail_tpl')->nullable()->after('mail_class');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mailinglist_templates', function (Blueprint $table) {
            $table->dropColumn('mail_class');
            $table->dropColumn('mail_tpl');
        });
    }
}
