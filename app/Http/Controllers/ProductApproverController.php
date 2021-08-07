<?php

namespace App\Http\Controllers;

use App\Product;
use App\Setting;
use App\Stage;
use App\Brand;
use App\Category;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ProductApproverController extends Controller
{
	public function __construct() {

//		$this->middleware('permission:approver-list',['only' => ['index']]);
//		$this->middleware('permission:approver-edit',['only' => ['edit','isFinal']]);
	}


	public function index(Stage $stage){

		$products = Product::latest()
												->where('stock', '>=', 1)
		                   ->where('stage','>=',$stage->get('Lister'))
		                   ->whereNull('dnf')
											 ->select(['id', 'sku', 'size', 'price_inr_special', 'brand', 'supplier', 'isApproved', 'stage', 'status', 'is_scraped', 'created_at'])
		                   ->paginate(Setting::get('pagination'));

		$roletype = 'Approver';

		$category_selection = Category::attr(['name' => 'category[]','class' => 'form-control select-multiple'])
		                                        ->selected(1)
		                                        ->renderAsDropdown();

		return view('partials.grid',compact('products','roletype', 'category_selection'))
			->with('i', (request()->input('page', 1) - 1) * 10);

	}

	public function edit(Product $productlister){

		return redirect( route('products.show',$productlister->id) );
	}

	public function isFinal(Product $product,Stage $stage){

		$result = self::magentoSoapUpdateStatus($product);

		if ($result) {

			$product->isFinal = 1;
			$product->stage   = $stage->get( 'Approver' );
			$product->save();

			NotificaitonContoller::store( 'has Final Approved', [ 'Inventory' ], $product->id );
			ActivityConroller::create( $product->id, 'approver', 'create' );

			$next_product = Product::latest()
			                   ->where('stage','>=', '6')
			                   ->whereNull('dnf')->where('isFinal', '!=', '1')->first();

			return redirect()->route('products.show', $next_product->id)->with( 'success', 'Product has been Final Approved' );
		}

		return back()->with('error','Error Occured while uploading. Check on magento');

//		return ['msg'=>'success', 'isApproved'  => $product->isApproved ];
	}


	public function magentoSoapUpdateStatus($product) {
		$options = array(
			'trace' => true,
			'connection_timeout' => 120,
			'wsdl_cache' => WSDL_CACHE_NONE,
		);

		$proxy = new \SoapClient(config('magentoapi.url'), $options);
		$sessionId = $proxy->login(config('magentoapi.user'), config('magentoapi.password'));
		$errors = 0;

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

			$reference_final_sku = $reference_sku . $reference_color;

			if(!empty($product->size)) {
				
				$euSize = explode(',', $product->size_eu);
				
				if(!empty($euSize)){
					$product_sizes = $euSize;
				}else{
					$product_sizes = explode(',', $product->size);
				}

				foreach ($product_sizes as $size) {
					if (in_array($size, $reference_array)) {
						try {
							$result = $proxy->catalogProductUpdate($sessionId, $reference_final_sku . '-' . $size , array('status' => 1));
						} catch (\Exception $e) {
							$errors++;
						}
					}
				}

				try {
					$result = $proxy->catalogProductUpdate($sessionId, $reference_final_sku, array('status' => 1));
				} catch (\Exception $e) {
					$errors++;
				}
			} else {
				try {
					$result = $proxy->catalogProductUpdate($sessionId, $reference_final_sku, array('status' => 1));
				} catch (\Exception $e) {
					$errors++;
				}
			}
		}

		if ($errors == 0) {
			$product->is_uploaded_date = Carbon::now();

			$product->isFinal = 1;
			$product->isListed = 1;

			$product->save();
		}

		return $errors > 0 ? false : $result;
	}

    public function magentoSoapUnlistProduct($product) {
        $options = array(
            'trace' => true,
            'connection_timeout' => 120,
            'wsdl_cache' => WSDL_CACHE_NONE,
        );

        $proxy = new \SoapClient(config('magentoapi.url'), $options);
        $sessionId = $proxy->login(config('magentoapi.user'), config('magentoapi.password'));
        $errors = 0;

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

            $reference_final_sku = $reference_sku . $reference_color;

            if(!empty($product->size)) {
                
                $euSize = explode(',', $product->size_eu);
				
				if(!empty($euSize)){
					$product_sizes = $euSize;
				}else{
					$product_sizes = explode(',', $product->size);
				}

                foreach ($product_sizes as $size) {
                    if (in_array($size, $reference_array)) {
                        try {
                            $result = $proxy->catalogProductUpdate($sessionId, $reference_final_sku . '-' . $size , array('status' => 2));
                        } catch (\Exception $e) {
                            $errors++;
                        }
                    }
                }

                try {
                    $result = $proxy->catalogProductUpdate($sessionId, $reference_final_sku, array('status' => 2));
                } catch (\Exception $e) {
                    $errors++;
                }
            } else {
                try {
                    $result = $proxy->catalogProductUpdate($sessionId, $reference_final_sku, array('status' => 2));
                } catch (\Exception $e) {
                    $errors++;
                }
            }
        }

        if ($errors == 0) {
            $product->is_uploaded_date = Carbon::now();

            $product->isFinal = 0;
            $product->isListed = 0;

            $product->save();
        }

        return $errors > 0 ? false : $result;
    }
}
