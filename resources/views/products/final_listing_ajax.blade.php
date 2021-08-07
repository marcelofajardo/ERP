@php $imageCropperRole = Auth::user()->hasRole('ImageCropers'); @endphp
<table class="table table-bordered table-striped" style="table-layout:fixed;">
    <thead>
        <tr>
            <th style="width:2%"><input type="checkbox" id="main_checkbox" name="choose_all"></th>
            <th style="width:8%">Product ID</th>
            <th style="width:4%">Image</th>
            <th style="width:7%">Brand</th>
            <th style="width:20%">Category</th>
            <th style="width:8%">Title</th>
            <th style="width:9%"> Description</th>
            <th style="width:8%">Composition</th>
            <th style="width:8%">Color</th>
            <th style="width:8%">Dimension</th>
            <th style="width:7%">Sizes</th>
            <th style="width:5%">Price</th>
            <th style="width:8%">Action</th>
            <th style="width:5%">Status</th>
            <th style="width:5%">User</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($products as $key => $product)
        <tr style="display: none" id="product{{ $product->id }}">
            <td colspan="15">
                <div class="row">
                    <div class="col-md-3">
                        <p class="same-color">{{ strtoupper($product->name) }}</p>
                        <br/>
                        <p class="same-color" style="font-size: 18px;">
                            <span style="text-decoration: line-through">EUR {{ number_format($product->price) }}</span>
                            EUR {{ number_format($product->price_eur_special) }}
                        </p>
                        <?php
                            // check brand sengment
                            if ($product->brands) {
                                $segmentPrice = \App\Brand::getSegmentPrice($product->brands->brand_segment, $product->category);
                                if ($segmentPrice) {
                                    echo "<p class='same-color'>Min Segment Price : " . $segmentPrice->min_price . "<br>
                                        Max Segment Price : " . $segmentPrice->max_price . "</p>";
                                }
                            }
                        ?>
                        <p>
                            <strong class="same-color" style="text-decoration: underline">Description</strong>
                            <br/>
                            <span id="description{{ $product->id }}" class="same-color">
                                {{ ucwords(strtolower(html_entity_decode($product->short_description))) }}
                            </span>
                        </p>
                        <br/>
                        @php
                            $descriptions = \App\ScrapedProducts::select('description','website')->where('sku', $product->sku)->get();
                        @endphp
                        @if ( $descriptions->count() > 0 )
                            @foreach ( $descriptions as $description )
                                @if ( !empty(trim($description->description)) && trim($description->description) != trim($product->short_description) )
                                    <hr/>
                                    <span class="same-color">
                                        {{ ucwords(strtolower(html_entity_decode($description->description))) }}
                                    </span>
                                    <p>
                                        <button class="btn btn-default btn-sm use-description"
                                                data-id="{{ $product->id }}"
                                                data-description="{{ str_replace('"', "'", html_entity_decode($description->description)) }}">
                                            Use this description ({{ $description->website }})
                                        </button>
                                        <button class="btn btn-default btn-sm set-description-site"
                                                data-id="{{ $product->id }}"
                                                data-description="{{ str_replace('"', "'", html_entity_decode($description->description)) }}">
                                            Set Description
                                        </button>
                                    </p>
                                @endif
                            @endforeach
                            <hr/>
                        @endif
                        @php
                            //getting proper composition and hscode
                            $composition = $product->commonComposition($product->category , $product->composition);
                            $hscode =  $product->hsCode($product->category , $product->composition);
                        @endphp
                        <p>
                            <strong class="same-color" style="text-decoration: underline;">HsCode</strong>
                            <br/>
                            <span class="same-color flex-column">{{ strtoupper($hscode) }}</span>
                        </p>
                        <p>
                            <strong>Sizes</strong>: {{ $product->size_eu }}<br/>
                            <strong>Dimension</strong>: {{ \App\Helpers\ProductHelper::getMeasurements($product) }}
                            <br/>
                        </p>
                        <p>
                            <span class="sololuxury-button">ADD TO BAG</span>
                            <span class="sololuxury-button"><i class="fa fa-heart"></i> ADD TO WISHLIST</span>
                        </p>
                        <p class="same-color">
                            View All:
                            <strong>{{ isset($product->product_category->id) ? \App\Category::getCategoryPathById($product->product_category->id)  : '' }}</strong>
                            <br/>
                            View All:
                            <strong>{{ $product->brands ? $product->brands->name : 'N/A' }}</strong>
                        </p>
                        <p class="same-color">
                            <strong>Style ID</strong>: {{ $product->sku }}
                            <br/>
                            <strong class="text-danger">{{ $product->is_on_sale ? 'On Sale' : '' }}</strong>
                        </p>
                    </div>
                    <div class="col-md-4">
                        @if(auth()->user()->isReviwerLikeAdmin('final_listing'))
                            <p class="text-right mt-5">
                                <button class="btn btn-xs btn-default edit-product-show" data-id="{{$product->id}}">Toggle Edit</button>
                                @if ($product->status_id == 9)
                                    <button type="button" class="btn btn-xs btn-secondary upload-magento" data-id="{{ $product->id }}" data-type="list">List
                                    </button>
                                @elseif ($product->status_id == 12)
                                    <button type="button" class="btn btn-xs btn-secondary upload-magento" data-id="{{ $product->id }}" data-type="update">Update</button>
                                @endif
                            </p>
                        @endif
                        @php
                            $logScrapers = \App\ScrapedProducts::where('sku', $product->sku)->where('validated', 1)->get();
                        @endphp
                        @if ($logScrapers)
                            <div>
                                <br/>
                                Successfully scraped on the following sites:<br/>
                                <ul>
                                    @foreach($logScrapers as $logScraper)
                                        @if($logScraper->url != "N/A")
                                            <li><a href="<?= $logScraper->url ?>"
                                                   target="_blank"><?= $logScraper->website ?></a>
                                                ( <?= $logScraper->last_inventory_at ?> )
                                            </li>
                                        @else
                                            <li><?= $logScraper->website ?></li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>
            </td>
        </tr>
        <tr id="product_{{ $product->id }}" class="">
            <td> <input type="checkbox" class="affected_checkbox" name="products_to_update[]" data-id="{{$product->id}}"></td>
            @php
                $websiteArraysForProduct = \App\Helpers\ProductHelper::getStoreWebsiteName($product->id);
            @endphp
            <td class="table-hover-cell">
                {{ $product->id }}
                @if($product->croppedImages()->count() == count($websiteArraysForProduct))
                    <span class="badge badge-success" >&nbsp;</span>
                @else
                    <span class="badge badge-warning" >&nbsp;</span>
                @endif
                @if(count($product->more_suppliers()) > 1)
                    <button style="padding:0px;" type="button" class="btn-link"
                    data-id="{{ $product->id }}" data-target="#product_suppliers_{{ $product->id }}"
                    data-toggle="modal">View
                    </button>
                    @endif
                <div>
                @if($product->supplier_link)
                    <a target="_new" title="{{ $product->sku }}" href="{{ $product->supplier_link }}">{{ substr($product->sku, 0, 5) . (strlen($product->sku) > 5 ? '...' : '') }}</a>
                @else 
                    <a title="{{ $product->sku }}" href="javascript:;">{{ substr($product->sku, 0, 5) . (strlen($product->sku) > 5 ? '...' : '') }}</a>
                @endif
                </div>
            </td>
            <td style="word-break: break-all; word-wrap: break-word">
                <button type="button" class="btn-link quick-view_image__"
                        data-id="{{ $product->id }}" data-target="#product_image_{{ $product->id }}"
                        data-toggle="modal">View
                </button>
            </td>

            <td>
                @if($product->brands)
                    <a title="{{ $product->brands->name }}" href="javascript:;">{{ substr($product->brands->name, 0, 5) . (strlen($product->brands->name) > 5 ? '...' : '') }}</a>
                @else
                    N/A
                @endif
            </td>

            <td class="table-hover-cell">
                <?php
                    $cat = [];
                    $catM = $product->categories;
                    if($catM) {
                        $parentM = $catM->parent;
                        $cat[]   = $catM->title;
                        if($parentM) {
                            $gparentM = $parentM->parent;
                            $cat[]    = $parentM->title;
                            if($gparentM) {
                                $cat[] = $gparentM->title;
                            }
                        }
                    }
                ?>
                @if (!$imageCropperRole)
                    <div class="mt-1">
                        <select class="form-control quick-edit-category select-multiple"
                                name="Category" data-placeholder="Category"
                                data-id="{{ $product->id }}">
                            <option></option>
                            @foreach ($category_array as $data)
                                <option value="{{ $data['id'] }}" {{ $product->category == $data['id'] ? 'selected' : '' }} >{{ $data['title'] }}</option>
                                @if(isset($data['child']) && is_array($data['child'])) 
                                    @foreach ($data['child'] as $child)
                                        <option value="{{ $child['id'] }}" {{ $product->category == $child['id'] ? 'selected' : '' }} >&nbsp;{{ $child['title'] }}</option>
                                        @if(isset($child['child']) && is_array($child['child'])) 
                                            @foreach ($child['child'] as $smchild)
                                                <option value="{{ $smchild['id'] }}" {{ $product->category == $smchild['id'] ? 'selected' : '' }} >&nbsp;&nbsp;{{ $smchild['title'] }}</option>
                                            @endforeach
                                        @endif
                                    @endforeach
                                @endif
                            @endforeach
                        </select>
                    </div>
                    @if ( isset($product->log_scraper_vs_ai) && $product->log_scraper_vs_ai->count() > 0 )
                        @foreach ( $product->log_scraper_vs_ai as $resultAi )
                            @php $resultAi = json_decode($resultAi->result_ai); @endphp
                            @if ( !empty($resultAi->category) )
                                <button id="ai-category-{{ $product->id }}" data-id="{{ $product->id }}"
                                        data-category="{{ \App\LogScraperVsAi::getCategoryIdByKeyword( $resultAi->category, $resultAi->gender, null ) }}"
                                        class="btn btn-default btn-sm mt-2 ai-btn-category">{{ ucwords(strtolower($resultAi->category)) }}
                                    (AI)
                                </button>
                            @endif
                        @endforeach
                    @endif
                @else
                @endif
                {{ implode(">",array_reverse($cat)) }}
            </td>
            <td class="table-hover-cell quick-edit-name quick-edit-name-{{ $product->id }}" data-id="{{ $product->id }}">
                @if (!$imageCropperRole)
                    <span class="quick-name">{{ $product->name }}</span>
                    <input name="text" class="form-control quick-edit-name-input hidden" placeholder="Product Name" value="{{ $product->name }}">
                @else
                    <span>{{ $product->name }}</span>
                @endif
            </td>
             <td class="table-hover-cell">
                <div class="quick-edit-description quick-edit-description-{{ $product->id }}" data-id="{{ $product->id }}">
                    @if (!$imageCropperRole)
                        <span class="quick-description">{{ $product->short_description}}</span>
                        <textarea name="description" id="textarea_description_{{ $product->id }}"
                                  class="form-control quick-edit-description-textarea hidden" rows="8"
                                  cols="80">{{ $product->short_description }}</textarea>
                    @else

                        <span class="short-description-container">{{ substr($product->short_description, 0, 100) . (strlen($product->short_description) > 100 ? '...' : '') }}</span>
                        <span class="long-description-container hidden">
                            <span class="description-container">{{ $product->short_description }}</span>
                        </span>

                    @endif
                </div>
                <div>
                    <button style="float:right;padding-right:0px;" type="button" class="btn btn-xs show-description" title="Edit description for specific Website" data-id="{{ $product->id }}" data-target="#description_modal_view_{{ $product->id }}"
                            data-toggle="modal"><i class="fa fa-info-circle"></i></button>
                </div>
            </td>
            <td class="table-hover-cell" data-id="{{ $product->id }}">
                @if (!$imageCropperRole)
                    @php
                        $arrComposition = ['100% Cotton', '100% Leather', '100% Silk', '100% Wool', '100% Polyester', '100% Acetate', '100% Polyamide', 'Cotton', 'Leather', 'Silk', 'Wool', 'Polyester'];
                        if(!in_array($product->composition , $arrComposition)){
                                $arrComposition[] = $product->composition;
                        }
                        $i=1;
                    @endphp
                    <select class="form-control quick-edit-composition-select select-multiple mt-1"
                            data-id="{{ $product->id }}"
                            name="composition" data-placeholder="Composition">
                        <option></option>
                        @foreach ($arrComposition as $compositionValue)
                            <option value="{{ $compositionValue }}" {{ $product->composition == $compositionValue ? 'selected' : '' }}>{{ $compositionValue }}</option>
                        @endforeach
                    </select>
                @else
                    <span class="quick-composition">{{ $product->composition }}</span>
                @endif
            </td>
            <td class="table-hover-cell">
                @if (!$imageCropperRole)
                    <select id="quick-edit-color-{{ $product->id }}"
                            class="form-control quick-edit-color select-multiple" name="color"
                            data-id="{{ $product->id }}">
                        @foreach ($colors as $color)
                            <option value="{{ $color }}" {{ $product->color == $color ? 'selected' : '' }}>{{ $color }}</option>
                        @endforeach
                    </select>
                @else
                    {{ $product->color }}
                @endif
            </td>


            <td class="table-hover-cell">
                @if (!$imageCropperRole)
                    <span class="lmeasurement-container">
                      <input type="text" name="measurement" class="form-control mt-1"
                             value="{{ !empty($product->lmeasurement) ? $product->lmeasurement : '' }}x{{ !empty($product->hmeasurement) ? $product->hmeasurement : ' ' }}x{{ !empty($product->dmeasurement) ? $product->dmeasurement : '' }}">
                    </span>
                @endif
            </td>
            <td>
                @php
                    $size_array = explode(',', $product->size_eu);
                @endphp

                {{ isset($size_array[0]) ? $size_array[0] : '' }} {{ isset($size_array[1]) ? ', '.$size_array[1] :  '' }}
            </td>
            <td class="table-hover-cell quick-edit-price" data-id="{{ $product->id }}">
                @if (!$imageCropperRole)
                    <span class="quick-price">{{ $product->price }}</span>
                    <input type="number" name="price" class="form-control quick-edit-price-input hidden" placeholder="100" value="{{ $product->price }}">
                @else
                    <span>EUR {{ $product->price }}</span>
                @endif
            </td>
            <td class="action">
                @if(auth()->user()->isReviwerLikeAdmin('final_listing'))
                    @if ($product->is_approved == 0)
                        <i style="cursor: pointer;" class="fa fa-check upload-magento" title="Approve" data-id="{{ $product->id }}" data-type="approve" aria-hidden="true"></i>
                    @elseif ($product->is_approved == 1 && $product->isUploaded == 0)
                        <i style="cursor: pointer;" class="fa fa-list upload-magento" title="List" data-id="{{ $product->id }}" data-type="list" aria-hidden="true"></i>
                    @elseif ($product->is_approved == 1 && $product->isUploaded == 1 && $product->isFinal == 0)
                        <i style="cursor: pointer;" class="fa fa-toggle-off upload-magento" title="Enable" data-id="{{ $product->id }}" data-type="enable" aria-hidden="true"></i>
                    @else
                        <i style="cursor: pointer;" class="fa fa-pencil upload-magento" title="Update" data-id="{{ $product->id }}" data-type="update" aria-hidden="true"></i>
                    @endif
                    @if ($product->product_user_id != null)
                        {{ \App\User::find($product->product_user_id)->name }}
                    @endif
                    <i style="cursor: pointer;" class="fa fa-upload upload-single {{$auto_push_product == 0 ? '' : 'hide'}}" data-id="{{ $product->id }}" title="push to magento" aria-hidden="true"></i>
                @else
                    <i style="cursor: pointer;" class="fa fa-toggle-off upload-magento" title="Enable" data-id="{{ $product->id }}" data-type="submit_for_approval" aria-hidden="true"></i>
                @endif
                <i style="cursor: pointer;" class="fa fa-tasks" data-toggle="modal" title="Activity"
                   data-target="#product_activity_{{ $product->id }}" aria-hidden="true"></i>
                <i style="cursor: pointer;" class="fa fa-trash" data-toggle="modal" title="Scrape"
                   data-target="#product_scrape_{{ $product->id }}" aria-hidden="true"></i>

            </td>
            <td>
                <select class="form-control post-remark" id="post_remark_{{$product->id}}"
                        data-id="{{$product->id}}" data-placeholder="Select Remark">
                    <option></option>
                    <option value="Category Incorrect" {{ $product->listing_remark == 'Category Incorrect' ? 'selected' : '' }} >
                        Category Incorrect
                    </option>
                    <option value="Price Not Incorrect" {{ $product->listing_remark == 'Price Not Incorrect' ? 'selected' : '' }} >
                        Price Not Correct
                    </option>
                    <option value="Price Not Found" {{ $product->listing_remark == 'Price Not Found' ? 'selected' : '' }} >
                        Price Not Found
                    </option>
                    <option value="Color Not Found" {{ $product->listing_remark == 'Color Not Found' ? 'selected' : '' }} >
                        Color Not Found
                    </option>
                    <option value="Category Not Found" {{ $product->listing_remark == 'Category Not Found' ? 'selected' : '' }} >
                        Category Not Found
                    </option>
                    <option value="Description Not Found" {{ $product->listing_remark == 'Description Not Found' ? 'selected' : '' }} >
                        Description Not Found
                    </option>
                    <option value="Details Not Found" {{ $product->listing_remark == 'Details Not Found' ? 'selected' : '' }} >
                        Details Not Found
                    </option>
                    <option value="Composition Not Found" {{ $product->listing_remark == 'Composition Not Found' ? 'selected' : '' }} >
                        Composition Not Found
                    </option>
                    <option value="Crop Rejected" {{ $product->listing_remark == 'Crop Rejected' ? 'selected' : '' }} >
                        Crop Rejected
                    </option>
                    <option value="Other">Other</option>
                </select>
            </td>
            <td>
                <select class="form-control select-multiple approved_by" name="approved_by"
                        id="approved_by" data-id="{{ $product->id }}" data-placeholder="Select user">
                    <option></option>
                    @foreach($users as $user)
                        <option value="{{$user->id}}" {{ $product->approved_by == $user->id ? 'selected' : '' }} >{{ $user->name }}</option>
                    @endforeach
                </select>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
