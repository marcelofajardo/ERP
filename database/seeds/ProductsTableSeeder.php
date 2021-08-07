<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Product;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create products statuses
        $this->_createStatuses();

        // Load Faker
        $faker = \Faker\Factory::create();
        $status = DB::table('status')->orderBy('id')->first();
        if(!$status) {
            DB::table('status')->insert(['name' => 'import']);
        }
        $status_id = DB::table('status')->orderBy('id')->first()->id;
        // Create 10.000 products
        for ($i = 0; $i < 2; $i++) {
            $product = new Product;
            $product->status_id = $status_id;
            $product->name = $faker->name;
            $product->short_description = $faker->paragraph;
            $product->sku = $faker->ean13;
            $product->size = $this->_getRandomSizes();
            $product->price = rand(100, 1500);
            $product->stock = rand(0, 3);
            $product->composition = $this->_getRandomComposition();
            $product->color = $faker->colorName;
            $product->save();
        }
    }

    private function _createStatuses()
    {
        // Set current statuses
        $arrStatus = [
            'import',
            'scrape',
            'ai',
            'auto crop',
            'crop approval',
            'crop sequencing',
            'image enhancement',
            'crop approval confirmation',
            'final approval',
            'manual attribute',
            'push to magento',
            'in magento',
            'unable to scrape',
            'unable to scrape image',
            'is being cropped',
            'crop skipped',
            'is being enhanced',
            'crop rejected',
            'is being sequenced'
        ];

        // Insert all of them
        foreach ($arrStatus as $status) {
            DB::table('status')->insert(['name' => trim($status)]);
        }
    }

    private function _getRandomSizes()
    {
        // Array with sizes
        $arrSizes = [
            'XS,L,XL',
            '00,0,2,4,8,10,12',
            '36,37,39,39.5,42',
            '7,8,9,10'
        ];

        // Return random sizes
        return $arrSizes[ rand(0, 3) ];
    }

    private function _getRandomComposition()
    {
        // Array with sizes
        $arrComposition = [
            '100 % nylon',
            '100 % wool',
            '100 % cotton',
            'leather'
        ];

        // Return random sizes
        return $arrComposition[ rand(0, 3) ];
    }
}
