<?php

namespace App\Http\Controllers;

use App\Category;
use App\Image;
use App\Product;
use App\ProductReference;
use App\ProductSizes;
use App\ScrapedProducts;
use App\Setting;
use App\Supplier;
use App\Sizes;
use App\Stage;
use App\Brand;
use App\ReadOnly\LocationList;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Plank\Mediable\Media;
use Plank\Mediable\MediaUploaderFacade as MediaUploader;
use Carbon\Carbon;

class ProductAttributeController extends Controller
{
    //
	public function __construct() {

//		$this->middleware('permission:attribute-list',['only' => ['sList','index']]);
//		$this->middleware('permission:attribute-create', ['only' => ['create','store']]);
//		$this->middleware('permission:attribute-edit', ['only' => ['edit','update']]);
//
//
//		$this->middleware('permission:attribute-delete', ['only' => ['destroy']]);
	}

	public function index(Stage $stage){

		$products = Product::latest()
		                   ->where('stage','>=',$stage->get('Searcher'))
						   		 		 ->whereNull('dnf')
											 ->select(['id', 'sku', 'size', 'price_inr_special', 'brand', 'supplier', 'isApproved', 'stage', 'status', 'is_scraped', 'created_at'])
		                   ->paginate(Setting::get('pagination'));
		$roletype = 'Attribute';

		$category_selection = Category::attr(['name' => 'category[]','class' => 'form-control select-multiple'])
		                                        ->selected(1)
		                                        ->renderAsDropdown();

		return view('partials.grid',compact('products','roletype', 'category_selection'))
			->with('i', (request()->input('page', 1) - 1) * 10);
	}

	public function sList(){

		$productattribute = Product::latest()->withMedia(config('constants.media_tags'))->paginate(Setting::get('pagination'));
		return view('productattribute.list',compact('productattribute'))
			->with('i', (request()->input('page', 1) - 1) * 10);

	}

	public function edit(Sizes $sizes,Product $productattribute)
	{

		// if( $productattribute->isApproved == 1)
		// 	return redirect(route('products.show',$productattribute->id));

		$data = [];

		$data['dnf'] = $productattribute->dnf;
		$data['id'] = $productattribute->id;
		$data['name'] = $productattribute->name;
		$data['short_description'] =$productattribute->short_description;

		$data['measurement_size_type'] = $productattribute->measurement_size_type;
		$data['lmeasurement'] = $productattribute->lmeasurement;
		$data['hmeasurement'] = $productattribute->hmeasurement;
		$data['dmeasurement'] = $productattribute->dmeasurement;

		$data['size'] = $productattribute->size ? explode(',', $productattribute->size) : [];

		$data['size_value'] = $productattribute->size_value;
		$data['sizes_array'] = $sizes->all();

		$data['composition'] = $productattribute->composition;
		$data['sku'] = $productattribute->sku;
		$data['made_in'] = $productattribute->made_in;
		$data['brand'] = $productattribute->brand;
		$data['color'] = $productattribute->color;
		$data['price'] = $productattribute->price;
		$data['price_inr'] = $productattribute->price_inr;
		$data['price_inr_special'] = $productattribute->price_inr_special;
		$data['price_special_offer'] = $productattribute->price_special_offer;
		$data['euro_to_inr'] = $productattribute->euro_to_inr;
		$data['suppliers'] = Supplier::all();
		$data['product_suppliers'] = $productattribute->suppliers;

		$data['isApproved'] = $productattribute->isApproved;
		$data['rejected_note'] = $productattribute->rejected_note;

		$data['images']  = $productattribute->getMedia(config('constants.media_tags'));

		$data['category'] = Category::attr(['name' => 'category','class' => 'form-control', 'id' => 'product-category'])
		                                        ->selected($productattribute->category)
		                                        ->renderAsDropdown();

    $data['old_category'] = $productattribute->category;
		$data['category_tree'] = [];
		$data['categories_array'] = [];

		foreach (Category::all() as $category) {
			if ($category->parent_id != 0) {
				$parent = $category->parent;
				if ($parent->parent_id != 0) {
					if(isset($data['category_tree'][$parent->parent_id][$parent->id])) {
						$data['category_tree'][$parent->parent_id][$parent->id][$category->id];
					}
				} else {
					$data['category_tree'][$parent->id][$category->id] = $category->id;
				}
			}

			$data['categories_array'][$category->id] = $category->parent_id;
		}

		$data['product_link'] = $productattribute->product_link;
		$data['supplier'] = $productattribute->supplier;
		$data['supplier_link'] = $productattribute->supplier_link;
		$data['description_link'] = $productattribute->description_link;
		$data['location'] = $productattribute->location;
		$data['reference'] = ScrapedProducts::where('sku', $productattribute->sku)->first() ? ScrapedProducts::where('sku', $productattribute->sku)->first()->properties : [];
		$data['scraped'] = $productattribute->scraped_products;
		$data['locations'] = (new LocationList)->all();
		$data['prod_size_qty'] = $productattribute->sizes;

		return view('productattribute.edit',$data);
	}

