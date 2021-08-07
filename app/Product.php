<?php

namespace App;

/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use App\Helpers\StatusHelper;
use Dompdf\Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Validator;
use Plank\Mediable\MediaUploaderFacade as MediaUploader;
use Plank\Mediable\Mediable;
use Spatie\Activitylog\Traits\LogsActivity;
use App\ScrapedProducts;
use App\ScrapActivity;
use App\SupplierInventory;
use App\Helpers\ProductHelper;
use App\Loggers\LogScraper;
use App\ProductQuicksellGroup;
use App\Services\Products\ProductsCreator;

class Product extends Model
{

    CONST STOCK_STATUS = [
        1 => "Active",
        2 => "Reserved",
        3 => "Damaged",
        4 => "On Hold"
    ];

    const IVA_PERCENTAGE = 22;

//  use LogsActivity;
    use Mediable;
    use SoftDeletes;

    CONST BAGS_CATEGORY_IDS = [11,39,50,192,210];

   /**
     * @var string
     * @SWG\Property(property="name",type="string")
     * @SWG\Property(property="brand",type="string")
     * @SWG\Property(property="category",type="string")
     * @SWG\Property(property="short_description",type="string")
     * @SWG\Property(property="price",type="string")
     * @SWG\Property(property="sku",type="string")
     * @SWG\Property(property="has_mediables",type="string")
     * @SWG\Property(property="status_id",type="integer")
     * @SWG\Property(property="id",type="integer")
     * @SWG\Property(property="is_barcode_check",type="boolean")
     * @SWG\Property(property="size_eu",type="string")
     * @SWG\Property(property="supplier",type="string")
     * @SWG\Property(property="supplier_id",type="integer")
     * @SWG\Property(property="user_id",type="integer")
     * @SWG\Property(property="shopify_id",type="integer")
     * @SWG\Property(property="stock_status",type="string")
     * @SWG\Property(property="scrap_priority",type="string")
     * @SWG\Property(property="assigned_to",type="integer")
     * @SWG\Property(property="quick_product",type="string")
     * @SWG\Property(property="approved_by",type="integer")
     * @SWG\Property(property="supplier_link",type="string")
     * @SWG\Property(property="composition",type="string")
     * @SWG\Property(property="lmeasurement",type="string")
     * @SWG\Property(property="hmeasurement",type="string")
     * @SWG\Property(property="dmeasurement",type="string")
     * @SWG\Property(property="size",type="string")
     * @SWG\Property(property="color",type="string")
     * @SWG\Property(property="last_brand",type="string")
     */
    protected $fillable = [
        'name',
        'brand',
        'category',
        'short_description',
        'price',
        'status_id',
        'id',
        'sku',
        'is_barcode_check',


        'has_mediables',
        'size_eu',
        'supplier',
        'supplier_id',
        'stock_status',
        'shopify_id',
        'scrap_priority',
        'assigned_to',
        'quick_product',
        'approved_by',
        'supplier_link',
        'composition',
        'lmeasurement',
        'hmeasurement',
        'dmeasurement',
        'size',
        'color',
        'suggested_color',
        'last_brand',
        'sub_status_id'
    ];

    protected $dates = ['deleted_at'];
    protected $appends = [];
    protected $communication = '';
    protected $image_url = '';
    public $images = [];

    public static function boot()
    {
        parent::boot();

        static::saved(function ($model) {
            $flag = 0;
            if ($model->hasMedia(config('constants.attach_image_tag'))) {
                $flag = 1;
            }
            if($model->has_mediables != $flag) {
                \DB::table("products")->where("id", $model->id)->update(["has_mediables" => $flag]);
            }
        });

        static::updating(function ($product) {
            $newCatID = $product->category;
            $oldCatID = $product->getOriginal('category');

            if($oldCatID != $newCatID && $newCatID > 1) {
                \DB::table("products")->where("id", $product->id)->update(["status_id" => StatusHelper::$autoCrop]);
                $data = array(
                    'product_id' => $product->id,
                    'old_status' => $product->status_id,
                    'new_status' => StatusHelper::$autoCrop,
                    'created_at' => date("Y-m-d H:i:s")
                );
                \App\ProductStatusHistory::addStatusToProduct($data);
            }

            $new_status_id = $product->status_id;
            $old_status_id = $product->getOriginal('status_id');
            if($old_status_id != $new_status_id) {
                $data = array(
                    'product_id' => $product->id,
                    'old_status' => $old_status_id,
                    'new_status' => $new_status_id,
                    'created_at' => date("Y-m-d H:i:s")
                );
                \App\ProductStatusHistory::addStatusToProduct($data);
            }
        });

        static::created(function ($model) {
            $flag = 0;
            if ($model->hasMedia(config('constants.attach_image_tag'))) {
                $flag = 1;
            }
            if($model->has_mediables != $flag) {
                \DB::table("products")->where("id", $model->id)->update(["has_mediables" => $flag]);
            }
            if($model->status_id) {
                $data = array(
                    'product_id' => $model->id,
                    'old_status' => $model->status_id,
                    'new_status' => $model->status_id,
                    'created_at' => date("Y-m-d H:i:s")
                );
                \App\ProductStatusHistory::addStatusToProduct($data);
            }
        });
    }

