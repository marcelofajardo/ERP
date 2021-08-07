<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call(TwilioCredentialSeeder::class);
        $this->call(PermissionTableSeeder::class);
        $this->call(UserTableSeeder::class);
        $this->call(ProductsTableSeeder::class);
        $this->call(CustomerTableSeeder::class);
        $this->call(ChatMessageSeeder::class);
        $this->call(LandingPageStatusSeeder::class);
        $this->call(EmailLeadsSeeder::class);
    }
}
