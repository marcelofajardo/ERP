<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use App\Images;
use Image;
use Storage;
use Auth;
use Carbon\Carbon;
use App\Setting;
use App\Tag;
use App\Category;
use App\Brand;
use Chumper\Zipper\Zipper;
use Plank\Mediable\Media;
use Plank\Mediable\MediaUploaderFacade as MediaUploader;

class ImageController extends Controller
{

  public function __construct() {

//		$this->middleware('permission:social-view', ['except' => ['approveImage', 'store', 'edit', 'update', 'attachImage', 'destroy']]);
//    $this->middleware('permission:social-create', ['only' => ['store', 'edit', 'update', 'attachImage', 'destroy']]);
//		$this->middleware('permission:social-manage', ['only' => ['approveImage', 'updateSchedule']]);
	}
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      if (!isset($request->sortby) || $request->sortby == 'asc') {
        $images = Images::where('status', '1')->whereNull('approved_date');
      } else {
        $images = Images::where('status', '1')->whereNull('approved_date')->latest();
      }

      $brand = '';
      $category = '';
      $price = null;

      if ($request->brand[0] != null) {
  			$images = $images->whereIn('brand', $request->brand);

  			$brand = $request->brand[0];
  		}

      if ($request->category[0] != null && $request->category[0] != 1) {
  			$is_parent = Category::isParent($request->category[0]);
  			$category_children = [];

  			if ($is_parent) {
  				$childs = Category::find($request->category[0])->childs()->get();

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
  				array_push($category_children, $request->category[0]);
  			}

				$images = $images->whereIn('category', $category_children);

  			$category = $request->category[0];
  		}

      // dd($images->get());

      if ($request->price != null) {
  			$exploded = explode(',', $request->price);
  			$min = $exploded[0];
  			$max = $exploded[1];

  			if ($min != '0' || $max != '10000000') {
					$images = $images->whereBetween('price_inr_special', [$min, $max]);
  			}

  			$price[0] = $min;
  			$price[1] = $max;
  		}

      $brands = Brand::getAll();
      $selected_categories = $request->category ? $request->category : 1;
  		$category_selection = Category::attr(['name' => 'category[]','class' => 'form-control select-multiple'])
  		                                        ->selected($selected_categories)
  		                                        ->renderAsDropdown();

      $images = $images->orderBy('id','desc')->with('product')->paginate(Setting::get('pagination'));
      