    /**
     * Create new or update existing (scraped) product by JSON
     * This is only for Excel imports at the moment
     * @param $json
     * @return bool|\Illuminate\Http\JsonResponse
     */
    public static function createProductByJson($json, $isExcel = 0, $nextExcelStatus = 2)
    {
        // Log before validating
        //LogScraper::LogScrapeValidationUsingRequest($json, $isExcel);

        // Check for required values
        if (
            !empty($json->title) &&
            !empty($json->sku) &&
            !empty($json->brand_id)
        ) {
            // Check for unique product
            $data[ 'sku' ] = ProductHelper::getSku($json->sku);
            $validator = Validator::make($data, [
                'sku' => 'unique:products,sku'
            ]);

            // Get formatted prices
            $formattedPrices = self::_getPriceArray($json);
            $formattedDetails = (new \App\Services\Products\ProductsCreator)->getGeneralDetails($json->properties,$json);

            $color = \App\ColorNamesReference::getProductColorFromObject($json);

            $composition = $formattedDetails['composition'];
            if(!empty($formattedDetails['composition'])) {
                $composition = \App\Compositions::getErpName($formattedDetails['composition']);
            }

            // If validator fails we have an existing product
            if ($validator->fails()) {
                // Get the product from the database
                try {
                    
                    if($json->product_id > 0) {
                        $product = Product::where('id', $json->product_id)->first();
                    }else{
                        $product = Product::where('sku', $data[ 'sku' ])->first();
                    }

                } catch (\Exception $e) {
                    $product = Product::where('sku', $data[ 'sku' ])->first();
                }
                

                // Return false if no product is found
                if (!$product) {
                    return false;
                }

                // Update from scrape to manual images
                if (!$product->is_approved && !$product->is_listing_rejected && $product->status_id == StatusHelper::$scrape && (int)$nextExcelStatus == StatusHelper::$unableToScrapeImages) {
                    $product->status_id = StatusHelper::$unableToScrapeImages;
                }

                // Update the name and description if the product is not approved and not rejected
                if (!$product->is_approved && !$product->is_listing_rejected) {
                    $product->name = ProductHelper::getRedactedText($json->title, 'name');
                    $product->short_description = ProductHelper::getRedactedText($json->description, 'short_description');
                }

                // Update color, composition and material used if the product is not approved
                if (!$product->is_approved) {
                    // Set color
                    $product->color = $color;
                    // Set composition
                    $product->composition = $composition;
                    
                }

                //Check if its json
                if (isset($json->properties[ 'size' ]) && is_array($json->properties[ 'size' ])) {
                    $json->properties[ 'size' ] = implode(',', $json->properties[ 'size' ]);
                }

                // Add sizes to the product
                if (isset($json->properties[ 'size' ]) && is_array($json->properties[ 'size' ]) && count($json->properties[ 'size' ]) > 0) {
                    // Implode the keys
                    $product->size = implode(',', array_values($json->properties[ 'size' ]));

                    // Replace texts in sizes
                    $product->size = ProductHelper::getRedactedText($product->size, 'composition');

                } elseif (isset($json->properties[ 'size' ]) && $json->properties[ 'size' ] != null) {
                    $product->size = $json->properties[ 'size' ];

                }

                // Set product values
                $product->lmeasurement = isset($json->properties[ 'lmeasurement' ]) && $json->properties[ 'lmeasurement' ] > 0 ? $json->properties[ 'lmeasurement' ] : null;
                $product->hmeasurement = isset($json->properties[ 'hmeasurement' ]) && $json->properties[ 'hmeasurement' ] > 0 ? $json->properties[ 'hmeasurement' ] : null;
                $product->dmeasurement = isset($json->properties[ 'dmeasurement' ]) && $json->properties[ 'dmeasurement' ] > 0 ? $json->properties[ 'dmeasurement' ] : null;
                $product->price = $formattedPrices[ 'price_eur' ];
                $product->price_inr = $formattedPrices[ 'price_inr' ];
                $product->price_inr_special = $formattedPrices[ 'price_inr_special' ];
                $product->price_inr_discounted = $formattedPrices[ 'price_inr_discounted' ];
                $product->price_eur_special = $formattedPrices[ 'price_eur_special' ];
                $product->price_eur_discounted = $formattedPrices[ 'price_eur_discounted' ];
                $product->is_scraped = $isExcel == 1 ? 0 : 1;
                $product->save();

                if ($product) {
                    if ($isExcel == 1) {
                        if (!$product->hasMedia(\Config('constants.excelimporter'))) {
                            foreach ($json->images as $image) {
                                try {
                                    $jpg = \Image::make($image)->encode('jpg');
                                } catch (\Exception $e) {
                                    $array = explode('/', $image);
                                    $filename_path = end($array);
                                    $jpg = \Image::make(public_path() . '/uploads/excel-import/' . $filename_path)->encode('jpg');
                                }
                                $filename = substr($image, strrpos($image, '/'));
                                $filename = str_replace(['/', '.JPEG', '.JPG', '.jpeg', '.jpg', '.PNG', '.png'], '', $filename);
                                $media = MediaUploader::fromString($jpg)->toDirectory('/product/' . floor($product->id / 10000) . '/' . $product->id)->useFilename($filename)->upload();
                                $product->attachMedia($media, config('constants.excelimporter'));
                            }
                        }
                    }

                }

                $product->checkExternalScraperNeed();


                // Update the product status
                ProductStatus::updateStatus($product->id, 'UPDATED_EXISTING_PRODUCT_BY_JSON', 1);

                // Set on sale
                if ($json->is_sale) {
                    $product->is_on_sale = 1;
                    $product->save();
                }

                // Check for valid supplier and store details linked to supplier
                if ($dbSupplier = Supplier::select('suppliers.id')->leftJoin("scrapers as sc", "sc.supplier_id", "suppliers.id")->where(function ($query) use ($json) {
                    $query->where('supplier', '=', $json->website)->orWhere('sc.scraper_name', '=', $json->website);
                })->first()) {
                    if ($product) {
                        $product->suppliers()->syncWithoutDetaching([
                            $dbSupplier->supplier_id => [
                                'title' => ProductHelper::getRedactedText($json->title, 'name'),
                                'description' => ProductHelper::getRedactedText($json->description, 'short_description'),
                                'supplier_link' => $json->url,
                                'stock' => $json->stock,
                                'price' => $formattedPrices[ 'price_eur' ],
                                'price_special' => $formattedPrices[ 'price_eur_special' ],
                                'supplier_id' => $dbSupplier->id,
                                'price_discounted' => $formattedPrices[ 'price_eur_discounted' ],
                                'size' => $json->properties[ 'size' ] ?? null,
                                'color' => $json->properties[ 'color' ],
                                'composition' => ProductHelper::getRedactedText($json->properties[ 'composition' ], 'composition'),
                                'sku' => $json->original_sku
                            ]
                        ]);
                        $product->supplier_id = $dbSupplier->id;
                    }
                }

                // Set duplicate count to 0
                $duplicateCount = 0;

                // Set empty array to hold supplier prices
                $supplierPrices = [];

                // Loop over each supplier
                foreach ($product->suppliers_info as $info) {
                    if ($info->price != '') {
                        $supplierPrices[] = $info->price;
                    }
                }

                // Loop over supplierPrices to find duplicates
                foreach (array_count_values($supplierPrices) as $price => $count) {
                    $duplicateCount++;
                }

                if ($duplicateCount > 1) {
                    // Different price
                    $product->is_price_different = 1;
                } else {
                    // Same price
                    $product->is_price_different = 0;
                }

                // Add 1 to stock - TODO: We can calculate the real stock across all suppliers
                $product->stock += 1;
                $product->save();

                // Set parameters for scrap activity
                $params = [
                    'website' => $json->website,
                    'scraped_product_id' => $product->id,
                    'status' => 1
                ];

                // Log scrap activity
                //ScrapActivity::create($params);

                // Return
                //returning 1 for Product Updated
                return array('product_created' => 0, 'product_updated' => 1);
            } else {
                // Create new product
                $product = new Product;

                // Return false if product could not be created
                if ($product == null) {
                    return false;
                }

                // Set product values
                $product->status_id = ($isExcel == 1 ? $nextExcelStatus : 3);
                $product->sku = $data[ 'sku' ];
                $product->supplier = $json->website;
                $product->brand = $json->brand_id;
                $product->category = $json->properties[ 'category' ] ?? 0;
                $product->name = ProductHelper::getRedactedText($json->title, 'name');
                $product->short_description = ProductHelper::getRedactedText($json->description, 'short_description');
                $product->supplier_link = $json->url;
                $product->stage = 3;
                $product->is_scraped = $isExcel == 1 ? 0 : 1;
                $product->stock = 1;
                $product->is_without_image = 1;
                $product->is_on_sale = $json->is_sale ? 1 : 0;
                $product->composition = $composition;
                $product->color = $color;
                $product->size = $json->properties[ 'size' ] ?? null;
                $product->lmeasurement = isset($json->properties[ 'lmeasurement' ]) && $json->properties[ 'lmeasurement' ] > 0 ? $json->properties[ 'lmeasurement' ] : null;
                $product->hmeasurement = isset($json->properties[ 'hmeasurement' ]) && $json->properties[ 'hmeasurement' ] > 0 ? $json->properties[ 'hmeasurement' ] : null;
                $product->dmeasurement = isset($json->properties[ 'dmeasurement' ]) && $json->properties[ 'dmeasurement' ] > 0 ? $json->properties[ 'dmeasurement' ] : null;
                $product->measurement_size_type = $json->properties[ 'measurement_size_type' ];
                $product->made_in = $json->properties[ 'made_in' ] ?? null;
                $product->price = $formattedPrices[ 'price_eur' ];
                $product->price_eur_special = $formattedPrices[ 'price_eur_special' ];
                $product->price_eur_discounted = $formattedPrices[ 'price_eur_discounted' ];
                $product->price_inr = $formattedPrices[ 'price_inr' ];
                $product->price_inr_special = $formattedPrices[ 'price_inr_special' ];
                $product->price_inr_discounted = $formattedPrices[ 'price_inr_discounted' ];

                // Try to save the product
                try {
                    $product->save();
                    $product->checkExternalScraperNeed();
                    //$json->product_id = $product->id;
                    //$json->save();
                } catch (\Exception $exception) {
                    $product->save();
                    return false;
                }

                // Update the product status
                ProductStatus::updateStatus($product->id, 'CREATED_NEW_PRODUCT_BY_JSON', 1);

                // Check for valid supplier and store details linked to supplier
                if ($dbSupplier = Supplier::select('suppliers.id')->leftJoin("scrapers as sc", "sc.supplier_id", "suppliers.id")->where(function ($query) use ($json) {
                    $query->where('supplier', '=', $json->website)->orWhere('sc.scraper_name', '=', $json->website);
                })->first()) {
                    if ($product) {
                        $product->suppliers()->syncWithoutDetaching([
                            $dbSupplier->supplier_id => [
                                'title' => ProductHelper::getRedactedText($json->title, 'name'),
                                'description' => ProductHelper::getRedactedText($json->description, 'short_description'),
                                'supplier_link' => $json->url,
                                'stock' => $json->stock,
                                'price' => $formattedPrices[ 'price_eur' ],
                                'price_special' => $formattedPrices[ 'price_eur_special' ],
                                'supplier_id' => $dbSupplier->id,
                                'price_discounted' => $formattedPrices[ 'price_eur_discounted' ],
                                'size' => $json->properties[ 'size' ] ?? null,
                                'color' => $json->properties[ 'color' ],
                                'composition' => ProductHelper::getRedactedText($json->properties[ 'composition' ], 'composition'),
                                'sku' => $json->original_sku
                            ]
                        ]);
                    }
                }

                // Return true Product Created
                return array('product_created' => 1, 'product_updated' => 0);
            }
        }

        // Return false by default
        return false;
    }

