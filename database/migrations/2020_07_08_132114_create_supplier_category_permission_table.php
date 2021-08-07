<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSupplierCategoryPermissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('supplier_category_permissions')){
            Schema::create('supplier_category_permissions', function (Blueprint $table) {
                $table->integer('user_id');
                $table->integer('supplier_category_id');

                /* $table->foreign('user_id')
                    ->references('id')->on('users')
                    ->onDelete('cascade');
                $table->foreign('category_id')
                    ->references('id')->on('supplier_category')
                    ->onDelete('cascade'); */
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('supplier_category_permissions');
    }
}
