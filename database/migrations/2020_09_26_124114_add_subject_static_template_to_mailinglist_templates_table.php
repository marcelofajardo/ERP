<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSubjectStaticTemplateToMailinglistTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mailinglist_templates', function (Blueprint $table) {
            $table->String('subject',255)->nullable();
            $table->text('static_template')->nullable();
        });
        
        \DB::statement("ALTER TABLE `mailinglist_templates` CHANGE `mail_tpl` `mail_tpl` VARCHAR(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mailinglist_templates', function (Blueprint $table) {
            $table->dropColumn('store_id');
            $table->dropColumn('static_template');
        });
    }
}