<div class="mb-5">
    <button class="btn btn-secondary text-left mass_action delete_checked_products">DELETE</button>
    <button class="btn btn-secondary text-left mass_action approve_checked_products">APPROVE</button>
    <button style="float: right" class="btn btn-secondary text-right">UPLOAD ALL</button>
</div>
<p class="mb-5">&nbsp;</p>
<?php echo $products->appends(request()->except("page"))->links(); ?>
    @foreach ($products as $key => $product)
        <div id="product_activity_{{ $product->id }}" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content ">
                    <div class="modal-header">
                        <h4 class="modal-title">Activity</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-striped table-bordered">
                            <tr>
                                <th>Activity</th>
                                <th>Date</th>
                                <th>User Name</th>
                                <th>Status</th>
                            </tr>
                            <tr>
                                <th>Cropping</th>
                                <td>{{ $product->crop_approved_at ?? 'N/A' }}</td>
                                <td>
                                    {{ $product->cropApprover ? $product->cropApprover->name : 'N/A' }}
                                </td>
                                <td>
                                    <select style="width: 90px !important;" data-id="{{$product->id}}"
                                            class="form-control-sm form-control reject-cropping bg-secondary text-light"
                                            name="reject_cropping"
                                            id="reject_cropping_{{$product->id}}">
                                        <option value="0">Select...</option>
                                        <option value="Images Not Cropped Correctly">Images Not Cropped
                                            Correctly
                                        </option>
                                        <option value="No Images Shown">No Images Shown</option>
                                        <option value="Grid Not Shown">Grid Not Shown</option>
                                        <option value="Blurry Image">Blurry Image</option>
                                        <option value="First Image Not Available">First Image Not
                                            Available
                                        </option>
                                        <option value="Dimension Not Available">Dimension Not
                                            Available
                                        </option>
                                        <option value="Wrong Grid Showing For Category">Wrong Grid
                                            Showing For Category
                                        </option>
                                        <option value="Incorrect Category">Incorrect Category</option>
                                        <option value="Only One Image Available">Only One Image
                                            Available
                                        </option>
                                        <option value="Image incorrect">Image incorrect</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>Sequencing</th>
                                <td>{{ $product->crop_ordered_at ?? 'N/A' }}</td>
                                <td>{{ $product->cropOrderer ? $product->cropOrderer->name : 'N/A' }}</td>
                                <td>
                                    <button style="width: 90px" data-button-type="sequence"
                                            data-id="{{$product->id}}"
                                            class="btn btn-secondary btn-sm reject-sequence">Reject
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <th>Approval</th>
                                <td>{{ $product->listing_approved_at ?? 'N/A' }}</td>
                                <td>{{ $product->approver ? $product->approver->name : 'N/A' }}</td>
                                <td>
                                    <select style="width: 90px !important;" data-id="{{$product->id}}"
                                            class="form-control-sm form-control reject-listing bg-secondary text-light"
                                            name="reject_listing" id="reject_listing_{{$product->id}}">
                                        <option value="0">Select Remark</option>
                                        <option value="Category Incorrect">Category Incorrect</option>
                                        <option value="Price Not Incorrect">Price Not Correct</option>
                                        <option value="Price Not Found">Price Not Found</option>
                                        <option value="Color Not Found">Color Not Found</option>
                                        <option value="Category Not Found">Category Not Found</option>
                                        <option value="Description Not Found">Description Not Found
                                        </option>
                                        <option value="Details Not Found">Details Not Found</option>
                                        <option value="Composition Not Found">Composition Not Found
                                        </option>
                                    </select>
                                </td>
                            </tr>
                            @php
                                // Set opener URL
                                $openerUrl = urlencode((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] .  $_SERVER['REQUEST_URI']);
                            @endphp
                            @if ( isset($product->log_scraper_vs_ai) && $product->log_scraper_vs_ai->count() > 0 )
                                <tr>
                                    <th>AI</th>
                                    <td></td>
                                    <td></td>
                                    <td>
                                        <button style="width: 90px" class="btn btn-secondary btn-sm"
                                                data-toggle="modal" id="linkAiModal{{ $product->id }}"
                                                data-target="#aiModal{{ $product->id }}">AI result
                                        </button>
                                        <div class="modal fade" id="aiModal{{ $product->id }}"
                                             tabindex="-1" role="dialog">
                                            <div class="modal-dialog modal-dialog modal-lg"
                                                 role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">{{ strtoupper($product->name) }}</h4>
                                                        <button type="button" class="close"
                                                                data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <iframe id="aiModalLoad{{ $product->id }}"
                                                                frameborder="0" border="0" width="100%"
                                                                height="800"></iframe>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <script>
                                            $('#linkAiModal{{ $product->id }}').click(function () {
                                                $('#aiModalLoad{{ $product->id }}').attr('src', '/log-scraper-vs-ai/{{ $product->id }}?opener={{ $openerUrl }}');
                                            });
                                        </script>
                                    </td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div id="product_scrape_{{ $product->id }}" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content ">
                    <div class="modal-header">
                        <h4 class="modal-title">Scraped sites</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        @php
                            $logScrapers = \App\ScrapedProducts::where('sku', $product->sku)->where('validated', 1)->get();
                        @endphp
                        @if ($logScrapers)
                            <div>
                                <ul>
                                    @foreach($logScrapers as $logScraper)
                                        @if($logScraper->url != "N/A")
                                            <li><a href="{!! $logScraper->url  !!}"
                                                   target="_blank">{!! $logScraper->website  !!} </a>
                                                ( {!! $logScraper->last_inventory_at  !!} )
                                            </li>
                                        @else
                                            <li>{!! $logScraper->website  !!}</li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div id="product_image_{{ $product->id }}" class="modal fade" role="dialog">
            <div class="modal-dialog modal-lg">
                <!-- Modal content-->
                <div class="modal-content ">
                    <div class="modal-header">
                        <h4 class="modal-title">Images</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                    @php
                        $anyCropExist = \App\SiteCroppedImages::where('product_id', $product->id)->first();
                    @endphp
                    <button type="button" value="reject" id="reject-all-cropping{{$product->id}}" data-product_id="{{$product->id}}" class="btn btn-xs btn-secondary pull-right reject-all-cropping">
                        @if($anyCropExist)
                            Reject All - Re Crop
                        @else 
                            All Rejected - Re Crop
                        @endif
                    </button>
                        @php 
                            $websiteList = $product->getWebsites();
                        @endphp
                        @if(!empty($websiteList))
                            @foreach($websiteList as $index => $site)
                                @php 
                                    $siteCroppedImage = \App\SiteCroppedImages::where('product_id', $product->id)->where('website_id' , $site->id)->first();
                                @endphp
                                <div class="product-slider {{$index == 0 ? 'd-block' : 'd-none'}}">
                                    <p style="text-align:center;">{{$site->title}}</p>
                                    <br>
                                    <div class="row">
                                        <div class="col-md-10">
                                        </div>
                                        <div class="col-md-2">
                                            <div class="d-flex" style="float: right;">
                                                <div class="form-group">
                                                    <button type="button" id="reject-product-cropping{{$site->id}}{{$product->id}}" data-site_id="{{$site->id}}" value="reject" data-product_id="{{$product->id}}" class="btn btn-xs btn-secondary reject-product-cropping">
                                                        @if($siteCroppedImage)
                                                            <span>Reject</span>
                                                        @else 
                                                            <span>Rejected</span>
                                                        @endif
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <div class="row">
                                    <div class="col-md-5">
                                        <?php 
                                            $gridImage = '';
                                            $tag = 'gallery_'.$site->cropper_color;
                                            $testing = false;
                                        ?>
                                        @if ($product->hasMedia($tag))
                                            @foreach($product->getMedia($tag) as $media)
                                                @if(strpos($media->filename, 'CROP') !== false || $testing == 1)
                                                    <?php
                                                    $width = 0;
                                                    $height = 0;
                                                    if (file_exists($media->getAbsolutePath())) {
                                                        list($width, $height) = getimagesize($media->getAbsolutePath());
                                                        $badge = "notify-red-badge";
                                                        if ($width == 1000 && $height == 1000) {
                                                            $badge = "notify-green-badge";
                                                        }
                                                    } else {
                                                        $badge = "notify-red-badge";
                                                    }
                                                    // Get cropping grid image
                                                    $gridImage = \App\Category::getCroppingGridImageByCategoryId($product->category);
                                                    if ($width == 1000 && $height == 1000 || $testing == 1) {
                                                    ?>
                                                    <div class="thumbnail-pic">
                                                        <div class="thumbnail-edit">
                                                            <a class="delete-thumbail-img"
                                                                                        data-product-id="{{ $product->id }}"
                                                                                        data-media-id="{{ $media->id }}"
                                                                                        data-media-type="gallery"
                                                                                        href="javascript:;"><i
                                                                            class="fa fa-trash fa-lg"></i>
                                                            </a>
                                                        </div>
                                                        <span class="notify-badge {{$badge}}">{{ $width."X".$height}}</span>
                                                        <img style="display:block; width: 70px; height: 80px; margin-top: 5px;"
                                                             src="{{ $media->getUrl() }}"
                                                             class="quick-image-container img-responive" alt=""
                                                             data-toggle="tooltip" data-placement="top"
                                                             title="ID: {{ $product->id }}"
                                                             onclick="replaceThumbnail('{{ $product->id }}','{{ $media->getUrl() }}','{{$gridImage}}','{{ $site->id }}')">
                                                    </div>
                                                    <?php } ?>
                                                @endif
                                            @endforeach
                                        @else
                                            <span>Site has not any cropped images Please click on Recrop</span>
                                        @endif
                                    </div>
                                    <div class="col-md-7" id="col-large-image{{ $product->id }}{{$site->id}}">
                                        @if ($product->hasMedia($tag))
                                            @php $siteImage =  $product->getMedia($tag)->first()->getUrl() @endphp
                                            <div onclick="bigImg('{{ $siteImage }}')"
                                                 style=" margin-bottom: 5px; width: 300px;height: 300px; background-image: url('{{ $siteImage }}'); background-size: 300px"
                                                 id="image{{ $product->id }}{{$site->id}}">
                                                <img style="width: 300px;" src="{{ asset('images/'.$gridImage) }}"
                                                     class="quick-image-container img-responive" style="width: 100%;"
                                                     alt="" data-toggle="tooltip" data-placement="top"
                                                     title="ID: {{ $product->id }}" id="image-tag{{ $product->id }}{{ $site->id }}">
                                            </div>
                                            <button onclick="cropImage('{{ $siteImage }}','{{ $product->id }}','{{ $site->id }}')"
                                                    class="btn btn-secondary">Crop Image
                                            </button>
                                            <button onclick="crop('{{ $siteImage }}','{{ $product->id }}','{{ $gridImage }}','{{ $site->id }}')"
                                                    class="btn btn-secondary">Crop
                                            </button>

                                        @endif
                                    </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                        <div class="text-center">
                            <i style="cursor: pointer;" class="fa fa-arrow-left product-slider-arrow-left"></i>
                            <i style="cursor: pointer;" class="fa fa-arrow-right product-slider-arrow"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="product_suppliers_{{ $product->id }}" class="modal fade" role="dialog">
            <div class="modal-dialog modal-lg">
                <!-- Modal content-->
                <div class="modal-content ">
                    <div class="modal-header">
                        <h4 class="modal-title">All Suppliers</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                      <table class="table table-striped table-bordered">
                      <thead>
                        <tr>
                            <th style="width:10%">Name</th>
                            <th style="width:4%">Visit</th>
                        </tr>
                    </thead>
                    <tbody>
                    @php
                            $product = \App\Product::find($product->id);
                            @endphp
                    @foreach($product->more_suppliers() as $index => $supplier)
                        <tr>
                            <td>{{$supplier->name}}</td>
                            <td><a target="_new" href="{{$supplier->link}}">Visit</a> </td>
                        </tr>
                    @endforeach
                    </tbody>
                      </table>
                    </div>
                </div>
            </div>
        </div>
        <div id="description_modal_view_{{ $product->id }}" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content ">
                    <div class="modal-header">
                        <h4 class="modal-title">Description</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <p>
                            <span id="description{{ $product->id }}" class="same-color">{{ ucwords(strtolower(html_entity_decode($product->short_description))) }}</span>
                        </p>
                        <br/>
                        @php
                            $descriptions = \App\ScrapedProducts::select('description','website')->where('sku', $product->sku)->get();
                        @endphp
                        @if ( $descriptions->count() > 0 )
                            @foreach ( $descriptions as $description )
                                @if ( !empty(trim($description->description)) && trim($description->description) != trim($product->short_description) )
                                    <hr/>
                                    <span class="same-color">{{ ucwords(strtolower(html_entity_decode($description->description))) }}</span>
                                    <p>
                                        <button class="btn btn-default btn-sm use-description"
                                                data-id="{{ $product->id }}"
                                                data-description="{{ str_replace('"', "'", html_entity_decode($description->description)) }}">
                                            Use this description ({{ $description->website }})
                                        </button>

                                        <button class="btn btn-default btn-sm set-description-site"
                                                data-id="{{ $product->id }}"
                                                data-description="{{ str_replace('"', "'", html_entity_decode($description->description)) }}">
                                            Set Description
                                        </button>
                                    </p>
                                @endif
                            @endforeach
                            <hr/>
                        @endif
                        <table class="table table-bordered table-striped" style="table-layout:fixed;">
                    <thead>
                    <tr>
                        <th style="width:20%">Website</th>
                        <th style="width:80%">Description</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php
                    $product = \App\Product::find($product->id);
                    $attributes = \App\StoreWebsiteProductAttribute::join('store_websites','store_websites.id','store_website_product_attributes.store_website_id')->where('product_id', $product->id)->select('store_website_product_attributes.description','store_websites.title')->get();
                    @endphp
                    @foreach($attributes as $index => $att)
                        <tr>
                            <td>{{$att->title}}</td>
                            <td>{{$att->description}}</td>
                        </tr>
                    @endforeach
                    
                    </tbody>
                    </table>
                    </div>
                </div>
            </div>
        </div>
@endforeach
