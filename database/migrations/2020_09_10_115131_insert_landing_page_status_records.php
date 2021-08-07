<?php

use Illuminate\Database\Migrations\Migration;

class InsertLandingPageStatusRecords extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        try {
            \DB::statement("INSERT INTO `landing_page_statuses` (`id`, `name`, `created_at`, `updated_at`) VALUES
    (1, 'De-active', '2020-09-10 04:22:41', '2020-09-10 04:22:41'),
    (2, 'Active', '2020-09-10 04:22:41', '2020-09-10 04:22:41'),
    (3, 'User Uploaded', '2020-09-10 04:22:41', '2020-09-10 04:22:41');");

        } catch (\Exception $e) {

        }

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
