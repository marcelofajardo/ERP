<?php

namespace Modules\StoreWebsite\Http\Controllers;

use App\Category;
use App\StoreWebsite;
use App\StoreWebsiteCategory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use seo2websites\MagentoHelper\MagentoHelper;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request, $id)
    {
        $title = "Attached Category | Store Website";

        if ($request->ajax()) {
            // send response into the json
            $categoryDropDown = \App\Category::attr([
                'name'  => 'category_id',
                'class' => 'form-control select-searchable',
            ])->renderAsDropdown();

            $storeWebsite = StoreWebsiteCategory::join("categories as c", "c.id", "store_website_categories.category_id")
                ->where("store_website_id", $id)
                ->select(["store_website_categories.*", "c.title"])
                ->get();

            return response()->json([
                "code"             => 200,
                "store_website_id" => $id,
                "data"             => $storeWebsite,
                'scdropdown'       => $categoryDropDown,
            ]);
        }

        return view('storewebsite::index', compact('title'));
    }

    /**
     * store cateogories
     *
     */

    public function store(Request $request)
    {
        $storeWebsiteId = $request->get("store_website_id");
        $post           = $request->all();

        $validator = Validator::make($post, [
            'store_website_id' => 'required',
            'category_id'      => 'unique:store_website_categories,category_id,NULL,id,store_website_id,' . $storeWebsiteId . '|required',
        ]);

        if ($validator->fails()) {
            $outputString = "";
            $messages     = $validator->errors()->getMessages();
            foreach ($messages as $k => $errr) {
                foreach ($errr as $er) {
                    $outputString .= "$k : " . $er . "<br>";
                }
            }
            return response()->json(["code" => 500, "error" => $outputString]);
        }

        $storeWebsiteCategory = new StoreWebsiteCategory();
        $storeWebsiteCategory->fill($post);
        $storeWebsiteCategory->save();

        return response()->json(["code" => 200, "data" => $storeWebsiteCategory]);

    }

    public function delete(Request $request, $id, $store_category_id)
    {
        $storeCategory = StoreWebsiteCategory::where("store_website_id", $id)->where("id", $store_category_id)->first();
        if ($storeCategory) {
            $storeCategory->delete();
        }
        return response()->json(["code" => 200, "data" => []]);
    }

    /**
     * Get child categories
     * @return []
     *
     */

    public function getChildCategories(Request $request, $id)
    {
        $categories = \App\Category::where("id", $id)->first();
        $return     = [];
        if ($categories) {
            $return[] = [
                "id"    => $categories->id,
                "title" => $categories->title,
            ];

            $this->recursiveChildCat($categories, $return);
        }

        return response()->json(["code" => 200, "data" => $return]);
    }

    /**
     * Recursive child category
     * @return []
     *
     */

    public function recursiveChildCat($categories, &$return = [])
    {
        foreach ($categories->childs as $cat) {
            if ($cat->title != "") {
                $return[] = [
                    "id"    => $cat->id,
                    "title" => $cat->title,
                ];
            }
            $this->recursiveChildCat($cat, $return);
        }
    }

    public function storeMultipleCategories(Request $request)
    {
        $swi        = $request->get("website_id");
        $categories = $request->get("categories");

        // store website category
        $ccat = StoreWebsiteCategory::where("store_website_id", $swi)->get()
            ->pluck("name")
            ->toArray();

        // check unique records
        $unique = array_diff($categories, $ccat);
        if (!empty($unique) && is_array($unique)) {
            foreach ($unique as $cat) {
                // StoreWebsiteCategory::create([
                //     "store_website_id" => $swi,
                //     "category_id" => $cat
                // ]);

                $category = Category::find($cat);

                if ($category->parent_id == 0) {
                    $case = 'single';
                } elseif ($category->parent->parent_id == 0) {
                    $case = 'second';
                } else {
                    $case = 'third';
                }

                //Check if category
                if ($case == 'single') {
                    $data['id']       = $category->id;
                    $data['level']    = 1;
                    $data['name']     = ucwords($category->title);
                    $data['parentId'] = 0;
                    $parentId         = 0;

                    if (class_exists('\\seo2websites\\MagentoHelper\\MagentoHelper')) {
                        $categ = MagentoHelper::createCategory($parentId, $data, $swi);
                    }
                    if ($category) {
                        $checkIfExist = StoreWebsiteCategory::where('store_website_id', $swi)->where('category_id', $category->id)->where('remote_id', $categ)->first();
                        if (empty($checkIfExist)) {
                            $storeWebsiteCategory                   = new StoreWebsiteCategory();
                            $storeWebsiteCategory->category_id      = $category->id;
                            $storeWebsiteCategory->store_website_id = $swi;
                            $storeWebsiteCategory->remote_id        = $categ;
                            $storeWebsiteCategory->save();
                        }
                    }
                }

                //if case second
                if ($case == 'second') {
                    $parentCategory = StoreWebsiteCategory::where('store_website_id', $swi)->where('category_id', $category->parent->id)->whereNotNull('remote_id')->first();
                    //if parent remote null then send to magento first
                    if (empty($parentCategory)) {

                        $data['id']       = $category->parent->id;
                        $data['level']    = 1;
                        $data['name']     = ucwords($category->parent->title);
                        $data['parentId'] = 0;
                        $parentId         = 0;

                        if (class_exists('\\seo2websites\\MagentoHelper\\MagentoHelper')) {

                            $parentCategoryDetails = MagentoHelper::createCategory($parentId, $data, $swi);

                        }
                        if ($parentCategoryDetails) {
                            $checkIfExist = StoreWebsiteCategory::where('store_website_id', $swi)->where('category_id', $category->id)->where('remote_id', $parentCategoryDetails)->first();
                            if (empty($checkIfExist)) {
                                $storeWebsiteCategory                   = new StoreWebsiteCategory();
                                $storeWebsiteCategory->category_id      = $category->id;
                                $storeWebsiteCategory->store_website_id = $swi;
                                $storeWebsiteCategory->remote_id        = $parentCategoryDetails;
                                $storeWebsiteCategory->save();
                            }
                        }

                        $parentRemoteId = $parentCategoryDetails;

                    } else {
                        $parentRemoteId = $parentCategory->remote_id;
                    }

                    $data['id']       = $category->id;
                    $data['level']    = 2;
                    $data['name']     = ucwords($category->title);
                    $data['parentId'] = $parentRemoteId;

                    if (class_exists('\\seo2websites\\MagentoHelper\\MagentoHelper')) {

                        $categoryDetail = MagentoHelper::createCategory($parentRemoteId, $data, $swi);

                    }

                    if ($categoryDetail) {
                        $checkIfExist = StoreWebsiteCategory::where('store_website_id', $swi)->where('category_id', $category->id)->where('remote_id', $categoryDetail)->first();
                        if (empty($checkIfExist)) {
                            $storeWebsiteCategory                   = new StoreWebsiteCategory();
                            $storeWebsiteCategory->category_id      = $category->id;
                            $storeWebsiteCategory->store_website_id = $swi;
                            $storeWebsiteCategory->remote_id        = $categoryDetail;
                            $storeWebsiteCategory->save();
                        }
                    }
                }

                //if case third
                if ($case == 'third') {
                    //Find Parent
                    $parentCategory = StoreWebsiteCategory::where('store_website_id', $swi)->where('category_id', $category->id)->whereNotNull('remote_id')->first();

                    //Check if parent had remote id
                    if (empty($parentCategory)) {

                        //check for grandparent
                        $grandCategory       = Category::find($category->parent->id);
                        $grandCategoryDetail = StoreWebsiteCategory::where('store_website_id', $swi)->where('category_id', $grandCategory->parent->id)->whereNotNull('remote_id')->first();

                        if (empty($grandCategoryDetail)) {

                            $data['id']       = $grandCategory->parent->id;
                            $data['level']    = 1;
                            $data['name']     = ucwords($grandCategory->parent->title);
                            $data['parentId'] = 0;
                            $parentId         = 0;

                            if (class_exists('\\seo2websites\\MagentoHelper\\MagentoHelper')) {

                                $grandCategoryDetails = MagentoHelper::createCategory($parentId, $data, $swi);

                            }

                            if ($grandCategoryDetails) {
                                $checkIfExist = StoreWebsiteCategory::where('store_website_id', $swi)->where('category_id', $category->parent->id)->where('remote_id', $grandCategoryDetails)->first();
                                if (empty($checkIfExist)) {
                                    $storeWebsiteCategory                   = new StoreWebsiteCategory();
                                    $storeWebsiteCategory->category_id      = $category->parent->id;
                                    $storeWebsiteCategory->store_website_id = $swi;
                                    $storeWebsiteCategory->remote_id        = $grandCategoryDetails;
                                    $storeWebsiteCategory->save();
                                }

                            }

                            $grandRemoteId = $grandCategoryDetails;

                        } else {
                            $grandRemoteId = $grandCategoryDetail->remote_id;
                        }
                        //Search for child category

                        $data['id']       = $category->parent->id;
                        $data['level']    = 2;
                        $data['name']     = ucwords($category->parent->title);
                        $data['parentId'] = $grandRemoteId;
                        $parentId         = $grandRemoteId;

                        if (class_exists('\\seo2websites\\MagentoHelper\\MagentoHelper')) {

                            $childCategoryDetails = MagentoHelper::createCategory($parentId, $data, $swi);

                        }

                        $checkIfExist = StoreWebsiteCategory::where('store_website_id', $swi)->where('category_id', $category->parent->id)->where('remote_id', $childCategoryDetails)->first();
                        if (empty($checkIfExist)) {
                            $storeWebsiteCategory                   = new StoreWebsiteCategory();
                            $storeWebsiteCategory->category_id      = $category->parent->id;
                            $storeWebsiteCategory->store_website_id = $swi;
                            $storeWebsiteCategory->remote_id        = $childCategoryDetails;
                            $storeWebsiteCategory->save();
                        }

                        $data['id']       = $category->id;
                        $data['level']    = 3;
                        $data['name']     = ucwords($category->title);
                        $data['parentId'] = $childCategoryDetails;

                        if (class_exists('\\seo2websites\\MagentoHelper\\MagentoHelper')) {

                            $categoryDetail = MagentoHelper::createCategory($childCategoryDetails, $data, $swi);

                        }

                        if ($categoryDetail) {
                            $checkIfExist = StoreWebsiteCategory::where('store_website_id', $swi)->where('category_id', $category->id)->where('remote_id', $categoryDetail)->first();
                            if (empty($checkIfExist)) {
                                $storeWebsiteCategory                   = new StoreWebsiteCategory();
                                $storeWebsiteCategory->category_id      = $category->id;
                                $storeWebsiteCategory->store_website_id = $swi;
                                $storeWebsiteCategory->remote_id        = $categoryDetail;
                                $storeWebsiteCategory->save();
                            }
                        }

                    }

                }

            }
        }

        // return response
        return response()->json(["code" => 200, "data" => ["store_website_id" => $swi], "message" => "Category has been saved successfully"]);
    }

    public function list(Request $request) {
        ini_set("memory_limit","-1");
        $title      = "Store Category";
        $categories = Category::query();

        if ($request->keyword != null) {
            $categories = $categories->where("title", "like", "%" . $request->keyword . "%");
        }

        //$categories = $categories->whereIn("id", [3]);

        $categories = $categories->get();

        $storeWebsite = StoreWebsite::all();
        $appliedQ     = StoreWebsiteCategory::all();

    return view("storewebsite::category.index", compact(['title', 'categories', 'storeWebsite', 'appliedQ']));
    }

    public function saveStoreCategory(Request $request)
    {
        $storeId = $request->store;
        $catId   = $request->category_id;
        if ($catId != null && $storeId != null) {
            $categoryStore = StoreWebsiteCategory::where("category_id", $catId)->where("store_website_id", $storeId)->first();
            $website       = \App\StoreWebsite::find($storeId);
            $category      = Category::find($catId);
            if ($category->parent_id == 0) {
                $case = 'single';
            } elseif ($category->parent->parent_id == 0) {
                $case = 'second';
            } else {
                $case = 'third';
            }
            if ($website && $category) {
            //coppied code

            //Check if category
            if ($case == 'single') {
                $data['id']       = $category->id;
                $data['level']    = 1;
                $data['name']     = ($request->category_name) ? ucwords($request->category_name) : ucwords($category->title);
                $data['parentId'] = 0;
                $parentId         = 0;

                if (class_exists('\\seo2websites\\MagentoHelper\\MagentoHelper')) {
                    $categ = MagentoHelper::createCategory($parentId, $data, $storeId);
                }
                if ($category) {
                    $checkIfExist = StoreWebsiteCategory::where('store_website_id', $storeId)->where('category_id', $category->id)->where('remote_id', $categ)->first();
                    if (empty($checkIfExist)) {
                        $storeWebsiteCategory                   = new StoreWebsiteCategory();
                        $storeWebsiteCategory->category_id      = $category->id;
                        $storeWebsiteCategory->store_website_id = $storeId;
                        $storeWebsiteCategory->remote_id        = $categ;
                        $storeWebsiteCategory->save();
                    }
                }
            }

            //if case second
            if ($case == 'second') {
                $parentCategory = StoreWebsiteCategory::where('store_website_id', $storeId)->where('category_id', $category->parent->id)->whereNotNull('remote_id')->first();
                //if parent remote null then send to magento first
                if (empty($parentCategory)) {

                    $data['id']       = $category->id;
                    $data['level']    = 1;
                    $data['name']     = ($request->category_name) ? ucwords($request->category_name) : ucwords($category->title);
                    $data['parentId'] = 0;
                    $parentId         = 0;

                    if (class_exists('\\seo2websites\\MagentoHelper\\MagentoHelper')) {

                        $parentCategoryDetails = MagentoHelper::createCategory($parentId, $data, $storeId);

                    }
                    if ($parentCategoryDetails) {
                        $checkIfExist = StoreWebsiteCategory::where('store_website_id', $storeId)->where('category_id', $category->id)->where('remote_id', $parentCategoryDetails)->first();
                        if (empty($checkIfExist)) {
                            $storeWebsiteCategory                   = new StoreWebsiteCategory();
                            $storeWebsiteCategory->category_id      = $category->id;
                            $storeWebsiteCategory->store_website_id = $storeId;
                            $storeWebsiteCategory->remote_id        = $parentCategoryDetails;
                            $storeWebsiteCategory->save();
                        }
                    }

                    $parentRemoteId = $parentCategoryDetails;

                } else {
                    $parentRemoteId = $parentCategory->remote_id;
                }

                $data['id']       = $category->id;
                $data['level']    = 2;
                $data['name']     = ucwords($category->title);
                $data['parentId'] = $parentRemoteId;

                if (class_exists('\\seo2websites\\MagentoHelper\\MagentoHelper')) {

                    $categoryDetail = MagentoHelper::createCategory($parentRemoteId, $data, $storeId);

                }

                if ($categoryDetail) {
                    $checkIfExist = StoreWebsiteCategory::where('store_website_id', $storeId)->where('category_id', $category->id)->where('remote_id', $categoryDetail)->first();
                    if (empty($checkIfExist)) {
                        $storeWebsiteCategory                   = new StoreWebsiteCategory();
                        $storeWebsiteCategory->category_id      = $category->id;
                        $storeWebsiteCategory->store_website_id = $storeId;
                        $storeWebsiteCategory->remote_id        = $categoryDetail;
                        $storeWebsiteCategory->save();
                    }
                }
            }

            //if case third
            if ($case == 'third') {
                //Find Parent
                $parentCategory = StoreWebsiteCategory::where('store_website_id', $storeId)->where('category_id', $category->id)->whereNotNull('remote_id')->first();

                //Check if parent had remote id
                if (empty($parentCategory)) {

                    //check for grandparent
                    $grandCategory       = Category::find($category->parent->id);
                    $grandCategoryDetail = StoreWebsiteCategory::where('store_website_id', $storeId)->where('category_id', $grandCategory->parent->id)->whereNotNull('remote_id')->first();

                    if (empty($grandCategoryDetail)) {

                        $data['id']       = $category->id;
                        $data['level']    = 1;
                        $data['name']     = ($request->category_name) ? ucwords($request->category_name) : ucwords($category->title);
                        $data['parentId'] = 0;
                        $parentId         = 0;

                        if (class_exists('\\seo2websites\\MagentoHelper\\MagentoHelper')) {

                            $grandCategoryDetails = MagentoHelper::createCategory($parentId, $data, $storeId);

                        }

                        if ($grandCategoryDetails) {
                            $checkIfExist = StoreWebsiteCategory::where('store_website_id', $storeId)->where('category_id', $category->parent->id)->where('remote_id', $grandCategoryDetails)->first();
                            if (empty($checkIfExist)) {
                                $storeWebsiteCategory                   = new StoreWebsiteCategory();
                                $storeWebsiteCategory->category_id      = $category->parent->id;
                                $storeWebsiteCategory->store_website_id = $storeId;
                                $storeWebsiteCategory->remote_id        = $grandCategoryDetails;
                                $storeWebsiteCategory->save();
                            }

                        }

                        $grandRemoteId = $grandCategoryDetails;

                    } else {
                        $grandRemoteId = $grandCategoryDetail->remote_id;
                    }
                    //Search for child category

                    $data['id']       = $category->parent->id;
                    $data['level']    = 2;
                    $data['name']     = ucwords($category->parent->title);
                    $data['parentId'] = $grandRemoteId;
                    $parentId         = $grandRemoteId;

                    if (class_exists('\\seo2websites\\MagentoHelper\\MagentoHelper')) {

                        $childCategoryDetails = MagentoHelper::createCategory($parentId, $data, $storeId);

                    }

                    $checkIfExist = StoreWebsiteCategory::where('store_website_id', $storeId)->where('category_id', $category->parent->id)->where('remote_id', $childCategoryDetails)->first();
                    if (empty($checkIfExist)) {
                        $storeWebsiteCategory                   = new StoreWebsiteCategory();
                        $storeWebsiteCategory->category_id      = $category->parent->id;
                        $storeWebsiteCategory->store_website_id = $storeId;
                        $storeWebsiteCategory->remote_id        = $childCategoryDetails;
                        $storeWebsiteCategory->save();
                    }

                    $data['id']       = $category->id;
                    $data['level']    = 3;
                    $data['name']     = ucwords($category->title);
                    $data['parentId'] = $childCategoryDetails;

                    if (class_exists('\\seo2websites\\MagentoHelper\\MagentoHelper')) {

                        $categoryDetail = MagentoHelper::createCategory($childCategoryDetails, $data, $storeId);

                    }

                    if ($categoryDetail) {
                        $checkIfExist = StoreWebsiteCategory::where('store_website_id', $storeId)->where('category_id', $category->id)->where('remote_id', $categoryDetail)->first();
                        if (empty($checkIfExist)) {
                            $storeWebsiteCategory                   = new StoreWebsiteCategory();
                            $storeWebsiteCategory->category_id      = $category->id;
                            $storeWebsiteCategory->store_website_id = $storeId;
                            $storeWebsiteCategory->remote_id        = $categoryDetail;
                            $storeWebsiteCategory->save();
                        }
                    }

                }

            }

            //end copy
            }

            $msg = '';
            if ($request->check == 0) {
                if ($categoryStore) {
                    $categoryStore->delete();
                    $msg = "Remove successfully";
                }
            } else {
                StoreWebsiteCategory::updateOrCreate(
                    ['category_id' => $catId, 'store_website_id' => $storeId],
                    ['category_name' => $request->category_name, 'remote_id' => @$categ]
                );
                $msg = "Added successfully";
            }
        }

        return response()->json(["code" => 200, "message" => $msg]);

    }
}