	public function delSizeQty($id) {
	    $q = ProductSizes::find($id);
	    if ($q) {
	        $q->delete();
        }

	    return redirect()->back()->with('success', 'Deleted successfullyy!');
    }

	public function update(Request $request,Guard $auth, Product $productattribute,Stage $stage)
	{
		$old_sizes = $productattribute->size;
		$old_color = $productattribute->color;
		$old_images = $productattribute->getMedia(config('constants.media_tags'));

		$productattribute->dnf = $request->input('dnf');
		$productattribute->name = $request->input('name');
		$productattribute->short_description = $request->input('short_description');

		$productattribute->measurement_size_type = $request->input('measurement_size_type');
		$productattribute->lmeasurement = $request->input('lmeasurement');
		$productattribute->hmeasurement = $request->input('hmeasurement');
		$productattribute->dmeasurement = $request->input('dmeasurement');

		$productattribute->size = $request->size ? implode(',', $request->size) : ($request->other_size ?? "");

		$productattribute->size_value = $request->input('size_value');

		$productattribute->composition = $request->input('composition');
		$productattribute->sku = $request->input('sku');
		$productattribute->made_in = $request->input('made_in');
		$productattribute->brand = $request->input('brand');
		$productattribute->color = $request->input('color');
		$productattribute->price = $request->input('price');
		$productattribute->price_special_offer = $request->input('price_special_offer');

		if(!empty($productattribute->brand)) {
			$productattribute->price_inr     = $this->euroToInr( $productattribute->price, $productattribute->brand );
			$productattribute->price_inr_special = $this->calculateSpecialDiscount( $productattribute->price_inr, $productattribute->brand );
		}

		if ($productattribute->stage < $stage->get('Attribute')) {
			$productattribute->stage = $stage->get('Attribute');
		} else if ($productattribute->stage == $stage->get('Attribute')) {
			$productattribute->stage = 4;
		}

		$productattribute->category = $request->input('category');
		$productattribute->product_link = $request->input('product_link');
		// $productattribute->supplier = $request->input('supplier');
		$productattribute->supplier_link = $request->input('supplier_link');
		$productattribute->description_link = $request->input('description_link');
		$productattribute->location = $request->input('location');
		$productattribute->last_attributer = Auth::id();

		$validations  = [
			'sku'   => 'required_without:dnf|unique:products,sku,'.$productattribute->id,
			// 'name'   => 'required_without:dnf',
			// 'short_description' => 'required_without:dnf',
			// 'composition' => 'required_without:dnf',
		];

		if($request->input('measurement_size_type') == 'size')
			$validations['size_value'] = 'required_without:dnf';
		elseif ( $request->input('measurement_size_type') == 'measurement' ){
			$validations['lmeasurement'] = 'required_without_all:hmeasurement,dmeasurement,dnf|numeric';
			$validations['hmeasurement'] = 'required_without_all:lmeasurement,dmeasurement,dnf|numeric';
			$validations['dmeasurement'] = 'required_without_all:lmeasurement,hmeasurement,dnf|numeric';
		}

		//:-( ahead
		$check_image = 0;
		$images = $productattribute->getMedia(config('constants.media_tags'));
		$images_no = sizeof($images);

		for( $i = 0 ; $i < 5 ; $i++) {

			if ( $request->input( 'oldImage'.$i ) != 0 ) {
				$validations['image.'.$i] = 'mimes:jpeg,bmp,png,jpg';

				if( empty($request->file('image.'.$i ) ) ){
					$check_image++;
				}
			}
		}

		$messages = [];
		if($check_image == $images_no) {
			$validations['image'] = 'required';
			$messages['image.required'] ='Atleast on image is required. Last image can not be removed';
		}
		//:-( over


		$this->validate( $request, $validations, $messages );

		self::replaceImages($request,$productattribute);

		$productattribute->save();

		if ($request->supplier) {
			$productattribute->suppliers()->detach();
			$productattribute->suppliers()->attach($request->supplier);
		}

		$success_message = 'Attribute updated successfully. ';

		if ($productattribute->isUploaded == 1) {
			$result = $this->magentoProductUpdate($productattribute, $old_sizes, $old_color, $old_images);

			if (!$result[1]) {
				$success_message .= "Not everything was updated correctly. Check product on Magento";
			}
		}

		NotificaitonContoller::store('has added attribute', ['Supervisors'], $productattribute->id);
		ActivityConroller::create($productattribute->id,'attribute','create');

		foreach ($request->get('qty', []) as $k=>$qty) {
		    if (!$qty) {
		        continue;
            }
		    $q = new ProductSizes();
		    $q->product_id = $productattribute->id;
		    $q->quantity = $qty;
		    $q->supplier_id = $productattribute->supplier_id;
		    $q->size = $request->get('sizex')[$k];
		    $q->save();
        }

		return redirect()->back()->with( 'success', $success_message);
	}

