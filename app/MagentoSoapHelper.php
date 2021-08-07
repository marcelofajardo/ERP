<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use App\Helpers\StatusHelper;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Helpers\ProductHelper;
use App\Loggers\LogListMagento;
use App\HsCodeGroupsCategoriesComposition;
use App\SimplyDutyCategory;
use \App\Helpers\TranslationHelper;
use App\Language;
use App\TranslatedProduct;

class MagentoSoapHelper
{
    private $_options = null;
    private $_proxy = null;
    private $_sessionId = null;

    function __construct()
    {
        // Set options
        $this->_options = [
            'trace' => true,
            'connection_timeout' => 120,
            'wsdl_cache' => WSDL_CACHE_NONE,
        ];

        // Connect
        $this->_connect();
    }

    private function _connect()
    {
        // Check if we have an existing session
        if ($this->_sessionId !== null) {
            return;
        }

        // Connect
        $this->_proxy = new \SoapClient(config('magentoapi.url'), $this->_options);

        // Set session ID
        try {
            $this->_sessionId = $this->_proxy->login(config('magentoapi.user'), config('magentoapi.password'));
        } catch (\SoapFault $fault) {
            // Log the error
            LogListMagento::log(null, "Unable to connect to Magento via SOAP: " . $fault->getMessage(), 'emergency');

            // Set session ID to false
            $this->_sessionId = false;
        }
    }

    public function pushProductToMagento(Product $product)
    {
        // Check for product and session
        if ($product === null || !$this->_sessionId) {
            return false;
        }

        // Set Magento categories
        $categories = Category::getCategoryTreeMagento($product->category);

        // Get brand
        // $brand = $product->brands()->get();

        // Push brand to categories array
        // 04NOV19 Brands is an attribute
        //if ($brand !== null && isset($brand[ 0 ]->magento_id)) {
        //    array_push($categories, $brand[ 0 ]->magento_id);
        //}

        // Add the product to the sales category
        // 03NOV19 We now set an attribute for bestbuys
        //if ($product->is_on_sale) {
        //    $categories[] = 1237;
        //}

        // No categories found?
        if (count($categories) == 0) {
            // Log the error
            LogListMagento::log($product->id, "No categories found for product ID " . $product->id, 'emergency');
            return false;
        }

        // Check for readiness to go to Magento
        if (!ProductHelper::checkReadinessForLive($product)) {
            // Write to log file
            LogListMagento::log($product->id, "Failed readiness test for product ID " . $product->id, 'emergency');

            // Update product status - sent to manual attribute
            $product->status_id = StatusHelper::$manualAttribute;
            $product->save();

            // Return false
            return false;
        }

        // Check for existing product references and remove them
        if ($product->references) {
            $product->references()->delete();
        }

        // Create a new product reference (without sizes)
        $reference = new ProductReference;
        $reference->product_id = $product->id;
        $reference->sku = $product->sku;
        $reference->color = $product->color;
        $reference->save();

        // Check for brand name
        if ( !isset($product->brands->name) ) {
            // Return false
            return false;
        }

        // Create meta description
        $meta = [];
        $meta[ 'description' ] = 'Shop ' . $product->brands->name . ' ' . $product->color . ' .. ' . $product->composition . ' ... ' . $product->product_category->title . ' Largest collection of luxury products in the world from Solo luxury at special prices';

        // If sizes are given we create a configurable product and several single child products
        if (!empty($product->size) && $product->size == 'OS') {
            $product->size = null;
            $result = $this->_pushSingleProduct($product, $categories, $meta);
        } elseif (!empty($product->size)) {
            $result = $this->_pushConfigurableProductWithChildren($product, $categories, $meta);
        } else {
            $result = $this->_pushSingleProduct($product, $categories, $meta);
        }

        // Handle result
        if ($result) {
            // Push images
            $this->_pushImages($product, $result);

            // Set product to uploaded and listed
            $product->status_id = StatusHelper::$inMagento;
            $product->isUploaded = 1;
            $product->is_uploaded_date = Carbon::now();
            $product->isListed = 1;
            $product->save();

            // Return true
            return true;
        }

        // Return result
        return $result;
    }

