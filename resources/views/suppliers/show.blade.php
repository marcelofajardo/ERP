@extends('layouts.app')

@section('title', 'Supplier Page')

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.5/css/bootstrap-select.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.min.css">

    <style>
        #chat-history {
            background-color: #EEEEEE;
            height: 450px;
            overflow-y: scroll;
            overflow-x: hidden;
            width: 100%;
        }

        .speech-wrapper .bubble.alt {
            margin: 0 0 25px 20% !important;
        }

        .show-images-wrapper {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
        }

        .label-attached-img {
            border: 1px solid #fff;
            display: block;
            position: relative;
            cursor: pointer;
        }

        .label-attached-img:before {
            background-color: white;
            color: white;
            content: " ";
            display: block;
            border-radius: 50%;
            border: 1px solid grey;
            position: absolute;
            top: -5px;
            left: -5px;
            width: 25px;
            height: 25px;
            text-align: center;
            line-height: 28px;
            transition-duration: 0.4s;
            transform: scale(0);
        }

        :checked + .label-attached-img {
            border-color: #ddd;
        }

        :checked + .label-attached-img:before {
            content: "✓";
            background-color: grey;
            transform: scale(1);
        }

        :checked + .label-attached-img img {
            transform: scale(0.9);
            box-shadow: 0 0 5px #333;
            z-index: -1;
        }

    </style>
@endsection

