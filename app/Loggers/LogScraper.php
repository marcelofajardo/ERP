<?php
// IF YOU UPDATE THIS FILE, UPDATE IT IN THE ERP REPOSITORY AS WELL

namespace App\Loggers;

use App\Helpers\ProductHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;
use App\SkuFormat;
use App\Brand;
use App\Category;
use App\Supplier;
use App\DeveloperTask;
use App\Scraper;

class LogScraper extends Model
{
    protected $table = 'log_scraper';
    protected $fillable = ['ip_address', 'website', 'url', 'sku', 'brand', 'title', 'description', 'properties', 'images', 'size_system', 'currency', 'price', 'discounted_price','category'];

    public static function LogScrapeValidationUsingRequest($request, $isExcel = 0)
    {
        // Set empty log for errors and warnings
        $errorLog = "";
        $warningLog = "";

        // Validate the website
        $errorLog .= self::validateWebsite($request->website);

        // Validate URL
        $errorLog .= self::validateUrl($request->url);

        // Validate SKU
        $errorLog .= self::validateSku($request->sku);

        //Check Regrex SKU
        $warningLog .= self::validateRegexSku($request->sku, $request->brand);

        // Validate brand
        $errorLog .= self::validateBrand(!empty($request->brand) ? $request->brand : '');

        // Validate title
        //$errorLog .= self::validateTitle($request->title);

        // Validate description
        $warningLog .= self::validateDescription($request->description);

        // Validate size_system
        $errorLog .= self::validateSizeSystem(!empty($request->size_system) ? $request->size_system : '');

        // Validate properties
        $warningLog .= self::validateProperty($request);

        // Validate image warnings
        $warningLog .= self::validateImageWarnings($request->images);

        // Validate image errors
        $errorLog .= self::validateImageErrors($request->images);

        // Validate currency
        $errorLog .= self::validateCurrency($request->currency);

        // Validate price
        $errorLog .= self::validatePrice($request->price);

        // Validate discounted price
        $errorLog .= self::validateDiscountedPrice($request->discounted_price);

        // Find existing record
        $logScraper = new LogScraper();

        // For excels we only need the SKU
        if ($isExcel == 1 && isset($request->sku)) {
            // Replace errors with warnings
            $errorLog = str_replace('[error]', '[warning]', $errorLog);

            // Update warningLog
            $warningLog = $errorLog . $warningLog;

            // Empty error log
            $errorLog = '';
        }

        // Update values
        $logScraper->ip_address = self::getRealIp();
        $logScraper->website = $request->website ?? "";
        $logScraper->url = $request->url ?? "";
        $logScraper->sku = ProductHelper::getSku($request->sku) ?? "";
        $logScraper->original_sku = $request->sku ?? "";
        $logScraper->brand = $request->brand ?? "";
        $logScraper->category = isset($request->properties[ 'category' ]) ? serialize($request->properties[ 'category' ]) : "";
        $logScraper->title = $request->title ?? "";
        $logScraper->description = $request->description ?? "";
        $logScraper->properties = isset($request->properties) ? serialize($request->properties) : "";
        $logScraper->images = isset($request->images) ? serialize($request->images) : "";
        $logScraper->size_system = $request->size_system ?? "";
        $logScraper->currency = $request->currency ?? "";
        $logScraper->price = $request->price ?? "0.00";
        $logScraper->discounted_price = $request->discounted_price ?? 0;
        $logScraper->is_sale = $request->is_sale ?? 0;
        $logScraper->validated = empty($errorLog) ? 1 : 0;
        $logScraper->validation_result = $errorLog . $warningLog;
        //$logScraper->raw_data = isset($_SERVER[ 'REMOTE_ADDR' ]) ? serialize($request->all()) : null;
        $logScraper->save();

        // Update modified date
        $logScraper->touch();
        $logScraper->save();

        // Return true or false
        return ["error" => $errorLog , "warning" => $warningLog];
    }

    public static function validateWebsite($website)
    {
        // Check if we have a value
        if (empty($website)) {
            return "[error] Website cannot be empty\n";
        }

        // Return an empty string
        return "";
    }

    public static function validateUrl($url)
    {
        // Check if we have a value
        if (empty($url)) {
            return "[error] URL cannot be empty\n";
        }

        // Check if the URL is valid
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return "[error] URL is not valid\n";
        }


