<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;
use App\Category;
use seo2websites\GoogleVision\LogGoogleVision;

class LogScraperVsAi extends Model
{
    /**
     * @var string
     * @SWG\Property(property="log_scraper_vs_ai",type="string")
     */
    protected $table = 'log_scraper_vs_ai';

    public static function getAiKeywordsFromResults( $results )
    {
        // Set empty array for images
        $arrImages = [];

        // Loop over results
        foreach ( $results as $result ) {
            // Get all images
            $jsonImages = json_decode( $result->media_input );

            // Loop over jsonImages and add them to array
            foreach ( $jsonImages as $key => $image ) {
                $arrImages[] = $image;
            }
        }

        // Create array with unique values
        $arrImages = array_unique( $arrImages );

        // Set empty array to hold keywords
        $arrKeywords = [];

        // Query results
        $logResults = LogGoogleVision::whereIn( 'image_url', $arrImages )->get();

        // Loop over results
        foreach ( $logResults as $logResult ) {
            // Explode response by newline
            $response = explode( "\n", $logResult->response );

            // Loop over response
            foreach ( $response as $row ) {
                // Store best guess label
                if ( substr( $row, 0, 17 ) == 'Best guess label:' ) {
                    $arrKeywords = self::_addKeyword( $arrKeywords, substr( $row, 18 ) );
                }

                // Store Object
                if ( substr( $row, 0, 7 ) == 'Object:' ) {
                    $arrKeywords = self::_addKeyword( $arrKeywords, substr( $row, 8, strpos( $row, ',' ) - 8 ) );
                }

                // Store Entity
                if ( substr( $row, 0, 7 ) == 'Entity:' ) {
                    $arrKeywords = self::_addKeyword( $arrKeywords, substr( $row, 8, strpos( $row, ',' ) - 8 ) );
                }
            }
        }

        // Reverse sort arrey by value
        arsort( $arrKeywords );

        // Filter for categories
        $arrCategories = self::_filterCategories( $arrKeywords );

        // Return array with keywords
        return $arrCategories;
    }

    private static function _addKeyword( $arrKeywords, $keyword )
    {
        // Check if key (keyword) exists
        if ( key_exists( $keyword, $arrKeywords ) ) {
            // Add 1 to the keyword
            $arrKeywords[ $keyword ]++;
        } else {
            // Add the keyword with value 1
            $arrKeywords[ $keyword ] = 1;
        }

        // Return array
        return $arrKeywords;
    }

    public static function _filterCategories( $arrKeywords )
    {
        // Set empty array for categories
        $arrCategories = [];

        // Loop over keywords
        foreach ( $arrKeywords as $keyword ) {
            // Skip empty keywords
            if ( !empty( $keyword ) ) {
                // Check database for result
                $dbResult = Category::where( 'title', $keyword )->orWhere( 'references', 'like', '%' . $keyword . '%' )->first();

                // Result? Add the keyword
                if ( $dbResult !== NULL ) {
                    $arrCategories = $keyword;
                }
            }
        }

        // Return categories
        return $arrCategories;
    }

    public static function getCategoryIdByKeyword( $keyword, $gender, $genderScraper )
    {
        // Set gender
        if ( empty($gender) ) {
            $gender = $genderScraper;
        }

        // Check database for result
        $dbResult = Category::where( 'title', $keyword )->get();

        // No result? Try where like
        if ( $dbResult->count() == 0 ) {
            $dbResult = Category::where( 'references', 'like', '%' . $keyword . '%' )->get();
        }

        // Still no result
        if ( $dbResult === NULL ) {
            return 0;
        }

        // Just one result
        if ( $dbResult->count() == 1 ) {
            return $dbResult->first()->id;
        }

        // Checking the result by gender only works if the gender is set
        if ( empty( $gender ) ) {
            return 0;
        }

        // Check results
        foreach ( $dbResult as $result ) {
            // Get parent Id
            $parentId = $result->parent_id;

            // Return 0 for a top category
            if ( $parentId == 0 ) {
                return $result->id;
            }

            // Return correct result by gender
            if ( $parentId == 2 && strtolower( $gender ) == 'women' ) {
                return $result->id;
            }

            // Return correct result by gender
            if ( $parentId == 3 && strtolower( $gender ) == 'men' ) {
                return $result->id;
            }

            // Other
            if ( $parentId > 0 ) {
                // Store category ID
                $categoryId = $result->id;

                // Get parent
                $dbParentResult = Category::find( $result->parent_id );

                // No result
                if ( $dbParentResult->count() == 0 ) {
                    return 0;
                }

                // Return correct result for women
                if ( $dbParentResult->parent_id == 2 && strtolower( $gender ) == 'women' ) {
                    return $categoryId;
                }

                // Return correct result for men
                if ( $dbParentResult->parent_id == 3 && strtolower( $gender ) == 'men' ) {
                    return $categoryId;
                }
            }
        }
    }

    public static function getGenderByCategoryId($categoryId) {
        // Set parent ID to high value
        $parentId = $categoryId;

        // Loop until parent ID is 0
        while ( $parentId > 0 ) {
            // Get category
            $category = Category::find($parentId);

            // Break if null
            if ( $category->count() == 0 ) {
                break;
            }

            // Set new parent ID
            $parentId = $category->parent_id;
            $categoryId = $category->id;
        }

        // Return women
        if ( $categoryId == 2 ) {
            return 'women';
        }

        // Return men
        if ( $categoryId == 3 ) {
            return 'men';
        }

        // Still here?
        return 0;
    }
}