@section('content')


    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h3>Supplier Page</h3>
            </div>
            <div class="pull-right mt-4">
                <a class="btn btn-xs btn-secondary" href="{{ route('supplier.index') }}">Back</a>
                {{-- <a class="btn btn-xs btn-secondary" href="#" id="quick_add_lead">+ Lead</a>
                <a class="btn btn-xs btn-secondary" href="#" id="quick_add_order">+ Order</a>
                <button type="button" class="btn btn-xs btn-secondary" data-toggle="modal" data-target="#privateViewingModal">Set Up for Private Viewing</button> --}}
            </div>
        </div>
    </div>

    {{-- @include('customers.partials.modal-private-viewing') --}}

    @include('partials.flash_messages')

    <div id="exTab2" class="container">
        <ul class="nav nav-tabs">
            <li class="active">
                <a href="#info-tab" data-toggle="tab">Supplier Info</a>
            </li>
            <li>
                <a href="#agents-tab" data-toggle="tab">Agents</a>
            </li>
            <li>
                <a href="#email-tab" data-toggle="tab" data-supplierid="{{ $supplier->id }}" data-type="inbox">Emails</a>
            </li>
            <li>
                <a href="#brands-tab" data-toggle="tab" data-supplierid="{{ $supplier->id }}" data-type="inbox">Brands</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-xs-12 col-md-4 border">
            <div class="tab-content">
                <div class="tab-pane mt-3" id="brands-tab">
                    <h2 class="page-heading">Brands</h2>
                    @if(strlen($supplier->brands) > 4)
                        @php
                            $dns = $supplier->brands;
                            $dns = str_replace('"[', '', $dns);
                            $dns = str_replace(']"', '', $dns);
                            $dns = explode(',', $dns);
                        @endphp

                        @foreach($dns as $dn)
                            <li>{{ $dn }}</li>
                        @endforeach
                    @else
                        N/A
                    @endif
                </div>
                <div class="tab-pane active mt-3" id="info-tab">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="form-group form-inline">
                                <input type="text" name="supplier" id="supplier_supplier" class="form-control input-sm" placeholder="Supplier" value="{{ $supplier->supplier }}">

                                @if ($supplier->is_flagged == 1)
                                    <button type="button" class="btn btn-image flag-supplier" data-id="{{ $supplier->id }}"><img src="/images/flagged.png"/></button>
                                @else
                                    <button type="button" class="btn btn-image flag-supplier" data-id="{{ $supplier->id }}"><img src="/images/unflagged.png"/></button>
                                @endif
                            </div>

                            <div class="form-group form-inline">
                                <input type="number" id="supplier_phone" name="phone" class="form-control input-sm" placeholder="910000000000" value="{{ $supplier->phone }}">
                            </div>

                            <div class="form-group">
                                <select class="form-control input-sm" name="default_phone" id="supplier_default_phone">
                                    <option value="">Select Default Phone</option>
                                    @if ($supplier->phone != '')
                                        <option value="{{ $supplier->phone }}" {{ $supplier->phone == $supplier->default_phone ? 'selected' : '' }}>{{ $supplier->phone }} - Supplier's Phone</option>
                                    @endif

                                    @if ($supplier->agents)
                                        @foreach ($supplier->agents as $agent)
                                            <option value="{{ $agent->phone }}" {{ $agent->phone == $supplier->default_phone ? 'selected' : '' }}>{{ $agent->phone }} - {{ $agent->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                        <!-- <div class="form-group">
              <select class="form-control form-control-sm" name="status" id="status">
                <option {{ !$supplier->status ? 'selected' : '' }} value="0">Inactive</option>
                <option {{ $supplier->status ? 'selected' : '' }} value="1">Active</option>
              </select>
            </div> -->

                            {{-- <div class="form-group">
                              <input type="number" id="supplier_whatsapp_number" name="whatsapp_number" class="form-control input-sm" placeholder="Whatsapp Number" value="{{ $supplier->whatsapp_number }}">
                            </div> --}}

                            <div class="form-group">
                                <textarea name="address" id="supplier_address" class="form-control input-sm" rows="3" cols="80" placeholder="Address">{{ $supplier->address }}</textarea>
                            </div>

                            {{-- @if (Auth::user()->hasRole('Admin') || Auth::user()->hasRole('HOD of CRM')) --}}
                            <div class="form-group">
                                <input type="email" name="email" id="supplier_email" class="form-control input-sm" placeholder="Email" value="{{ $supplier->email }}">
                            </div>

                            {{-- <div class="form-group">
                              <select class="form-control input-sm" name="default_email" id="supplier_default_email">
                                <option value="">Select Default Email</option>
                                @if ($supplier->email != '')
                                  <option value="{{ $supplier->email }}" {{ $supplier->email == $supplier->default_email ? 'selected' : '' }}>{{ $supplier->email }} - Supplier's Email</option>
                                @endif

                                @if ($supplier->agents)
                                  @foreach ($supplier->agents as $agent)
                                    <option value="{{ $agent->email }}" {{ $agent->email == $supplier->default_email ? 'selected' : '' }}>{{ $agent->email }} - {{ $agent->name }}</option>
                                  @endforeach
                                @endif
                              </select>
                            </div> --}}

                            <div class="form-group">
                                <input type="text" name="instagram_handle" id="supplier_instagram_handle" class="form-control input-sm" placeholder="Instagram Handle" value="{{ $supplier->instagram_handle }}">
                            </div>

                            <div class="form-group">
                                <input type="text" name="social_handle" id="supplier_social_handle" class="form-control input-sm" placeholder="Social Handle" value="{{ $supplier->social_handle }}">
                            </div>


                            <div class="form-group">
                              <label>Update By</label>
                                <p>@if(isset($user)) {{ $user->name }} @endif</p>
                            </div>



                            <div class="form-group">
                                    <select class="form-control change-whatsapp-no" data-supplier-id="<?php echo $supplier->id; ?>">
                                        <option value="">-No Selected-</option>
                                        @foreach(array_filter(config("apiwha.instances")) as $number => $apwCate)
                                            @if($number != "0")
                                                <option {{ ($number == $supplier->whatsapp_number && $supplier->whatsapp_number != '') ? "selected='selected'" : "" }} value="{{ $number }}">{{ $number }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                            </div>


                            <div class="form-group">
                                <input type="text" name="website" id="supplier_website" class="form-control input-sm" placeholder="Website" value="{{ $supplier->website }}">
                            </div>

                            <div class="form-group">
                                <input type="text" name="gst" id="supplier_gst" class="form-control input-sm" placeholder="GST" value="{{ $supplier->gst }}">
                            </div>
                            <div class="form-group">
                                {!!Form::select('supplier_category_id', [null=>'Select a category'] + $suppliercategory->toArray(), $supplier->supplier_category_id, ['class' => 'form-control form-control-sm' , 'id' => 'supplier_category_id'])!!}
                            </div>
                            <div class="form-group">
                                {!!Form::select('supplier_status_id', $supplierstatus, $supplier->supplier_status_id, ['class' => 'form-control form-control-sm', 'id' => 'supplier_status_id'])!!}
                            </div>

                            <div class="form-group">
                                @php

                                    $scrapersList = [];
                                    if(!$supplier->scrapers->isEmpty()) {
                                        foreach($supplier->scrapers as $ssc) {
                                            $scrapersList[] = $ssc->scraper_name;
                                        }
                                    }

                                    $scrapersName = implode(",",$scrapersList);
                                @endphp

                                <input type="text" name="scraper_name" id="supplier_scraper_name" class="form-control input-sm" placeholder="Scraper Name" value="{{ $scrapersName }}">
                                
                                {{-- <input type="text" name="scraper_name" id="supplier_scraper_name" class="form-control input-sm" placeholder="Scraper Name" value="{{ $scrapers[0]->scraper_name }}"> --}}

                            </div>

                            <div class="form-group">
                                <input type="text" name="inventory_lifetime" id="supplier_inventory_lifetime" class="form-control input-sm" placeholder="Inventory Lifetime (in days)" value="{{ ($supplier->scraper) ? $supplier->scraper->inventory_lifetime : '' }}">
                            </div>

                            <div class="form-group">
                                <button type="button" id="updateSupplierButton" class="btn btn-xs btn-secondary">Save</button>
                            </div>
                        </div>

                    </div>
                </div>

                @include('suppliers.partials.agent-modals')

                <div class="tab-pane mt-3" id="agents-tab">
                    <button type="button" class="btn btn-xs btn-secondary mb-3 create-agent" data-toggle="modal" data-target="#createAgentModal" data-id="{{ $supplier->id }}">Add Agent</button>

                    <div id="agentAccordion">
                        @foreach ($supplier->agents as $key => $agent)
                            <div class="card">
                                <div class="card-header" id="headingAgent{{ $key + 1 }}">
                                    <h5 class="mb-0">
                                        <button class="btn btn-link collapsed collapse-fix" data-toggle="collapse" data-target="#agent{{ $key + 1 }}" aria-expanded="false" aria-controls="agent{{ $key + 1 }}">
                                            {{ $key + 1 }} {{ $agent->name }}
                                        </button>
                                    </h5>
                                </div>
                                <div id="agent{{ $key + 1 }}" class="collapse collapse-element" aria-labelledby="headingAgent{{ $key + 1 }}" data-parent="#agentAccordion">
                                    <div class="card-body">
                                        <form action="{{ route('agent.update', $agent->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')

                                            <input type="hidden" name="model_id" value="{{ $supplier->id }}">
                                            <input type="hidden" name="model_type" value="App\Supplier">

                                            <div class="form-group">
                                                <strong>Name</strong>
                                                <input type="text" name="name" class="form-control input-sm" value="{{ $agent->name }}">
                                            </div>

                                            <div class="form-group">
                                                <strong>Phone:</strong>
                                                <input type="number" name="phone" class="form-control input-sm" value="{{ $agent->phone }}">

                                                @if ($errors->has('phone'))
                                                    <div class="alert alert-danger">{{$errors->first('phone')}}</div>
                                                @endif
                                            </div>

                                            <div class="form-group">
                                                <strong>Address:</strong>
                                                <input type="text" name="address" class="form-control input-sm" value="{{ $agent->address }}">

                                                @if ($errors->has('address'))
                                                    <div class="alert alert-danger">{{$errors->first('address')}}</div>
                                                @endif
                                            </div>

                                            <div class="form-group">
                                                <strong>Email:</strong>
                                                <input type="email" name="email" class="form-control input-sm" value="{{ $agent->email }}">

                                                @if ($errors->has('email'))
                                                    <div class="alert alert-danger">{{$errors->first('email')}}</div>
                                                @endif
                                            </div>

                                            <div class="form-group text-center">
                                                <button type="submit" class="btn btn-xs btn-secondary">Update</button>
                                            </div>
                                        </form>

                                        <form action="{{ route('agent.destroy', $agent->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="btn btn-image"><img src="/images/delete.png"/></button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="tab-pane mt-3" id="email-tab">
                    <div id="exTab3" class="mb-3">
                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a href="#email-inbox" data-toggle="tab" id="email-inbox-tab" data-supplierid="{{ $supplier->id }}" data-type="inbox">Inbox</a>
                            </li>
                            <li>
                                <a href="#email-sent" data-toggle="tab" id="email-sent-tab" data-supplierid="{{ $supplier->id }}" data-type="sent">Sent</a>
                            </li>
                            <li class="nav-item ml-auto">
                                <button type="button" class="btn btn-image" data-toggle="modal" data-target="#emailSendModal"><img src="{{ asset('images/filled-sent.png') }}"/></button>
                            </li>
                        </ul>
                    </div>

                    <div id="email-container">
                        @include('purchase.partials.email')
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-md-4 mb-3">
            <div class="border">
                <form action="{{ route('whatsapp.send', 'supplier') }}" method="POST" enctype="multipart/form-data">
                    <div class="d-flex">
                        @csrf

                        <div class="form-group">
                            <div class="upload-btn-wrapper btn-group pr-0 d-flex">
                                <button class="btn btn-image px-1"><img src="/images/upload.png"/></button>
                                <input type="file" name="image"/>

                                <button type="submit" class="btn btn-image px-1 send-communication received-customer"><img src="/images/filled-sent.png"/></button>
                            </div>
                        </div>

                        <div class="form-group flex-fill mr-3">
                            <button type="button" id="supplierMessageButton" class="btn btn-image"><img src="/images/support.png"/></button>
                            <textarea class="form-control mb-3 hidden" style="height: 110px;" name="body" placeholder="Received from Supplier"></textarea>
                            <input type="hidden" name="status" value="0"/>
                        </div>
                    </div>

                </form>

                <form action="{{ route('whatsapp.send', 'supplier') }}" method="POST" enctype="multipart/form-data">
                    <div id="paste-container" style="width: 200px;">

                    </div>

                    <div class="d-flex">
                        @csrf

                        <div class="form-group">
                            <div class=" d-flex flex-column">
                                <div class="">
                                    <div class="upload-btn-wrapper btn-group px-0">
                                        <button class="btn btn-image px-1"><img src="/images/upload.png"/></button>
                                        <input type="file" name="image"/>

                                    </div>
                                    <button type="submit" class="btn btn-image px-1 send-communication"><img src="/images/filled-sent.png"/></button>

                                </div>
                            </div>
                        </div>

                        <div class="form-group flex-fill mr-3">
                            <textarea id="message-body" class="form-control mb-3" style="height: 110px;" name="body" placeholder="Send for approval"></textarea>

                            <input type="hidden" name="screenshot_path" value="" id="screenshot_path"/>
                            <input type="hidden" name="status" value="1"/>

                            <div class="paste-container"></div>


                        </div>
                    </div>

                    <div class="pb-4 mt-3">
                        <div class="row">
                            <div class="col">
                                <select name="quickCategory" id="quickCategory" class="form-control input-sm mb-3">
                                    <option value="">Select Category</option>
                                    @foreach($reply_categories as $category)
                                        <option value="{{ $category->approval_leads }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>

                                <select name="quickComment" id="quickComment" class="form-control input-sm">
                                    <option value="">Quick Reply</option>
                                </select>
                            </div>
                            <div class="col">
                                <button type="button" class="btn btn-xs btn-secondary" data-toggle="modal" data-target="#ReplyModal" id="approval_reply">Create Quick Reply</button>
                            </div>
                        </div>
                    </div>

                </form>
                <div class="row">
                    <div class="col">
                        <select name="autoTranslate" id="autoTranslate" class="form-control input-sm mb-3">
                            <option value="">Translations Languages</option>
                            <option value="fr" {{ $supplier->language === 'fr'  ? 'selected' : '' }}>French</option>
                            <option value="de" {{ $supplier->language === 'de'  ? 'selected' : '' }}>German</option>
                            <option value="it" {{ $supplier->language === 'it'  ? 'selected' : '' }}>Italian</option>
                        </select>
                    </div>
                    <div class="col">
                        <button type="button" class="btn btn-xs btn-secondary" id="auto-translate">Add translation language</button>
                    </div>
                </div>
            </div>
            <div id="notes" class="mt-3">
                <div class="panel-group">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" href="#collapse1">Remarks ({{ is_array($supplier->notes) ? count($supplier->notes) : 0 }})</a>
                            </h4>
                        </div>
                        <div id="collapse1" class="panel-collapse collapse">
                            <div class="panel-body" id="note_list">
                                @if($supplier->notes && is_array($supplier->notes))
                                    @foreach($supplier->notes as $note)
                                        <li>{{ $note }}</li>
                                    @endforeach
                                @endif
                            </div>
                            <div class="panel-footer">
                                <input name="add_new_remark" id="add_new_remark" type="text" placeholder="Type new remark..." class="form-control add-new-remark">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="excel" class="mt-3">
                <div class="panel-group">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" href="#collapse-excel">Excel Importer </a>
                            </h4>
                        </div>
                        <div id="collapse-excel" class="panel-collapse collapse">

                            <div class="panel-footer">
                                <form action="/supplier/excel-import" method="POST" enctype="multipart/form-data">
                                    @csrf
                                <input name="excel_file" type="file" class="form-control">
                                <input type="hidden" name="id" value="{{ $supplier->id }}">
                                <button type="submit" class="btn btn-secondary">Submit</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-md-4">
            <div class="border">
                {{-- <h4>Messages</h4> --}}

                <div class="row">
                    <form action="{{ route('supplier.image') }}" method="post" enctype="multipart/form-data" style="width: 100%">
                        @csrf
                        <button type="buttin" class="btn btn-xs btn-secondary" value="1" name="type" id="createProduct">Create Product</button>
                        <button type="button" class="btn btn-xs btn-secondary" value="2" name="type" id="createGroup">Create Product Group</button>
                        <button type="button" class="btn btn-xs btn-secondary" value="3" name="type" id="createInStockProduct">Create InStock Product</button>
                        <a type="button" class="btn btn-xs btn-image load-communication-modal" data-is_admin="{{ Auth::user()->hasRole('Admin') }}" data-is_hod_crm="{{ Auth::user()->hasRole('HOD of CRM') }}" data-object="supplier" data-id="{{$supplier->id}}" data-load-type="text" data-all="1" title="Load messages"><img src="/images/chat.png" alt=""></a>
                        <a type="button" class="btn btn-xs btn-image load-communication-modal" data-is_admin="{{ Auth::user()->hasRole('Admin') }}" data-is_hod_crm="{{ Auth::user()->hasRole('HOD of CRM') }}" data-object="supplier" data-id="{{$supplier->id}}" data-attached="1" data-load-type="images" data-all="1" title="Load Auto Images attacheds"><img src="/images/archive.png" alt=""></a>
                        <a type="button" class="btn btn-xs btn-image load-communication-modal" data-is_admin="{{ Auth::user()->hasRole('Admin') }}" data-is_hod_crm="{{ Auth::user()->hasRole('HOD of CRM') }}" data-object="supplier" data-id="{{$supplier->id}}" data-attached="1" data-load-type="pdf" data-all="1" title="Load PDF"><img src="/images/icon-pdf.svg" alt=""></a>
                        <input type="text" name="search_chat_pop"  class="form-control search_chat_pop" placeholder="Search Message">
                        <div class="load-communication-modal chat-history-load-communication-modal" data-is_admin="{{ Auth::user()->hasRole('Admin') }}" data-is_hod_crm="{{ Auth::user()->hasRole('HOD of CRM') }}"  style="display: none;" data-object="supplier" data-attached="1" data-id="{{ $supplier->id }}"></div>
                        <div class="col-12" id="chat-history">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @include('suppliers.partials.modal-email')

    @include('customers.partials.modal-reply')

    @include('suppliers.partials.modal-create-group')

    @include('suppliers.partials.instock-product')


    <div class="row mt-5">
        <div class="col-xs-12">

            {{-- @include('customers.partials.modal-instruction') --}}

            <div class="table-responsive">
                <table class="table table-sm table-bordered m-0">
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Purchase NO</th>
                        <th>Shipping Cost</th>
                        <th>Final Price</th>
                        <th>Delivery</th>
                        <th>Products</th>
                        <th>Retail</th>
                        <th>Discounted Price</th>
                    </tr>
                    @foreach ($supplier->purchases()->orderBy('created_at', 'DESC')->limit(3)->get() as $key => $purchase)
                        @php
                            $products_count = 1;
                            if ($purchase->products) {
                              $products_count = count($purchase->products) + 1;
                            }
                        @endphp
                        <tr>
                            <td rowspan="{{ $products_count }}">{{ $key + 1 }}</td>
                            <td rowspan="{{ $products_count }}">{{ \Carbon\Carbon::parse($supplier->created_at)->format('H:i d-m') }}</td>
                            <td rowspan="{{ $products_count }}"><a href="{{ route('purchase.show', $purchase->id) }}">{{ $purchase->id }}</a></td>
                            <td rowspan="{{ $products_count }}">{{ $purchase->shipment_cost }}</td>
                            <td rowspan="{{ $products_count }}">
                                @php
                                    $total_purchase_price = 0;
                                    if ($purchase->products) {
                                      foreach ($purchase->products as $product) {
                                        $total_purchase_price += $product->price_special;
                                      }
                                    }
                                @endphp

                                {{ $total_purchase_price + $purchase->shipment_cost }}
                            </td>
                            <td rowspan="{{ $products_count }}">{{ $purchase->shipment_status }}</td>
                        </tr>

                        @if ($purchase->products)
                            @foreach ($purchase->products as $product)
                                <tr>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->price_inr }}</td>
                                    <td>{{ $product->price_special }}</td>
                                </tr>
                            @endforeach
                        @endif
                    @endforeach
                </table>
            </div>

            <div id="supplierAccordion">
                <div class="card mb-5">
                    <div class="card-header" id="headingSupplier">
                        <h5 class="mb-0">
                            <button class="btn btn-link collapsed collapse-fix" data-toggle="collapse" data-target="#supplierAcc" aria-expanded="false" aria-controls="">
                                Rest of Purchases
                            </button>
                        </h5>
                    </div>
                    <div id="supplierAcc" class="collapse collapse-element" aria-labelledby="headingSupplier" data-parent="#supplierAccordion">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tbody>
                                    @foreach ($supplier->purchases()->orderBy('created_at', 'DESC')->offset(3)->limit(100)->get() as $key => $purchase)
                                        @php
                                            $products_count = 1;
                                            if ($purchase->products) {
                                              $products_count = count($purchase->products) + 1;
                                            }
                                        @endphp
                                        <tr>
                                            <td rowspan="{{ $products_count }}">{{ $key + 1 }}</td>
                                            <td rowspan="{{ $products_count }}">{{ \Carbon\Carbon::parse($supplier->created_at)->format('H:i d-m') }}</td>
                                            <td rowspan="{{ $products_count }}"><a href="{{ route('purchase.show', $purchase->id) }}">{{ $purchase->id }}</a></td>
                                            <td rowspan="{{ $products_count }}">{{ $purchase->shipment_cost }}</td>
                                            <td rowspan="{{ $products_count }}">
                                                @php
                                                    $total_purchase_price = 0;
                                                    if ($purchase->products) {
                                                      foreach ($purchase->products as $product) {
                                                        $total_purchase_price += $product->price_special;
                                                      }
                                                    }
                                                @endphp

                                                {{ $total_purchase_price + $purchase->shipment_cost }}
                                            </td>
                                            <td rowspan="{{ $products_count }}">{{ $purchase->shipment_status }}</td>
                                        </tr>

                                        @if ($purchase->products)
                                            @foreach ($purchase->products as $product)
                                                <tr>
                                                    <td>{{ $product->name }}</td>
                                                    <td>{{ $product->price_inr }}</td>
                                                    <td>{{ $product->price_special }}</td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @include('customers.partials.modal-remark')

        </div>
    </div>

    <div id="chat-list-history" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Communication</h4>&nbsp;&nbsp;
                    <input type="text" name="search_chat_pop"  class="form-control search_chat_pop" placeholder="Search Message" style="width: 50%;">&nbsp;&nbsp;
                    <!-- <input type="text" name="search_chat_pop_time"  class="form-control search_chat_pop_time" placeholder="Search Time" style="width: 200px;"> -->
          <input style="min-width: 30px;" placeholder="Search by date" value="" type="text" class="form-control search_chat_pop_time" name="search_chat_pop_time">
                    
                </div>
                <div class="modal-body" style="background-color: #999999;">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>



    <form action="" method="POST" id="product-remove-form">
        @csrf
    </form>

    {{-- @include('customers.partials.modal-forward') --}}

@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.5/js/bootstrap-select.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.2.0/socket.io.js"></script>

    <script type="text/javascript">

        $(document).ready(function () {
            //$('.chat-history-load-communication-modal').trigger('click');
        });

        $(document).on('keyup', '.add-new-remark', function (event) {
            let note = $(this).val();
            let self = this;
            if (event.which != 13) {
                return;
            }
            $.ajax({
                url: "{{ action('SupplierController@addNote', $supplier->id) }}",
                data: {
                    note: note,
                    _token: "{{csrf_token()}}"
                },
                type: 'post',
                success: function () {
                    toastr['success']('Remark added successfully', 'success');
                    $(self).removeAttr('disabled');
                    $(self).val('');
                    $('#note_list').append('<li>' + note + '</li>');
                },
                beforeSend: function () {
                    $(self).attr('disabled', true);
                },
                error: function () {
                    $(self).removeAttr('disabled');
                }
            });
        });

        $('#date, #report-completion-datetime').datetimepicker({
            format: 'YYYY-MM-DD HH:mm'
        });

        $(document).ready(function () {
            $(".select-multiple").multiselect();
        });

        $('.change_status').on('change', function () {
            var thiss = $(this);
            var token = "{{ csrf_token() }}";
            var status = $(this).val();


            if ($(this).hasClass('order_status')) {
                var id = $(this).data('orderid');
                var url = '/order/' + id + '/changestatus';
            } else {
                var id = $(this).data('leadid');
                var url = '/leads/' + id + '/changestatus';
            }

            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    _token: token,
                    status: status
                }
            }).done(function (response) {
                if ($(thiss).hasClass('order_status') && status == 'Product shiped to Client') {
                    $('#tracking-wrapper-' + id).css({'display': 'block'});
                }

                $(thiss).siblings('.change_status_message').fadeIn(400);

                setTimeout(function () {
                    $(thiss).siblings('.change_status_message').fadeOut(400);
                }, 2000);
            }).fail(function (errObj) {
                alert("Could not change status");
            });
        });

        $('#whatsapp_change').on('change', function () {
            var thiss = $(this);
            var token = "{{ csrf_token() }}";
            var number = $(this).val();
            var supplier_id = {{ $supplier->id }};
            var url = "{{ url('customer') }}/" + customer_id + "/updateNumber";

            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    _token: token,
                    whatsapp_number: number
                }
            }).done(function (response) {
                $(thiss).siblings('.change_status_message').fadeIn(400);

                setTimeout(function () {
                    $(thiss).siblings('.change_status_message').fadeOut(400);
                }, 2000);
            }).fail(function (response) {
                alert("Could not change status");
            });
        });

        $(document).on('click', ".collapsible-message", function () {
            var selection = window.getSelection();
            if (selection.toString().length === 0) {
                var short_message = $(this).data('messageshort');
                var message = $(this).data('message');
                var status = $(this).data('expanded');

                if (status == false) {
                    $(this).addClass('expanded');
                    $(this).html(message);
                    $(this).data('expanded', true);
                    // $(this).siblings('.thumbnail-wrapper').remove();
                    $(this).closest('.talktext').find('.message-img').removeClass('thumbnail-200');
                    $(this).closest('.talktext').find('.message-img').parent().css('width', 'auto');
                } else {
                    $(this).removeClass('expanded');
                    $(this).html(short_message);
                    $(this).data('expanded', false);
                    $(this).closest('.talktext').find('.message-img').addClass('thumbnail-200');
                    $(this).closest('.talktext').find('.message-img').parent().css('width', '200px');
                }
            }
        });

        $(document).ready(function () {
            var container = $("div#message-container");
            // var sendBtn = $("#waMessageSend");
            var supplierId = "{{ $supplier->id }}";
            var addElapse = false;

            $(document).on('click', '.send-communication', function (e) {
                e.preventDefault();

                var thiss = $(this);
                var url = $(this).closest('form').attr('action');
                var token = "{{ csrf_token() }}";
                var file = $($(this).closest('form').find('input[type="file"]'))[0].files[0];
                var status = $(this).closest('form').find('input[name="status"]').val();
                var screenshot_path = $('#screenshot_path').val();
                var supplier_id = {{ $supplier->id }};
                var formData = new FormData();

                formData.append("_token", token);
                formData.append("image", file);
                formData.append("message", $(this).closest('form').find('textarea').val());
                // formData.append("moduletype", $(this).closest('form').find('input[name="moduletype"]').val());
                formData.append("supplier_id", supplier_id);
                formData.append("assigned_to", $(this).closest('form').find('select[name="assigned_to"]').val());
                formData.append("status", status);
                formData.append("screenshot_path", screenshot_path);

                if ($(this).closest('form')[0].checkValidity()) {
                    $.ajax({
                        type: 'POST',
                        url: url,
                        data: formData,
                        processData: false,
                        contentType: false
                    }).done(function (response) {
                        console.log(response);
                        pollMessages();
                        $(thiss).closest('form').find('textarea').val('');
                        $('#paste-container').empty();
                        $('#screenshot_path').val('');
                        $(thiss).closest('form').find('.dropify-clear').click();

                        if ($(thiss).hasClass('received-customer')) {
                            $(thiss).closest('form').find('#supplierMessageButton').removeClass('hidden');
                            $(thiss).closest('form').find('textarea').addClass('hidden');
                        }
                    }).fail(function (response) {
                        console.log(response);
                        alert('Error sending a message');
                    });
                } else {
                    $(this).closest('form')[0].reportValidity();
                }

            });

        });

        $(document).on('click', '.change_message_status', function (e) {
            e.preventDefault();
            var url = $(this).data('url');
            var token = "{{ csrf_token() }}";
            var thiss = $(this);

            if ($(this).hasClass('wa_send_message')) {
                var message_id = $(this).data('messageid');
                var message = $('#message_body_' + message_id).find('p').data('message').toString().trim();

                $.ajax({
                    url: "{{ url('whatsapp/updateAndCreate') }}",
                    type: 'POST',
                    data: {
                        _token: token,
                        moduletype: "customer",
                        message_id: message_id
                    },
                    beforeSend: function () {
                        $(thiss).text('Loading');
                    }
                }).done(function (response) {
                }).fail(function (errObj) {
                    console.log(errObj);
                    alert("Could not create whatsapp message");
                });
            }
            $.ajax({
                url: url,
                type: 'GET'
            }).done(function (response) {
                $(thiss).remove();
            }).fail(function (errObj) {
                alert("Could not change status");
            });


        });

        $(document).on('click', '.edit-message', function (e) {
            e.preventDefault();
            var thiss = $(this);
            var message_id = $(this).data('messageid');

            $('#message_body_' + message_id).css({'display': 'none'});
            $('#edit-message-textarea' + message_id).css({'display': 'block'});

            $('#edit-message-textarea' + message_id).keypress(function (e) {
                var key = e.which;

                if (key == 13) {
                    e.preventDefault();
                    var token = "{{ csrf_token() }}";
                    var url = "{{ url('message') }}/" + message_id;
                    var message = $('#edit-message-textarea' + message_id).val();

                    if ($(thiss).hasClass('whatsapp-message')) {
                        var type = 'whatsapp';
                    } else {
                        var type = 'message';
                    }

                    $.ajax({
                        type: 'POST',
                        url: url,
                        data: {
                            _token: token,
                            body: message,
                            type: type
                        },
                        success: function (data) {
                            $('#edit-message-textarea' + message_id).css({'display': 'none'});
                            $('#message_body_' + message_id).text(message);
                            $('#message_body_' + message_id).css({'display': 'block'});
                        }
                    });
                }
            });
        });

        $(document).on('click', '.thumbnail-delete', function (event) {
            event.preventDefault();
            var thiss = $(this);
            var image_id = $(this).data('image');
            var message_id = $(this).closest('.talk-bubble').find('.collapsible-message').data('messageid');
            // var message = $(this).closest('.talk-bubble').find('.collapsible-message').data('message');
            var token = "{{ csrf_token() }}";
            var url = "{{ url('message') }}/" + message_id + '/removeImage';
            var type = 'message';

            if ($(this).hasClass('whatsapp-image')) {
                type = "whatsapp";
            }

            // var image_container = '<div class="thumbnail-wrapper"><img src="' + image + '" class="message-img thumbnail-200" /><span class="thumbnail-delete" data-image="' + image + '">x</span></div>';
            // var new_message = message.replace(image_container, '');

            // if (new_message.indexOf('message-img') != -1) {
            //   var short_new_message = new_message.substr(0, new_message.indexOf('<div class="thumbnail-wrapper">')).length > 150 ? (new_message.substr(0, 147)) : new_message;
            // } else {
            //   var short_new_message = new_message.length > 150 ? new_message.substr(0, 147) + '...' : new_message;
            // }

            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    _token: token,
                    image_id: image_id,
                    message_id: message_id,
                    type: type
                },
                success: function (data) {
                    $(thiss).parent().remove();
                    // $('#message_body_' + message_id).children('.collapsible-message').data('messageshort', short_new_message);
                    // $('#message_body_' + message_id).children('.collapsible-message').data('message', new_message);
                }
            });
        });

        $(document).ready(function () {
            $("body").tooltip({selector: '[data-toggle=tooltip]'});
        });

        $('#approval_reply').on('click', function () {
            $('#model_field').val('Approval Lead');
        });

        $('#internal_reply').on('click', function () {
            $('#model_field').val('Internal Lead');
        });

        $('#approvalReplyForm').on('submit', function (e) {
            e.preventDefault();

            var url = "{{ route('reply.store') }}";
            var reply = $('#reply_field').val();
            var category_id = $('#category_id_field').val();
            var model = $('#model_field').val();

            $.ajax({
                type: 'POST',
                url: url,
                headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    reply: reply,
                    category_id: category_id,
                    model: model
                },
                success: function (reply) {
                    // $('#ReplyModal').modal('hide');
                    $('#reply_field').val('');
                    if (model == 'Approval Lead') {
                        $('#quickComment').append($('<option>', {
                            value: reply,
                            text: reply
                        }));
                    } else {
                        $('#quickCommentInternal').append($('<option>', {
                            value: reply,
                            text: reply
                        }));
                    }

                }
            });
        });

        $('#quick_add_lead').on('click', function (e) {
            e.preventDefault();

            var thiss = $(this);
            var token = "{{ csrf_token() }}";
            var url = "{{ route('leads.store') }}";
            var customer_id = {{ $supplier->id }};
            var created_at = moment().format('YYYY-MM-DD HH:mm');

            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    _token: token,
                    customer_id: customer_id,
                    rating: 1,
                    status: 3,
                    assigned_user: 6,
                    created_at: created_at
                },
                beforeSend: function () {
                    $(thiss).text('Creating...');
                },
                success: function () {
                    location.reload();
                }
            }).fail(function (error) {
                console.log(error);
                alert('There was an error creating a lead');
            });
        });

        $(document).on('click', '.forward-btn', function () {
            var id = $(this).data('id');
            $('#forward_message_id').val(id);
        });

        $(document).on('click', '.complete-call', function (e) {
            e.preventDefault();

            var thiss = $(this);
            var token = "{{ csrf_token() }}";
            var url = "{{ route('instruction.complete') }}";
            var id = $(this).data('id');
            var assigned_from = $(this).data('assignedfrom');
            var current_user = {{ Auth::id() }};

            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    _token: token,
                    id: id
                },
                beforeSend: function () {
                    $(thiss).text('Loading');
                }
            }).done(function (response) {
                // $(thiss).parent().html(moment(response.time).format('DD-MM HH:mm'));
                $(thiss).parent().html('Completed');


            }).fail(function (errObj) {
                console.log(errObj);
                alert("Could not mark as completed");
            });
        });

        $(document).on('click', '.pending-call', function (e) {
            e.preventDefault();

            var thiss = $(this);
            var token = "{{ csrf_token() }}";
            var url = "{{ route('instruction.pending') }}";
            var id = $(this).data('id');

            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    _token: token,
                    id: id
                },
                beforeSend: function () {
                    $(thiss).text('Loading');
                }
            }).done(function (response) {
                $(thiss).parent().html('Pending');
                $(thiss).remove();
            }).fail(function (errObj) {
                console.log(errObj);
                alert("Could not mark as completed");
            });
        });

        $('#quickCategory').on('change', function () {
            var replies = JSON.parse($(this).val());
            $('#quickComment').empty();

            $('#quickComment').append($('<option>', {
                value: '',
                text: 'Quick Reply'
            }));

            replies.forEach(function (reply) {
                $('#quickComment').append($('<option>', {
                    value: reply.reply,
                    text: reply.reply
                }));
            });
        });

        $('#quickCategoryInternal').on('change', function () {
            var replies = JSON.parse($(this).val());
            $('#quickCommentInternal').empty();

            $('#quickCommentInternal').append($('<option>', {
                value: '',
                text: 'Quick Reply'
            }));

            replies.forEach(function (reply) {
                $('#quickCommentInternal').append($('<option>', {
                    value: reply.reply,
                    text: reply.reply
                }));
            });
        });

        $(document).on('click', '.collapse-fix', function () {
            if (!$(this).hasClass('collapsed')) {
                var target = $(this).data('target');
                var all = $('.collapse-element').not($(target));

                Array.from(all).forEach(function (element) {
                    $(element).removeClass('in');
                });
            }
        });

        $('.add-task').on('click', function (e) {
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
                    module_type: 'instruction'
                },
            }).done(response => {
                alert('Remark Added Success!')
                window.location.reload();
            }).fail(function (response) {
                console.log(response);
            });
        });


        $(".view-remark").click(function () {
            var id = $(this).attr('data-id');

            $.ajax({
                type: 'GET',
                headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('task.gettaskremark') }}',
                data: {
                    id: id,
                    module_type: "instruction"
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

        $(document).on('click', '.track-shipment-button', function () {
            var thiss = $(this);
            var order_id = $(this).data('id');
            var awb = $('#awb_field_' + order_id).val();

            $.ajax({
                type: "POST",
                url: "{{ route('stock.track.package') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    awb: awb
                },
                beforeSend: function () {
                    $(thiss).text('Tracking...');
                }
            }).done(function (response) {
                $(thiss).text('Track');

                $('#tracking-container-' + order_id).html(response);
            }).fail(function (response) {
                $(thiss).text('Tracking...');
                alert('Could not track this package');
                console.log(response);
            });
        });

        $(document).on('click', '.verify-btn', function (e) {
            e.preventDefault();

            var thiss = $(this);
            var id = $(this).data('id');

            $.ajax({
                type: "POST",
                url: "{{ route('instruction.verify') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id
                },
                beforeSend: function () {
                    $(thiss).text('Verifying...');
                }
            }).done(function (response) {
                // $(thiss).parent().html('<span class="badge">Verified</span>');
                var current_user = {{ Auth::id() }};

                $(thiss).closest('tr').remove();

                // var row = '<tr><td></td><td></td><td></td><td>' + response.instruction + '</td><td>' + moment(response.completed_at).format('DD-MM HH:mm') + '</td><td>Completed</td><td>' + verify_button + '</td><td></td><td></td></tr>';
                // console.log(row);
                //
                // $('#5 tbody').append($(row));
                window.location.reload();
            }).fail(function (response) {
                $(thiss).text('Verify');
                console.log(response);
                alert('Could not verify the instruction!');
            });
        });

        $('#createInstructionReplyButton').on('click', function (e) {
            e.preventDefault();

            var url = "{{ route('reply.store') }}";
            var reply = $('#instruction_reply_field').val();

            $.ajax({
                type: 'POST',
                url: url,
                headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    reply: reply,
                    category_id: 1,
                    model: 'Instruction'
                },
                success: function (reply) {
                    $('#instruction_reply_field').val('');
                    $('#instructionComment').append($('<option>', {
                        value: reply,
                        text: reply
                    }));
                }
            });
        });

        // if ($(this).is(":focus")) {
        // Created by STRd6
        // MIT License
        // jquery.paste_image_reader.js
        (function ($) {
            var defaults;
            $.event.fix = (function (originalFix) {
                return function (event) {
                    event = originalFix.apply(this, arguments);
                    if (event.type.indexOf('copy') === 0 || event.type.indexOf('paste') === 0) {
                        event.clipboardData = event.originalEvent.clipboardData;
                    }
                    return event;
                };
            })($.event.fix);
            defaults = {
                callback: $.noop,
                matchType: /image.*/
            };
            return $.fn.pasteImageReader = function (options) {
                if (typeof options === "function") {
                    options = {
                        callback: options
                    };
                }
                options = $.extend({}, defaults, options);
                return this.each(function () {
                    var $this, element;
                    element = this;
                    $this = $(this);
                    return $this.bind('paste', function (event) {
                        var clipboardData, found;
                        found = false;
                        clipboardData = event.clipboardData;
                        return Array.prototype.forEach.call(clipboardData.types, function (type, i) {
                            var file, reader;
                            if (found) {
                                return;
                            }
                            if (type.match(options.matchType) || clipboardData.items[i].type.match(options.matchType)) {
                                file = clipboardData.items[i].getAsFile();
                                reader = new FileReader();
                                reader.onload = function (evt) {
                                    return options.callback.call(element, {
                                        dataURL: evt.target.result,
                                        event: evt,
                                        file: file,
                                        name: file.name
                                    });
                                };
                                reader.readAsDataURL(file);
                                return found = true;
                            }
                        });
                    });
                });
            };
        })(jQuery);

        var dataURL, filename;
        $("html").pasteImageReader(function (results) {
            console.log(results);

            // $('#message-body').on('focus', function() {
            filename = results.filename, dataURL = results.dataURL;

            var img = $('<div class="image-wrapper position-relative"><img src="' + dataURL + '" class="img-responsive" /><button type="button" class="btn btn-xs btn-secondary remove-screenshot">x</button></div>');

            $('#paste-container').empty();
            $('#paste-container').append(img);
            $('#screenshot_path').val(dataURL);
            // });

        });

        $(document).on('click', '.remove-screenshot', function () {
            $(this).closest('.image-wrapper').remove();
            $('#screenshot_path').val('');
        });
        // }


        $(document).on('click', '.change-history-toggle', function () {
            $(this).siblings('.change-history-container').toggleClass('hidden');
        });

        $('#instructionCreateButton').on('click', function (e) {
            e.preventDefault();

            var assigned_to = $('#instruction_user_id').val();
            var category_id = $('#instruction_category_id').val();
            var instruction = $('#instruction-body').val();
            var send_whatsapp = $('#sendWhatsappCheckbox').prop('checked') ? 'send' : '';
            var is_priority = $('#instructionPriority').prop('checked') ? 'on' : '';

            console.log(send_whatsapp);

            if ($(this).closest('form')[0].checkValidity()) {
                $.ajax({
                    type: 'POST',
                    url: "{{ route('instruction.store') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        assigned_to: assigned_to,
                        category_id: category_id,
                        instruction: instruction,
                        customer_id: {{ $supplier->id }},
                        send_whatsapp: send_whatsapp,
                        is_priority: is_priority,
                    }
                }).done(function () {
                    $('#instructionModal').find('.close').click();
                }).fail(function (response) {
                    console.log(response);
                    alert('Could not create an instruction');
                });
            } else {
                $(this).closest('form')[0].reportValidity();
            }
        });

        $('#supplierMessageButton').on('click', function () {
            $(this).siblings('textarea').removeClass('hidden');
            $(this).addClass('hidden');
        });

        $('#updateSupplierButton').on('click', function () {
            var id = {{ $supplier->id }};
            var thiss = $(this);
            var supplier = $('#supplier_supplier').val();
            var phone = $('#supplier_phone').val();
            var default_phone = $('#supplier_default_phone').val();
            var whatsapp_number = $('#supplier_whatsapp_number').val();
            var address = $('#supplier_address').val();
            var email = $('#supplier_email').val();
            // var default_email = $('#supplier_default_email').val();
            var instagram_handle = $('#supplier_instagram_handle').val();
            var social_handle = $('#supplier_social_handle').val();
            var website = $('#supplier_website').val();
            var gst = $('#supplier_gst').val();
            var status = $('#status').val();
            var supplier_category_id = $('#supplier_category_id').val();
            var supplier_status_id = $('#supplier_status_id').val();
            var supplier_scraper_name = $('#supplier_scraper_name').val();
            var supplier_inventory_lifetime = $('#supplier_inventory_lifetime').val();

            $.ajax({
                type: "POST",
                url: "{{ url('supplier') }}/" + id,
                data: {
                    _token: "{{ csrf_token() }}",
                    _method: "PUT",
                    supplier: supplier,
                    phone: phone,
                    default_phone: default_phone,
                    // whatsapp_number: whatsapp_number,
                    address: address,
                    email: email,
                    // default_email: default_email,
                    instagram_handle: instagram_handle,
                    social_handle: social_handle,
                    website: website,
                    gst: gst,
                    status: status,
                    supplier_category_id: supplier_category_id,
                    supplier_status_id: supplier_status_id,
                    scraper_name: supplier_scraper_name,
                    inventory_lifetime: supplier_inventory_lifetime
                },
                beforeSend: function () {
                    $(thiss).text('Saving...');
                }
            }).done(function () {
                $(thiss).text('Save');
                $(thiss).removeClass('btn-secondary');
                $(thiss).addClass('btn-success');

                setTimeout(function () {
                    $(thiss).addClass('btn-secondary');
                    $(thiss).removeClass('btn-success');
                }, 2000);
            }).fail(function (response) {
                $(thiss).text('Save');
                console.log(response);
                alert('Could not update supplier');
            });
        });

        $('#email_order_id').on('change', function () {
            var order_id = $(this).val();

            var subject = $(this).closest('form').find('input[name="subject"]').val();
            var new_subject = order_id + ' ' + subject;

            $(this).closest('form').find('input[name="subject"]').val(new_subject);
        });

        $('#showActionsButton').on('click', function () {
            $('#actions-container').toggleClass('hidden');
        });

        $(document).on('click', '.show-images-button', function () {
            $(this).siblings('.show-images-wrapper').toggleClass('hidden');
        });

        $(document).on('click', '.fix-message-error', function () {
            var id = $(this).data('id');
            var thiss = $(this);

            $.ajax({
                type: "POST",
                url: "{{ url('whatsapp') }}/" + id + "/fixMessageError",
                data: {
                    _token: "{{ csrf_token() }}",
                },
                beforeSend: function () {
                    $(thiss).text('Fixing...');
                }
            }).done(function () {
                $(thiss).remove();
            }).fail(function (response) {
                $(thiss).html('<img src="/images/flagged.png" />');

                console.log(response);

                alert('Could not mark as fixed');
            });
        });

        $(document).on('click', '.resend-message', function () {
            var id = $(this).data('id');
            var thiss = $(this);

            $.ajax({
                type: "POST",
                url: "{{ url('whatsapp') }}/" + id + "/resendMessage",
                data: {
                    _token: "{{ csrf_token() }}",
                },
                beforeSend: function () {
                    $(thiss).text('Sending...');
                }
            }).done(function () {
                $(thiss).remove();
            }).fail(function (response) {
                $(thiss).text('Resend');

                console.log(response);

                alert('Could not resend message');
            });
        });

        $(document).on('click', '.email-fetch', function (e) {
            e.preventDefault();

            var uid = $(this).data('uid');
            var type = $(this).data('type');
            var email_type = 'server';
            var thiss = $(this);

            if (uid == 'no') {
                uid = $(this).data('id');
                email_type = 'local';
            }

            $(thiss).closest('.card').find('.email-content').find('.resend-email-button').attr('data-id', uid);
            $(thiss).closest('.card').find('.email-content').find('.resend-email-button').attr('data-emailtype', email_type);
            $(thiss).closest('.card').find('.email-content').find('.resend-email-button').attr('data-type', type);

            $.ajax({
                type: "GET",
                url: "{{ route('purchase.email.fetch') }}",
                data: {
                    uid: uid,
                    type: type,
                    email_type: email_type
                },
                beforeSend: function () {
                    $(thiss).closest('.card').find('.email-content .card').html('Loading...');
                }
            }).done(function (response) {
                $(thiss).closest('.card').find('.email-content .card').html(response.email);
            }).fail(function (response) {
                $(thiss).closest('.card').find('.email-content .card').html();
                alert('Could not fetch an email');
                console.log(response);
            })
        });

        $('a[href="#email-tab"], #email-inbox-tab, #email-sent-tab').on('click', function () {
            var supplier_id = $(this).data('supplierid');
            var type = $(this).data('type');

            $.ajax({
                url: "{{ route('purchase.email.inbox') }}",
                type: "GET",
                data: {
                    supplier_id: supplier_id,
                    type: type
                },
                beforeSend: function () {
                    $('#email-tab #email-container .card').html('Loading emails');
                }
            }).done(function (response) {
                console.log(response);
                $('#email-tab #email-container').html(response.emails);
            }).fail(function (response) {
                $('#email-tab #email-container .card').html();

                alert('Could not fetch emails');
                console.log(response);
            });
        });

        $(document).on('click', '.pagination a', function (e) {
            e.preventDefault();

            var url = "/purchase/email/inbox" + $(this).attr('href');

            $.ajax({
                url: url,
                type: "GET"
            }).done(function (response) {
                $('#email-tab #email-container').html(response.emails);
            }).fail(function (response) {
                alert('Could not load emails');
                console.log(response);
            });
        });

        $(document).on('click', '.flag-supplier', function () {
            var supplier_id = $(this).data('id');
            var thiss = $(this);

            $.ajax({
                type: "POST",
                url: "{{ route('supplier.flag') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    supplier_id: supplier_id
                },
                beforeSend: function () {
                    $(thiss).text('Flagging...');
                }
            }).done(function (response) {
                if (response.is_flagged == 1) {
                    $(thiss).html('<img src="/images/flagged.png" />');
                } else {
                    $(thiss).html('<img src="/images/unflagged.png" />');
                }
            }).fail(function (response) {
                $(thiss).html('<img src="/images/unflagged.png" />');

                alert('Could not flag supplier!');

                console.log(response);
            });
        });

        // REPLY

        $(document).on('click', '.email-reply-link', function (e) {
            e.preventDefault();

            var DELAY = 300;
            var el = $(this);
            var parent = el.parent();

            el.fadeOut(function () {
                parent.find('.cancel-email-reply-link').fadeIn();
            });

            parent.find('.cancel-email-forward-link').click();

            parent.find('.email-reply-form').show(DELAY);
        });

        $(document).on('click', '.cancel-email-reply-link', function (e) {
            e.preventDefault();

            var DELAY = 300;
            var el = $(this);
            var parent = el.parent();

            el.fadeOut(function () {
                parent.find('.email-reply-link').fadeIn();
            });

            parent.find('.email-reply-form').hide(DELAY);
        });

        $(document).on('click', '.email-reply-form-submit-button', function (e) {
            e.preventDefault();

            var el = $(this);
            var parentEl = el.parent().parent().parent();
            var form = el.parent().parent();
            var data = form.serializeArray();
            var action = form.attr('action');

            $.ajax({
                type: "POST",
                url: action,
                data: data,
            }).done(function (response) {
                if (response.success === true) {
                    form.find('.form-group .reply-message-textarea').val('')
                    if (parentEl.find('.cancel-email-reply-link')) {
                        parentEl.find('.cancel-email-reply-link').click()
                    }

                    parentEl.find('.reply-success-messages')
                        .html(response.message)
                        .show(300).delay(4000).hide(300);
                } else {
                    parentEl.find('.reply-error-messages')
                        .html(response.errors.join('<br>'))
                        .show(300).delay(4000).hide(300);
                }
            }).fail(function (response) {
                parentEl.find('.reply-error-messages')
                    .html(response.data.messages.join('<br>'))
                    .show(300).delay(4000).hide(300);
            });
        });

        // FORWARD

        $(document).on('click', '.email-forward-link', function (e) {
            e.preventDefault();

            var DELAY = 300;
            var el = $(this);
            var parent = el.parent();

            el.fadeOut(function () {
                parent.find('.cancel-email-forward-link').fadeIn();
            });

            parent.find('.cancel-email-reply-link').click();

            parent.find('.email-forward-form').show(DELAY);
        });

        $(document).on('click', '.cancel-email-forward-link', function (e) {
            e.preventDefault();

            var DELAY = 300;
            var el = $(this);
            var parent = el.parent();

            el.fadeOut(function () {
                parent.find('.email-forward-link').fadeIn();
            });

            parent.find('.email-forward-form').hide(DELAY);
        });

        $(document).on('click', '.email-forward-form-submit-button', function (e) {
            e.preventDefault();

            var el = $(this);
            var parentEl = el.parent().parent().parent();
            var form = el.parent().parent();
            var data = form.serializeArray();
            var action = form.attr('action');

            $.ajax({
                type: "POST",
                url: action,
                data: data,
            }).done(function (response) {
                if (response.success === true) {
                    form.find('.form-group .forward-message-textarea').val('')
                    if (parentEl.find('.cancel-email-forward-link')) {
                        parentEl.find('.cancel-email-forward-link').click()
                    }

                    parentEl.find('.forward-success-messages')
                        .html(response.message)
                        .show(300).delay(4000).hide(300);
                } else {
                    parentEl.find('.forward-error-messages')
                        .html(response.errors.join('<br>'))
                        .show(300).delay(4000).hide(300);
                }
            }).fail(function (response) {
                parentEl.find('.forward-error-messages')
                    .html(response.data.messages.join('<br>'))
                    .show(300).delay(4000).hide(300);
            });
        });

        $(document).on('click', '.add-forward-to', function (e) {
            e.preventDefault();

            var el = ` <div class="row mb-3">
            <div class="col-md-10">
                <input type="text" name="to[]" class="form-control">
            </div>
            <div class="col-md-2 text-center">
                <button type="button" class="btn btn-image delete-forward-to"><img src="/images/delete.png"></button>
            </div>
        </div>`;

            $('#forward-to-emails-list').append(el);
        });

        $(document).on('click', '.delete-forward-to', function (e) {
            e.preventDefault();
            var parent = $(this).parent().parent();

            parent.hide(300, function () {
                parent.remove();
            });
        });

        // cc

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

        $(document).ready(function() {
           $(".select-multiple").multiselect();
           $(".select-multiple2").select2();
        });

        $(function() {
         $('.selectpicker').selectpicker();
        });

         $(document).on('click', '#createProduct', function (e) {
            e.preventDefault();
             var images = [];
            $.each($("input[name='product']:checked"), function(e,v){
                var image = $(v).closest(".show-thumbnail-image").attr("href");
                    images.push(image);
            });
            if(images.length == 0) {
                alert("select some images first");
                return;
            }
            $("#images_product").val(JSON.stringify(images));
            $("#count_product_images").html(images.length);
            $('#productSingleGroupDetails').modal('show');
        });
        $(document).on('submit', '#productSingleGroupDetailsForm', function (e) {
            e.preventDefault();
             var url = $(this).attr('action');
            $.ajax({
                url: url,
                method:"POST",
                data: $(this).serialize(),
            }).done(function (response) {
                if(response.code == 200) {
                    toastr['success'](response.message, 'success');
                }
                else {
                    toastr['error'](response.message, 'error');
                }
                $('#productSingleGroupDetails').modal('hide');


            }).fail(function (errObj) {
                console.log(errObj);
            });
           
        });

          $(document).on('click', '#createGroup', function (e) {
            e.preventDefault();
             var images = [];
            $.each($("input[name='checkbox[]']:checked"), function(){
                images.push($(this).val());
            });
            
            if(images.length == 0) {
                alert("select some images first");
                return;
            }
            $("#images").val(JSON.stringify(images));
            $("#count_images").html(images.length);
            $('#productGroupDetails').modal('show');
        });

        $(document).on('submit', '#productGroupDetailsForm', function (e) {
            e.preventDefault();
             var url = $(this).attr('action');
            $.ajax({
                url: url,
                method:"POST",
                data: $(this).serialize(),
            }).done(function (response) {
                if(response.code == 200) {
                    toastr['success'](response.message, 'success');
                }
                else {
                    toastr['error'](response.message, 'error');
                }
                $('#productGroupDetails').modal('hide');


            }).fail(function (errObj) {
                console.log(errObj);
            });
           
        });


          $(document).on('click', '#createInStockProduct', function (e) {
            e.preventDefault();
             var images = [];
            $.each($("input[name='checkbox[]']:checked"), function(){
                images.push($(this).val());
            });
            if(images.length == 0){
                alert('Please Select Image');
            }else{
                $("#images").val(JSON.stringify(images));
                $("#count_images").html(images.length);
                $('#productModal').modal('show');
            }

        });

        $(document).on('click', '#auto-translate', function (e) {
            e.preventDefault();
            var supplier_id = {{ $supplier->id }};
            var language = $("#autoTranslate").val();
            let self = $(this);
            $.ajax({
                url: "/supplier/language-translate/"+supplier_id,
                method:"PUT",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                  },
                data:{id:supplier_id, language:language },
                cache: false,
                beforeSend: function () {
                    $(self).text('Saving...');
                },
                success: function(res) {
                    $(self).removeClass('btn-secondary');
                    $(self).addClass('btn-success');

                    setTimeout(function () {
                        $(self).text('Add translation language');
                        $(self).addClass('btn-secondary');
                        $(self).removeClass('btn-success');
                    }, 2000);
                }
            })
        });

        function processExcel(id){
            attachment = $('#email'+id).attr('data-attached');
            $.ajax({
                url: '/supplier/excel-import',
                type: 'POST',
                dataType: 'json',
                data: {
                    _token: "{{csrf_token()}}",
                    'email_id' : id,
                    'attachment' : attachment,
                    'id' : "{{ $supplier-> id }}",
                },
            })
            .done(function() {
                alert('Added For Import');
            })
            .fail(function() {
                alert('Error During Import');
            })


        }

         $(document).on('change', '.change-whatsapp-no', function () {
            var $this = $(this);
            $.ajax({
                type: "POST",
                url: "{{ route('supplier.change.whatsapp') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    supplier_id : $this.data("supplier-id"),
                    number: $this.val()
                }
            }).done(function () {
                alert('Number updated successfully!');
            }).fail(function (response) {
                console.log(response);
            });
        });



        function createProduct(){
            var images = [];
            $.each($("input[name='checkbox[]']:checked"), function(){
                images.push($(this).val());
            });
            sku = $('#sku').val();
            category = $('#category').val();
            if(images.length == 0){
                alert('Please select image');
            }else if(sku == ''){
                alert('Please enter sku');
            }else if(category == ''){
                alert('Please select category');
            }else if(location_data == ''){
                alert('Please select location');
            }else{
                size = $('#size-selection').val();
                name = $('#name').val();
                brand = $('#brand').val();
                color = $('#color').val();

                supplier = $('#supplier').val();
                price = $('#price').val();
                price_inr_special_stock = $('#price_inr_special_stock').val();
                location_data = $('#location_data').val();
                $.ajax({
                    type: "POST",
                    url: "{{ route('supplier.image') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        type : 3,
                        images : $('#images').val(),
                        sku : sku,
                        size : size,
                        name : name,
                        brand : brand,
                        color : color,
                        supplier : supplier,
                        price : price,
                        price_special : price_inr_special_stock,
                        category : category,
                        location : location_data,
                    }
                }).done(function (response) {
                    if(response.code == 200) {
                    toastr['success'](response.message, 'success');
                }
                else {
                    toastr['error'](response.message, 'error');
                }
                    $('#productModal').modal('hide');
                }).fail(function (response) {
                    console.log(response);
                });
                }
            }




    </script>
@endsection
