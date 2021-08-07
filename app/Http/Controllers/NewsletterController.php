<?php

namespace App\Http\Controllers;

use App\Newsletter;
use App\NewsletterProduct;
use App\Product;
use App\StoreWebsite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class NewsletterController extends Controller
{
    const GALLERY_TAG_NAME = "gallery";

    public function __construct()
    {

    }

    public function index(Request $request)
    {
        $title          = "Newsletter";
        $store_websites = null;

        return view("newsletter.index", compact(['title', 'store_websites']));
    }

    public function records(Request $request)
    {
        $records = \App\Newsletter::join("newsletter_products as np", "newsletters.id", "np.newsletter_id")
        ->leftJoin("users as u", "u.id", "newsletters.updated_by")
        ->leftJoin("mailinglists as m", "m.id", "newsletters.mail_list_id");

        $keyword = request("keyword");

        if (!empty($keyword)) {
            $records = $records->where(function ($q) use ($keyword) {
                $q->where("np.product_id", "LIKE", "%$keyword%");
            });
        }

        $dateFrom = request("date_from");
        if ($dateFrom != null) {
            $records = $records->where("newsletters.created_at", '>=', $dateFrom);
        }

        $dateTo = request("date_to");
        if ($dateTo != null) {
            $records = $records->where("newsletters.created_at", '<=', $dateTo);
        }

        $sent_at = request("send_at");
        if ($sent_at != null) {
            $records = $records->whereDate("newsletters.sent_at", '=', $sent_at);
        }

        $records = $records->groupBy('newsletters.id')->select(["newsletters.*", "u.name as updated_by","m.name as mailinglist_name"])->latest()->paginate();

        $store_websites = StoreWebsite::where('website_source', '=', 'shopify')->get();

        $items = [];

        foreach ($records->items() as &$rec) {
            $images = [];
            if (!$rec->newsletterProduct->isEmpty()) {
                foreach ($rec->newsletterProduct as $nwP) {
                    if ($nwP->product) {
                        $media = $nwP->product->getMedia(config('constants.attach_image_tag'))->first();
                        if ($media) {
                            $images[] = [
                                "url"        => $media->getUrl(),
                                "id"         => $nwP->id,
                                "product_id" => $nwP->product->id,
                            ];
                        }
                    }
                }
            }
            $rec->product_images    = $images;
            $rec->store_websiteName = ($rec->storeWebsite) ? $rec->storeWebsite->website : "";
            $items[]                = $rec;
        }

        return response()->json(["code" => 200, "data" => $items, "total" => $records->total(), "pagination" => (string) $records->render()]);
    }

    public function save(Request $request)
    {
        $params     = $request->all();
        $productIds = json_decode($request->get("images"), true);

        $errorMessage = [];
        $needToSave   = [];

        if (!empty($productIds)) {
            foreach ($productIds as $productId) {
                $product = \App\Product::find($productId);
                if ($product) {
                    $needToSave[] = $product->id;
                } else {
                    $errorMessage[] = "Product not found : {$productId}";
                }
            }
        }

        if (count($needToSave) > 0) {
            $newsletter             = new Newsletter;
            $newsletter->subject    = "DRAFT";
            $newsletter->updated_by = auth()->user()->id;
            if ($newsletter->save()) {
                foreach ($needToSave as $ns) {
                    $nProduct                = new NewsletterProduct;
                    $nProduct->product_id    = $ns;
                    $nProduct->newsletter_id = $newsletter->id;
                    $nProduct->save();
                }
            }
        }

        if (count($errorMessage) > 0) {
            return redirect()->route('newsletters.index')->withError('There was some issue for given products : ' . implode("<br>", $errorMessage));
        }

        return redirect()->route('newsletters.index')->withSuccess('You have successfully added newsletter products!');
    }

    public function store(Request $request)
    {
        $post = $request->all();

        $validator = Validator::make($post, [
            //'sent_at' => 'required',
            'subject' => 'required',
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

        $id = $request->get("id", 0);

        $records = Newsletter::find($id);

        if (!$records) {
            $records = new Newsletter;
        }

        $records->fill($post);
        $records->save();

        return response()->json(["code" => 200, "data" => $records]);

    }

    /**
     * Edit Page
     * @param  Request $request [description]
     * @return
     */

    public function edit(Request $request, $id)
    {
        $newsletter = Newsletter::where("id", $id)->first();

        if ($newsletter) {
            $newsletter->newsletterProduct;
            return response()->json(["code" => 200, "data" => $newsletter]);
        }

        return response()->json(["code" => 500, "error" => "Wrong row id!"]);
    }

    /**
     * delete Page
     * @param  Request $request [description]
     * @return
     */

    public function delete(Request $request, $id)
    {
        $newsletter = Newsletter::where("id", $id)->first();

        if ($newsletter) {
            $newsletter->newsletterProduct()->delete();
            $newsletter->delete();
            return response()->json(["code" => 200]);
        }

        return response()->json(["code" => 500, "error" => "Wrong row id!"]);
    }

    public function deleteImage($id)
    {
        $newsletterProduct = NewsletterProduct::find($id);

        if ($newsletterProduct) {
            $newsletterProduct->delete();
        }

        return response()->json(["code" => 200, "message" => "Deleted successfully"]);

    }

    public function preview(Request $request, $id)
    {
        $newsletter = Newsletter::find($id);

        if ($newsletter) {
            $template = \App\MailinglistTemplate::getNewsletterTemplate($newsletter->store_website_id);
            if ($template) {
                $products = $newsletter->products;
                if (!$products->isEmpty()) {
                    foreach ($products as $product) {
                        if ($product->hasMedia(config('constants.attach_image_tag'))) {
                            foreach ($product->getMedia(config('constants.attach_image_tag')) as $image) {
                                $product->images[] = $image->getUrl();
                            }
                        }
                    }
                }
                
                echo view($template->mail_tpl, compact('products', 'newsletter'));
            }
        }

        echo "No Preview found";
        die;
    }
}
