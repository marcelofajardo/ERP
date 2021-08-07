<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('status', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
        });

        // Insert default data
        DB::table('status')->insert(['name' => 'import']);
        DB::table('status')->insert(['name' => 'scrape']);
        DB::table('status')->insert(['name' => 'ai']);
        DB::table('status')->insert(['name' => 'auto crop']);
        DB::table('status')->insert(['name' => 'crop approval']);
        DB::table('status')->insert(['name' => 'crop sequencing']);
        DB::table('status')->insert(['name' => 'image enhancement']);
        DB::table('status')->insert(['name' => 'crop approval confirmation']);
        DB::table('status')->insert(['name' => 'final approval']);
        DB::table('status')->insert(['name' => 'manual attribute']);
        DB::table('status')->insert(['name' => 'push to magento']);
        DB::table('status')->insert(['name' => 'in magento']);

        // Update table products
        Schema::table('products', function (Blueprint $table) {
            $table->integer('status_id')->unsigned()->default('1')->after('id');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->foreign('status_id')->references('id')->on('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign('products_status_id_foreign');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('status_id');
        });

        Schema::dropIfExists('status');
    }
}