    private static function _getPriceArray($json)
    {
        // Get brand object by brand ID
        $brand = Brand::find($json->brand_id);

        if (strpos($json->price, ',') !== false) {
            if (strpos($json->price, '.') !== false) {
                if (strpos($json->price, ',') < strpos($json->price, '.')) {
                    $priceEur = str_replace(',', '', $json->price);
                } else {
                    $priceEur = str_replace(',', '|', $json->price);
                    $priceEur = str_replace('.', ',', $priceEur);
                    $priceEur = str_replace('|', '.', $priceEur);
                    $priceEur = str_replace(',', '', $priceEur);
                }
            } else {
                $priceEur = str_replace(',', '.', $json->price);
            }
        } else {
            $priceEur = $json->price;
        }

        // Get numbers and trim final price
        $priceEur = trim(preg_replace('/[^0-9\.]/i', '', $priceEur));

        //
        if (strpos($priceEur, '.') !== false) {
            // Explode price
            $exploded = explode('.', $priceEur);

            // Check if there are numbers after the dot
            if (strlen($exploded[ 1 ]) > 2) {
                if (count($exploded) > 2) {
                    $sliced = array_slice($exploded, 0, 2);
                } else {
                    $sliced = $exploded;
                }

                // Convert price to the lowest minor unit
                $priceEur = implode('', $sliced);
            }
        }

        // Set price to rounded finalPrice
        $priceEur = round($priceEur);

        // Check if the euro to rupee rate is set
        if (!empty($brand->euro_to_inr)) {
            $priceInr = $brand->euro_to_inr * $priceEur;
        } else {
            $priceInr = Setting::get('euro_to_inr') * $priceEur;
        }

        // Build price in INR and special price
        $priceInr = round($priceInr, -3);

        //Build Special Price In EUR
        if (!empty($priceEur) && !empty($priceInr)) {
            $priceEurSpecial = $priceEur - ($priceEur * $brand->deduction_percentage) / 100;
            $priceInrSpecial = $priceInr - ($priceInr * $brand->deduction_percentage) / 100;
        } else {
            $priceEurSpecial = '';
            $priceInrSpecial = '';
        }

        // Product on sale?
        if ($json->is_sale == 1 && $brand->sales_discount > 0 && !empty($priceEurSpecial)) {
            $priceEurDiscounted = $priceEurSpecial - ($priceEurSpecial * $brand->sales_discount) / 100;
            $priceInrDiscounted = $priceInrSpecial - ($priceInrSpecial * $brand->sales_discount) / 100;
        } else {
            $priceEurDiscounted = 0;
            $priceInrDiscounted = 0;
        }

        // Return prices
        return [
            'price_eur' => $priceEur,
            'price_eur_special' => $priceEurSpecial,
            'price_eur_discounted' => $priceEurDiscounted,
            'price_inr' => $priceInr,
            'price_inr_special' => $priceInrSpecial,
            'price_inr_discounted' => $priceInrDiscounted
        ];
    }