      return view('images.index')->with([
        'images'  => $images,
        'brands'  => $brands,
        'category_selection'  => $category_selection,
        'brand'  => $brand,
        'category'  => $category,
        'price'  => $price
      ]);
    }

    public function indexNew(Request $request)
    {
      if (!isset($request->sortby) || $request->sortby == 'asc') {
        $images = Images::where('images.status', '1')->whereNull('approved_date');
      } else {
        $images = Images::where('images.status', '1')->whereNull('approved_date')->latest();
      }

      $brand = '';
      $category = '';
      $price = null;

      //Purpose : Add isset() for Brancd - DEVTASK-4378
      if (isset($request->brand) && $request->brand[0] != null) {
         $images = $images->whereIn('brand', $request->brand);

         $brand = $request->brand[0];
        }

      //Purpose : Add isset() for Category - DEVTASK-4378
      if (isset($request->category) && $request->category[0] != null && $request->category[0] != 1) {
         $is_parent = Category::isParent($request->category[0]);
         $category_children = [];

         if ($is_parent) {
             $childs = Category::find($request->category[0])->childs()->get();

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
             array_push($category_children, $request->category[0]);
         }

                $images = $images->whereIn('category', $category_children);

         $category = $request->category[0];
        }

      // // dd($images->get());

      if ($request->price != null) {
            $exploded = explode(',', $request->price);
            $min = $exploded[0];
            $max = $exploded[1];

            if ($min != '0' || $max != '10000000') {
                    $images = $images->whereBetween('price_inr_special', [$min, $max]);
            }

            $price[0] = $min;
            $price[1] = $max;
        }

      $brands = Brand::getAll();
      $selected_categories = $request->category ? $request->category : 1;
        $category_selection = Category::attr(['name' => 'category[]','class' => 'form-control select-multiple'])
                                                ->selected($selected_categories)
                                                ->renderAsDropdown();
        if( !empty(request('product_name')) ){
            $images->leftjoin('products','products.id','=','images.product_id');
            $images->where('products.name', 'like', '%'.request('product_name').'%');
        }
      $images = $images->select('images.*')->orderBy('id','desc')
                ->groupBy(\DB::raw('ifnull(product_id,images.id)'))
                ->paginate(Setting::get('pagination'));
      
      return view('images.index-new')->with([
        'images'  => $images,
        'brands'  => $brands,
        'category_selection'  => $category_selection,
        'brand'  => $brand,
        'category'  => $category,
        'price'  => $price
      ]);
    }

    public function approved(Request $request)
    {
      if (!isset($request->sortby) || $request->sortby == 'asc') {
        $images = Images::where('status', '1')->whereNotNull('approved_date');
      } else {
        $images = Images::where('status', '1')->whereNotNull('approved_date')->latest();
      }

      $brand = '';
      $category = '';
      $price = null;

      if ($request->brand[0] != null) {
  			$images = $images->whereIn('brand', $request->brand);

  			$brand = $request->brand[0];
  		}

      if ($request->category[0] != null && $request->category[0] != 1) {
  			$is_parent = Category::isParent($request->category[0]);
  			$category_children = [];

  			if ($is_parent) {
  				$childs = Category::find($request->category[0])->childs()->get();

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
  				array_push($category_children, $request->category[0]);
  			}

				$images = $images->whereIn('category', $category_children);

  			$category = $request->category[0];
  		}

      // dd($images->get());

      if ($request->price != null) {
  			$exploded = explode(',', $request->price);
  			$min = $exploded[0];
  			$max = $exploded[1];

  			if ($min != '0' || $max != '10000000') {
					$images = $images->whereBetween('price_inr_special', [$min, $max]);
  			}

  			$price[0] = $min;
  			$price[1] = $max;
  		}

      $brands = Brand::getAll();
      $selected_categories = $request->category ? $request->category : 1;
  		$category_selection = Category::attr(['name' => 'category[]','class' => 'form-control select-multiple'])
  		                                        ->selected($selected_categories)
  		                                        ->renderAsDropdown();

      $images = $images->paginate(Setting::get('pagination'));

      return view('images.approved')->with([
        'images'  => $images,
        'brands'  => $brands,
        'category_selection'  => $category_selection,
        'brand'  => $brand,
        'category'  => $category,
        'price'  => $price
      ]);
    }

    public function final(Request $request)
    {
      $stats_brand = Images::where('status', '2')->whereNotNull('publish_date')->whereBetween('publish_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->select('brand', 'category', 'publish_date')->get()->groupBy([function($date) {
        return Carbon::parse($date->publish_date)->format('Y-m-d');
      }, 'brand'])->toArray();
      $stats_category = Images::where('status', '2')->whereNotNull('publish_date')->whereBetween('publish_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->select('brand', 'category', 'publish_date')->get()->groupBy([function($date) {
        return Carbon::parse($date->publish_date)->format('Y-m-d');
      }, 'category'])->toArray();
      $categories = Category::all();

      $categories_array = [];
      foreach ($categories as $category) {
        $categories_array[$category->id] = $category->title;
      }
      // dd($stats_brand);

      if (!isset($request->sortby) || $request->sortby == 'asc') {
        $images = Images::where('status', '2');
      } else {
        $images = Images::where('status', '2')->latest();
      }

      $brand = '';
      $category = '';
      $price = null;

      if ($request->brand[0] != null) {
  			$images = $images->whereIn('brand', $request->brand);

  			$brand = $request->brand[0];
  		}

      if ($request->category[0] != null && $request->category[0] != 1) {
  			$is_parent = Category::isParent($request->category[0]);
  			$category_children = [];

  			if ($is_parent) {
  				$childs = Category::find($request->category[0])->childs()->get();

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
  				array_push($category_children, $request->category[0]);
  			}

				$images = $images->whereIn('category', $category_children);

  			$category = $request->category[0];
  		}

      // dd($images->get());

      if ($request->price != null) {
  			$exploded = explode(',', $request->price);
  			$min = $exploded[0];
  			$max = $exploded[1];

  			if ($min != '0' || $max != '10000000') {
					$images = $images->whereBetween('price_inr_special', [$min, $max]);
  			}

  			$price[0] = $min;
  			$price[1] = $max;
  		}

      $brands = Brand::getAll();
      $selected_categories = $request->category ? $request->category : 1;
  		$category_selection = Category::attr(['name' => 'category[]','class' => 'form-control'])
  		                                        ->selected($selected_categories)
  		                                        ->renderAsDropdown();

      $images = $images->paginate(Setting::get('pagination'));

      $image_sets = Images::whereNotNull('publish_date')->get()->groupBy('publish_date');

      return view('images.final')->with([
        'images'  => $images,
        'image_sets'  => $image_sets,
        'brands'  => $brands,
        'category_selection'  => $category_selection,
        'brand'  => $brand,
        'category'  => $category,
        'price'  => $price,
        'stats_brand' => $stats_brand,
        'stats_category' => $stats_category,
        'categories_array'  => $categories_array
      ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $this->validate($request, [
        'images'  => 'required'
      ]);

      if ($request->hasfile('images')) {
        foreach ($request->file('images') as $key => $image) {
          $filename = time() . $key . '.' . $image->getClientOriginalExtension();
          $location = public_path('uploads/social-media/') . $filename;

          Image::make($image)->encode('jpg', 65)->save($location);

          $new_image = new Images;
          $new_image->filename = $filename;

          if ($request->image_id) {
            $old_image = Images::find($request->image_id);
            $new_image->brand = $old_image->brand;
            $new_image->category = $old_image->category;
            $new_image->price = $old_image->price;
            $new_image->publish_date = $old_image->publish_date;
          }

          if ($request->lifestyle == 1) {
            $new_image->lifestyle = 1;
          }

          $new_image->status = $request->status;
          $new_image->save();

          if ($request->image_id) {
            foreach ($old_image->tags as $tag) {
              $new_image->tags()->attach($tag);
            }

            $old_image->delete();
          }


        }
      }

      if ($request->status == '1') {
        return redirect()->route('image.grid')->with('success', 'The image(s) were successfully uploaded');
      } elseif ($request->status == '2') {
        return redirect()->route('image.grid.approved')->with('success', 'The image(s) were successfully uploaded');
      }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      $image = Images::find($id);
      $brands = Brand::getAll();
      $categories = Category::all();
      $categories_array = [];

      foreach ($categories as $category) {
        $categories_array[$category->id] = $category->title;
      }

      return view('images.show')->with([
        'image'             => $image,
        'brands'            => $brands,
        'categories_array'  => $categories_array
      ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      $image = Images::find($id);
      // $selected_categories = is_array(json_decode($image->category, true)) ? json_decode($image->category, true) : [] ;
      $category_select = Category::attr(['name' => 'category','class' => 'form-control'])
	                                       ->selected($image->category)
	                                       ->renderAsDropdown();
      $brands = Brand::getAll();

      return view('images.edit')->with([
        'image' => $image,
        'category_select' => $category_select,
        'brands' => $brands
      ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
      $this->validate($request, [
        'price' => 'sometimes|nullable|integer'
      ]);

      $image = Images::find($id);

      if ($request->hasfile('image')) {
        Storage::disk('uploads')->delete("social-media/$image->filename");

        $filename = time() . '.' . $request->file('image')->getClientOriginalExtension();
        $location = public_path('uploads/social-media/') . $filename;

        Image::make($request->file('image'))->encode('jpg', 65)->save($location);

        $image->filename = $filename;
      }

      $image->brand = $request->brand;
      $image->category = $request->category;
      $image->price = $request->price;
      $image->publish_date = $request->publish_date;
      $image->save();

      $tags = Tag::all();
      $tags_array = [];
      $image->tags()->detach();

      if (count($tags) > 0) {
        foreach ($tags as $key => $tag) {
          $tags_array[$key] = $tag->tag;
        }
      }

      if (isset($request->tags)) {
        foreach ($request->tags as $tag) {
          if (!in_array($tag, $tags_array)) {
            $new_tag = Tag::create(['tag' => $tag]);
          } else {
            $new_tag = Tag::where('tag', $tag)->first();
          }

          $image->tags()->attach($new_tag);
        }
      }

      return redirect()->route('image.grid.edit', $image->id)->with('success', 'You have successfully updated image');
    }

    public function updateSchedule(Request $request)
    {
      foreach ($request->images as $image) {
        $img = Images::find($image['id']);
        $img->publish_date = $request->date;
        $img->save();
      }

      return response('success');
    }

    public function set(Request $request)
    {
      $this->validate($request, [
        'image_id'      => 'required',
        'publish_date'  => 'required'
      ]);

      foreach (json_decode($request->image_id) as $image_id) {
        $image = Images::find($image_id);
        $image->publish_date = $request->publish_date;
        $image->save();
      }

      return redirect()->route('image.grid.final.approval')->with('success', 'You have successfully created a set');
    }

    public function setDownload(Request $request)
    {
      $images = Images::whereIn('id', json_decode($request->images))->get();

      $images_array = [];
      foreach ($images as $image) {
        $path = public_path('uploads/social-media') . '/' . $image->filename;
        array_push($images_array, $path);
      }

      \Zipper::make(public_path('images.zip'))->add($images_array)->close();

      return response()->download(public_path('images.zip'))->deleteFileAfterSend();
    }

    public function approveImage(Request $request, $id)
    {
      $image = Images::find($id);

      $image->approved_user = Auth::id();
      $image->approved_date = Carbon::now();

      if ($image->lifestyle == 1) {
        $image->status = 2;
      }

      $image->save();

      if ($request->ajax()) {
        if ($image->status == '1') {
          return response('success');
        } elseif ($image->status == '2') {
          return response(['user' => $image->approved_user, 'date' => "$image->approved_date"]);
        }
      }

      if ($image->status == '1') {
        return redirect()->route('image.grid')->with('success', 'You have successfully approved image');
      } elseif ($image->status == '2') {
        return redirect()->route('image.grid.final.approval')->with('success', 'You have successfully approved image');
      }

    }

    public function attachImage(Request $request)
    {
      if ($request->images) {
        foreach (json_decode($request->images) as $image) {
          $new_image = new Images;
          $media = Media::find($image);
          $new_image->save();

          $new_image->attachMedia($media,config('constants.media_tags'));
        }
      }
      return redirect()->route('image.grid')->with('success', 'You have successfully attached images');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      $image = Images::withTrashed()->find($id);

      Storage::disk('uploads')->delete("social-media/$image->filename");

      $image->tags()->detach();
      $image->detachMedia(config('constants.media_tags'));
      $image->forceDelete();

      return redirect()->back()->with('success', 'The image was successfully deleted');
    }

    public function download($id)
    {
      $image = Images::find($id);

      if ($image->filename != '') {
        $path = public_path('uploads/social-media') . '/' . $image->filename;
      } else {
        $path = $image->getMedia(config('constants.media_tags'))->first()->getAbsolutePath();
      }

      return response()->download($path);
    }

    public function imageQueue(Request $request){
      $validator=Validator::make($request->all(), [
        'search_term' =>'required|string|min:3|max:60'
      ]);

      if($validator->fails()) {
          return Redirect::Back()
              ->withErrors($validator)
              ->withInput($request->all());
      }

      $new=new \App\SearchQueue;
      $new->search_type='image';
      $new->model_name='App\Images';
      $new->search_term=$request->search_term;
      $new->created_at=$new->updated_at=time();

      if($new->save()){

        //call google image scraper
        $postData = ['data' => array(array('id' => $new->id, 'search_term' => $request->search_term))];
        $postData = json_encode($postData);
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => env('NODE_SCRAPER_SERVER') . "api/googleSearchImages",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_HTTPHEADER => array(
            "Content-Type: application/json",
        ),
        CURLOPT_POSTFIELDS => "$postData"
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        $messages="new search queue added successfuly";
        return Redirect::Back()
              ->with('success',$messages);
      }else{
        $messages[]="Sorry! Please try again";
        return Redirect::Back()
              ->withErrors($messages);
      }
    }
}