    private function _pushConfigurableProductWithChildren(Product $product, $categories = [], $meta = [])
    {
        // Create empty array to store SKUs
        $associatedSkus = [];
        $productId = $product->id;
        // Get all the sizes
        $arrSizes = explode(',', $product->size);
        $hasEuSize = false;
        $euArrSize = explode(',', $product->size_eu);
        if(!empty($euArrSize)) {
            $arrSizes = $euArrSize;
            $hasEuSize = true;
        }

        // Loop over each size and create a single (child) product
        foreach ($arrSizes as $size) {
            // Get correct size
            if(!$hasEuSize){
                $size = ProductHelper::getWebsiteSize($product->size_system, $size, $product->category);
            }

            // Set SKU
            $sku = $product->sku . $product->color;

            $productId = $product->id;

             //Getting Hscode Attribute    
            
            
            // Create a new product reference for this size
            $reference = new ProductReference;
            $reference->product_id = $product->id;
            $reference->sku = $product->sku;
            $reference->color = $product->color;
            $reference->size = $size;
            $reference->save();

            // Set product data for Magento
            $productData = array(
                'categories' => $categories,
                'name' => $product->name,
                'description' => $meta[ 'description' ],
                'short_description' => $product->short_description,
                'website_ids' => array(1), // ID or code of website
                'status' => 1, // 1 = Enabled, 2 = Disabled
                'visibility' => 1, // 1 = Not visible, 2 = Catalog, 3 = Search, 4 = Catalog/Search
                'tax_class_id' => 2, // Default VAT setting
                'weight' => 0,
                'stock_data' => [
                    'use_config_manage_stock' => 1,
                    'manage_stock' => 1,
                    'qty' => 1,
                    'is_in_stock' => 1,
                ],
                'price' => $product->price, // Same price as configurable product, no price change
                'special_price' => $product->price_eur_special,
                'additional_attributes' => [
                    'single_data' => [
                        ['key' => 'composition', 'value' => $product->commonComposition($product->category,$product->composition),],
                        ['key' => 'color', 'value' => $product->color,],
                        ['key' => 'sizes', 'value' => $size,],
                        ['key' => 'country_of_manufacture', 'value' => $product->made_in,],
                        ['key' => 'brands', 'value' => $product->brands()->get()[ 0 ]->name,],
                        ['key' => 'bestbuys', 'value' => $product->is_on_sale ? 1 : 0],
                        ['key' => 'flashsales', 'value' => 0],
                        ['key' => 'hscode', 'value' => $product->hsCode($product->category,$product->composition),],
                    ]
                ]
            );

            // Push simple product to Magento
            $result = $this->_pushProduct('simple', $sku, $productData, $size , $productId);

            // Successful
            if ($result) {
                $associatedSkus[] = $sku . '-' . $size;
            }
        }

        // Check if we have associated SKUs
        if (count($associatedSkus) == 0) {
            return false;
        }

        /**
         * Set product data for configurable product
         */
        $productData = array(
            'categories' => $categories,
            'name' => $product->name,
            'description' => '<p></p>',
            'short_description' => $product->short_description,
            'website_ids' => array(1), // Id or code of website
            'status' => 1, // 1 = Enabled, 2 = Disabled
            'visibility' => 4, // 1 = Not visible, 2 = Catalog, 3 = Search, 4 = Catalog/Search
            'tax_class_id' => 2, // Default VAT setting
            'weight' => 0,
            'stock_data' => array(
                'use_config_manage_stock' => 1,
                'manage_stock' => 1,
                'qty' => 1,
                'is_in_stock' => 1,
            ),
            'price' => $product->price, // Same price as configurable product, no price change
            'special_price' => $product->price_eur_special,
            'associated_skus' => $associatedSkus, // Simple products to associate
            'configurable_attributes' => array(155),
            'additional_attributes' => [
                'single_data' => [
                    ['key' => 'composition', 'value' => $product->commonComposition($product->category,$product->composition),],
                    ['key' => 'color', 'value' => $product->color,],
                    ['key' => 'country_of_manufacture', 'value' => $product->made_in,],
                    ['key' => 'brands', 'value' => $product->brands()->get()[ 0 ]->name,],
                    ['key' => 'manufacturer', 'value' => ucwords($product->brands()->get()[ 0 ]->name),],
                    ['key' => 'bestbuys', 'value' => $product->is_on_sale ? 1 : 0],
                    ['key' => 'flashsales', 'value' => 0],
                    ['key' => 'hscode', 'value' => $product->hsCode($product->category,$product->composition),],
                ]
            ]
        );

        // Get result
        $result = $this->_pushProduct('configurable', $sku, $productData,'',$productId);

        // Return result
        return $result;
    }