    public function messages()
    {
        return $this->hasMany('App\Message', 'moduleid')->where('moduletype', 'product')->latest()->first();
    }

    public function product_category()
    {
        return $this->belongsTo('App\Category', 'category');
    }

    public function log_scraper_vs_ai()
    {
        return $this->hasMany('App\LogScraperVsAi');
    }

    public function getCommunicationAttribute()
    {
        return $this->messages();
    }

    public function getImageurlAttribute()
    {
        return $this->getMedia(config('constants.media_tags'))->first() ? $this->getMedia(config('constants.media_tags'))->first()->getUrl() : '';
    }

    public function notifications()
    {
        return $this->hasMany('App\Notification');
    }

    public function suppliers()
    {
        return $this->belongsToMany('App\Supplier', 'product_suppliers', 'product_id', 'supplier_id');
    }

    public function suppliers_info()
    {
        return $this->hasMany('App\ProductSupplier');
    }

    public function private_views()
    {
        return $this->belongsToMany('App\PrivateView', 'private_view_products', 'product_id', 'private_view_id');
    }

    public function suggestions()
    {
        return $this->belongsToMany('App\SuggestedProduct', 'suggested_product_lists', 'product_id', 'suggested_products_id');
    }

    public function amends()
    {
        return $this->hasMany(CropAmends::class, 'product_id', 'id');
    }

    public function brands()
    {
        return $this->hasOne('App\Brand', 'id', 'brand');
    }

    public function categories()
    {
        return $this->hasOne('App\Category', 'id', 'category');
    }

    public function references()
    {
        return $this->hasMany('App\ProductReference');
    }

    public static function getPendingProductsCount($roleType)
    {
        $stage = new Stage();
        $stage_no = intval($stage->getID($roleType));

        return DB::table('products')
            ->where('stage', $stage_no - 1)
            ->where('isApproved', '!=', -1)
            ->whereNull('dnf')
            ->whereNull('deleted_at')
            ->count();
    }

    public function purchases()
    {
        return $this->belongsToMany('App\Purchase', 'purchase_products', 'product_id', 'purchase_id');
    }

    public function sizes()
    {
        return $this->hasMany(ProductSizes::class);
    }

    public function orderproducts()
    {
        return $this->hasMany('App\OrderProduct', 'product_id', 'id');
    }

    public function scraped_products()
    {
        return $this->hasOne('App\ScrapedProducts', 'product_id', 'id');
    }

    public function many_scraped_products()
    {
        return $this->hasMany('App\ScrapedProducts', 'sku', 'sku');
    }

    public function user()
    {
        return $this->belongsToMany('App\User', 'user_products', 'product_id', 'user_id');
    }

    public function cropApprover()
    {
        return $this->belongsTo(User::class, 'crop_approved_by', 'id');
    }

    public function cropRejector()
    {
        return $this->belongsTo(User::class, 'crop_rejected_by', 'id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by', 'id');
    }

    public function rejector()
    {
        return $this->belongsTo(User::class, 'listing_rejected_by', 'id');
    }

    public function cropOrderer()
    {
        return $this->belongsTo(User::class, 'crop_ordered_by', 'id');
    }

    public function rejectedCropApprover()
    {
        return $this->hasOne(User::class, 'reject_approved_by', 'id');
    }

    public function activities()
    {
        return $this->hasMany(ListingHistory::class, 'product_id', 'id');
    }

    public function statuses()
    {
        return $this->hasMany(ProductStatus::class, 'product_id', 'id');
    }

    public function groups()
    {
        return $this->hasMany(ProductQuicksellGroup::class, 'product_id', 'id');
    }

    public function croppedImages()
    {
        return $this->hasMany(SiteCroppedImages::class, 'product_id', 'id');
    }
    public function mediables()
    {
        return $this->hasMany(Mediable::class, 'mediable_id', 'id');
    }

