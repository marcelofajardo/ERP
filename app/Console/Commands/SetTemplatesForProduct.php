<?php

namespace App\Console\Commands;

use App\Product;
use App\ProductTemplate;
use App\Template;
use DB;
use Illuminate\Console\Command;
use Plank\Mediable\Media;

class SetTemplatesForProduct extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'template:product';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gets Selected Product From Template and Process Them to Template Queue';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            $totalCount = 0;
            $templates  = Template::where('auto_generate_product', 1)->get();
            foreach ($templates as $template) {
                if ($totalCount > 10000) {
                    break;
                    print('Completed making 10000 entries');
                }
                //chunk this in 1000

                Product::chunk(1000, function ($products) use ($totalCount, $template) {

                    foreach ($products as $product) {
                        $checkTag = 'template_' . $template->id;
                        $mediable = DB::table('mediables')->where('tag', $checkTag)->where('mediable_id', $product->id)->first();

                        if ($mediable != null) {
                            break;
                        }

                        if ($product->getMedia(config('constants.media_tags'))->count() == 0) {
                            break;
                        }

                        if ($totalCount > 10000) {
                            break;
                            print('Completed making 10000 entries');
                        }

                        $productTemplate                = new ProductTemplate;
                        $productTemplate->template_no   = $template->id;
                        $productTemplate->product_title = $product->name;
                        $productTemplate->brand_id      = $product->brand;
                        $productTemplate->currency      = 'eur';
                        if (empty($product->price)) {
                            $product->price = 0;
                        }
                        if (empty($product->price_eur_discounted)) {
                            $product->price_eur_discounted = 0;
                        }
                        $productTemplate->price            = $product->price;
                        $productTemplate->discounted_price = $product->price_eur_discounted;
                        $productTemplate->product_id       = $product->id;
                        $productTemplate->is_processed     = 0;
                        $productTemplate->save();
                        $totalCount++;
                        foreach ($product->getMedia(config('constants.media_tags'))->all() as $media) {
                            $media = Media::find($media->id);
                            $tag   = 'template-image';
                            $productTemplate->attachMedia($media, $tag);
                        }
                    }
                });

            }
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }
    }
}
