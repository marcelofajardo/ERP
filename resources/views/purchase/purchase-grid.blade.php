@extends('layouts.app')

@section('favicon' , 'purchase-grid.png')

@section('title', 'Purchase Grid - ERP Sololuxury')

@section("styles")
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css">
    <style type="text/css">
        .modal-xl {
            width: 90%;
            max-width: 1200px;
        }

        .select2-container {
            width: 215px !important;
        }
        .dis-none {
            display: none;
        }
        .margin-top-set {
            margin-top: 5px;
        }
    </style>
@endsection


@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <h2 class="page-heading">Purchase {{ $page == 'canceled-refunded' ? 'Canceled / Refunded' : ($page == 'delivered' ? 'Delivered' : ($page == 'ordered' ? 'Ordered' : ( $page == 'non_ordered' ? 'Non Ordered' : ''))) }} Grid <?php echo isset($totalSku) ? "(".$totalSku.")" : ""; ?></h2>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <form action="{{url()->current()}}" method="GET" class="form-inline align-items-start">
                    <div class="form-group mr-3 mb-3">
                        <input name="term" type="text" class="form-control" id="product-search"
                               value="{{ isset($term) ? $term : '' }}"
                               placeholder="name, sku, supplier">
                    </div>
                    <div class="form-group mr-3 mb-3">
                        {!! $categoryFilter !!}
                    </div>
                    @if (!$page || $page=='non_ordered')
                        <div class="form-group mr-3">
                            <select class="form-control select-multiple2" name="status[]" multiple placeholder="Order Status">
                                @foreach ($order_status as $key => $name)
                                    <option value="{{ $key }}" {{ in_array(strtolower($key), array_map("strtolower",request()->get('status', []))) ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div class="form-group mr-3">
                        {!! Form::select('supplier[]', $suppliers_array, (!empty(request()->get('supplier')[0]) ? request()->get('supplier')[0] : ''), ['placeholder' => 'Select a Supplier','class' => 'form-control select-multiple2']) !!}
                    </div>

                    <div class="form-group mr-3">
                        @php $brands = \App\Brand::getAll(); @endphp
                        <select class="form-control select-multiple2" name="brand[]" multiple placeholder="Brands">
                            @foreach ($brands as $key => $name)
                                <option value="{{ $key }}" {{ isset($brand) && $brand == $key ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mr-3">
                        @php $colors = new \App\Colors(); @endphp
                        <select class="form-control select-multiple2" name="color[]" multiple placeholder="Colors...">
                            @foreach ($colors->all() as $key => $col)
                                <option value="{{ $key }}" {{ in_array($key, request()->get('color', [])) ? 'selected' : '' }}>{{ $col }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mr-3">
                        <input name="size" type="text" class="form-control"
                               value="{{ request()->get('size') }}"
                               placeholder="Size">
                    </div>

                    <div class="form-group mr-3">
                        <?php echo Form::select("customer_id", [], request()->get('customer', null), ["class" => "form-control customer-search-box", 'placeholder' => 'Select a Customer', "style" => "width:100%;"]);  ?>
                    </div>

                    <input type="checkbox" name="in_pdf" id="in_pdf"> <label for="in_pdf">Download PDF</label>

                    <button type="submit" class="btn btn-image"><img src="/images/search.png"/></button>

                    <a href="{{url()->current()}}" class="btn btn-image"><img src="/images/clear-filters.png"/></a>
            </div>
            <div class="pull-right">
                <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#emailToAllModal">Bulk Email</button>
            </div>
            </form>
        </div>
    </div>

    @include('partials.flash_messages')
    @include('purchase.partials.modal-emailToAll')

    {{-- {!! $products->appends(Request::except('page'))->links() !!} --}}

    <?php
    $query = http_build_query(Request::except('page'));
    $query = url()->current() . (($query == '') ? $query . '?page=' : '?' . $query . '&page=');
    ?>

    <div class="form-group position-fixed" style="top: 50px; left: 20px;">
        Goto :
        <select onchange="location.href = this.value;" class="form-control" id="page-goto">
            @for($i = 1 ; $i <= $products->lastPage() ; $i++ )
                <option value="{{ $query.$i }}" {{ ($i == $products->currentPage() ? 'selected' : '') }}>{{ $i }}</option>
            @endfor
        </select>
    </div>

    <div class="infinite-scroll">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>Product</th>
                    <th>SKU</th>
                    <th width="366px">Supplier</th>
                    <!-- <th>Suppliers</th> -->
                    <th>Brand</th>
                    <th>Remarks</th>
                </tr>
                </thead>

                <tbody>
                @foreach ($products as $product)
                    @php $custcount = count($product['customers']);
                    @endphp
                    <tr>
                        <td>
                            <a href="{{ route('products.show', $product['id']) }}" target="_blank"><img src="{{ $product['image'] }}" class="img-responsive" style="width: 100px !important" alt=""></a>
                        </td>
                        @php
                            $suppliersArray = [];
                            $data = DB::select('SELECT sp.id,s.website,sp.url,s.supplier FROM `scraped_products` sp 
                            JOIN scrapers sc on sc.scraper_name=sp.website 
                            JOIN suppliers s ON s.id=sc.supplier_id 
                            WHERE last_inventory_at > DATE_SUB(NOW(), INTERVAL sc.inventory_lifetime DAY) and sp.sku = :sku', ['sku' =>$product['sku']]);

                            $cnt = count($data);
                        @endphp
                        @if($cnt > 0)
                            @php
                                $suppliersArray = DB::select('SELECT suppliers.id, supplier, ps.product_id,suppliers.website
                                   FROM suppliers
                                   INNER JOIN product_suppliers as ps on suppliers.id = ps.supplier_id and ps.product_id = :product_id
                                   LEFT JOIN purchase_product_supplier on purchase_product_supplier.supplier_id =suppliers.id and purchase_product_supplier.product_id = ps.product_id', ['product_id' =>$product['id']]);
                            @endphp
                        @endif
                        <td>
                            @if($custcount > 1)
                                <a href="javascript:void(0);" class="expandrow" data-id="{{$product['id']}}">{{$product['sku'] }}</a>
                            @else
                                {{$product['sku'] }}
                            @endif

                            <?php if(!empty($data)) { ?>
	                            <?php foreach($data as $suppliers){ ?>
		                            <a target="_blank" href="<?php echo $suppliers->url; ?>"><?php echo $suppliers->supplier; ?></a>
		                            <br>
	                            <?php } ?>
                            <?php } ?>

                        </td>
                        <td><!-- {{ array_key_exists($product['single_supplier'], $suppliers_array) ? $suppliers_array[$product['single_supplier']] : 'No Supplier' }} -->

                            <?php
                            foreach ($activSuppliers as $value) {
                                $isNeed = true;
                                foreach ($suppliersArray as $v) {
                                    if ($v->id == $value->id) {
                                        $isNeed = false;
                                        break;
                                    }
                                }
                                if ($isNeed) {
                                    $suppliersArray[] = $value;
                                }
                            }
                            ?>

                            <select name="supplier[]" id="supplier_{{$product['id']}}" class="form-control select-multiple2 supplier_msg" multiple data-product-id="{{$product['id']}}" placeholder="supplier">
                                @foreach($suppliersArray as $sup)
                                    <option value="{{$sup->id}}"> {{ $sup->product_id != '' ? '* ' : ''}} {{$sup->supplier}}</option>
                                @endforeach
                            </select>
                            <input type="text" name="message" id="message_{{$product['id']}}" placeholder="whatsapp message..." class="form-control send-message margin-top-set">
                            <?php 
                                $productID = $product['id']; 
                                $productSize = $product['size'];
                            ?>
                            <input type="button" class="btn btn-xs btn-secondary margin-top-set" id="btnmsg_{{$product['id']}}" name="send" value="SendMSG" onclick="sendMSG('{{ $productID }}', '{{ $productSize }}');">
                            <div class="supplier_msg_con" style="margin-top: 10px;">
                                <?php foreach ($product[ 'supplier_msg' ] as $supplier_msg) { ?>
                                <b>{{$supplier_msg['supplier']}}</b>
                                <?php foreach ($supplier_msg[ 'chat_messages' ] as $chat_messages) { ?>
                                <div class="talk-bubble">
                                    <div class="talktext">
                                                <span>
                                                    <p class="collapsible-message">{{$chat_messages['message']}}</p>
                                                </span>
                                        <em>{{$chat_messages['created_at']}}</em>
                                    </div>
                                </div>
                                <?php } ?>
                                <br>
                                <?php } ?>
                                <br>
                            </div>
                        </td>
                        <td>{{ $product['brand'] }}</td>
                        <td>
                            <a href class="add-task" data-toggle="modal" data-target="#addRemarkModal" data-id="{{ $product['id'] }}">Add</a>
                            <span> | </span>
                            <a href class="view-remark" data-toggle="modal" data-target="#viewRemarkModal" data-id="{{ $product['id'] }}">View</a>
                            <span> | </span>
                            <button class="btn btn-image expand-row-btn" data-class="purchase-expand-row-{{$product['id']}}"><img src="/images/forward.png"></button>
                        </td>
                    </tr>
                    <tr id="product_cust_{{$product['id']}}" class="dis-none purchase-expand-row-{{$product['id']}}">
                        <td colspan="6">
                            <table class="table table-bordered" width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Customers</th>
                                    <th>Price In Order</th>
                                    <th>Order Date</th>
                                    <th>Order Advance</th>
                                    <th>Ordered Size</th>
                                    <th>Ordered Status</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($product['order_products'] as $order_product)
                                    @if ( isset($order_product->order) )
                                        <tr>
                                            <td>
                                                @if ( isset($order_product->order->customer) )
                                                    <input type="hidden" class="select-product-dis" name="products[]" value="{{ $product['id'] }}" data-customer="{{ $order_product->order->customer->id }}" data-supplier="{{ $product['single_supplier'] }}"/>
                                                    <input type="hidden" name="customer[]" value="{{ $order_product->order->customer->id }}"/>
                                                    <input type="checkbox" class="select-product" name="purchase_products[]" value="{{ $order_product->id }}#{{ $product['single_supplier'] }}">
                                                @endif
                                            </td>

                                            <td>
                                                @if ( isset($order_product->order->customer) )
                                                    <a href="{{ route('customer.show', $order_product->order->customer->id) }}" target="_blank">{{ $order_product->order->customer->name }}</a>
                                                @endif
                                            </td>
                                            <td>{{ $order_product->product_price }}</td>
                                            <td>
                                                @if ($order_product->order)
                                                    {{ \Carbon\Carbon::parse($order_product->order->order_date)->format('d-m') }}
                                                @else
                                                    No Order
                                                @endif
                                            </td>
                                            <td>
                                                @if ( isset($order_product->order) )
                                                {{ $order_product->order->advance_detail }}</li>
                                                @else
                                                    No Order
                                                @endif
                                            </td>
                                            <td>
                                                {{ $order_product->size }}
                                            </td>
                                            <td>
                                                <?php if(isset($order_product->order) && !empty($order_product->order)) { ?>
                                                <div>
                                                    <span class="order-status change-order-status {{ ($order_product->order->order_status == 'Follow up for advance' || $order_product->order->order_status_id == \App\Helpers\OrderHelper::$followUpForAdvance) ? 'active-bullet-status' : '' }}" title="Follow up for advance" data-id="Follow up for advance" data-orderid="{{ $order_product->order->id }}" style="cursor:pointer; background-color: #666666;"></span>
                                                    <span class="order-status change-order-status {{ ($order_product->order->order_status == 'Advance received' || $order_product->order->order_status_id == \App\Helpers\OrderHelper::$advanceRecieved) ? 'active-bullet-status' : '' }}" title="Advance received" data-id="Advance received" data-orderid="{{ $order_product->order->id }}" style="cursor:pointer; background-color: #4c4c4c;"></span>
                                                    <span class="order-status change-order-status {{ ($order_product->order->order_status == 'Delivered' || $order_product->order->order_status_id == \App\Helpers\OrderHelper::$delivered) ? 'active-bullet-status' : '' }}" title="Delivered" data-id="Delivered" data-orderid="{{ $order_product->order->id }}" style="cursor:pointer; background-color: #323232;"></span>
                                                    <span class="order-status change-order-status {{ ($order_product->order->order_status == 'Cancel' || $order_product->order->order_status_id == \App\Helpers\OrderHelper::$cancel) ? 'active-bullet-status' : '' }}" title="Cancel" data-id="Cancel" data-orderid="{{ $order_product->order->id }}" style="cursor:pointer; background-color: #191919;"></span>
                                                    <span class="order-status change-order-status {{ ($order_product->order->order_status == 'Product shiped to Client' || $order_product->order->order_status_id == \App\Helpers\OrderHelper::$productShippedToClient) ? 'active-bullet-status' : '' }}" title="Product shiped to Client" data-id="Product shiped to Client" data-orderid="{{ $order_product->order->id }}" style="cursor:pointer; background-color: #414a4c;"></span>
                                                    <span class="order-status change-order-status {{ ($order_product->order->order_status == 'Refund to be processed' || $order_product->order->order_status_id == \App\Helpers\OrderHelper::$refundToBeProcessed) ? 'active-bullet-status' : '' }}" title="Refund to be processed" data-id="Refund to be processed" data-orderid="{{ $order_product->order->id }}" style="cursor:pointer; background-color: #CCCCCC;"></span>
                                                    <span class="order-status change-order-status {{ ($order_product->order->order_status == 'Refund Credited' || $order_product->order->order_status_id == \App\Helpers\OrderHelper::$refundCredited) ? 'active-bullet-status' : '' }}" title="Refund Credited" data-id="Refund Credited" data-orderid="{{ $order_product->order->id }}" style="cursor:pointer; background-color: #95a5a6;"></span>
                                                </div>
                                                <?php } ?>
                                            </td>
                                            <td>
                                                @if ( isset($order_product->order->customer) )
                                                    <button type="submit" class="btn btn-secondary alternative_offers" data-brand="{{$product['brand_id']}}" data-category="{{$product['category']}}" data-price="0" data-customer_id="{{$order_product->order->customer->id}}">Alternative Offers</button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <tr class="dis-none purchase-expand-row-{{$product['id']}}">
                        <td colspan="6">
                            <table class="table table-bordered" width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Supplier</th>
                                    <th>Date</th>
                                    <th>EURO Price</th>
                                    <th>Discount</th>
                                    <th>Final Price</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($product['supplier_msg'] as $supplier_id => $supplier_msg)
                                    <tr class="price-row-db" data-inr="{{$product['price_inr'] }}">
                                        <td>
                                            <input type="checkbox" name="supplier_ids[]" value="{{$supplier_id}}">
                                        </td>

                                        <td>
                                            <a href="{{ route('supplier.show', $supplier_id) }}">{{$supplier_msg['supplier']}}</a>
                                        </td>
                                        <td>
                                            {{ isset($supplier_msg['chat_messages'][0]['created_at']) 
                                            ? date('d-m-Y', strtotime($supplier_msg['chat_messages'][0]['created_at']))
                                            : "-"
                                            }}
                                        </td>
                                        <td>
                                            {{$product['price'] }}
                                        </td>
                                        <td>
                                            <input type="text" name="discount" value="" placeholder="Enter discount" class="discount_db form-control col-md-6">
                                        </td>
                                        <td>
                                            {{$product['price_inr'] }}
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        {!! $products->appends(Request::except('page'))->links() !!}
    </div>

    <form action="{{ route('purchase.store') }}" method="POST" class="position-fixed" style="bottom: 20px; left: 50%;">
        @csrf
        <input type="hidden" name="purchase_handler" value="{{ Auth::id() }}"/>
        <input type="hidden" name="supplier_id" value=""/>
        <input type="hidden" name="products" value="">
        <input type="hidden" name="customer" value="">
        <input type="hidden" name="order_products" value="">


        <div class="row">
            <div class="col text-center">
                <button type="submit" class="btn btn-secondary" id="createPurchaseButton">Submit</button>
            </div>
        </div>
    </form>

    {{-- <div class="row mt-6"> --}}
    <div class="purchaseGrid" id="purchaseGrid">

        {{-- @foreach ($products as $supplier => $supplier_products)
          <h4>{{ $supplier }}</h4>
          <div class="row mt-6">
            {{dd($products)}}
            @foreach ($supplier_products as $product)
              <div class="col-md-3 col-xs-6 text-center">
                <img src="{{ $product['image'] }}" class="img-responsive grid-image" alt="" />

                {{dd($product)}}
                <a href="{{ route('products.show', $product['id']) }}" class="btn btn-image"><img src="/images/view.png" /></a>
              </div>
            @endforeach
          </div>
        @endforeach --}}
    </div>



    {{-- <div class="row">
        <div class="col-2">
            <div class="form-group">
                Goto :
                <select onchange="location.href = this.value;" class="form-control">
                    @for($i = 1 ; $i <= $products->lastPage() ; $i++ )
                        <option value="{{ $query.$i }}" {{ ($i == $products->currentPage() ? 'selected' : '') }}>{{ $i }}</option>
                    @endfor
                </select>
            </div>
        </div>
    </div> --}}
    {{-- </div> --}}

    <!-- Modal -->
    <div id="addRemarkModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add New Remark</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>

                </div>
                <div class="modal-body">
                    <form id="add-remark">
                        <input type="hidden" name="id" value="">
                        <textarea rows="1" name="remark" class="form-control"></textarea>
                        <button type="button" class="btn btn-secondary mt-2" id="addRemarkButton">Add Remark</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>



    <!-- Modal -->
    <div id="viewRemarkModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">View Remark</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>

                </div>
                <div class="modal-body">
                    <div id="remark-list">

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>

    <!-- Modal -->
    <div id="alternative_offers" class="modal fade" role="dialog">
        <div class="modal-dialog modal-xl">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Alternative Offers</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>

                </div>
                <div class="modal-body">
                    <div class="row" id="alternative_offers_con">
                        <div class="col-lg-12">
                            <form action="{{ route('attachImages', 'customer') }}" method="GET" class="form-inline align-items-start" id="alternative_offers_search_form">
                                <div class="form-group mr-3 mb-3">
                                    {!! $category_selection !!}
                                </div>
                                <div class="form-group mr-3">
                                    <select class="form-control select-multiple2" name="brand[]" data-placeholder="Select brand.." multiple>
                                        @foreach ($brands as $key => $name)
                                            <option value="{{ $key }}">{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group mr-3">
                                    <select class="form-control select-multiple2" name="supplier[]" data-placeholder="Select Supplier.." multiple>
                                        @foreach ($suppliers as $activSupplier)
                                            @if(isset($activSupplier->id) && isset($activSupplier->supplier))
                                                <option value="{{ $activSupplier->id }}">{{ $activSupplier->supplier }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group mr-3 mb-3">
                                    <input placeholder="Shoe Size" type="text" name="shoe_size" value="{{request()->get('shoe_size')}}" class="form-control-sm form-control" style="width: 100px;">
                                </div>
                                <div class="form-group mr-3">
                                    <strong class="mr-3">Price</strong>
                                    <input type="text" name="price_min" class="form-control" placeholder="min. price" value="{{ isset($_GET['price_min']) ? (int) $_GET['price_min'] : '' }}" style="width: 100px;">
                                    <input type="text" name="price_max" class="form-control" placeholder="max. price" value="{{ isset($_GET['price_max']) ? (int) $_GET['price_max'] : '' }}" style="width: 100px;">
                                </div>
                                <input type="hidden" name="selected_products" value="" id="selected_products">
                                <button type="submit" class="btn btn-image"><img src="/images/filter.png"/></button>
                            </form>
                        </div>
                        <div class="col-lg-6">
                            <form method="POST" id="attachImageForm">
                                @csrf
                                <input type="hidden" name="images" id="images" value="">
                                <input type="hidden" name="image" value="">
                                <input type="hidden" name="screenshot_path" value="">
                                <textarea name="message" placeholder="Message" class="form-control"></textarea>
                                <input type="hidden" name="customer_id" value="" class="customer_id">
                                <input type="hidden" name="status" value="1">
                                <input type="hidden" id="send_pdf" name="send_pdf" value="0"/>
                            </form>
                        </div>
                        <div class="col-lg-6">
                            <button type="button" class="btn btn-secondary select-all-product-btn" data-count="0">Select All</button>
                            <button type="button" class="btn btn-secondary select-all-product-btn" data-count="20">Select 20</button>
                            <button type="button" class="btn btn-secondary select-all-product-btn" data-count="30">Select 30</button>
                            <button type="button" class="btn btn-secondary select-all-product-btn" data-count="50">Select 50</button>
                            <button type="button" class="btn btn-secondary select-all-product-btn" data-count="100">Select 100</button>
                        </div>
                        <div class="col-lg-12">
                            <div class="productGrid" id="productGrid"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>

    <div id="confirmPdf" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <p>Choose the format for sending</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary btn-approve-pdf">PDF</button>
                    <button type="button" class="btn btn-secondary btn-ignore-pdf">Images</button>
                </div>
            </div>
        </div>
    </div>

    {{-- {!! $leads->links() !!} --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jscroll/2.3.7/jquery.jscroll.min.js"></script>
    <script>
        /*$(document).on('change', '.supplier_msg', function(){
            supplier_msg($(this));
        })*/

        var selectAllBtn = $(".select-all-product-btn");
            selectAllBtn.on("click", function (e) {
                var $this = $(this);
                var vcount = 0;

                vcount = $this.data('count');
                if (vcount == 0) {
                    vcount = 'all';
                }
                var productCardCount = $(".product-list-card").length;

                if((vcount == "all" || 1 == 1) && $this.hasClass("has-all-selected") === false && (productCardCount < vcount || vcount == "all") ) {

                    e.preventDefault();

                    $('#selected_products').val(JSON.stringify(image_array));

                    $('#selected_products').val(JSON.stringify(image_array));
                    var formData = $('#searchForm').serializeArray();
                    formData.push({name: "limit", value: vcount}) ;
                    formData.push({name: "page", value: 1}) ;
                    
                    var url = "{{ route('attachImages', 'customer') }}";


                    $.ajax({
                        url: url,
                        data : formData,
                        beforeSend: function() {
                            $('#productGrid').html('<img id="loading-image" src="/images/pre-loader.gif"/>');
                        }
                    }).done(function (data) {
                        //all_product_ids = data.all_product_ids;
                        $('#productGrid').html(data.html);
                        $('#products_count').text(data.products_count);
                        $('.lazy').Lazy({
                            effect: 'fadeIn'
                        });

                        if ($this.hasClass("has-all-selected") === false) {
                            $this.html("Deselect " + vcount);
                            if (vcount == 'all') {
                                $(".select-pr-list-chk").prop("checked", true).trigger('change');
                            } else {
                                var boxes = $(".select-pr-list-chk");
                                for (i = 0; i < vcount; i++) {
                                    try {
                                        $(boxes[i]).prop("checked", true).trigger('change');
                                    } catch (err) {
                                    }
                                }
                            }
                            $this.addClass("has-all-selected");
                        } 
                    }).fail(function () {
                        alert('Error searching for products');
                    });

                }else {
                    if ($this.hasClass("has-all-selected") === false) {
                        $this.html("Deselect " + vcount);
                        if (vcount == 'all') {
                            $(".select-pr-list-chk").prop("checked", true).trigger('change');
                        } else {
                            var boxes = $(".select-pr-list-chk");
                            for (i = 0; i < vcount; i++) {
                                try {
                                    $(boxes[i]).prop("checked", true).trigger('change');
                                } catch (err) {
                                }
                            }
                        }
                        $this.addClass("has-all-selected");
                    }else {
                        $this.html("Select " + vcount);
                        if (vcount == 'all') {
                            $(".select-pr-list-chk").prop("checked", false).trigger('change');
                        } else {
                            var boxes = $(".select-pr-list-chk");
                            for (i = 0; i < vcount; i++) {
                                try {
                                    $(boxes[i]).prop("checked", false).trigger('change');
                                } catch (err) {
                                }
                            }
                        }
                        $this.removeClass("has-all-selected");
                    }
                }
        })

        function supplier_msg(_this) {
            var suppliers = _this.val();
            var product_id = _this.data('product-id');
            $.ajax({
                url: "{{ route('get.msg.supplier') }}",
                data: {'suppliers': suppliers, 'product_id': product_id}
            }).done(function (data) {
                var html = '';
                $.each(data, function (key, value) {
                    html += '<b>' + value.supplier + '</b>';
                    $.each(value.chat_messages, function (key, value) {
                        html += '<div class="talk-bubble">'
                        html += '   <div class="talktext">'
                        html += '       <span>'
                        html += '           <p class="collapsible-message">' + value.message + '</p>'
                        html += '       </span>'
                        html += '       <em>' + value.created_at + ' </em>'
                        html += '   </div>'
                        html += '</div>'
                    });
                    html += '<br>';
                });
                _this.closest('td').find('.supplier_msg_con').html(html);
            }).fail(function () {
                _this.closest('td').find('.supplier_msg_con').html('');
            });

            /*'
                '*/
        }

        $(document).ready(function () {
            $(".select-multiple2").each(function () {
                $(this).select2({
                    placeholder: $(this).attr('placeholder')
                });
            })
        });

        $('#alternative_offers').on("scroll", function () {
            $('.lazy').Lazy({
                effect: 'fadeIn'
            });
        });

        $(".alternative_offers").click(function () {
            $('#alternative_offers_search_form').find("select[name='category[]']").val($(this).data('category')).trigger('change');
            ;
            $('#alternative_offers_search_form').find("select[name='brand[]']").val($(this).data('brand')).trigger('change');
            ;
            $('#alternative_offers_search_form').find("input[name='price_min']").val($(this).data('price'));
            $('#attachImageForm').find(".customer_id").val($(this).data('customer_id'));
            $.ajax({
                url: "{{ route('attachImages', 'customer') }}",
                data: $('#alternative_offers_search_form').serialize()
            }).done(function (data) {
                all_product_ids = data.all_product_ids;
                $('#productGrid').html(data.html);
                $(".page-goto").remove();
                $('.lazy').Lazy({
                    effect: 'fadeIn'
                });
                $('#alternative_offers').modal('show');
            }).fail(function () {
                alert('Error searching for products');
            });
        });
        var image_array = [];

        function unique(list) {
            var result = [];
            $.each(list, function (i, e) {
                if ($.inArray(e, result) == -1) result.push(e);
            });
            return result;
        }

        $("#alternative_offers_con").on('change', '.select-pr-list-chk', function (e) {
            var $this = $(this);
            var productCard = $this.closest(".product-list-card").find(".attach-photo-all");
            if (productCard.length > 0) {
                var image = productCard.data("image");
                if ($this.is(":checked") === true) {
                    Object.keys(image).forEach(function (index) {
                        image_array.push(image[index]);
                    });

                    image_array = unique(image_array);

                } else {
                    Object.keys(image).forEach(function (key) {
                        var index = image_array.indexOf(image[key]);
                        image_array.splice(index, 1);
                    });
                    image_array = unique(image_array);
                }
            }
        });

        $("#alternative_offers_con").on('click', '.pagination a', function (e) {
            e.preventDefault();
            var url = $(this).attr('href') + '&selected_products=' + JSON.stringify(image_array);

            getProducts(url);
        });

        function getProducts(url) {
            $.ajax({
                url: url
            }).done(function (data) {
                $('#productGrid').html(data.html);
                $('.lazy').Lazy({
                    effect: 'fadeIn'
                });
            }).fail(function () {
                alert('Error loading more products');
            });
        }

        $("#alternative_offers_con").on('click', '.attach-photo', function (e) {
            e.preventDefault();
            var image = $(this).data('image');

            if ($(this).data('attached') == 0) {
                $(this).data('attached', 1);
                image_array.push(image);
            } else {
                var index = image_array.indexOf(image);

                $(this).data('attached', 0);
                image_array.splice(index, 1);
            }

            $(this).toggleClass('btn-success');
            $(this).toggleClass('btn-secondary');

            console.log(image_array);
        });

        $("#alternative_offers_con").on('click', '.attach-photo-all', function (e) {
            e.preventDefault();
            var image = $(this).data('image');

            if ($(this).data('attached') == 0) {
                $(this).data('attached', 1);

                Object.keys(image).forEach(function (index) {
                    image_array.push(image[index]);
                });
            } else {
                Object.keys(image).forEach(function (key) {
                    var index = image_array.indexOf(image[key]);

                    image_array.splice(index, 1);
                });

                $(this).data('attached', 0);
            }

            $(this).toggleClass('btn-success');
            $(this).toggleClass('btn-secondary');

            console.log(image_array);
        });

        $('#alternative_offers_search_form').on('submit', function (e) {
            e.preventDefault();

            $('#selected_products').val(JSON.stringify(image_array));

            var url = "{{ route('attachImages', 'customer') }}";
            var formData = $('#alternative_offers_search_form').serialize();

            $.ajax({
                url: url,
                data: formData
            }).done(function (data) {
                $('#productGrid').html(data.html);
                $(".page-goto").remove();
                $('.lazy').Lazy({
                    effect: 'fadeIn'
                });
            }).fail(function () {
                alert('Error searching for products');
            });
        });

        $(document).on('click', '#sendImageMessage', function (e) {
            e.preventDefault();
            if (image_array.length == 0) {
                alert('Please select some images');
            } else {
                $('#images').val(JSON.stringify(image_array));
                 $("#confirmPdf").modal("show");
            }
        });

        $(".btn-approve-pdf").on("click",function() {
            $("#send_pdf").val("1");
            $.ajax({
                method: 'post',
                url: "{{ route('whatsapp.send', 'customer') }}",
                data: $('#attachImageForm').serialize()
            }).done(function (data) {
                alert('You have successfully send message!');
                $('#alternative_offers').modal('hide');
                $("#confirmPdf").modal("hide");
            }).fail(function () {
                alert('Error searching for products');
            });
        });

        $(".btn-ignore-pdf").on("click",function() {
            $("#send_pdf").val("0");
            $.ajax({
                method: 'post',
                url: "{{ route('whatsapp.send', 'customer') }}",
                data: $('#attachImageForm').serialize()
            }).done(function (data) {
                alert('You have successfully send message!');
                $('#alternative_offers').modal('hide');
                $("#confirmPdf").modal("hide");
            }).fail(function () {
                alert('Error searching for products');
            });
        });

        // Array.prototype.groupBy = function (prop) {
        //     return this.reduce(function (groups, item) {
        //         const val = item[prop]
        //         groups[val] = groups[val] || []
        //         groups[val].push(item)
        //         return groups
        //     }, {})
        // };
        //
        // var suppliers_array = {!! json_encode($suppliers_array) !!};
        // const products = [
        //         @foreach ($products as $product)
        //
        //     {
        //         'id': '{{ $product['id'] }}',
        //         'sku': '{{ $product['sku'] }}',
        //         'supplier': '{{ $product['supplier'] }}',
        //         'suppliers' : "{{ $product['supplier_list'] }}",
        //         'single_supplier': "{{ $product['single_supplier'] }}",
        //         'image': '{{ $product['image']}}',
        //         'link': '{{ route('products.show', $product['id']) }}',
        //         'customer_id': '{{ $product['customer_id'] != 'No Customer' && $product['customer_id'] != 'No Order' ? route('customer.post.show', $product['customer_id']) : '#noCustomer' }}',
        //         'customer_names': '{{ $product['customer_names'] }}',
        //         'order_price': '{{ $product['order_price'] }}',
        //         'order_date': '{{ $product['order_date'] }}'
        //
        //     },
        //     @endforeach
        // ];
        //
        // const groupedByTime = products.groupBy('single_supplier');
        //
        // jQuery(document).ready(function () {
        //
        //     Object.keys(groupedByTime).forEach(function (key) {
        //
        //         let html = '<form action="{{ route('purchase.store') }}" method="POST"><input type="hidden" name="_token" value="{{ csrf_token() }}" /><input type="hidden" name="purchase_handler" value="{{ Auth::id() }}" /><input type="hidden" name="supplier_id" value="' + key + '" />';
        //             html += '<div class="supplier-wrapper"><div class="form-check pull-right"><input type="checkbox" class="select-all" id="' + key.replace(/[^a-zA-Z0-9]/g, '-') + '"><label class="form-check-label" for="' + key.replace(/[^a-zA-Z0-9]/g, '-') + '">Select All</label></div><h4>' + suppliers_array[key] + '</h4></div><div class="row">';
        //
        //         groupedByTime[key].forEach(function (product) {
        //
        //             html += `
        //                 <div class="col-md-3 col-xs-6 text-center">
        //                   <a href="` + product['customer_id'] + `">
        //                     <img src="` + product['image'] + `" class="img-responsive grid-image" alt="" data-toggle="tooltip" data-html="true" data-placement="top" title="<strong>Name: </strong>` + product['customer_names'] + `<br><strong>Price in Order: </strong>` + product['order_price'] + `<br><strong>Order Date: </strong>` + moment(product['order_date']).format('DD-MM') + `<br><strong>Supplier: </strong>` + product['supplier'] + `<br><strong>Suppliers: </strong>` + product['suppliers'] + `<br><strong>Sku: </strong>` + product['sku'] + `" />
        //                                     <input type="checkbox" class="` + key.replace(/[^a-zA-Z0-9]/g, '-') + `" name="products[]" value="` + product['id'] + `">
        //                                     <a href="` + product['link'] + `" class="btn btn-image"><img src="/images/view.png" /></a>
        //                                      {{--<p>Status : `+ ( ( product['isApproved'] ===  '1' ) ?
        //                                                             'Approved' : ( product['isApproved'] ===  '-1' ) ? 'Rejected' : 'Nil') +`</p>--}}
        //                     {{--@can('supervisor-edit')
        //                         <button data-id="`+product['id']+`"
        //                                 class="btn btn-approve btn-secondary `+ ( ( product['isApproved'] ===  '1' ) ? 'btn-success' : '' ) +` ">
        //                                 `+ ( ( product['isApproved'] ===  '1' ) ? 'Approved' : 'Approve' ) +`
        //                         </button>
        //                     @endcan--}}
        //
        //                 </a></div>
        //             `;
        //         });
        //
        //         jQuery('#purchaseGrid').append(html + '</div><div class="row"><div class="col text-center"><button type="submit" class="btn btn-secondary">Submit</button></div></div></form>');
        //     });
        //
        // });

        $(document).ready(function () {
            $("body").tooltip({selector: '[data-toggle=tooltip]'});
        });

        $(document).on('click', '.select-all', function () {
            var id = $(this).attr('id');

            if ($(this).is(':checked')) {
                $('.' + id).prop('checked', true);
            } else {
                $('.' + id).prop('checked', false);
            }
        });

        var selected_products = [];
        var selected_customer = [];
        var selected_order_products = [];

        $(document).on('click', '.select-product', function () {
            var checked = $(this).prop('checked');
            if (checked) {
                selected_order_products.push($(this).val());
            } else {
                var index = selected_order_products.indexOf($(this).val());
                selected_order_products.splice(index, 1);
            }
        });

        $(document).on('click', '.select-product', function () {
            var supplier_id = $(this).data('supplier');
            var customer_id = $(this).data('customer');
            $('input[name="supplier_id"]').val(supplier_id);

            var checked = $(this).prop('checked');

            if (checked) {
                selected_products.push($(this).val());
                selected_customer.push(customer_id);
            } else {
                var index = selected_products.indexOf($(this).val());

                selected_products.splice(index, 1);

                var index2 = selected_customer.indexOf(customer_id);

                selected_customer.splice(index2, 1);
            }

            console.log(selected_products);
            console.log(selected_customer);
        });

        $(window).scroll(function () {
            var next_page = $('.pagination li.active + li a');
            if (next_page && next_page.attr('href')) {
                var page_number = next_page.attr('href').split('?page=');

                var current_page = page_number[1] - 1;
                $('#page-goto option[value="' + page_number[0] + '?page=' + current_page + '"]').attr('selected', 'selected');
            }
        });

        $(document).ready(function () {
            $('ul.pagination').hide();
            $(function () {
                $('.infinite-scroll').jscroll({
                    autoTrigger: true,
                    loadingHtml: '<img class="center-block" src="/images/loading.gif" alt="Loading..." />',
                    padding: 2500,
                    nextSelector: '.pagination li.active + li a',
                    contentSelector: 'div.infinite-scroll',
                    callback: function () {
                        // $('ul.pagination').remove();
                        $(".select-multiple2").each(function () {
                            $(this).select2({
                                placeholder: $(this).attr('placeholder')
                            });
                        })
                    }
                });
            });
        });

        $(document).on('click', '.add-task', function (e) {
            e.preventDefault();
            var id = $(this).data('id');
            $('#add-remark input[name="id"]').val(id);
        });

        $('#addRemarkButton').on('click', function () {
            var id = $('#add-remark input[name="id"]').val();
            var remark = $('#add-remark textarea[name="remark"]').val();

            $.ajax({
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('task.addRemark') }}',
                data: {
                    id: id,
                    remark: remark,
                    module_type: 'purchase-grid'
                },
            }).done(response => {
                alert('Remark Added Success!')
                window.location.reload();
            }).fail(function (response) {
                console.log(response);
            });
        });


        $(document).on('click', ".view-remark", function () {
            var id = $(this).attr('data-id');

            $.ajax({
                type: 'GET',
                headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('task.gettaskremark') }}',
                data: {
                    id: id,
                    module_type: "purchase-grid"
                },
            }).done(response => {
                var html = '';

                $.each(response, function (index, value) {
                    html += ' <p> ' + value.remark + ' <br> <small>By ' + value.user_name + ' updated on ' + moment(value.created_at).format('DD-M H:mm') + ' </small></p>';
                    html + "<hr>";
                });
                $("#viewRemarkModal").find('#remark-list').html(html);
            });
        });

        $('#createPurchaseButton').on('click', function (e) {
            e.preventDefault();
            if (selected_order_products.length > 0) {
                $(this).closest('form').find('input[name="order_products"]').val(JSON.stringify(selected_order_products));
                /*$(this).closest('form').find('input[name="products"]').val(JSON.stringify(selected_products));
                $(this).closest('form').find('input[name="customer"]').val(JSON.stringify(selected_customer));*/
                $(this).closest('form').submit();
            } else {
                alert('Please select atleast one product');
            }
        });

        function sendMSG(id, size) {
            var supplier_id = $('#supplier_' + id).val();

            supplier_id = JSON.stringify(supplier_id);

            var message = $('#message_' + id).val();
            if (supplier_id == '') {

                $('#supplier_' + id).css('border', '1px solid red')
                return false;
            }
            if (message == '') {

                $('#message_' + id).css('border', '1px solid red')
                return false;
            }
            $('#btnmsg_' + id).val('Sending...');
            $.ajax({
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('purchase.sendmsgsupplier') }}',
                data: {
                    id: id,
                    message: message,
                    supplier_id: supplier_id,
                    size: size
                }
            }).done(response => {
                $('#btnmsg_' + id).val('SendSMG');
                $('#btnmsg_' + id).removeClass('btn-secondary');
                $('#btnmsg_' + id).addClass('btn-success');
                supplier_msg($('#supplier_' + id));
                setTimeout(function () {
                    $('#btnmsg_' + id).addClass('btn-secondary');
                    $('#btnmsg_' + id).removeClass('btn-success');
                    $('#message_' + id).val('');
                }, 2000);

            }).fail(function (response) {
                $('#btnmsg_' + id).val('SendSMG');
                console.log(response);
            });

        }


        $(document).on('click', '.add-cc', function (e) {
            e.preventDefault();

            if ($('#cc-label').is(':hidden')) {
                $('#cc-label').fadeIn();
            }

            var el = `<div class="row cc-input">
            <div class="col-md-10">
                <input type="text" name="cc[]" class="form-control mb-3">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-image cc-delete-button"><img src="/images/delete.png"></button>
            </div>
        </div>`;

            $('#cc-list').append(el);
        });

        $(document).on('click', '.cc-delete-button', function (e) {
            e.preventDefault();
            var parent = $(this).parent().parent();

            parent.hide(300, function () {
                parent.remove();
                var n = 0;

                $('.cc-input').each(function () {
                    n++;
                });

                if (n == 0) {
                    $('#cc-label').fadeOut();
                }
            });
        });

        // bcc

        $(document).on('click', '.add-bcc', function (e) {
            e.preventDefault();

            if ($('#bcc-label').is(':hidden')) {
                $('#bcc-label').fadeIn();
            }

            var el = `<div class="row bcc-input">
            <div class="col-md-10">
                <input type="text" name="bcc[]" class="form-control mb-3">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-image bcc-delete-button"><img src="/images/delete.png"></button>
            </div>
        </div>`;

            $('#bcc-list').append(el);
        });

        $(document).on('click', '.bcc-delete-button', function (e) {
            e.preventDefault();
            var parent = $(this).parent().parent();

            parent.hide(300, function () {
                parent.remove();
                var n = 0;

                $('.bcc-input').each(function () {
                    n++;
                });

                if (n == 0) {
                    $('#bcc-label').fadeOut();
                }
            });
        });

        $(document).on('click', '.change-order-status', function () {
            let orderId = $(this).attr('data-orderid');
            let status = $(this).attr('title');

            let url = '/order/' + orderId + '/changestatus';

            let thiss = $(this);

            $.ajax({
                url: url,
                type: 'post',
                data: {
                    _token: "{{ csrf_token() }}",
                    status: status
                },
                success: function () {
                    toastr['success']('Status changed successfully!', 'Success');
                    $(thiss).siblings('.change-order-status').removeClass('active-bullet-status');
                    $(thiss).addClass('active-bullet-status');
                    if (status == 'Product shiped to Client') {
                        $('#tracking-wrapper-' + id).css({'display': 'block'});
                    }
                }
            });
        });

        var customerSearch = function () {
            $(".customer-search-box").select2({
                tags: true,
                ajax: {
                    url: '/erp-leads/customer-search',
                    dataType: 'json',
                    delay: 750,
                    data: function (params) {
                        return {
                            q: params.term, // search term
                        };
                    },
                    processResults: function (data, params) {

                        params.page = params.page || 1;

                        return {
                            results: data,
                            pagination: {
                                more: (params.page * 30) < data.total_count
                            }
                        };
                    },
                },
                placeholder: 'Search for Customer by id, Name, No',
                escapeMarkup: function (markup) {
                    return markup;
                },
                minimumInputLength: 2,
                templateResult: formatCustomer,
                templateSelection: (customer) => customer.text || customer.name,

            });
        };

        function formatCustomer(customer) {
            if (customer.loading) {
                return customer.name;
            }

            if (customer.name) {
                return "<p> <b>Id:</b> " + customer.id + (customer.name ? " <b>Name:</b> " + customer.name : "") + (customer.phone ? " <b>Phone:</b> " + customer.phone : "") + "</p>";
            }
        }

        customerSearch();

        $(document).on("keyup",".discount_db",function(){
            var discount = $(this).val();
            if(discount < 0 || discount > 100) {
                alert("Please enter a valid discount number");
            }

            var inrPrice = $(this).closest(".price-row-db").data("inr");
                if(typeof inrPrice != "undefined" && discount > 0) {
                    var discountedPrice = (inrPrice * discount) / 100;
                    var retailPrice = inrPrice - discountedPrice;   
                        
                        if(retailPrice > 0) {
                            var discountedPrice = (retailPrice * 22) / 100;
                            var retailPrice = retailPrice - discountedPrice;
                            $(this).closest(".price-row-db").find("td").last().html(retailPrice);   
                        }          
                }
        });

        $(document).on("click",".expand-row-btn",function() {
            var className = $(this).data("class");
            $("."+className).toggleClass("dis-none");
        });

    </script>

@endsection
