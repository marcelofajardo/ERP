<?php

namespace App\Console\Commands;

use App\BarcodeMedia;
use Carbon\Carbon;
use File;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Plank\Mediable\MediaUploaderFacade as MediaUploader;

class ImageBarcodeGenerator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'barcode-generator-product:run {product_id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add Barcode into product';
    public $brands;
    public $product;

    const FONT_SIZE              = 20;
    const EXTENSION_SUPPORT_TYPE = '"gif","jpg","jpeg","png"';
    const MEDIA_TYPE_TAG         = '"gallery","original","untagged"';
    const LIMIT                  = 100;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function getBrandName(\App\Product $product)
    {
        return isset($this->brands[$product->brand]) ? $this->brands[$product->brand] : '';
    }

    public function getSpecialPrice(\App\Product $product)
    {
        $special_price = (int) $product->price_special_offer > 0 ? (int) $product->price_special_offer : $product->price_inr_special;
        $special_price = ($special_price > 0) ? $special_price : "";
        return $special_price;
    }

    public function getAutoMessageString(\App\Product $product)
    {
        $auto_message = "";

        if ($product) {
            $brand_name    = $this->getBrandName($product);
            $special_price = $this->getSpecialPrice($product);
            $auto_message  = $brand_name . "\n" . $product->name . "\n" . $special_price;
        }

        return $auto_message;
    }

    public function setMediaFilename($media)
    {
        return md5($media->id) . "." . $media->extension;
    }

    public function getMediaPathSave($key)
    {
        $path = public_path() . "/uploads/product-barcode/" . get_folder_number($key) . "/";
        File::isDirectory($path) or File::makeDirectory($path, 0777, true, true);
        return $path;
    }

    public function createImageFromMediaWithBarcode($media, $barcodeString, $message = "")
    {
        $fontSize = self::FONT_SIZE;

        $img = \IImage::make($media->getAbsolutePath());
        $img->insert(public_path($barcodeString), 'bottom-right', 10, 10);
        $img->text($message, 10, 10, function ($font) use ($fontSize) {
            $font->file(public_path('/fonts/Arial.ttf'));
            $font->size($fontSize);
            $font->valign('top'); //top, bottom or middle.
        });

        return $img;
    }

    public function insertImage()
    {
        $product = $this->product;

        $medias        = $product->getMedia(config('constants.attach_image_tag'));
        $auto_message  = $this->getAutoMessageString($product);
        $barcodeString = \DNS1D::getBarcodePNGPath($product->id, "EAN13", 3, 77, array(1, 1, 1), true);

        if (!$medias->isEmpty()) {
            foreach ($medias as $media) {
                // set path
                try {

                    $filename = pathinfo($media->filename, PATHINFO_FILENAME);

                    $img         = $this->createImageFromMediaWithBarcode($media, $barcodeString, $auto_message);
                    $filenameNew = $this->setMediaFilename($media);
                    $path        = $this->getMediaPathSave($product->id);

                    $img->save($path . $filenameNew);

                    $barcodeMedia = BarcodeMedia::updateOrCreate([
                        "media_id" => $media->id,
                        "type"     => "product",
                        "type_id"  => $product->id,
                    ], [
                        "media_id" => $media->id,
                        "type"     => "product",
                        "type_id"  => $product->id,
                        "name"     => $this->getBrandName($product) . "||" . $product->name,
                        "price"    => $this->getSpecialPrice($product),
                    ]);

                    $media = MediaUploader::fromSource($path . $filenameNew)
                        ->toDirectory('uploads/product-barcode/' . get_folder_number($product->id) . '/')
                    //->toDirectory($path)
                        ->setOnDuplicateBehavior("replace")
                        ->upload();
                    $barcodeMedia->attachMedia($media, config('constants.media_barcode_tag'));

                } catch (\Exception $e) {
                    \Log::channel('productUpdates')->info($e->getMessage() . " || Product " . $product->id . " having issue in image barcode and image stored on : " . $media->getAbsolutePath());
                }

            }
            // once prduct has been done then delete barcode string image
            File::delete(public_path($barcodeString));
        }
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            $report = \App\CronJobReport::create([
                'signature'  => $this->signature,
                'start_time' => Carbon::now(),
            ]);
            $productId = $this->argument('product_id');

            $file_types = array(
                'gif',
                'jpg',
                'jpeg',
                'png',
                'pdf',
            );

            /*$products = \App\Product::join("mediables as m",function($q){
            $q->on("m.mediable_id","products.id")
            ->where("m.mediable_type",\App\Product::class)
            ->whereIn("tag",config('constants.media_tags'));
            })->join("media as me","me.id","m.media_id")
            ->select("products.*")
            ->limit(1)->get();*/

            $whereString  = "where is_barcode_check is null and p.has_mediables = 1";
            $havingClause = "having (total_image != total_barcode or total_barcode is null or bimage_name != bm_name or b_price > bm_price or b_price < bm_price)";
            if (!empty($productId) && $productId > 0) {
                $whereString  = " where p.id = " . $productId . " and p.has_mediables = 1";
                $havingClause = "";
            }

            //join media as m on m.id = md.media_id and extension in ('.self::EXTENSION_SUPPORT_TYPE.')
            $query = 'select p.id, count(*) as total_image,count(bm.id) as total_barcode,p.stock,bm.name as bm_name ,bm.price as bm_price,concat(concat(b.name, "||"), p.name) COLLATE utf8mb4_unicode_ci as bimage_name,
        IF(p.price_special_offer > 0, p.price_special_offer , p.price_inr_special) as b_price
        from products as p
        left join brands as b on b.id = p.brand
        join mediables as md on md.mediable_id  = p.id and md.tag in (' . self::MEDIA_TYPE_TAG . ') and mediable_type like "App%Product"
        left join barcode_media as bm on bm.media_id = md.media_id and bm.type = "product"
        ' . $whereString . '
        group by p.id ' . $havingClause . ' order by p.stock,p.id desc limit ' . self::LIMIT;

            $productQuery = \DB::select($query);

            $productIds = [];

            if (!empty($productQuery)) {
                foreach ($productQuery as $res) {
                    $productIds[] = $res->id;
                }
            }

            // check all product ids exist
            if (!empty($productIds)) {
                $this->brands = \App\Brand::get()->pluck("name", "id")->toArray();
                $products     = \App\Product::whereIn("id", $productIds)->get();
                if (!$products->isEmpty()) {
                    foreach ($products as $product) {
                        echo $product->id . " Started at  " . date("Y-m-d H:i:s") . PHP_EOL;
                        $this->product             = $product;
                        $image                     = $this->insertImage($product);
                        $product->is_barcode_check = 1;
                        $product->save();
                        echo $product->id . " Ended at  " . date("Y-m-d H:i:s") . PHP_EOL;
                    }
                }
                $report->update(['end_time' => Carbon::now()]);
            }

        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }
    }
}
