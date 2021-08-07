<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterWhatsappConfigTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('whatsapp_configs', function (Blueprint $table) {
            $table->string('instance_id')->nullable()->after('recharge_date');
            $table->string('token')->nullable()->after('instance_id');
            $table->integer('is_default')->nullable()->default(0)->after('token');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('whatsapp_configs', function (Blueprint $table) {
            $table->dropColumn('instance_id');
            $table->dropColumn('token');
            $table->dropColumn('is_default');
        });
    }
}
