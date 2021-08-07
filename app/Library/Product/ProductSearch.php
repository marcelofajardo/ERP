<?php

namespace App\Library\Product;

use App\Category;
use App\Product;

class ProductSearch
{

    public $params;

    public function __construct($params)
    {
        $this->params = $params;
        $this->cleanParams();
    }

    /**
     * clean all params
     * @return []
     */

    public function cleanParams()
    {
        return array_filter($this->params);
    }

    /**
     * find matched categories
     * @return []
     */

    public function matchedCategories($categoies)
    {
        $category_children = [];

        foreach ($categoies as $category) {
            if($category == 1) {
               continue;
            }
            $is_parent = Category::isParent($category);
            if ($is_parent) {
                $childs = Category::find($category)->childs()->get();
                foreach ($childs as $child) {
                    $is_parent = Category::isParent($child->id);
                    if ($is_parent) {
                        $children = Category::find($child->id)->childs()->get();
                        foreach ($children as $chili) {
                            array_push($category_children, $chili->id);
                        }
                    } else {
                        array_push($category_children, $child->id);
                    }
                }
            } else {
                array_push($category_children, $category);
            }
        }

        return $category_children;

    }

    /**
     * Get result
     * @return []
     *
     */

    public function getQuery()
    {

        $params = $this->params;

        // starting with new query
        $products = (new Product())->newQuery()->whereNull("deleted_at")->whereNull('dnf')->latest();
        //loop through params and add result
        if (!empty($params)) {
            foreach ($params as $key => $value) {
                // 
                if(empty($value)) {
                    continue;
                }

                switch ($key) {
                    case 'brand':
                        $products = $products->whereIn('brand', $value);
                        break;

                    case 'color':
                        $products = $products->whereIn('color', $value);
                        break;

                    case 'category':    
                        $matchedCategories = $this->matchedCategories($value);
                        if(!empty($matchedCategories)) {
                            $products          = $products->whereIn('category', $matchedCategories);
                        }
                        break;

                    case 'price_min':
                        $products = $products->where('price_inr_special', '>=', $value);
                        break;

                    case 'price_max':
                        $products = $products->where('price_inr_special', '<=', $value);
                        break;

                    case 'discounted_percentage_min':
                        $products = $products->where('discounted_percentage', '>=', $value);
                        break;

                    case 'discounted_percentage_max':
                        $products = $products->where('discounted_percentage', '<=', $value);
                        break;    

                    case 'supplier':
                        $products = $products->whereRaw("products.id in (SELECT product_id FROM product_suppliers WHERE supplier_id IN (" . implode(',', $value) . "))");
                        break;

                    case 'scrapper':
                        $products = $products->whereRaw("products.id in (SELECT product_id FROM product_suppliers WHERE supplier_id IN (select supplier_id from scrapers where id in (" . implode(',', $value) . ")))");
                        break;

                    case 'size':
                        if (trim($value) != '') {
                            $products = $products->whereNotNull('size')->where(function ($query) use ($value) {
                                $query->where('size', $value)->orWhere('size', 'LIKE', "%$value,")->orWhere('size', 'LIKE', "%,$value,%");
                            });
                        }
                        break;

                    case 'location':
                        $products = $products->whereIn('location', $value);
                        break;

                    case 'type':

                        if (count($value) > 1) {

                            $products = $products->where(function ($query) use ($value) {
                                $query->where('is_scraped', 1)->orWhere('status', 2);
                            });

                        } else {

                            if ($value[0] == 'scraped') {
                                $products = $products->where('is_scraped', 1);
                            } elseif ($value[0] == 'imported') {
                                $products = $products->where('status', 2);
                            } else {
                                $products = $products->where('isUploaded', 1);
                            }

                        }

                        break;

                    case 'date':
                        if ($value != '') {
                            if (isset($products)) {
                                if (isset($params["type"][0]) && $params["type"][0] == 'uploaded') {
                                    $products = $products->where('is_uploaded_date', 'LIKE', "%$value%");
                                } else {
                                    $products = $products->where('created_at', 'LIKE', "%$value%");
                                }
                            }
                        }
                        break;

                    case 'term':
                        if (trim($value) != '') {
                            $products = $products->where(function ($query) use ($value) {
                                $query->where('supplier', 'LIKE', "%$value%")->orWhere('id', 'LIKE', "%$value%");
                                $query->orWhere('sku', 'LIKE', "%$value%")->orWhere('id', 'LIKE', "%$value%");
                                if ($value == -1) {
                                    $query = $query->orWhere('isApproved', -1);
                                }
                                $brand_id = \App\Brand::where('name', 'LIKE', "%$value%")->value('id');
                                if ($brand_id) {
                                    $query = $query->orWhere('brand', 'LIKE', "%$brand_id%");
                                }
                                $category_id = $category = Category::where('title', 'LIKE', "%$value%")->value('id');
                                if ($category_id) {
                                    $query = $query->orWhere('category', $category_id);
                                }
                            });
                        }
                        break;
                    case 'ids':
                        $products = $products->whereIn('id', $value);
                        break;
                    case 'quick_product':
                        if ($value === 'true') {
                            $products = $products->where('quick_product', 1);
                        }
                        // assing product to varaible so can use as per condition for join table media
                        if ($value !== 'true') {
                            $products = $products->whereRaw("(stock > 0 OR (supplier LIKE '%In-Stock%'))");
                        }
                        break;
                    case 'without_category':
                        $products = $products->where('category',"<=", 0);
                        break;
                    case 'without_color':
                            $products = $products->where('color',"=", null);
                        break;
                    case 'without_composition':
                        $products = $products->where('composition',"=", null);
                        break;    
                    case 'source_of_search':
                        if ($value == "attach_media") {
                            $products = $products->join("mediables", function ($query) {
                                $query->on("mediables.mediable_id", "products.id")->where("mediable_type", "App\Product");
                            })->groupBy('products.id');
                        }
                        break;
                    case 'quick_sell_groups':
                        if (!empty($value)) {
                            $products = $products->whereRaw("(id in (select product_id from product_quicksell_groups where quicksell_group_id in (" . implode(",", $value) . ") ))");
                        }
                        break;
                    case 'final_approval':
                        if (!empty($value) && strtolower($value) == "on") {
                            $products = $products->where("status_id",\App\Helpers\StatusHelper::$finalApproval);
                        }
                        break;
	                case 'is_on_sale';
	                    $products = $products->where('is_on_sale', 1);
	                    break;
                    default:
                        # code...
                        break;
                }
            }
        }

        // check with product quick product
        if(!isset($params["quick_product"]) || $params["quick_product"] != "true") {
            $products = $products->whereRaw("(stock > 0 OR (supplier LIKE '%In-Stock%'))");
        }


        return $products;

    }

}
