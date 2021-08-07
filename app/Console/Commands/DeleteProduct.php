<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Product;
use File;

class DeleteProduct extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'product:delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Product Delete';

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
        //
        /*$deleteby = $this->ask('Delete product by ?');
        $ids      = $this->ask('Enter Ids');
        $limit    = $this->ask('Enter limit');

        if(!empty($deleteby) && ($deleteby == "supplier" || $deleteby == "product"  || $deleteby == "soldout")) {
            if($deleteby == "supplier") {
                $supplierProduct = \App\ProductSupplier::whereIn("supplier_id",explode(",",$ids))->limit($limit)->get();
                if(!$supplierProduct->isEmpty()) {
                    foreach($supplierProduct as $sp) {
                        $product = \App\Product::find($sp->product_id);
                        if($product) {
                            $this->deleteProduct($product);
                        }
                    }
                }
            }else if($deleteby == "product"){
                $products = \App\Product::whereIn("id",explode(",",$ids))->limit($limit)->get();
                if(!$products->isEmpty()) {
                    foreach($products as $p) {
                        $this->deleteProduct($p);
                    }
                }
            }else if($deleteby == "soldout"){
                $products = \App\Product::where("stock","<=" ,0)->where("supplier","!=", "in-stock")->limit($limit)->get();
                if(!$products->isEmpty()) {
                    foreach($products as $p) {
                        $this->deleteProduct($p);
                    }
                }
            }
        }*/

        for ($i=0; $i<20000; $i++) { 

            $product = \App\Product::leftJoin("order_products as op","op.product_id","products.id")->where("stock","<=" ,0)
            ->where("supplier","!=", "in-stock")
            ->havingRaw("op.product_id is null")
            ->groupBy("products.id")
            ->select(["products.*","op.product_id"])
            ->first();

            if($product) {
               $this->deleteProduct($product);
            }
        }
    }

    public function deleteProduct(Product $product)
    {
        // check if product is empty then delete only 
        if($product->orderproducts->isEmpty()) {
            $id = $product->id;
            echo "Started to delete #".$id."\n";
            if (!$product->media->isEmpty()) {
                foreach ($product->media as $image) {
                    $image_path = $image->getAbsolutePath();
                    if (File::exists($image_path)) {
                        echo $image_path." Being Deleted for #".$product->id."\n";
                        File::delete($image_path);
                    }
                    $image->delete();
                }
            }

            // delete supplier detach
            $product->suppliers()->detach();
            
            if ($product->user()) {
                $product->user()->detach();
            }
            
            $product->references()->delete();
            $product->suggestions()->detach();


            //\App\BloggerProductImage::where("product_id",$product->id)->delete();
            \App\CropAmends::where("product_id",$product->id)->delete();
            \App\CroppedImageReference::where("product_id",$product->id)->delete();
            \App\ErpLeadSendingHistory::where("product_id",$product->id)->delete();
            \App\ErpLeads::where("product_id",$product->id)->delete();
            \App\Instruction::where("product_id",$product->id)->delete();
            \App\InventoryStatusHistory::where("product_id",$product->id)->delete();
            \App\LandingPageProduct::where("product_id",$product->id)->delete();
            \App\ListingHistory::where("product_id",$product->id)->delete();
            //\App\ListingPayments::where("product_id",$product->id)->delete();
            \App\Loggers\LogListMagento::where("product_id",$product->id)->delete();
            \App\LogScraperVsAi::where("product_id",$product->id)->delete();
            \App\Notification::where("product_id",$product->id)->delete();
            \DB::statement("Delete from private_view_products where product_id = ".$product->id);
            //\App\PrivateViewProduct::where("product_id",$product->id)->delete();
            //\App\PrivateView::where("product_id",$product->id)->delete();
            \DB::statement("Delete from product_attributes where product_id = ".$product->id);
            //\App\ProductAttribute::where("product_id",$product->id)->delete();
            \App\ProductCategoryHistory::where("product_id",$product->id)->delete();
            \App\ProductColorHistory::where("product_id",$product->id)->delete();
            \App\ProductDispatch::where("product_id",$product->id)->delete();
            \App\ProductLocationHistory::where("product_id",$product->id)->delete();
            \App\ProductPushErrorLog::where("product_id",$product->id)->delete();
            \App\ProductQuicksellGroup::where("product_id",$product->id)->delete();
            \App\ProductReference::where("product_id",$product->id)->delete();
            \App\ProductSizes::where("product_id",$product->id)->delete();
            \App\ProductStatus::where("product_id",$product->id)->delete();
            \App\ProductStatusHistory::where("product_id",$product->id)->delete();
            \App\ProductTemplate::where("product_id",$product->id)->delete();
            \App\Product_translation::where("product_id",$product->id)->delete();
            \App\ProductVerifyingUser::where("product_id",$product->id)->delete();
            \App\PurchaseDiscount::where("product_id",$product->id)->delete();

            \DB::statement("Delete from purchase_product_supplier where product_id = ".$product->id);

            //\App\PurchaseProductSupplier::where("product_id",$product->id)->delete();
            \App\PurchaseProduct::where("product_id",$product->id)->delete();
            \App\RejectedImages::where("product_id",$product->id)->delete();
            \App\ReturnExchangeProduct::where("product_id",$product->id)->delete();
            \App\ScrapActivity::where("scraped_product_id",$product->id)->delete();
            \App\ScrapeQueues::where("product_id",$product->id)->delete();
            \App\ScrapedProducts::where("sku",$product->sku)->delete();
            \App\SiteCroppedImages::where("product_id",$product->id)->delete();
            //\DB::statement("Delete from site_products where product_id = ".$product->id);
            //\App\StockProduct::where("product_id",$product->id)->delete();
            \App\StoreWebsiteProductAttribute::where("product_id",$product->id)->delete();
            \App\StoreWebsiteProduct::where("product_id",$product->id)->delete();
            \App\SuggestedProductList::where("product_id",$product->id)->delete();
            \App\SuggestionProduct::where("product_id",$product->id)->delete();
            \App\SupplierDiscountInfo::where("product_id",$product->id)->delete();
            \App\TranslatedProduct::where("product_id",$product->id)->delete();
            //\DB::statement("Delete from user_manual_crops where product_id = ".$product->id);

            //\App\UserManualCrop::where("product_id",$product->id)->delete();
            \App\UserProductFeedback::where("product_id",$product->id)->delete();
            \App\UserProduct::where("product_id",$product->id)->delete();
            \App\WebsiteProduct::where("product_id",$product->id)->delete();
            $product->forceDelete();
            echo "End to delete #".$id."\n";
        }
    }
}
