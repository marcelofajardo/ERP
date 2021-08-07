<?php
 
 namespace App\Library\Watson\Action;

 use App\Brand;
 use App\Category;
 use App\Product;

 /**
  * 
  */
 class SendProductImages
 {

 	CONST SENDING_LIMIT = 30; 
 	CONST FEMALE_CATEGORY = 2;
 	CONST MALE_CATEGORY = 3;

 	public $brand;
 	public $cateogry;
 	public $products;
 	public $mediaIds;
 	public $params;
 	
 	public function __construct($attributes, $params)
 	{
 		# code...
 		$this->params = $params;
 		$this->excludeAttributes($attributes);
 	}

 	/**
 	 * check is option matched
 	 * @return boolean
 	 */

 	public function isOptionMatched()
 	{
 		return (!is_null($this->brand) && !is_null($this->category)) ? true : false;
 	}

 	/**
 	 * if we have already brand and category then now send images 
 	 * @return []
 	 */

 	public function getResults()
 	{
 		$images = [];
 		$ids 	= [];
 		// Removed more options from here as we don't need product for now
 		$this->products = $products = \App\Product::attachProductChat([$this->brand->id],[$this->category->id],[]);

 		if($products) {
 			foreach($products as $product) {
 				$ids[] = $product->id;
 				if($product->hasMedia(config("constants.attach_image_tag"))){
 					$media = $product->getMedia(config("constants.attach_image_tag"))->first();
 					if ($media) {
 						$this->mediaIds[] = $images[] = $media->id;
 					}	
 				}
 			}
 		}

 		return [
 			"media_ids" => $images , 
 			"params" => [
	 			"brands" => [$this->brand->id],
	 			"category" => [$this->category->id],
	 			"products" => $ids
	 		]
 		];

 	}

 	
 	/**
 	 *  Check brand and category name match 
 	 *  
 	 */

 	private function excludeAttributes($attributes)
 	{
 		if(isset($attributes->value)) {

	 		$brandCatStr = explode(" " ,$attributes->value);
	 		$brand = isset($brandCatStr[0]) ? $brandCatStr[0] : null;

	 		if(!empty($brand)) {
	 			$matchedBrands = Brand::where("name","like","{$brand}")->get();
	 			if($matchedBrands->isEmpty()) {
	 				$matchedBrands = Brand::where("name","like","{$brand}%")->get();
	 			}
	 			if(!empty($matchedBrands)) {
	 				foreach($matchedBrands as $mBrand) {
	 					$categoryMatch = str_replace(strtolower($mBrand->name), "", strtolower($attributes->value));
	 					$category = Category::where("title",trim($categoryMatch));
	 					if(isset($this->params["gender"])) {
	 						switch ($this->params["gender"]) {
	 							case self::FEMALE_CATEGORY:
		 								$category = $category->where(function($q){
		 									$q->whereNotIn('parent_id', function($q){
											    $q->select('id')->from('categories')->where("parent_id",self::MALE_CATEGORY);
											});	
		 								})->orWhere('parent_id',1);
	 								break;
	 							case self::MALE_CATEGORY:
	 									$category = $category->where(function($q){
		 									$q->whereNotIn('parent_id', function($q){
											    $q->select('id')->from('categories')->where("parent_id",self::FEMALE_CATEGORY);
											});	
		 								})->orWhere('parent_id',1);
	 								break;
	 						}
	 					}

	 					$category = $category->first();

	 					// if category and brand both matched then assign to current object
	 					if($category) {
	 						$this->brand 	= $mBrand;
	 						$this->category = $category;
	 					}
	 				}
	 			}
	 		}
 		}
 		return false;
 	}
 } 