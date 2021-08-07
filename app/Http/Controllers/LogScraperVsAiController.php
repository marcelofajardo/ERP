<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\LogScraperVsAi;
use App\Product;

class logScraperVsAiController extends Controller
{
    public function index( Request $request )
    {
        // Set empty alert
        $alert = '';

        // Load product
        $product = Product::find( $request->id );

        // Check for submit
        if ( !empty( $request->id ) && !empty( $request->category ) && !empty( $request->color ) ) {
            // Product not found
            if ( $product === NULL ) {
                if ( !empty( $request->opener ) ) {
                    return redirect( urldecode( $request->opener ) )->with( 'alert', 'Product not found' );
                } else {
                    return redirect()->back()->with( 'alert', 'Product not found' );
                }
            }

            // Update product
            $product->category = $request->category == 'dropdown' ? (int) $request->category_dropdown : $request->category;
            $product->color = $request->color == 'dropdown' ? ucwords( strtolower( $request->color_dropdown ) ) : ucwords( strtolower( $request->color ) );
            $product->save();

            // Redirect back to opener page
            if ( !empty( $request->opener ) ) {
                return redirect( urldecode( $request->opener ) );
            } else {
                return redirect()->back();
            }
        } elseif ( !empty( $request->id ) && !empty( $request->category ) && empty( $request->color ) ) {
            if ( !empty( $request->opener ) ) {
                return redirect( urldecode( $request->opener ) )->with( 'alert', 'Color not set' );
            } else {
                return redirect()->back()->with( 'alert', 'Color not set' );
            }
        } elseif ( !empty( $request->id ) && empty( $request->category ) && !empty( $request->color ) ) {
            if ( !empty( $request->opener ) ) {
                return redirect( urldecode( $request->opener ) )->with( 'alert', 'Category not set' );
            } else {
                return redirect()->back()->with( 'alert', 'Category not set' );
            }
        }

        // Get results
        $results = LogScraperVsAi::where( 'product_id', $request->id )->orderBy( 'created_at', 'desc' )->get();

        // Get keywords by result
        $keywords = LogScraperVsAi::getAiKeywordsFromResults( $results );

        // Get gender by scraper category
        $genderScraper = \App\LogScraperVsAi::getGenderByCategoryId( (int) $product->category );

        // Return view
        return view( 'log-scraper-vs-ai.index', compact( 'results', 'keywords', 'genderScraper' ) );
    }
}
