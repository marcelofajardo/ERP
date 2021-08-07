<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblBulkMessageCutomerDnd extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::create('customer_bulk_messages_dnd', function (Blueprint $table) {
            $table->unsignedBigInteger('customer_id');
            $table->string('filter', 251);
            $table->timestamps();
            $table->primary(['customer_id', 'filter']);
            
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
    }
}