	public function calculateSpecialDiscount($price,$brand) {

//		$dis_per = Setting::get('special_price_discount');
		$dis_per = BrandController::getDeductionPercentage($brand);

		$dis_price = $price - ($price * $dis_per)/100;

		return round($dis_price,-3);
	}

	public function euroToInr($price,$brand){

		$euro_to_inr =  BrandController::getEuroToInr($brand);

		if(!empty($euro_to_inr))
			$inr = $euro_to_inr*$price;
		else
			$inr = Setting::get('euro_to_inr')*$price;

		return round($inr,-3);
	}


	public function replaceImages($request,$productattribute){
		$delete_array = [];
		for( $i = 0 ; $i < 5 ; $i++) {

			if ( $request->input( 'oldImage' . $i ) != 0 ) {
				$delete_array[] = $request->input( 'oldImage' . $i );
			}

			if( !empty($request->file('image.'.$i ) ) ){
				$media = MediaUploader::fromSource($request->file('image.'.$i ))
				->toDirectory('product/'.floor($productattribute->id / config('constants.image_per_folder')))
				->upload();
				$productattribute->attachMedia($media,config('constants.media_tags'));
			}
		}

		$results = Media::whereIn('id' , $delete_array )->get();
		$results->each(function($media) {
			Image::trashImage($media->basename);
			$media->delete();
		});

	}

	public static function rejectedProductCountByUser(){

		return Product::where('last_attributer', Auth::id() )
		        ->where('isApproved',-1)
				->count();
	}

