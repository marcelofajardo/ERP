<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
class ColorNamesReference extends Model
{
    /**
     * @var string
        * @SWG\Property(property="color_code",type="string")
      * @SWG\Property(property="color_name",type="string")
     */
    protected $fillable = ['color_code','color_name'];

    // Get product color from text
    public static function getProductColorFromObject($productObject)
    {
        // Get distinct color names used on ERP
        $mainColorNames = ColorNamesReference::distinct('color_name')->get(['color_name','erp_name']);
        
        // Check if color exists
        if (isset($productObject->properties['color']) || isset($productObject->properties->color)) {
            $colerRef = $productObject->properties['color'];
            foreach ($mainColorNames as $colorName) {
                if(!empty($colerRef) && is_string($colerRef) && !empty($colorName->color_name)) {
                    if (stristr($colerRef, $colorName->color_name)) {
                        return $colorName->erp_name;
                    }
                }
            }
            // in this case color refenrece we don't found so we need to add that one
            if(!empty($colerRef) && is_string($colerRef)) {
                $creferenceModel = ColorNamesReference::where('color_name',$colerRef)->first();
                if(!$creferenceModel) {
                    ColorNamesReference::create([
                        'color_code' => '',
                        'color_name' => $colerRef
                    ]);
                }
            }
            
        }

        // Check if color can be found in url
        if (!empty($productObject->url)) {
            foreach ($mainColorNames as $colorName) {
                if(!empty($productObject->url) && !empty($colorName->color_name)) {
                    if (stristr(self::_replaceKnownProblems($productObject->url), $colorName->color_name)) {
                        return $colorName->erp_name;
                    }
                }
            }
        }

        // Check if color can be found in title
        if (!empty($productObject->title)) {
            foreach ($mainColorNames as $colorName) {
                if(!empty($productObject->title) && !empty($colorName->color_name)) {
                    if (stristr(self::_replaceKnownProblems($productObject->title), $colorName->color_name)) {
                        return $colorName->erp_name;
                    }
                }
            }
        }

        // Check if color can be found in description
        if (!empty($productObject->description)) {
            foreach ($mainColorNames as $colorName) {
                if(!empty($productObject->description) && !empty($colorName->color_name)) {
                    if (stristr(self::_replaceKnownProblems($productObject->description), $colorName->color_name)) {
                        return $colorName->erp_name;
                    }
                }
            }
        }

        // Return an empty string by default
        return '';
    }

    public static function getColorRequest($color = "" , $url = "" , $title = "", $description = "")
    {
        // Get distinct color names used on ERP
        $mainColorNames = ColorNamesReference::distinct('color_name')->get(['color_name','erp_name']);
        
        // Check if color exists
        if (!empty($color)) {
            foreach ($mainColorNames as $colorName) {
                if(!empty($color) && !empty($colorName->color_name)) {
                    if (stristr($color, $colorName->color_name)) {
                        return $colorName->erp_name;
                    }
                }
            }
            // in this case color refenrece we don't found so we need to add that one
            ColorNamesReference::create([
                'color_code' => '',
                'color_name' => $color
            ]);

            if(!empty($color)) {
                $creferenceModel = ColorNamesReference::where('color_name',$color)->first();
                if(!$creferenceModel) {
                    ColorNamesReference::create([
                        'color_code' => '',
                        'color_name' => $color
                    ]);
                }
            }
            
        }

        // Check if color can be found in url
        if (!empty($url)) {
            foreach ($mainColorNames as $colorName) {
                if(!empty($url) && !empty($colorName->color_name)) {
                    if (stristr(self::_replaceKnownProblems($url), $colorName->color_name)) {
                        return $colorName->erp_name;
                    }
                }
            }
        }

        // Check if color can be found in title
        if (!empty($title)) {
            foreach ($mainColorNames as $colorName) {
                if(!empty($title) && !empty($colorName->color_name)) {
                    if (stristr(self::_replaceKnownProblems($title), $colorName->color_name)) {
                        return $colorName->erp_name;
                    }
                }
            }
        }

        // Check if color can be found in description
        if (!empty($description)) {
            foreach ($mainColorNames as $colorName) {
                if(!empty($description) && !empty($colorName->color_name)) {
                    if (stristr(self::_replaceKnownProblems($description), $colorName->color_name)) {
                        return $colorName->erp_name;
                    }
                }
            }
        }

        // Return an empty string by default
        return '';
    }

    private static function _replaceKnownProblems($text)
    {
        // Replace known problems
        $text = str_ireplace('off-white', '', $text);
        $text = str_ireplace('off+white', '', $text);
        $text = str_ireplace('off%20white', '', $text);
        $text = str_ireplace('off white', '', $text);
        $text = str_ireplace('offwhite', '', $text);

        // Return text
        return $text;
    }
}