    public function attachImagesToProduct($arrImages = null)
    {
        // check media exist or
        $mediaRecords = false;
        if ($this->hasMedia(\Config('constants.media_original_tag'))) {
            foreach($this->getMedia(\Config('constants.media_original_tag')) as $mRecord) {
                if(file_exists($mRecord->getAbsolutePath())) {
                    $mediaRecords = true;
                }
            }
        }

        if (!$mediaRecords || is_array($arrImages)) {
            // images given
            if (is_array($arrImages) && count($arrImages) > 0) {
                $scrapedProduct = true;
            } else {
                //getting image details from scraped Products
                $scrapedProduct = ScrapedProducts::where('sku', $this->sku)->orderBy('updated_at','desc')->first();
            }

            if ($scrapedProduct != null and $scrapedProduct != '') {
                //Looping through Product Images
                $countImageUpdated = 0;

                // Set arr images
                if (!is_array($arrImages)) {
                    $arrImages = $scrapedProduct->images;
                }

                foreach ($arrImages as $image) {
                    //check if image has http or https link
                    if (strpos($image, 'http') === false) {
                        continue;
                    }

                    try {
                        //generating image from image
                        //this was quick fix for redirect url issue
                        $redirect = \App\Helpers::findUltimateDestination($image,2);
                        if($redirect != null) {
                           $image = str_replace(" ","%20",$redirect);
                        }
                        $jpg = \Image::make($image)->encode('jpg');
                    } catch (\Exception $e) {
                        // if images are null
                        $jpg = null;
                        // need to define error update
                        if($scrapedProduct && is_object($scrapedProduct)) {
                            $lastScraper = ScrapedProducts::where("sku", $this->sku)->latest()->first();
                            if($lastScraper) {
                                $lastScraper->validation_result = $lastScraper->validation_result.PHP_EOL."[error] ".$e->getMessage()." One or more images has an invalid URL : ".$image.PHP_EOL;
                                $lastScraper->save();
                            }
                        }

                    }
                    if ($jpg != null) {
                        $filename = substr($image, strrpos($image, '/'));
                        $filename = str_replace(['/', '.JPEG', '.JPG', '.jpeg', '.jpg', '.PNG', '.png'], '', urldecode($filename));

                        //save image to media
                        $media = MediaUploader::fromString($jpg)->toDirectory('/product/' . floor($this->id / 10000) . '/' . $this->id)->useFilename($filename)->onDuplicateReplace()->upload();
                        $this->attachMedia($media, config('constants.media_original_tag'));
                        $countImageUpdated++;
                    }
                }
                // here is the StatusHelper::$AI being used so disable that status for not
                /*if ($countImageUpdated != 0) {
                    //Updating the Product Status
                    $this->status_id = StatusHelper::$AI;
                    $this->save();
                    // Call status update handler
                    StatusHelper::updateStatus($this, StatusHelper::$AI);
                }*/

            }
        }
    }

    // public function commonComposition($category,$composition)
    // {

    //     $hscodeList = HsCodeGroupsCategoriesComposition::where('category_id', $category)->where('composition',$composition)->first();

    //     if($hscodeList != null && $hscodeList != '')
    //     {
    //         $groupId = $hscodeList->hs_code_group_id;
    //         $group = HsCodeGroup::find($groupId);
    //         $hscodeDetails = SimplyDutyCategory::find($group->hs_code_id);
    //         if($hscodeDetails != null && $hscodeDetails != ''){
    //             if($hscodeDetails->correct_composition != null){
    //                 return $hscodeDetails->correct_composition;
    //             }else{
    //                 return $composition;
    //             }
                
    //         }else{
    //             return $composition;
    //         }
    //     }else{
    //         return $composition;
    //     }

    // }

     public function commonComposition($category,$composition)
    {

        $hscodeList = HsCodeGroupsCategoriesComposition::where('category_id', $category)->where('composition',$composition)->first();

        if($hscodeList != null && $hscodeList != '')
        {
            $groupId = $hscodeList->hs_code_group_id;
            $group = HsCodeGroup::find($groupId);
            if($group != null && $group != '' && $group->composition != null){
                return $group->composition;
            }else{
                $hscodeDetails = HsCode::find($group->hs_code_id);
                if($hscodeDetails != null && $hscodeDetails != ''){
                    if($hscodeDetails->correct_composition != null){
                        return $hscodeDetails->correct_composition;
                    }else{
                        return $composition;
                    }
                
                }else{
                    return $composition;
                }
            }
        }else{
            return $composition;
        }

    }

