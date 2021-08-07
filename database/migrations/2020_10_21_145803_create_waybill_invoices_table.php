<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWaybillInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('waybill_invoices', function (Blueprint $table) {
            $table->increments('id');
            $table->string("line_type",10)->nullable();
            $table->string("billing_source",10)->nullable();
            $table->string("original_invoice_number")->nullable();        
            $table->string("invoice_number",40)->nullable();
            $table->string("invoice_identifier",40)->nullable();
            $table->string("invoice_type",10)->nullable();
            $table->string("invoice_date",15)->nullable();
            $table->text("payment_terms")->nullable();
            $table->string("due_date",10)->nullable();
            $table->string("parent_account",25)->nullable();
            $table->string("billing_account")->nullable();
            $table->string("billing_account_name",40)->nullable();
            $table->string("billing_account_name_additional",40)->nullable();
            $table->string("billing_address_1")->nullable();
            $table->string("billing_postcode")->nullable();
            $table->string("billing_city")->nullable();
            $table->string("billing_state_province",50)->nullable();
            $table->string("billing_country_code",10)->nullable();
            $table->string("billing_contact",40)->nullable();
            $table->string("shipment_number",255);
            $table->string("shipment_date",15)->nullable();
            $table->string("product")->nullable();
            $table->string("product_name",100)->nullable();
            $table->integer("pieces")->nullable();
            $table->string("origin",25)->nullable();
            $table->string("orig_name",25)->nullable();
            $table->string("orig_country_code",10)->nullable();
            $table->string("orig_country_name",40)->nullable();
            $table->string("senders_name")->nullable();
            $table->string("senders_city")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('waybill_invoices');
    }
}
