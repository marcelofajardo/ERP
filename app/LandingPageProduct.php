<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use App\Helpers\StatusHelper;
use Illuminate\Database\Eloquent\Model;

class LandingPageProduct extends Model
{

    /**
     * @var string
     * @SWG\Property(property="name",type="string")
     * @SWG\Property(property="product_id",type="integer")
     * @SWG\Property(property="description",type="string")
     * @SWG\Property(property="price",type="float")
     * @SWG\Property(property="shopify_id",type="integer")
     * @SWG\Property(property="stock_status",type="string")
     * @SWG\Property(property="store_website_id",type="integer")
     * @SWG\Property(property="status",type="sting")
      * @SWG\Property(property="landing_page_status_id",type="integer")
     * @SWG\Property(property="start_date",type="datetime")
     * @SWG\Property(property="end_date",type="datetime")
     * @SWG\Property(property="created_at",type="datetime")
     * @SWG\Property(property="updated_at",type="datetime")
   
     */

    const STATUS = [
        "De-active",
        "Active",
        "APPROVED" => 2,
        "USER_UPLOADED" => "User Uploaded"
    ];

    const GALLERY_TAG_NAME = "gallery";

    protected $fillable = ['product_id', 'name', 'description', 'price', 'shopify_id', 'stock_status', 'store_website_id', 'status', 'landing_page_status_id', 'start_date', 'end_date', 'created_at', 'updated_at'];

    public function product()
    {
        return $this->hasOne(\App\Product::class, "id", "product_id");
    }

    public function getShopifyPushData($product = null, $storeWebsiteId = null)
    {
        $landingPageProduct = ($product) ? $product : $this->product;

        if (!StatusHelper::isApproved($landingPageProduct->status_id) && $landingPageProduct->status_id != StatusHelper::$finalApproval) {
            return false;
        }

        // create a html for submit the file
        $html   = [];
        $html[] = ($product) ? $product->short_description : $this->description;

        if (!empty($landingPageProduct->composition)) {
            //$html[] = "<p><b>Composition</b> : {$landingPageProduct->composition}</p>";
        }

        if (!empty($landingPageProduct->lmeasurement) || !empty($landingPageProduct->hmeasurement) || !empty($landingPageProduct->dmeasurement)) {
            //$html[] = "<p><b>Dimensions</b> : L - {$landingPageProduct->lmeasurement} , H - {$landingPageProduct->hmeasurement} , D - {$landingPageProduct->dmeasurement}   </p>";
        }

        $storeWebsiteId = ($storeWebsiteId) ? $storeWebsiteId : $this->store_website_id;

        if ($storeWebsiteId) {
            $sizeCharts = \App\BrandCategorySizeChart::getSizeChat($landingPageProduct->brand, $landingPageProduct->category, $storeWebsiteId);
            if (!empty($sizeCharts)) {
                foreach ($sizeCharts as $sizeC) {
                    // $sizeC  = str_replace(env("APP_URL"), "", $sizeC);
                    $sizeC  = str_replace(config('env.APP_URL'), "", $sizeC);
                    // $sizeC  = env("SHOPIFY_CDN").$sizeC;
                    $sizeC  = config('env.SHOPIFY_CDN').$sizeC;
                    //$html[] = '<p><b>Size Chart</b> : <a href="' . $sizeC . '">Here</a></p>';
                }
            }
        }

        if ($landingPageProduct) {
            $productData = [
                'product' => [
                    'images'          => [],
                    'product_type'    => ($landingPageProduct->product_category && $landingPageProduct->category > 1) ? $landingPageProduct->product_category->title : "",
                    'published_scope' => 'web',
                    'title'           => ($product) ? $product->name : $this->name,
                    'body_html'       => implode("<br>", $html),
                    //'variants'        => [],
                    'vendor'          => ($landingPageProduct->brands) ? $landingPageProduct->brands->name : "",
                    'tags'            => 'Home Page',
                    'barcode'         =>  $landingPageProduct->id
                ],
            ];
        }

        // Add images to product
        if ($landingPageProduct->hasMedia(config('constants.attach_image_tag'))) {
            foreach ($landingPageProduct->getAllMediaByTag() as $tag => $medias) {
                // if there is specific color then only send the images
                if (strpos($tag, self::GALLERY_TAG_NAME) !== false) {
                    foreach ($medias as $image) {
                        $productData['product']['images'][] = ['src' => $image->getUrl()];
                    }
                }
            }
        }
        
        $generalOptions = [
            'barcode'              => (string) ($product) ? $product->id : $this->product_id,
            'fulfillment_service'  => 'manual',
            'requires_shipping'    => true,
            'sku'                  => $landingPageProduct->sku,
            'title'                => ($product) ? $product->name : (string) $this->name,
            'inventory_management' => 'shopify',
            'inventory_policy'     => 'deny',
            //'inventory_quantity'   => ($this->stock_status == 1) ? $landingPageProduct->stock : 0,
        ];

        if($product) {
            $generalOptions['inventory_quantity'] = $product->stock;
        }else{
            $generalOptions['inventory_quantity'] = ($this->stock_status == 1) ? $landingPageProduct->stock : 0;
        }

        if($this->stock_status != 1) {
            $productData['product']['published'] = false;
            $productData['product']['published_scope'] = false;
        }else{
            $productData['product']['published'] = true;
            $productData['product']['published_scope'] = "web";
        }

        if (!empty($landingPageProduct->size)) {
            $productSizes = explode(',', $landingPageProduct->size);
            $values       = [];
            $sizeOptions  = [];
            foreach ($productSizes as $size) {
                array_push($values, (string) $size);
                $sizeOptions[$size] = $this->price;
            }
            $variantsOption = [
                'name'   => 'sizes',
                'values' => $values,
            ];
            $productData['product']['options'][] = $variantsOption;
        }

        $countryGroupOptions = [];

        // setup for price
        $countryVariants = [];
        if ($this->store_website_id > 0) {
            $countryGroups = \App\CountryGroup::all();
            if (!$countryGroups->isEmpty()) {
                $countryList = [];
                foreach ($countryGroups as $cg) {
                    array_push($countryList, (string) $cg->name);
                    $price        = $landingPageProduct->getPrice($this->store_website_id, $cg->id);
                    $firstCountry = $cg->groupItems->first();
                    // get the duty price of first country to see
                    $dutyPrice = 0;
                    if ($firstCountry) {
                        $dutyPrice = $landingPageProduct->getDuty($firstCountry->country_code);
                    }
                    $countryGroupOptions[$cg->name] = $price['total'] + $dutyPrice;
                }
                $variantsOption = [
                    'name'   => 'country',
                    'values' => $countryList,
                ];
                $productData['product']['options'][] = $variantsOption;
            }
        }

        foreach ($countryGroupOptions as $k => $v) {
            if (!empty($sizeOptions)) {
                foreach ($sizeOptions as $p => $d) {
                    $generalOptions["option1"]            = $p;
                    $generalOptions["option2"]            = $k;
                    $generalOptions["price"]              = $v;
                    $productData['product']['variants'][] = $generalOptions;
                }
            } else {
                $generalOptions["option1"]            = $k;
                $generalOptions["price"]              = $v;
                $productData['product']['variants'][] = $generalOptions;
            }
        }

        return $productData;

    }

    public function landing_page_status()
    {
        return $this->belongsTo(LandingPageStatus::class, 'landing_page_status_id');
    }
}