    public function hsCode($category,$composition){
        $hscodeList = HsCodeGroupsCategoriesComposition::where('category_id', $category)->where('composition',$composition)->first();

        if($hscodeList != null && $hscodeList != '')
        {
            $groupId = $hscodeList->hs_code_group_id;
            $group = HsCodeGroup::find($groupId);
            $hscodeDetails = HsCode::find($group->hs_code_id);
            if($hscodeDetails != null && $hscodeDetails != ''){
                if($hscodeDetails->description != null){
                    return $hscodeDetails->code;
                }else{
                    return false;
                }
                
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public function isGroupExist($category,$composition,$parentCategory,$childCategory){
        $composition = strip_tags($composition);
        $composition = str_replace(['&nbsp;','/span>'],' ',$composition);

        $hscodeList = HsCodeGroupsCategoriesComposition::where('category_id', $category)->where('composition', 'LIKE', '%'.$composition.'%')->first();

        if($hscodeList != null && $hscodeList != '')
        {
            
            return false;
        }else{
          
            return true;
        }      
    }


    public function websiteProducts()
    {
        return $this->hasMany("App\WebsiteProduct","product_id","id");
    }

    
    public function publishedOn()
    {
        return array_keys($this->websiteProducts->pluck("product_id","store_website_id")->toArray());
    }

    /**
     * get product images from watson
     * 
     */

    public static function attachProductChat($brands = [], $category = [], $existeProducts = [])
    {
        return \App\Product::whereIn("brand", $brands)->whereIn("category", $category)
                ->whereNotIn("id", $existeProducts)
                ->join("mediables as m",function($q){
                    $q->on("m.mediable_id","products.id")->where("m.mediable_type",\App\Product::class);
                })
                ->where("stock",">",0)
                ->orderBy("created_at", "desc")
                ->limit(\App\Library\Watson\Action\SendProductImages::SENDING_LIMIT)
                ->get();
    }

    /**
    * Get price calculation
    * @return float
    **/
    public function getPrice($websiteId,$countryId = null, $countryGroup = null,$isOvveride = false, $dutyPrice = 0)
    {
        $website        = is_object($websiteId) ? $websiteId : \App\StoreWebsite::find($websiteId);
        $priceRecords   = null;

        $brandM   = @$this->brands; 
        $productPrice = $this->price;
        $brandID = 0;
        if(isset($brandM) && $brandM) {
            $brandID = $brandM->id;
        }

        // category discount
        $segmentDiscount = 0;
        if(!empty($this->category)) {
            $catdiscount  = \DB::table("categories")->join("category_segments as cs","cs.id","categories.category_segment_id")
            ->join("category_segment_discounts as csd","csd.category_segment_id","cs.id")
            ->where('categories.id',$this->category)
            ->where('csd.brand_id',$brandID)
            ->select("csd.*")
            ->first();

            if($catdiscount) {
                if($catdiscount->amount_type == "percentage") {
                    $percentage = $catdiscount->amount;
                    $percentageA = ($productPrice * $percentage) / 100;
                    $segmentDiscount = $percentageA;
                    $productPrice = $productPrice - $percentageA;
                }else{
                    $segmentDiscount = $catdiscount->amount;
                    $productPrice = $productPrice - $catdiscount->amount;
                }
            }
        }


        if($isOvveride) {
            $productPrice = \App\Product::getIvaPrice($productPrice);
        }

        // add a product price duty
        if($dutyPrice > 0) {
            $totalAmount    = $productPrice * $dutyPrice / 100;
            $productPrice   = $productPrice + $totalAmount;
        }


        if($website) {

           $brand    = @$this->brands->brand_segment;
           $category = $this->category;
           $country  = $countryId;

           

           $priceModal = \App\PriceOverride::where("store_website_id",$website->id);
           $priceCModal = clone $priceModal;

           if(!empty($brand) && !empty($category) && !empty($country))  {
              $priceRecords = $priceModal->where("country_code",$country)->where("brand_segment",$brand)->where("category_id",$category)->first();
           }

           if(!$priceRecords) {
              $priceModal = $priceCModal;
              $priceRecords = $priceModal->where(function($q) use($brand, $category, $country) {
                $q->orWhere(function($q) use($brand, $category) {
                    $q->where("brand_segment", $brand)->where("category_id",$category);
                })->orWhere(function($q) use($brand, $country) {
                    $q->where("brand_segment", $brand)->where("country_code",$country);
                })->orWhere(function($q) use($country, $category) {
                    $q->where("country_code", $country)->where("category_id",$category);
                });
              })->first();
           }

           if(!$priceRecords) {
              $priceModal = $priceCModal;
              $priceRecords = $priceModal->where("brand_segment",$brand)->first();
           }

           if(!$priceRecords) {
              $priceModal = $priceCModal;
              $priceRecords = $priceModal->where("category_id",$category)->first();
           }

           if(!$priceRecords) {
              $priceModal = $priceCModal;
              $priceRecords = $priceModal->where("country_code",$country)->first();
           }

           if($priceRecords) {
              if($priceRecords->calculated == "+") {
                 if($priceRecords->type == "PERCENTAGE")  {
                    $price = ($productPrice * $priceRecords->value) / 100;
                    return ["original_price" => $this->price , "promotion" => $price,'segment_discount' => $segmentDiscount , "total" =>  $productPrice + $price];
                 }else{
                    return ["original_price" => $this->price , "promotion" => $priceRecords->value,'segment_discount' => $segmentDiscount , "total" =>  $productPrice + $priceRecords->value];
                 }
              }
              if($priceRecords->calculated == "-") {
                 if($priceRecords->type == "PERCENTAGE")  {
                    $price = ($productPrice * $priceRecords->value) / 100;
                    return ["original_price" => $this->price , "promotion" => -$price ,'segment_discount' => $segmentDiscount, "total" =>  $productPrice - $price];
                 }else{
                    return ["original_price" => $this->price , "promotion" => - $priceRecords->value,'segment_discount' => $segmentDiscount , "total" =>  $productPrice - $priceRecords->value];
                 }
              }
           }
        }

        return ["original_price" => $this->price , "promotion" => "0.00",'segment_discount' => $segmentDiscount , "total" =>  $productPrice];
    }

    public function getDuty($countryCode , $withtype = false)
    {

        $countryCode = \App\SimplyDutyCountry::where("country_code",$countryCode)->first();

        if($countryCode) {
            return (float)$countryCode->default_duty;
        }

       /*$hsCode = ($this->product_category) ? $this->product_category->simplyduty_code : null;
       if(!empty($hsCode)){
            $duty = \App\CountryDuty::leftJoin("duty_groups as dg","dg.id","country_duties.duty_group_id")
            ->where("country_duties.hs_code",$hsCode)
            ->where("country_duties.destination",$countryCode)
            ->select(["country_duties.*","dg.id as has_group","dg.duty as group_duty","dg.vat as group_vat"])
            ->first();

            if($duty) {
                if($duty->has_group != null) {
                    return $duty->group_duty + $duty->group_vat;
                }else{
                    return $duty->duty_percentage + $duty->vat_percentage;
                }
            }
       }*/
        
        return (float)"0.00";

    }

    public function storeWebsiteProductAttributes($storeId = 0)
    {
        return \App\StoreWebsiteProductAttribute::where("product_id", $this->id)->where("store_website_id",$storeId)->first();
    }

    public function checkExternalScraperNeed($fromscraper = false)
    {
        $parentcate = ($this->category > 0 && $this->categories) ? $this->categories->parent_id :  null;

        if(empty($this->name) 
            || $this->name == ".." 
            || empty($this->short_description) 
            || empty($this->price) 
            || !$this->hasMedia(\Config('constants.media_original_tag'))
        ) {
            $this->status_id = StatusHelper::$requestForExternalScraper;
            if(empty($this->name)) {
                $this->sub_status_id = StatusHelper::$unknownTitle;
            }

            if(empty($this->short_description)) {
                $this->sub_status_id = StatusHelper::$unknownDescription;
            }

            if(empty($this->price)) {
                $this->sub_status_id = StatusHelper::$unknownPrice;
            }

            $this->save();
        }else if(empty($this->composition) || empty($this->color) || empty($this->category || $this->category < 1)) {

            if(empty($this->composition)) {
                $this->status_id = StatusHelper::$requestForExternalScraper;
                $this->sub_status_id = StatusHelper::$unknownComposition;
            }else if(empty($this->color)) {
                $this->status_id = StatusHelper::$requestForExternalScraper;
                $this->sub_status_id = StatusHelper::$unknownColor;
            }else {
                $this->status_id = StatusHelper::$requestForExternalScraper;
                $this->sub_status_id = StatusHelper::$unknownCategory;
            }
            
            $this->save();
        }else if (!($this->category > 0 && $this->categories && $this->categories->need_to_check_measurement)
        ) {
            $this->status_id = StatusHelper::$unknownMeasurement;
            $this->sub_status_id = null;
            $this->save();
        } else{

            // check that product has how many description
            $descriptionCount = $this->suppliers_info->count();
            if($descriptionCount <= 1) {
                $this->status_id = StatusHelper::$requestForExternalScraper;
                $this->sub_status_id = StatusHelper::$unknownDescription;
                $this->save();
            }

            // if validation pass and status is still external scraper then remove and put for the auto crop
            if($this->status_id == StatusHelper::$requestForExternalScraper) {
                if(!($this->category > 0 && $this->categories && $this->categories->need_to_check_size)) {
                   $this->status_id =  StatusHelper::$unknownSize;
                   $this->sub_status_id = null;
                   $this->save();
                }else{
                   $this->status_id =  StatusHelper::$autoCrop;
                   $this->sub_status_id = null;
                   $this->save();
                }
            }
        }

        // if status not request for external scraper then store it
        if($this->status_id != StatusHelper::$requestForExternalScraper) {
           $this->sub_status_id = null;
           $this->save();
        }
    }

    public function landingPageProduct()
    {
        return $this->hasOne('App\LandingPageProduct','product_id','id');
    }

    /**
    * This is using for ingoring the product for next step
    * like due to problem in crop we are not sending white product on approval
    *
    */
    public function isNeedToIgnore()
    {
        if(strtolower($this->color) == "white") {
            $this->status_id = \App\Helpers\StatusHelper::$manualCropping;
            $this->save();
        }
    }

    public function getStoreBrand($storeId)
    {
        $platformId = 0;

        $brand = $this->brands;
        if($brand) {
            $storeWebsiteBrand = \App\StoreWebsiteBrand::where("brand_id",$brand->id)->where("store_website_id",$storeId)->first();
            if($storeWebsiteBrand) {
                $platformId = $storeWebsiteBrand->magento_value;
            }
        }

        return $platformId;
    }

    public static function getProducts($filter_data = array())
    {
        $columns = array(
            'products.id',
            'products.name as product_name',
            'b.name as brand_name',
            'c.title as category_name',
            'products.category',
            'products.supplier',
            'products.sku',
            'products.size',
            'products.color',
            'products.suggested_color',
            'products.composition',
            'products.size_eu',
            'products.stock',
            'psu.size_system',
            'status_id',
            'sub_status_id',
            'products.created_at',

            //'inventory_status_histories.date as history_date',
            \DB::raw('count(distinct psu.id) as total_product'),
            \DB::raw('IF(sp.discounted_percentage IS null, 00 , max(sp.discounted_percentage) ) discounted_percentage ')
        );
        $query =  \App\Product::with('many_scraped_products.brand')->leftJoin("brands as b",function($q){
                $q->on("b.id","products.brand");
            })
            ->leftJoin("categories as c",function($q){
                $q->on("c.id","products.category");
            })
            ->leftJoin("scraped_products as sp",function($q){
                $q->on("sp.product_id","products.id");
            })
            ->Join("product_suppliers as psu",function($q){
                $q->on("psu.product_id","products.id");
            });

        //  check filtering
        if(isset($filter_data['product_names']))      $query = $query->whereIn('products.name',$filter_data['product_names']);

        if(isset($filter_data['product_status']))      $query = $query->whereIn('products.status_id',$filter_data['product_status']);


        if(isset($filter_data['brand_names']))        $query = $query->whereIn('brand',$filter_data['brand_names']);
        if(isset($filter_data['product_categories'])) $query = $query->whereIn('category',$filter_data['product_categories']);
        //$query = $query->leftJoin('inventory_status_histories','inventory_status_histories.product_id','products.id');
        if(isset($filter_data['in_stock'])) {
            if($filter_data['in_stock'] == 1) {
                $query = $query->where('products.stock',">",0);
            }else {
                $query = $query->where('products.stock',"<=",0);
            }
        }
        if(isset($filter_data['date'])) {
            //$query = $query->where('inventory_status_histories.date',$filter_data['date']);
        }

        if( isset($filter_data['start_date']) && isset($filter_data['end_date']) ) {
            //$query = $query->whereBetween('inventory_status_histories.date',[ $filter_data['start_date'], $filter_data['end_date'] ] );
        }

        if(isset($filter_data['date'])) {
            $query = $query->whereDate('products.created_at',$filter_data['date']);
        }

        if(isset($filter_data['date'])) {
            $query = $query->whereDate('products.created_at',$filter_data['date']);
        }

        if(isset($filter_data['discounted_percentage_min'])) {
            $query = $query->where('products.discounted_percentage', '>=',$filter_data['discounted_percentage_min']);
        }

        if(isset($filter_data['discounted_percentage_max'])) {
            $query = $query->where('products.discounted_percentage', '<=',$filter_data['discounted_percentage_max']);
        }
        
        if(isset($filter_data['no_category']) && $filter_data['no_category'] == "on") {
            $query = $query->where('products.category',"<=",0);
        }

        if(isset($filter_data['no_size']) && $filter_data['no_size'] == "on") {
            $query = $query->where('products.status_id',"=",\App\Helpers\StatusHelper::$unknownSize);
        }

        if (isset($filter_data['supplier']) && is_array($filter_data['supplier']) && $filter_data['supplier'][0] != null) {
            $suppliers_list = implode(',', $filter_data['supplier']);
            $query = $query->whereRaw(\DB::raw("products.id IN (SELECT product_id FROM product_suppliers WHERE supplier_id IN ($suppliers_list))"));
        }

        // if(isset($filter_data['date']))               $query = $query->where('products.created_at', 'like', '%'.$filter_data['date'].'%');
        if(isset($filter_data['term'])) {
            $term = $filter_data['term'];
            $query = $query->where(function($q) use ($term) {
                $q->where('products.name', 'LIKE', "%$term%")
                ->orWhere('products.sku', 'LIKE', "%$term%")
                ->orWhere('c.title', 'LIKE', "%$term%")
                ->orWhere('b.name', 'LIKE', "%$term%")
                ->orWhere('products.id', 'LIKE', "%$term%");
            });
        }

        if(isset($filter_data['supplier_count'])) {
            $query = $query->havingRaw('count(products.id) = '.$filter_data['supplier_count']);
        }

        return $query->groupBy("products.id")->with('suppliers_info', 'productstatushistory')->orderBy('products.created_at','DESC')->paginate(Setting::get('pagination'),$columns);
    }
    
    public static function getPruductsNames()
    {
        $columns = array('name');
        $result = array();

        $products_names =  \App\Product::distinct('name')->get($columns);
        foreach ( $products_names as $product_name ) {
            $result[$product_name->name] = $product_name->name;
        }

        asort( $result );
        return $result;
    }

    public static function getPruductsCategories()
    {
        $columns = array('category');
        $result = array();

        $products_categories =  \App\Product::distinct('category')->get($columns);
        foreach ( $products_categories as $product_category ) {
            $result[$product_category->category] = $product_category->category;
        }

        asort( $result );
        return $result;
    }

    public static function getPruductsSku()
    {
        $columns = array('sku');
        $result = array();

        $products_sku =  \App\Product::distinct('sku')->get($columns);
        foreach ( $products_sku as $product_sku ) {
            $result[$product_sku->sku] = $product_sku->sku;
        }

        asort( $result );
        return $result;
    } 
    public function getStatusName()
    {
        return @\App\Helpers\StatusHelper::getStatus()[$this->status_id];
    }

    public static function getProductBySKU($sku)
    {
         return Product::where('sku',$sku)->first();
    }


    public function more_suppliers() {
        $more_suppliers = DB::select('SELECT sp.url as link,s.supplier as name
                            FROM `scraped_products` sp
                            JOIN scrapers sc on sc.scraper_name=sp.website
                            JOIN suppliers s ON s.id=sc.supplier_id
                            WHERE last_inventory_at > DATE_SUB(NOW(), INTERVAL sc.inventory_lifetime DAY) and sp.sku = :sku', ['sku' => $this->sku]);
        return $more_suppliers;
    }

    public function getWebsites()
    {
        $websites = ProductHelper::getStoreWebsiteName($this->id,$this);
        return \App\StoreWebsite::whereIn("id",$websites)->get();
    }

    public function expandCategory()
    {
       $cat = [];
       $list = $this->categories;
       if($list) {
            $cat[] = $list->title;
            $parent = $list->parent;
          if($parent)   {
             $cat[] = $parent->title;
             $parent = $parent->parent;
             if($parent) {
                $cat[] = $parent->title;
                $parent = $parent->parent;
                if($parent) {
                    $cat[] = $parent->title;
                 }
             }
          }
       }
       
       return implode(" >> ", $cat);
    }

    public function getRandomDescription()
    {
        $descriptions = $this->suppliers_info()->pluck("description")->toArray();
        return $descriptions;
    }

    public function setRandomDescription($website, $stock = 1)
    {
        $product = $this;
        $description = $product->short_description;
        // assign description game wise
        // store random description from the website
        $storeWebsiteAttributes = $product->storeWebsiteProductAttributes($website->id);
        if ($storeWebsiteAttributes && !empty($storeWebsiteAttributes->description)) {
            $description = $storeWebsiteAttributes->description;
        }else{
            $randomDescription = $product->getRandomDescription();
            if(!empty($randomDescription)) {
                $randomDescription[] = $product->short_description;
                $storeWebsitePA = \App\StoreWebsiteProductAttribute::where("product_id", $product->id)->get();
                if(!$storeWebsitePA->isEmpty()) {
                    foreach($storeWebsitePA  as $swpa) {
                        foreach($randomDescription as $des) {
                            if(strtolower($des) !=  strtolower($swpa->description)) {
                                $description  = $des;
                            }
                        }
                    }
                }else{
                    shuffle($randomDescription);
                    $description  = $randomDescription[0];
                }

                // if description is not empty
                if(!empty($description)) {
                    $storeWebsitePA                     = new \App\StoreWebsiteProductAttribute;
                    $storeWebsitePA->product_id         = $product->id;
                    $storeWebsitePA->price              = $product->price;
                    $storeWebsitePA->discount           = "0.00";
                    $storeWebsitePA->discount_type      = "percentage";
                    $storeWebsitePA->stock              = $stock;
                    $storeWebsitePA->store_website_id   = $website->id;
                    $storeWebsitePA->description        = $description;
                    $storeWebsitePA->save();
                }
            }
        }

        return $description;
    }

    public static function getIvaPrice($price)
    {
        $percentage = self::IVA_PERCENTAGE;
        $percentageA = ($price * $percentage) / 100;
        return $price - $percentageA;
    }

    public function productstatushistory()
    {
        return $this->hasMany('App\ProductStatusHistory','product_id');
    }

    public function checkPriceRange()
    {
        $get_brand_segment = $this->brands()->first();
        $get_category = $this->category;

        if($get_brand_segment != null &&  isset($get_brand_segment) && $get_brand_segment->brand_segment != ""){
            $getbrandpricerange = \App\BrandCategoryPriceRange::where(['category_id'=>$get_category,'brand_segment'=>$get_brand_segment->brand_segment])->first();
            if($getbrandpricerange != null){
                if($this->price != "" && $this->price >= $getbrandpricerange->min_price && $this->price <= $getbrandpricerange->max_price){
                    return true;
                }else{
                    return false;
                }
            }else{
                return true;
            }
        }else{
            return true;
        }
    }

    public function useCommaKeywords()
    {
        return str_replace(" ", ",", $this->title);
    }
}
