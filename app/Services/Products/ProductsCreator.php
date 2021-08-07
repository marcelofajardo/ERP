<?php

namespace App\Services\Products;

use App\Http\Controllers\ProductController;
use App\SkuColorReferences;
use Validator;
use Illuminate\Support\Facades\Log;
use App\Brand;
use App\Category;
use App\ColorNamesReference;
use App\Product;
use App\ProductStatus;
use App\ScrapActivity;
use App\Supplier;
use App\Helpers\ProductHelper;
use App\Helpers\StatusHelper;
use App\SupplierBrandCount;
use App\SupplierCategoryCount;
use App\Setting;
use App\Compositions;
use App\DescriptionChange;
use App\ScrappedCategoryMapping;

class ProductsCreator
{
    public function createProduct($image, $isExcel = 0)
    {
        // Debug log
        Log::channel('productUpdates')->debug("[Start] createProduct is called");

        // Set supplier
        $supplierModel = Supplier::leftJoin("scrapers as sc", "sc.supplier_id", "suppliers.id")->where(function ($query) use ($image) {
            $query->where('supplier', '=', $image->website)->orWhere('sc.scraper_name', '=', $image->website);
        })->first();

        // Do we have a supplier?
        if ($supplierModel == null) {
            // Debug
            Log::channel('productUpdates')->debug("[Error] Supplier is null " . $image->website);
            // check if the object is related to scraped product then we will add the error over there
            $image->validated = 0;
            $image->validation_result = "[Error] Supplier is null " . $image->website." while adding sku ".$image->sku;
            $image->save();
            // Return false
            return false;
        } else {
            $supplierId = $supplierModel->id;
            $supplier = $supplierModel->supplier;
        }

        // Get formatted data
        $formattedPrices = $this->formatPrices($image);
        $formattedDetails = $this->getGeneralDetails($image->properties,$image);

        // Set data.sku for validation
        $data[ 'sku' ] = ProductHelper::getSku($image->sku);
        $validator = Validator::make($data, [
            'sku' => 'unique:products,sku'
        ]);

        // Get color
        $isWithColor = false;
        if (isset($image->properties['color'])) {
            if(!empty($image->properties['color'])) {
                $isWithColor = true;
            }
        }
        $color = ColorNamesReference::getProductColorFromObject($image);

        $composition = $formattedDetails['composition'];
        if(!empty($formattedDetails['composition'])) {
            $composition = Compositions::getErpName($formattedDetails['composition']);
        }

        $description = $image->description;
        if(!empty($description)) {
            $description = DescriptionChange::getErpName($description);
        }


        // Store count
        try {
            SupplierBrandCount::firstOrCreate(['supplier_id' => $supplierId, 'brand_id' => $image->brand_id]);
            if (!empty($formattedDetails[ 'category' ])) {
                SupplierCategoryCount::firstOrCreate(['supplier_id' => $supplierId, 'category_id' => $formattedDetails[ 'category' ]]);
            }
            if (!empty($color)) {
                SkuColorReferences::firstOrCreate(['brand_id' => $image->brand_id, 'color_name' => $color]);
            }
        } catch (\Exception $e) {
            // var_dump($e->getMessage());
        }

        // Product validated
        if ($validator->fails()) {
            // Debug
            Log::channel('productUpdates')->debug("[validator] fails - sku exists " . ProductHelper::getSku($image->sku));

            // Try to get the product from the database
            if($image->product_id > 0) {
                $product = Product::where('id', $image->product_id)->first();
            }else{
                $product = Product::where('sku', $data[ 'sku' ])->first();
            }

            // Does the product exist? This should not fail, since the validator told us it's there
            if (!$product) {
                // Debug
                Log::channel('productUpdates')->debug("[Error] No product!");
                $image->validated = 0;
                $image->validation_result = "[Error] No product! " . $image->website." while adding sku ".$image->sku;
                $image->save();
                // Return false
                return false;
            }

            // Is the product not approved yet?
            if (!StatusHelper::isApproved($image->status_id)) {
                // Check if we can update the title - not manually entered
                $manual = ProductStatus::where('name', 'MANUAL_TITLE')->where("product_id",$product->id)->first();
                if ($manual == null || (int)$manual->value == 0) {
                    $product->name = ProductHelper::getRedactedText($image->title, 'name');
                }

                // Check if we can update the short description - not manually entered
                $manual = ProductStatus::where('name', 'MANUAL_SHORT_DESCRIPTION')->where("product_id",$product->id)->first();
                if ($manual == null || (int)$manual->value == 0) {
                    $product->short_description = ProductHelper::getRedactedText($description, 'short_description');
                }

                // Check if we can update the color - not manually entered
                $manual = ProductStatus::where('name', 'MANUAL_COLOR')->where("product_id",$product->id)->first();
                if ($manual == null || (int)$manual->value == 0) {
                    $product->color = $color;
                }

                // Check if we can update the composition - not manually entered
                $manual = ProductStatus::where('name', 'MANUAL_COMPOSITION')->where("product_id",$product->id)->first();
                if ($manual == null || (int)$manual->value == 0) {
                    // Check for composition key
                    $product->composition = $composition;
                    
                    // Check for material_used key
                    if (isset($image->properties[ 'material_used' ])) {
                        $product->composition = trim(ProductHelper::getRedactedText($image->properties[ 'material_used' ] ?? '', 'composition'));
                    }
                }

                $manual = ProductStatus::where('name', 'MANUAL_CATEGORY')->where("product_id",$product->id)->first();
                if ($manual == null || (int)$manual->value == 0) {
                    // Update the category
                    $product->category = $formattedDetails[ 'category' ];
                }

                // if product has not entry with manual category
                if($product->category < 2) {
                    // Update the category
                    $product->category = $formattedDetails[ 'category' ];
                    $product->status_id = \App\Helpers\StatusHelper::$autoCrop;
                }
            }

            // Get current sizes
            $allSize = [];

            // Update with scraped sizes
            if (is_array($image->properties[ 'sizes' ]) && count($image->properties[ 'sizes' ]) > 0) {
                $sizes = $image->properties[ 'sizes' ];
                $euSize = [];

                // Loop over sizes and redactText
                if (is_array($sizes) && $sizes > 0) {
                    foreach ($sizes as $size) {
                        $helperSize = ProductHelper::getRedactedText($size, 'composition');
                        $allSize[] = $helperSize;
                        //find the eu size and update into the field
                        /*$euSize[]  = ProductHelper::getWebsiteSize($image->size_system, $helperSize, $product->category);*/
                    }
                }

                $product->size = implode(',', $allSize);
                // get size system
                $supplierSizeSystem = \App\ProductSupplier::getSizeSystem($product->id, $supplierModel->id);
                $euSize = ProductHelper::getEuSize($product, $allSize, !empty($supplierSizeSystem) ? $supplierSizeSystem : $image->size_system);
                $product->size_eu = implode(',', $euSize);
                \App\ProductSizes::where('product_id',$product->id)->where('supplier_id',$supplierModel->id)->delete();
                if(empty($euSize)) {
                    $product->status_id = \App\Helpers\StatusHelper::$unknownSize;
                }else{
                    foreach($euSize as $es) {
                        \App\ProductSizes::updateOrCreate([
                           'product_id' =>  $product->id,'supplier_id' => $supplierModel->id, 'size' => $es 
                        ],[
                           'product_id' =>  $product->id,'quantity' => 1,'supplier_id' => $supplierModel->id, 'size' => $es
                        ]);
                    }
                }
            }

            // Store measurement
            $product->lmeasurement = $formattedDetails[ 'lmeasurement' ] > 0 ? $formattedDetails[ 'lmeasurement' ] : null;
            $product->hmeasurement = $formattedDetails[ 'hmeasurement' ] > 0 ? $formattedDetails[ 'hmeasurement' ] : null;
            $product->dmeasurement = $formattedDetails[ 'dmeasurement' ] > 0 ? $formattedDetails[ 'dmeasurement' ] : null;
            $product->price = $formattedPrices[ 'price_eur' ];
            $product->price_eur_special = $formattedPrices[ 'price_eur_special' ];
            $product->price_eur_discounted = $formattedPrices[ 'price_eur_discounted' ];
            $product->price_inr = $formattedPrices[ 'price_inr' ];
            $product->price_inr_special = $formattedPrices[ 'price_inr_special' ];
            $product->price_inr_discounted = $formattedPrices[ 'price_inr_discounted' ];
            $product->is_scraped = $isExcel == 1 ? $product->is_scraped : 1;
            $product->discounted_percentage = $image->discounted_percentage;
            // check if the product category is not set
            if($product->category <= 1) {
                $product->status_id = \App\Helpers\StatusHelper::$unknownCategory;
            }

            $product->save();
            $product->attachImagesToProduct();
            $image->product_id = $product->id;
            $image->save();
            // check that if product has no title and everything then send to the external scraper
            $product->checkExternalScraperNeed();

            \Log::channel('productUpdates')->info("Saved product id :" . $product->id);

            // check for the auto crop 
            $needToCheckStatus = [
                StatusHelper::$requestForExternalScraper,
                StatusHelper::$unknownComposition,
                StatusHelper::$unknownColor,
                StatusHelper::$unknownCategory,
                StatusHelper::$unknownMeasurement,
                StatusHelper::$unknownSize
            ];

            if(!in_array($product->status_id, $needToCheckStatus)) {
                $product->status_id = \App\Helpers\StatusHelper::$autoCrop;
            }


            if ($image->is_sale) {
                $product->is_on_sale = 1;
                $product->save();
            }

            // check that if the product color is white then we need to remove that 
            $product->isNeedToIgnore();


            if ($db_supplier = Supplier::select('suppliers.id')->leftJoin("scrapers as sc", "sc.supplier_id", "suppliers.id")->where(function ($query) use ($supplier) {
                $query->where('supplier', '=', $supplier)->orWhere('sc.scraper_name', '=', $supplier);
            })->first()) {
                if ($product) {

                    $productSupplier = \App\ProductSupplier::where("supplier_id",$db_supplier->id)->where("product_id",$product->id)->first();
                    if(!$productSupplier)  {
                        $productSupplier = new \App\ProductSupplier;
                        $productSupplier->supplier_id = $db_supplier->id;
                        $productSupplier->product_id = $product->id;
                    }

                    $productSupplier->title = $image->title;
                    $productSupplier->description = $description;
                    $productSupplier->supplier_link = $image->url;
                    $productSupplier->stock = 1;
                    $productSupplier->price = $formattedPrices[ 'price_eur' ];
                    $productSupplier->price_special = $formattedPrices[ 'price_eur_special' ];
                    $productSupplier->price_discounted = $formattedPrices[ 'price_eur_discounted' ];
                    $productSupplier->size = $formattedDetails[ 'size' ];
                    $productSupplier->color = $formattedDetails[ 'color' ];
                    $productSupplier->composition = $formattedDetails[ 'composition' ];
                    $productSupplier->sku = $image->original_sku;
                    $productSupplier->size_system = $image->size_system;
                    $productSupplier->save();

                    $product->supplier_id = $db_supplier->id;


                    /*$product->suppliers()->syncWithoutDetaching([
                        $db_supplier->id => [
                            'title' => $image->title,
                            'description' => $image->description,
                            'supplier_link' => $image->url,
                            'stock' => 1,
                            'price' => $formattedPrices[ 'price_eur' ],
                            'price_special' => $formattedPrices[ 'price_eur_special' ],
                            'price_discounted' => $formattedPrices[ 'price_eur_discounted' ],
                            'size' => $formattedDetails[ 'size' ],
                            'color' => $formattedDetails[ 'color' ],
                            'composition' => $formattedDetails[ 'composition' ],
                            'sku' => $image->original_sku
                        ]
                    ]);*/
                }
            }

            $dup_count = 0;
            $supplier_prices = [];

            foreach ($product->suppliers_info as $info) {
                if ($info->price != '') {
                    $supplier_prices[] = $info->price;
                }
            }

            foreach (array_count_values($supplier_prices) as $price => $c) {
                $dup_count++;
            }

            if ($dup_count > 1) {
                $product->is_price_different = 1;
            } else {
                $product->is_price_different = 0;
            }

            $product->stock += 1;
            $product->save();

            $supplier = $image->website;

            $params = [
                'website' => $supplier,
                'scraped_product_id' => $product->id,
                'status' => 1
            ];

            //ScrapActivity::create($params);

            Log::channel('productUpdates')->debug("[Success] Updated product");

            return;

        } else {
            Log::channel('productUpdates')->debug("[validator] success - new sku " . ProductHelper::getSku($image->sku));
            $product = new Product;
        }

        if ($product === null) {
            Log::channel('productUpdates')->debug("[Skipped] Product is null");
            $image->validated = 0;
            $image->validation_result = "[Skipped] Product is null " . $image->website." while adding sku ".$image->sku;
            $image->save();
            return;
        }
        // Changed status to auto crop now
        // check that product category is set then send auto crop otherwise send on the missing category status
        $product->sku = str_replace(' ', '', $image->sku);
        $product->brand = $image->brand_id;
        $product->supplier = $supplier;
        $product->supplier_id = $supplierModel->id;
        $product->name = $image->title;
        $product->short_description = $description;
        $product->supplier_link = $image->url;
        $product->stage = 3;
        $product->is_scraped = $isExcel == 1 ? 0 : 1;
        $product->stock = 1;
        $product->is_without_image = 1;
        $product->is_on_sale = $image->is_sale ? 1 : 0;

        $product->composition = $composition;
        $product->size = $formattedDetails[ 'size' ];
        $product->lmeasurement = (int)$formattedDetails[ 'lmeasurement' ];
        $product->hmeasurement = (int)$formattedDetails[ 'hmeasurement' ];
        $product->dmeasurement = (int)$formattedDetails[ 'dmeasurement' ];
        $product->measurement_size_type = $formattedDetails[ 'measurement_size_type' ];
        $product->made_in = $formattedDetails[ 'made_in' ];
        $product->category = $formattedDetails[ 'category'];
        if($product->category > 1) {
            $product->status_id = \App\Helpers\StatusHelper::$autoCrop;
        }else{
            $product->status_id = \App\Helpers\StatusHelper::$unknownCategory;
        }

        // color has been updating from here
        if($isWithColor) {
           $product->color = $color;
        }else{
           $product->suggested_color = $color;
           $product->status_id = \App\Helpers\StatusHelper::$unknownColor;
        }

        // start to update the eu size
        $allSize = [];

        // Update with scraped sizes
        if (is_array($image->properties[ 'sizes' ]) && count($image->properties[ 'sizes' ]) > 0) {
            $sizes = $image->properties[ 'sizes' ];
            $euSize = [];

            // Loop over sizes and redactText
            if (is_array($sizes) && $sizes > 0) {
                foreach ($sizes as $size) {
                    $helperSize = ProductHelper::getRedactedText($size, 'composition');
                    $allSize[] = $helperSize;
                    //find the eu size and update into the field
                    /*$euSize[]  = ProductHelper::getWebsiteSize($image->size_system, $helperSize, $product->category);*/
                }
            }

            $product->size = implode(',', $allSize);
            // get size system
            $supplierSizeSystem = \App\ProductSupplier::getSizeSystem($product->id, $supplierModel->id);
            $euSize = ProductHelper::getEuSize($product, $allSize, !empty($supplierSizeSystem) ? $supplierSizeSystem : $image->size_system);
            $product->size_eu = implode(',', $euSize);
            \App\ProductSizes::where('product_id',$product->id)->where('supplier_id',$supplierModel->id)->delete();
            if(empty($euSize)) {
                $product->status_id = \App\Helpers\StatusHelper::$unknownSize;
            }else{
                foreach($euSize as $es) {
                    \App\ProductSizes::updateOrCreate([
                       'product_id' =>  $product->id,'supplier_id' => $supplierModel->id, 'size' => $es 
                    ],[
                       'product_id' =>  $product->id,'quantity' => 1,'supplier_id' => $supplierModel->id, 'size' => $es
                    ]);
                }
            }
        }

        $product->price = $formattedPrices[ 'price_eur' ];
        $product->price_eur_special = $formattedPrices[ 'price_eur_special' ];
        $product->price_eur_discounted = $formattedPrices[ 'price_eur_discounted' ];
        $product->price_inr = $formattedPrices[ 'price_inr' ];
        $product->price_inr_special = $formattedPrices[ 'price_inr_special' ];
        $product->price_inr_discounted = $formattedPrices[ 'price_inr_discounted' ];
        $product->discounted_percentage = $image->discounted_percentage;

        try {
            $product->save();
            //$setProductDescAndNameLanguages = new ProductController();
            //$setProductDescAndNameLanguages->listMagento(request() ,$product->id);
            $image->product_id = $product->id;
            $image->save();
            $product->attachImagesToProduct();

            // check that if product has no title and everything then send to the external scraper
            $product->checkExternalScraperNeed();
            $product->isNeedToIgnore();

            Log::channel('productUpdates')->debug("[New] Product created with ID " . $product->id);
        } catch (\Exception $exception) {
            Log::channel('productUpdates')->alert("[Exception] Couldn't create product");
            Log::channel('productUpdates')->alert($exception->getMessage());

            $image->validated = 0;
            $image->validation_result = "[Exception] Couldn't create product " . $exception->getMessage()." while adding sku ".$image->sku;
            $image->save();

            return;
        }

        if ($db_supplier = Supplier::select('suppliers.id')->leftJoin("scrapers as sc", "sc.supplier_id", "suppliers.id")->where(function ($query) use ($supplier) {
            $query->where('supplier', '=', $supplier)->orWhere('sc.scraper_name', '=', $supplier);
        })->first()) {

            $productSupplier = \App\ProductSupplier::where("supplier_id",$db_supplier->id)->where("product_id",$product->id)->first();
            if(!$productSupplier)  {
                $productSupplier = new \App\ProductSupplier;
                $productSupplier->supplier_id = $db_supplier->id;
                $productSupplier->product_id = $product->id;
            }

            $productSupplier->title = $image->title;
            $productSupplier->description = $description;
            $productSupplier->supplier_link = $image->url;
            $productSupplier->stock = 1;
            $productSupplier->price = $formattedPrices[ 'price_eur' ];
            $productSupplier->price_special = $formattedPrices[ 'price_eur_special' ];
            $productSupplier->price_discounted = $formattedPrices[ 'price_eur_discounted' ];
            $productSupplier->size = $formattedDetails[ 'size' ];
            $productSupplier->color = $formattedDetails[ 'color' ];
            $productSupplier->composition = $formattedDetails[ 'composition' ];
            $productSupplier->sku = $image->original_sku;
            $productSupplier->size_system = $image->size_system;
            $productSupplier->save();
            $image->product_id = $product->id;
            $image->save();
            /*$product->suppliers()->syncWithoutDetaching([
                $db_supplier->id => [
                    'title' => $image->title,
                    'description' => $image->description,
                    'supplier_link' => $image->url,
                    'stock' => 1,
                    'price' => $formattedPrices[ 'price_eur' ],
                    'price_special' => $formattedPrices[ 'price_eur_special' ],
                    'price_discounted' => $formattedPrices[ 'price_eur_discounted' ],
                    'size' => $formattedDetails[ 'size' ],
                    'color' => $formattedDetails[ 'color' ],
                    'composition' => $formattedDetails[ 'composition' ],
                    'sku' => $image->original_sku
                ]
            ]);*/
        }
    }

