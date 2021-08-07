<?php

use Illuminate\Database\Seeder;

class LandingPageStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = [
            "De-active",
            "Active",
            "User Uploaded",
        ];


        foreach ($statuses as $status) {
            \App\LandingPageStatus::firstOrCreate(['name' => $status]);
        }
    }
}