	public function magentoProductUpdate($product, $old_sizes = null, $old_color = null, $old_images = null) {
		$options = array(
			'trace' => true,
			'connection_timeout' => 120,
			'wsdl_cache' => WSDL_CACHE_NONE,
		);

		$proxy = new \SoapClient(config('magentoapi.url'), $options);
		$sessionId = $proxy->login(config('magentoapi.user'), config('magentoapi.password'));

		$sku = $product->sku . $product->color;
		$old_sku = $product->sku . $old_color;
		$reference_final_sku = $sku;
		$categories = CategoryController::getCategoryTreeMagentoIds($product->category);
		$brand= $product->brands()->get();
		$errors = 0;
		$updated_product = 0;

		array_push($categories,$brand[0]->magento_id);

		if(!empty($product->size)) {
			$associated_skus = [];
			$sizes_array = explode(',', $product->size);

			if ($product->references) {
				$reference_array = [];
				$reference_color = '';
				$reference_sku = '';

				foreach ($product->references as $reference) {
					if ($reference->size != '') {
						$reference_array[] = $reference->size;
					}

					$reference_color = $reference->color;
					$reference_sku = $reference->sku;
				}

				// $reference_final_sku = str_replace(' ', '', $reference_sku . $reference_color);
				$reference_final_sku = $reference_sku . $reference_color;
				$product_sizes = explode(',', $product->size);

				foreach ($product_sizes as $size) {
					if (in_array($size, $reference_array)) {
						// UPDATES SIMPLE PRODUCT
						$productData = array(
							'categories'            => $categories,
							'name'                  => $product->name,
							'description'           => '<p></p>',
							'short_description'     => $product->short_description,
							'website_ids'           => array(1),
							// Id or code of website
							'status'                => $product->isFinal ?? 2,
							// 1 = Enabled, 2 = Disabled
							'visibility'            => 1,
							// 1 = Not visible, 2 = Catalog, 3 = Search, 4 = Catalog/Search
							'tax_class_id'          => 2,
							// Default VAT
							'weight'                => 0,
							'stock_data' => array(
								'use_config_manage_stock' => 1,
								'manage_stock' => 1,
								'qty'					=> $product->stock,
								'is_in_stock'	=> $product->stock >= 1 ? 1 : 0,
							),
							'price'                 => $product->price_eur_special,
							// Same price than configurable product, no price change
							'special_price'         => $product->price_eur_discounted,
							'additional_attributes' => array(
								'single_data' => array(
									array( 'key' => 'msrp', 'value' => $product->price, ),
									array( 'key' => 'composition', 'value' => $product->composition, ),
									array( 'key' => 'color', 'value' => $product->color, ),
									array( 'key' => 'sizes', 'value' => $size, ),
									array( 'key' => 'country_of_manufacture', 'value' => $product->made_in, ),
									array( 'key' => 'brands', 'value' => BrandController::getBrandName( $product->brand ), ),
								),
							),
						);

						// Update product simple
						// dump('updates simple product');
						// dump($reference_final_sku);

						try {
							$result = $proxy->catalogProductUpdate($sessionId, $reference_final_sku . '-' . $size, $productData);
							$associated_skus[] = $reference_final_sku . '-' . $size;
						} catch (\Exception $e) {
							$errors++;

							try {
								$result = $proxy->catalogProductUpdate($sessionId, $reference_sku . '-' . $size, $productData);
								$associated_skus[] = $reference_final_sku . '-' . $size;
							} catch (\Exception $e) {
								$errors++;
							}
						}
					} else {
						$new_reference = new ProductReference;
						$new_reference->product_id = $product->id;
						$new_reference->sku = $reference_sku;
						$new_reference->color = $reference_color;
						$new_reference->size = $size;
						$new_reference->save();

						// CREATES NEW SIMPLE PRODUCT
						$productData = array(
							'categories'            => $categories,
							'name'                  => $product->name,
							'description'           => '<p></p>',
							'short_description'     => $product->short_description,
							'website_ids'           => array(1),
							// Id or code of website
							'status'                => $product->isFinal ?? 2,
							// 1 = Enabled, 2 = Disabled
							'visibility'            => 1,
							// 1 = Not visible, 2 = Catalog, 3 = Search, 4 = Catalog/Search
							'tax_class_id'          => 2,
							// Default VAT
							'weight'                => 0,
							'stock_data' => array(
								'use_config_manage_stock' => 1,
								'manage_stock' => 1,
								'qty'					=> $product->stock,
								'is_in_stock'	=> $product->stock >= 1 ? 1 : 0,
							),
							'price'                 => $product->price_eur_special,
							// Same price than configurable product, no price change
							'special_price'         => $product->price_eur_discounted,
							'additional_attributes' => array(
								'single_data' => array(
									array( 'key' => 'msrp', 'value' => $product->price, ),
									array( 'key' => 'composition', 'value' => $product->composition, ),
									array( 'key' => 'color', 'value' => $product->color, ),
									array( 'key' => 'sizes', 'value' => $size, ),
									array( 'key' => 'country_of_manufacture', 'value' => $product->made_in, ),
									array( 'key' => 'brands', 'value' => BrandController::getBrandName( $product->brand ), ),
								),
							),
						);

						// Creation of product simple
						// dump('creates simple product');
						try {
							$result            = $proxy->catalogProductCreate($sessionId, 'simple', 14, $reference_sku . $reference_color . '-' . $size, $productData);
							$associated_skus[] = $reference_final_sku . '-' . $size;
						} catch (\Exception $e) {
							$errors++;
						}
					}
				}
			}

			/**
			 * Configurable product
			 */
			$productData = array(
				'categories'              => $categories,
				'name'                    => $product->name,
				'description'             => '<p></p>',
				'short_description'       => $product->short_description,
				'website_ids'             => array(1),
				// Id or code of website
				// 'status'                  => 2,
				// 1 = Enabled, 2 = Disabled
				// 'visibility'              => 4,
				// 1 = Not visible, 2 = Catalog, 3 = Search, 4 = Catalog/Search
				// 'tax_class_id'            => 2,
				// Default VAT
				// 'weight'                  => 0,
				'stock_data' => array(
					'use_config_manage_stock' => 1,
					'manage_stock' => 1,
					'qty'					=> $product->stock,
					'is_in_stock'	=> $product->stock >= 1 ? 1 : 0,
				),
				'price'                   => $product->price_eur_special,
				// Same price than configurable product, no price change
				'special_price'           => $product->price_eur_discounted,
				'associated_skus'         => $associated_skus,
				// Simple products to associate
				// 'configurable_attributes' => array( 155 ),
				'additional_attributes'   => array(
					'single_data' => array(
						array( 'key' => 'msrp', 'value' => $product->price, ),
						array( 'key' => 'composition', 'value' => $product->composition, ),
						array( 'key' => 'color', 'value' => $product->color, ),
						array( 'key' => 'country_of_manufacture', 'value' => $product->made_in, ),
						array( 'key' => 'brands', 'value' => BrandController::getBrandName( $product->brand ), ),
					),
				),
			);

			// Creation of configurable product
			$error_message = '';

			try {
				// dump('updates configurable product');
				$result = $proxy->catalogProductUpdate($sessionId, $reference_final_sku, $productData);
				// dump('product updated');
				// dump($associated_skus);
			} catch (\Exception $e) {
				$error_message = $e->getMessage();
				$errors++;

				try {
					$result = $proxy->catalogProductUpdate($sessionId, $reference_sku, $productData);

					$updated_product = 1;
				} catch (\Exception $e) {
					$error_message = $e->getMessage();
					$errors++;
				}
			}

			if ($error_message == 'Product not exists.') {
				// dump($error_message);
				// dump('configurable product doesnt exist');
				// dump($reference_sku);
				// $productData['status'] = $product->isFinal ?? 2;
				// $productData['visibility'] = 4;
				// $productData['tax_class_id'] = 2;
				// $productData['weight'] = 0;
				//
				// try {
				// 	$result = $proxy->catalogProductDelete($sessionId, $old_sku);
				//
				// 	$deleted_count++;
				// } catch (\Exception $e) {
				// 	$error_message = $e->getMessage();
				// }
				//
				// $result = $proxy->catalogProductCreate($sessionId, 'configurable', 14, $sku, $productData);
			}
		} else {
			$measurement = 'L-'.$product->lmeasurement.',H-'.$product->hmeasurement.',D-'.$product->dmeasurement;

			if ($product->references) {
				$reference_sku = $product->sku;
				$reference_color = $product->color;

				foreach ($product->references as $reference) {
					$reference_sku = $reference->sku;
					$reference_color = $reference->color;
				}

				$reference_final_sku = $reference_sku . $reference_color;
			}

			$productData = array(
				'categories'            => $categories,
				'name'                  => $product->name,
				'description'           => '<p></p>',
				'short_description'     => $product->short_description,
				'website_ids'           => array(1),
				// Id or code of website
				// 'status'                => 2,
				// 1 = Enabled, 2 = Disabled
				// 'visibility'            => 4,
				// 1 = Not visible, 2 = Catalog, 3 = Search, 4 = Catalog/Search
				// 'tax_class_id'          => 2,
				// Default VAT
				// 'weight'                => 0,
				'stock_data' => array(
					'use_config_manage_stock' => 1,
					'manage_stock' => 1,
					'qty'					=> $product->stock,
					'is_in_stock'	=> $product->stock > 1 ? 1 : 0,
				),
				'price'                 => $product->price_eur_special,
				// Same price than configurable product, no price change
				'special_price'         => $product->price_eur_discounted,
				'additional_attributes' => array(
					'single_data' => array(
						array( 'key' => 'msrp', 'value' => $product->price, ),
						array( 'key' => 'composition', 'value' => $product->composition, ),
						array( 'key' => 'color', 'value' => $product->color, ),
						array( 'key' => 'measurement', 'value' => $measurement, ),
						array( 'key' => 'country_of_manufacture', 'value' => $product->made_in, ),
						array( 'key' => 'brands', 'value' => BrandController::getBrandName( $product->brand ), ),
					),
				),
			);

			// Creation of product simple
			$error_message = '';
			try {
				// dump('updates configurable product without sizes');
				$result = $proxy->catalogProductUpdate($sessionId, $reference_final_sku, $productData);
			} catch (\Exception $e) {
				$error_message = $e->getMessage();
				$errors++;

				try {
					$result = $proxy->catalogProductUpdate($sessionId, $reference_sku, $productData);

					$updated_product = 1;
				} catch (\Exception $e) {
					$errors++;
				}
			}

			if ($error_message == 'Product not exists.') {
				// dump('PRODUCT NOT EXISTS / CREATING NEW');
				// dump('configurable product without sizes doesnot exists');
				$productData['status'] = $product->isFinal ?? 2;
				$productData['visibility'] = 4;
				$productData['tax_class_id'] = 2;
				$productData['weight'] = 0;

				$result = $proxy->catalogProductCreate($sessionId, 'simple', 4, $reference_final_sku, $productData);
			}
		}

		$i = 0;
		$images = $product->getMedia(config('constants.media_tags'));

		if ($updated_product == 1) {
			$old_images = $proxy->catalogProductAttributeMediaList($sessionId, $reference_final_sku);

			foreach ($old_images as $old_image) {
				try {
					$result = $proxy->catalogProductAttributeMediaRemove(
						$sessionId,
						$reference_final_sku,
						$old_image->file
					);
				} catch (\Exception $e) {
					$errors++;

					try {
						$result = $proxy->catalogProductAttributeMediaRemove(
							$sessionId,
							$reference_sku,
							$old_image->file
						);
					} catch (\Exception $e) {
						$errors++;
					}
				}
			}
		}

		foreach ($images as $image){
			$file = array(
				'name' => pathinfo($image->getBasenameAttribute(), PATHINFO_FILENAME),
				'content' => base64_encode(file_get_contents($image->getAbsolutePath())),
				'mime' => mime_content_type($image->getAbsolutePath())
			);

			$types = $i ? array('') : array('size_guide','image','small_image','thumbnail');
			$types = $i == 1 ? array('hover_image') : $types;

			try {
				$result = $proxy->catalogProductAttributeMediaCreate(
					$sessionId,
					$reference_final_sku,
					array('file' => $file, 'label' => pathinfo($image->getBasenameAttribute(), PATHINFO_FILENAME), 'position' => ++$i , 'types' => $types, 'exclude' => 0)
				);
			} catch (\Exception $e) {
				$errors++;

				try {
					$result = $proxy->catalogProductAttributeMediaCreate(
						$sessionId,
						$reference_sku,
						array('file' => $file, 'label' => pathinfo($image->getBasenameAttribute(), PATHINFO_FILENAME), 'position' => ++$i , 'types' => $types, 'exclude' => 0)
					);
				} catch (\Exception $e) {
					$errors++;
				}
			}
		}

		if ($errors > 0) {
			return [isset($result) ? $result : '', FALSE];
		} else {
			return [isset($result) ? $result : '', TRUE];
		}
	}
}
