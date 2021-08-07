<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use \App\Supplier;

class CreateScrapersTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('scrapers', function (Blueprint $table) {
            $table->engine = 'MyISAM';
            $table->increments('id');
            $table->integer('supplier_id');
            $table->integer('parent_supplier_id')->nullable()->default(0);
            $table->string('scraper_name')->nullable();
            $table->integer('scraper_type')->nullable();
            $table->integer('scraper_total_urls')->default(0);
            $table->integer('scraper_new_urls')->default(0);
            $table->integer('scraper_existing_urls')->default(0);
            $table->integer('scraper_start_time');
            $table->text('scraper_logic', 65535)->nullable();
            $table->integer('scraper_made_by')->nullable();
            $table->integer('scraper_priority')->nullable();
            $table->string('inventory_lifetime')->nullable();
            $table->integer('next_step_in_product_flow')->nullable();
            $table->timestamps();
        });

        // once table has been generate we need to fill the all information to the new tables
        $suppliers = Supplier::where("scraper_name", "!=", "")->get();

        if (!$suppliers->isEmpty()) {
            foreach ($suppliers as $supplier) {
                \DB::table('scrapers')->updateOrInsert(
                    ['supplier_id' => $supplier->id],
                    [
                        'supplier_id'               => $supplier->id,
                        'parent_supplier_id'        => $supplier->scraper_parent_id,
                        'scraper_name'              => $supplier->scraper_name,
                        'scraper_type'              => $supplier->scraper_type,
                        'scraper_total_urls'        => $supplier->scraper_total_urls,
                        'scraper_new_urls'          => $supplier->scraper_new_urls,
                        'scraper_existing_urls'     => $supplier->scraper_existing_urls,
                        'scraper_start_time'        => is_null($supplier->scraper_start_time) ? 0 : substr($supplier->scraper_start_time, 0, 2),
                        'scraper_logic'             => $supplier->scraper_logic,
                        'scraper_made_by'           => $supplier->scraper_madeby,
                        'scraper_priority'          => $supplier->scraper_priority,
                        'inventory_lifetime'        => $supplier->inventory_lifetime,
                        'next_step_in_product_flow' => 0,
                    ]
                );
            }
        }

        // now delete old fields from supplier table
        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropColumn('scraper_parent_id');
            $table->dropColumn('scraper_name');
            $table->dropColumn('scraper_type');
            $table->dropColumn('scraper_total_urls');
            $table->dropColumn('scraper_new_urls');
            $table->dropColumn('scraper_existing_urls');
            $table->dropColumn('scraper_start_time');
            $table->dropColumn('scraper_logic');
            $table->dropColumn('scraper_madeby');
            $table->dropColumn('scraper_priority');
            $table->dropColumn('inventory_lifetime');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // once table has been generate we need to fill the all information to the new tables

        Schema::table('suppliers', function (Blueprint $table) {
            $table->string('scraper_name')->nullable()->after('reminder_message');
            $table->integer('scraper_parent_id')->nullable()->default(0)->after('scraper_name');
            $table->integer('scraper_type')->nullable()->after('scraper_parent_id');
            $table->integer('scraper_total_urls')->default(0)->after('scraper_type');
            $table->integer('scraper_new_urls')->default(0)->after('scraper_total_urls');
            $table->integer('scraper_existing_urls')->default(0)->after('scraper_new_urls');
            $table->integer('scraper_start_time')->after('scraper_existing_urls');
            $table->text('scraper_logic', 65535)->after('scraper_start_time');
            $table->integer('scraper_madeby')->after('scraper_logic');
            $table->integer('scraper_priority')->after('scraper_madeby');
            $table->string('inventory_lifetime')->after('scraper_priority');
        });

        $suppliers = \DB::table('scrapers')->where("scraper_name", "!=", "")->get();

        if (!empty($suppliers)) {
            foreach ($suppliers as $supplier) {
                \DB::table('suppliers')->where('id', $supplier->supplier_id)->update(
                    [
                        'scraper_parent_id'     => $supplier->parent_supplier_id,
                        'scraper_name'          => $supplier->scraper_name,
                        'scraper_type'          => $supplier->scraper_type,
                        'scraper_total_urls'    => $supplier->scraper_total_urls,
                        'scraper_new_urls'      => $supplier->scraper_new_urls,
                        'scraper_existing_urls' => $supplier->scraper_existing_urls,
                        'scraper_start_time'    => $supplier->scraper_start_time,
                        'scraper_logic'         => $supplier->scraper_logic,
                        'scraper_madeby'        => $supplier->scraper_made_by,
                        'scraper_priority'      => $supplier->scraper_priority,
                        'inventory_lifetime'    => $supplier->inventory_lifetime,
                    ]
                );
            }
        }

        Schema::drop('scrapers');
    }

}