    private function _pushSingleProduct(Product $product, $categories, $meta)
    {
        // Set SKU
        $sku = $product->sku . $product->color;

        $productId = $product->id;

        // Set measurement
        $measurement = ProductHelper::getMeasurements($product);

        // Set product data
        $productData = array(
            'categories' => $categories,
            'name' => strtoupper($product->name),
            'description' => '<p></p>',
            'short_description' => ucwords(strtolower($product->short_description)),
            'website_ids' => array(1), // Id or code of website
            'status' => 1, // 1 = Enabled, 2 = Disabled
            'visibility' => 4, // 1 = Not visible, 2 = Catalog, 3 = Search, 4 = Catalog/Search
            'tax_class_id' => 2, // Default VAT setting
            'weight' => 0,
            'stock_data' => [
                'use_config_manage_stock' => 1,
                'manage_stock' => 1,
                'qty' => 1,
                'is_in_stock' => 1,
            ],
            'price' => $product->price,
            'special_price' => $product->price_eur_special,
            'additional_attributes' => [
                'single_data' => [
                    ['key' => 'composition', 'value' => ucwords($product->commonComposition($product->category,$product->composition)),],
                    ['key' => 'color', 'value' => ucwords($product->color),],
                    ['key' => 'measurement', 'value' => $measurement,],
                    ['key' => 'country_of_manufacture', 'value' => ucwords($product->made_in),],
                    ['key' => 'brands', 'value' => ucwords($product->brands()->get()[ 0 ]->name),],
                    ['key' => 'manufacturer', 'value' => ucwords($product->brands()->get()[ 0 ]->name),],
                    ['key' => 'bestbuys', 'value' => $product->is_on_sale ? 1 : 0],
                    ['key' => 'hscode', 'value' => $product->hsCode($product->category,$product->composition),]
                ]
            ]
        );

        // Get result
        $result = $this->_pushProduct('single', $sku, $productData ,'',$productId);

        // Return result
        return $result;
    }

    private function _pushProduct($productType, $sku, $productData = [], $size = null , $productId = null)
    {
        // Set product specific SKU
        $sku = $sku . (!empty($size) ? '-' . $size : '');

        // Try to store the product in Magento
        try {
            // Get result
            $result = $this->_proxy->catalogProductCreate(
                $this->_sessionId,
                $productType,
                14, // Attribute set
                $sku,
                $productData
            );

            // Log info
            Log::channel('listMagento')->info("Product (" . $productType . ") with SKU " . $sku . " successfully pushed to Magento");
            if($result){
                $translated = $this->languageTranslate($result,$productData,$sku,$productId);
                if($translated == true){
                    return $result;
                }
            }
            // Return result
           // return $result;
        } catch (\Exception $e) {
            // Check exception message to see if the product already exists
            if ($e->getMessage() == 'The value of attribute "SKU" must be unique') {
                // Log info
                Log::channel('listMagento')->info("Product (" . $productType . ") with SKU " . $sku . " already exists in Magento");

                // Return true
                return true;
            }

            // Log alert
            Log::channel('listMagento')->alert("Product (" . $productType . ") with SKU " . $sku . " failed while pushing to Magento. Message: " . $e->getMessage());

            // Return false
            return false;
        }
    }