        // Return an empty string
        return "";
    }

    public static function validateSku($sku)
    {
        // Check if we have a value
        if (empty($sku)) {
            return "[error] SKU cannot be empty\n";
        }

        // Check for length
        /*if (strlen($sku) < 5) {
            return "[error] SKU must be at least five characters\n";
        }*/

        // Return an empty string
        return "";
    }

    public static function validateBrand($brand)
    {
        // Check if we have a value
        if (empty($brand)) {
            return "[error] Brand cannot be empty\n";
        }

        // Return an empty string
        return "";
    }

    public static function validateTitle($title)
    {
        // Check if we have a value
        if (empty($title)) {
            return "[error] Title cannot be empty\n";
        }

        // Return an empty string
        return "";
    }

    public static function validateDescription($description)
    {
        // Check if we have a value
        if (empty($description)) {
            return "[warning] Description is empty\n";
        }

        // Return an empty string
        return "";
    }

    public static function validateProperty($request)
    {
        // Check if we have a value
        $string = "";
        
        if(isset($request->properties)) {
           $properties =  $request->properties;
           if(empty($properties['category'])){
              $string .= "[warning] Category is empty".PHP_EOL;
           }
        }
        
        return $string;
    }

    

    public static function validateSizeSystem($sizeSystem)
    {
        // Check if we have a value
        if (empty($sizeSystem)) {
            return "[error] Size system is missing\n";
        }

        // Return an empty string
        return "";
    }

    public static function validateImageWarnings($images)
    {
        // Check if we have a value
        if (empty($images)) {
            return "[warning] Product without images\n";
        }

        // Return an empty string
        return "";
    }

    public static function validateImageErrors($images)
    {
        // Check if we have an array
        if ($images != '' && !is_array($images)) {
            return "[error] Images must be an array\n";
        }

        // Check image URLS
        if (is_array($images)) {
            foreach ($images as $image) {
                if (!filter_var($image, FILTER_VALIDATE_URL)) {
                    return "[error] One or more images has an invalid URL\n";
                }
            }
        }

        // Return an empty string
        return "";
    }

    public static function validateCurrency($currency)
    {
        // Check if we have a value
        if (empty($currency)) {
            return "[error] Currency cannot be empty\n";
        }

        // Check for three characters
        if (strlen($currency) != 3) {
            return "[error] Currency must be exactly three characters\n";
        }

        // Return an empty string
        return "";
    }

    public static function validatePrice($price)
    {
        // Check if we have a value
        if (empty($price)) {
            return "[error] Price cannot be empty\n";
        }

        // Check for comma's
        if (stristr($price, ',')) {
            return "[error] Comma in the price\n";
        }

        // Check for two dots
        if (substr_count($price, '.') > 1) {
            return "[error] More than one dot in the price\n";
        }

        // Check if price is a float value
        if ((float)$price == 0) {
            return "[error] Price must be of type float/double\n";
        }

        // Return an empty string
        return "";
    }

    public static function validateDiscountedPrice($discountedPrice)
    {
        // Check if discounted price is a float value
        if (!empty($discountedPrice) && (float)$discountedPrice == 0) {
            return "[error] Discounted price must be of type float/double\n";
        }

        // Return an empty string
        return "";
    }

    private static function getRealIp()
    {
        // Check which IP to use
        if (!empty($_SERVER[ 'HTTP_CLIENT_IP' ])) {
            $ip = $_SERVER[ 'HTTP_CLIENT_IP' ];
        } elseif (!empty($_SERVER[ 'HTTP_X_FORWARDED_FOR' ])) {
            $ip = $_SERVER[ 'HTTP_X_FORWARDED_FOR' ];
        } elseif (!empty($_SERVER[ 'REMOTE_ADDR' ])) {
            $ip = $_SERVER[ 'REMOTE_ADDR' ];
        } else {
            $ip = "none";
        }

        // Return IP
        return $ip;
    }

    public static function validateRegexSku($sku, $brand)
    {
        $skuNew = ProductHelper::getSku($sku);
        // Do we have a brand?
        if ($brand != null) {
            // Find brand ID from brand
            $brand = Brand::where('name', $brand)->first();

            // Brand found?
            if ($brand != null) {
                // Get SKU from brand ID
                $skuFormat = SkuFormat::where('brand_id', $brand->id)->first();

                // If sku_format is not empty
                if (!empty($skuFormat->sku_format)) {
                    try {
                        // Run brand regex on sku
                        preg_match('/' . $skuFormat->sku_format . '/', $skuNew, $matches, PREG_UNMATCHED_AS_NULL);

                        // Do we have a match
                        if (isset($matches) && isset($matches[ 0 ]) && $matches != null) {
                            // Is the match equal to the SKU
                            if ($matches[ 0 ] == $skuNew) {
                                // Return if we have a match
                                return;
                            }
                        }
                    } catch (\Exception $e) {
                        return "[warning] Regex generated an exception for brand " . $brand->name . " with regex '" . $skuFormat->sku_format . "'\n";
                    }
                }

                // If sku_format_without_color is not empty
                if (!empty($skuFormat->sku_format_without_color)) {
                    try {
                        // Run brand regex on sku
                        preg_match('/' . $skuFormat->sku_format_without_color . '/', $skuNew, $matchesWithoutColor, PREG_UNMATCHED_AS_NULL);

                        // Do we have a match
                        if (isset($matchesWithoutColor) && isset($matchesWithoutColor[ 0 ]) && $matchesWithoutColor != null) {
                            // Is the match equal to the SKU
                            if ($matchesWithoutColor[ 0 ] == $skuNew) {
                                // Return if we have a match
                                return;
                            }
                        }
                    } catch (\Exception $e) {
                        return "[warning] Regex without color generated an exception for brand " . $brand->name . " with regex '" . $skuFormat->sku_format . "'\n";
                    }
                }

                // Still here? Send a warning TODO: Will be an error in the future
                return "[warning] SKU failed regex test\n";
            }
        }

        // If we end up here, there is no regex set for this brand TODO: Will be an error in the future
        return "[warning] No brand found (" . $brand . ")\n";
    }

    public function skuFormat($sku, $brand)
    {
        try {

            $brand = Brand::where('name',$brand)->first();

            $sku = SkuFormat::where('brand_id',$brand->id)->first();
            if($sku != null){
                return $sku->sku_format;
            }else{
                return '';
            }


        }catch (Exception $e) {

            return '';
        }

    }

    public function skuFormatExample($sku, $brand)
    {
        try {

            $brand = Brand::where('name',$brand)->first();

            $sku = SkuFormat::where('brand_id',$brand->id)->first();
            if($sku != null){
                return $sku->sku_examples;
            }else{
                return '';
            }
        }catch (Exception $e) {

            return '';
        }
    }

    public function skuError($validation)
    {
        try {
            $validations = explode('[warning]', $validation);
            if(is_array($validations)){
                foreach ($validations as $validation) {
                    if(strpos($validation, 'SKU') !== false){
                        return $validation;
                    }
                }
            }

            return '';

        } catch (Exception $e) {
            return '';
        }
    }

    public function dataUnserialize($string){
        try {
            $string = @unserialize($string);
            if(is_array($string)){
                return implode(' , ', $string);
            }else{
                return $string;
            }

        } catch (Exception $e) {
            return $string;
        }

    }

    public function scraper(){
        return $this->hasOne(Scraper::class,'scraper_name','website');
    }

    public function taskType($supplier,$category,$brand){
        $string = $supplier.$category.$brand;
        $reference = md5(strtolower($string));
        $issue = DeveloperTask::where('reference',$reference)->first();
        if($issue != null && $issue != ''){
            if($issue->status == 'Done'){
                return '<p>Issue Resolved</p><button type="button" class="btn btn-xs btn-image load-communication-modal" data-object="developer_task" data-id="'.$issue->id.'" title="Load messages"><img src="/images/chat.png" alt=""></button>';
            }else{
                return '<p>Issue Pending</p><button type="button" class="btn btn-xs btn-image load-communication-modal" data-object="developer_task" data-id="'.$issue->id.'" title="Load messages"><img src="/images/chat.png" alt=""></button>';
            }
        }else{
            return false;
        }
    }

    public function brandLink($link,$sku){

         if($link != null){
            return $link = str_replace('[SEARCH]',$sku,$link);
        }else{
            return false;
         }
    }

    public function getFailedCount($supplier,$brand){

        $count = LogScraper::where('brand',$brand)->where('website',$supplier)->where('validation_result', 'LIKE', '%SKU failed regex test%')->count();
        return $count;

    }

    public function getSKUExample($brand){
        $brand = Brand::select('id')->where('name',$brand)->first();
        if($brand != null && $brand != ''){
            $format = SkuFormat::select('sku_examples')->where('brand_id',$brand->id)->first();
            if($format != null && $format != ''){
                $formats = explode(',',$format->sku_examples);
                return $formats[0];
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public function getSKUExampleFormat($brand){
        $brand = Brand::select('id')->where('name',$brand)->first();
        if($brand != null && $brand != ''){
            $format = SkuFormat::select('sku_format')->where('brand_id',$brand->id)->first();
            if($format != null && $format != ''){
                return $format->sku_format;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public function getSKUExampleFromLogScraper($supplier,$brand){
        $skuLog = LogScraper::where('brand',$brand)->where('website',$supplier)->where('validation_result', 'LIKE', '%SKU failed regex test%')->first();
        if($skuLog != null && $skuLog != ''){
            return $skuLog->sku;
        }else{
            return false;
        }

    }

    public function getSKUExampleLinkFromLogScraper($supplier,$brand){
        $skuLog = LogScraper::where('brand',$brand)->where('website',$supplier)->where('validation_result', 'LIKE', '%SKU failed regex test%')->first();
        if($skuLog != null && $skuLog != ''){
            return $skuLog->url;
        }else{
            return false;
        }

    }

    public function brands(){
        return $this->hasOne(Brand::class,'name','brand');
    }

    public function skuStringCompareWithExample($example,$sku)
    {
        if($example != null && $sku != null){
            $sample = explode(',',$example);
            $string = str_replace(' ', '-', $sample[0]); // Replaces all spaces with hyphens.
            $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string);

            if(strlen($string) < strlen($sku)){
                return 'SKU string count is bigger the example';
            }else{
                return 'String Count Is Proper';
            }

        }else{
            return "SKU Example Not Present";
        }
    }
}
