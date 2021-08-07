<?php

use App\ChatMessage;
use Illuminate\Database\Seeder;

class ChatMessageSeeder extends Seeder
{
    public function run()
    {
        // Load Faker
        $faker = \Faker\Factory::create();

        // Create 1000 customers
        for ($i = 0; $i < 1000; $i++) {
            $message              = new ChatMessage();
            $message->number      = "919016398686";
            $message->message     = $faker->sentence($nbWords = 10, $variableNbWords = true);
            $message->customer_id = 2001;
            $message->approved    = 1;
            $message->status      = 2;
            $message->save();
        }
    }
}