    private function _pushImages(Product $product, $magentoProductId = 0)
    {
        // Get images which belong to product
        $images = $product->getMedia(config('constants.media_gallery_tag'));

        // Set i to 0
        $i = 0;

        // Loop over images
        foreach ($images as $image) {
            // Only run if the file exists
            if (file_exists($image->getAbsolutePath()) && stristr($image->getAbsolutePath(), 'cropped')) {
                // Set file attributes
                $file = array(
                    'name' => $image->getBasenameAttribute(),
                    'content' => base64_encode(file_get_contents($image->getAbsolutePath())),
                    'mime' => mime_content_type($image->getAbsolutePath())
                );

                // Set image type
                $types = $i ? [] : ['size_guide', 'image', 'small_image', 'thumbnail'];
                $types = $i == 1 ? ['hover_image'] : $types;

                // Push image to Magento
                if ($i < 5) {
                    try {
                        $this->_proxy->catalogProductAttributeMediaCreate(
                            $this->_sessionId,
                            $magentoProductId,
                            array('file' => $file, 'label' => $image->getBasenameAttribute(), 'position' => ++$i, 'types' => $types, 'exclude' => 0)
                        );

                        // Log info
                        Log::channel('listMagento')->info("Image for product " . $product->id . " with name " . $file[ 'name' ] . " successfully pushed to Magento");
                    } catch (\SoapFault $e) {
                        // Log alert
                        Log::channel('listMagento')->alert("Image for product " . $product->id . " with name " . $file[ 'name' ] . " failed while pushing to Magento with message: " . $e->getMessage());
                    }
                }
            }
        }

        // Return
        return;
    }

    public function catalogCategoryInfo($magentoId = 0)
    {
        // Check for product and session
        if ((int)$magentoId == 0 || !$this->_sessionId) {
            return false;
        }

        try {
            $result = $this->_proxy->catalogCategoryInfo(
                $this->_sessionId,
                $magentoId
            );
        } catch (\SoapFault $e) {
            echo $e->getMessage();
            return false;
        }

        // Return result
        return $result;
    }

    public function catalogCategoryCreate($parentId = 0, $arrCategoryData = [])
    {
        // Check for product and session
        if (!$this->_sessionId) {
            return false;
        }

        try {
            $result = $this->_proxy->catalogCategoryCreate(
                $this->_sessionId,
                $parentId,
                $arrCategoryData
            );
        } catch (\SoapFault $e) {
            echo $e->getMessage();
            return false;
        }

        // Return result
        return $result;
    }

    public function languageTranslate($id,$productData,$sku,$productId)
    {
        $languages = Language::whereNotNull('locale')->get();
        foreach ($languages as $language) {
            $name = $productData['name'];
            $name = TranslationHelper::translate('en', $language->locale, $name);

            $description = $productData['description'];
            $description = TranslationHelper::translate('en', $language->locale, $description);

            $shortDescription = $productData['short_description'];
            $shortDescription = TranslationHelper::translate('en', $language->locale, $shortDescription);

            $product = Product::find($productId);

            //Insert data into translation table
            $translated = new TranslatedProduct;
            $translated->product_id = $product->id;
            $translated->description = $description;
            $translated->short_description = $shortDescription;
            $translated->name = $name;
            $translated->language_id = $language->id;
            $translated->save();

            $updateProductData = array(
                                'name' => $name, 
                                'description' => $description,
                                'short_description' => $shortDescription,
                            );
        
            $result = $this->_proxy->catalogProductUpdate(
                $this->_sessionId,
                $id,
                $updateProductData,
                $language->code
            );
        }
        
        return true;
        //save translated data
        //update product on magento
        
    }
}