    public function formatPrices($image)
    {
        // Get brand from database
        $brand = Brand::find($image->brand_id);

        // Check for EUR to INR
        if (!empty($brand->euro_to_inr)) {
            $priceInr = (float)$brand->euro_to_inr * (float)trim($image->price);
        } else {
            $priceInr = (float)Setting::get('euro_to_inr') * (float)trim($image->price);
        }

        // Set INR price
        $priceInr = round($priceInr, -3);

        if (!empty($image->price) && !empty($priceInr)) {
            $priceEurSpecial = $image->price - ($image->price * $brand->deduction_percentage) / 100;
            $priceInrSpecial = $priceInr - ($priceInr * $brand->deduction_percentage) / 100;
        } else {
            $priceEurSpecial = '';
            $priceInrSpecial = '';
        }

        // Product on sale?
        if ($image->is_sale == 1 && $brand->sales_discount > 0 && !empty($priceEurSpecial)) {
            $priceEurDiscounted = $priceEurSpecial - ($priceEurSpecial * $brand->sales_discount) / 100;
            $priceInrDiscounted = $priceInrSpecial - ($priceInrSpecial * $brand->sales_discount) / 100;
        } else {
            $priceEurDiscounted = 0;
            $priceInrDiscounted = 0;
        }

        // Return prices
        return [
            'price_eur' => $image->price,
            'price_eur_special' => $priceEurSpecial,
            'price_eur_discounted' => $priceEurDiscounted,
            'price_inr' => $priceInr,
            'price_inr_special' => $priceInrSpecial,
            'price_inr_discounted' => $priceInrDiscounted
        ];
    }

