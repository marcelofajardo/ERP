<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SkuFormat;

class TextController extends Controller
{
    public function index()
    {
    	$sku = '590716WA6F01000';
    	$skuFormat = SkuFormat::where('brand_id', 2)->first();
    	
               // Run brand regex on sku
            preg_match('/' . $skuFormat->sku_format . '/', $sku, $matches, PREG_UNMATCHED_AS_NULL);

            // Do we have a match
            if (isset($matches) && isset($matches[ 0 ]) && $matches != null) {
                // Is the match equal to the SKU
                if ($matches[ 0 ] == $sku) {
                    // Return if we have a match
                    dd('yo');
                }
            }
                    
               
    }
}
