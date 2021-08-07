<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ScrapeController extends Controller
{
    /**
     * @SWG\Get(
     *   path="/scrape/queue",
     *   tags={"Scrape"} ,
     *   summary="Scrape Queue list",
     *   operationId="scrape-queue",
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error"),
     *      @SWG\Parameter(
     *          name="email",
     *          in="path",
     *          required=true, 
     *          type="string" 
     *      ),
            @SWG\Parameter(
     *          name="website",
     *          in="path",
     *          required=true, 
     *          type="string" 
     *      ),
     * )
     *
     */
   /**
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getUrlFromQueue()
    {
        // Random array
        $arrData = [];
        $arData[] = [
            'id' => 1,
            'url' => 'https://www.matchesfashion.com/intl/products/1272559',
            'name' => 'matchesfashion',
            'scope' => [
                'title',
                'description'
            ]
        ];

        $arData[] = [
            'id' => 2,
            'url' => 'https://www.matchesfashion.com/intl/products/Saint-Laurent-West-harness-suede-boots-1251075',
            'name' => 'matchesfashion',
            'scope' => [
                '*'
            ]
        ];

        $arData[] = [
            'id' => 3,
            'url' => 'https://www.matchesfashion.com/intl/products/Dolce-%26-Gabbana-Angel-square-metal-sunglasses-1279179',
            'name' => 'matchesfashion',
            'scope' => [
                'color',
                'composition'
            ]
        ];

        $arData[] = [
            'id' => 4,
            'url' => 'https://www.matchesfashion.com/intl/products/Aquazzura-Saint-Honore-70-pointed-toe-suede-boots-1280856',
            'name' => 'matchesfashion',
            'scope' => [
                'description',
                'color'
            ]
        ];

        $arData[] = [
            'id' => 5,
            'url' => 'https://shop.nordstrom.com/s/burberry-colorblock-vintage-check-gauze-wool-silk-scarf/5187547',
            'name' => 'nordstrom',
            'scope' => [
                'category',
                'color',
                'composition'
            ]
        ];

        $arData[] = [
            'id' => 6,
            'url' => 'https://shop.nordstrom.com/s/bardot-arabella-body-con-dress/4754475',
            'name' => 'nordstrom',
            'scope' => [
                '*'
            ]
        ];

        $arData[] = [
            'id' => 7,
            'url' => 'https://shop.nordstrom.com/s/gucci-gg-marmont-2-0-matelasse-leather-mini-backpack/4972084',
            'name' => 'nordstrom',
            'scope' => [
                'description',
                'category',
                'composition'
            ]
        ];

        $arData[] = [
            'url' => false
        ];

        // Set json to return
        return response()->json($arData[ rand(0, count($arData) - 1) ]);
    }


    /**
     * @SWG\Get(
     *   path="/scrape/process",
     *   tags={"Scrape"} ,
     *   summary="Scrape process",
     *   operationId="scrape-process",
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error"),
     *      @SWG\Parameter(
     *          name="email",
     *          in="path",
     *          required=true, 
     *          type="string" 
     *      ),
            @SWG\Parameter(
     *          name="website",
     *          in="path",
     *          required=true, 
     *          type="string" 
     *      ),
     * )
     *
     */
    public function processDataFromScraper()
    {
        return response()->json(['result' => 'ok']);
    }
}