    public function getGeneralDetails($properties_array,$scrapedProduct = null)
    {
        if (array_key_exists('material_used', $properties_array)) {
            $composition = (is_array($properties_array[ 'material_used' ])) ? implode(" ",$properties_array[ 'material_used' ]) : (string)$properties_array[ 'material_used' ];
        }

        if (array_key_exists('color', $properties_array)) {
            $color = $properties_array[ 'color' ];
        }

        if (array_key_exists('sizes', $properties_array)) {
            $orgSizes = $properties_array[ 'sizes' ];
            $tmpSizes = [];

            // Loop over sizes
            foreach ($orgSizes as $size) {
                if (substr(strtoupper($size), -2) == 'IT') {
                    $size = str_replace('IT', '', $size);
                    $size = trim($size);
                }

                if (!empty($size) || $size == 0) {
                    $tmpSizes[] = $size;
                }
            }
            $newSize = [];
            if(count($tmpSizes) != 0){
                foreach ($tmpSizes as $size) {
                    $ifSizeExist = \App\Size::where('name',$size)->first();
                    if($ifSizeExist){
                        $newSize[] = $size;
                    }else{
                        //check in reference
                        $ifSizeExist = \App\Size::where('references','LIKE','%'.$size.'%')->first();
                        if($ifSizeExist){
                            $references = $ifSizeExist->references;
                            $referenceArray = explode(',', $references);
                            $found = 0;
                            foreach ($referenceArray as $ref) {
                                if($ref == $size){
                                    $newSize[] = $ifSizeExist->name;
                                    $found = 1;
                                }
                            }
                            if($found == 0){
                                //check if it exist in unknown
                                $ifExistInUnknown = \App\UnknownSize::where('size','LIKE','%'.$size.'%')->first();
                                if($ifExistInUnknown){

                                }else{
                                    //save unknown size
                                    $unknown = new \App\UnknownSize;
                                    $unknown->size = $size;
                                    $unknown->save();
                                }
                            }
                        }else{
                            //check if it exist in unknown
                            $ifExistInUnknown = \App\UnknownSize::where('size','LIKE','%'.$size.'%')->first();
                            if($ifExistInUnknown){

                            }else{
                                //save unknown size
                                $unknown = new \App\UnknownSize;
                                $unknown->size = $size;
                                $unknown->save();
                            }
                        }
                    }
                }
            }
            $size = implode(',', $newSize);
        }

        if (array_key_exists('dimension', $properties_array)) {
            if (is_array($properties_array[ 'dimension' ])) {
                $exploded = $properties_array[ 'dimension' ];
                if (count($exploded) > 0) {
                    if (array_key_exists('0', $exploded)) {
                        $lmeasurement = (int)$exploded[ 0 ];
                        $measurement_size_type = 'measurement';
                    }

                    if (array_key_exists('1', $exploded)) {
                        $hmeasurement = (int)$exploded[ 1 ];
                    }

                    if (array_key_exists('2', $exploded)) {
                        $dmeasurement = (int)$exploded[ 2 ];
                    }
                }
            }
        }

        // Get category
        $liForMen = ['MAN', 'MEN', 'UOMO', 'MALE'];
        $liForWoMen = ['WOMAN', 'WOMEN', 'DONNA', 'FEMALE'];
        $liForKids = ['KIDS'];

        if (array_key_exists('category', $properties_array)) {
            // Check if category is an array
            if (is_array($properties_array[ 'category' ])) {
                // Set gender to null
                $gender = null;

                // Loop over categories to find gender
                foreach ($properties_array[ 'category' ] as $category) {
                    // Check for gender man
                    if (in_array(strtoupper($category), $liForMen)) {
                        $gender = 'MEN';
                    }

                    // Check for gender woman
                    if (in_array(strtoupper($category), $liForWoMen)) {
                        $gender = 'WOMEN';
                    }

                    // check for kids
                    if (in_array(strtoupper($category), $liForKids)) {
                        $gender = 'KIDS';
                    }
                }

                // check if gender is still null then try to looks from url if we found there 
                if($scrapedProduct && !empty($scrapedProduct->url) && is_null($gender)) {
                    // check for men
                    foreach($liForMen  as $lim) {
                        if(strpos($lim, $scrapedProduct->url) !== false) {
                            $gender = "MEN";
                        }
                    }
                    // check for women
                    foreach($liForWoMen  as $liw) {
                        if(strpos($liw, $scrapedProduct->url) !== false) {
                            $gender = "WOMEN";
                        }
                    }
                    // check for kids
                    foreach($liForKids  as $lik) {
                        if(strpos($lik, $scrapedProduct->url) !== false) {
                            $gender = "KIDS";
                        }
                    }
                }

                // Try to get category ID
                $category = Category::getCategoryIdByKeyword(end($properties_array[ 'category' ]), $gender);
                
                if(!$category){
                    $categoryReference = implode('/',$properties_array[ 'category' ]);

                    ScrappedCategoryMapping::updateOrCreate([
                        'name' =>$categoryReference
                    ],[
                        'name' => $categoryReference
                        ]);

                    // $unknownCategory = Category::where('title','LIKE','%Unknown Category%')->first();
                    // //checking if it already exist in reference table
                    // $results = explode(',', $unknownCategory->references);
                    // $exist = 0;
                    // foreach ($results as $result) {
                    //     if(strtolower($result) == strtolower($categoryReference)){
                    //         $exist = 1;
                    //         break;
                    //     }
                    // }
                    // if($exist == 0){
                    //     $unknownCategory->references = $unknownCategory->references . ',' . $categoryReference;
                    //     $unknownCategory->save();    
                    // }
                    
                }
            
            }
        }

        if (array_key_exists('country', $properties_array)) {
            $made_in = $properties_array[ 'country' ];
        }

        return [
            'composition' => isset($composition) ? $composition : '',
            'color' => isset($color) ? $color : '',
            'size' => isset($size) ? $size : '',
            'lmeasurement' => isset($lmeasurement) ? $lmeasurement : '',
            'hmeasurement' => isset($hmeasurement) ? $hmeasurement : '',
            'dmeasurement' => isset($dmeasurement) ? $dmeasurement : '',
            'measurement_size_type' => isset($measurement_size_type) ? $measurement_size_type : '',
            'made_in' => isset($made_in) ? $made_in : '',
            'category' => isset($category) ? $category : 1,
        ];
    }
}