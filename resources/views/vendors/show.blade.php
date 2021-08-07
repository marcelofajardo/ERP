@extends('layouts.app')

@section('title', 'Vendor Page')

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
        }

        .speech-wrapper .bubble.alt {
            margin: 0 0 25px 20% !important;
        }

        .show-images-wrapper {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
        }

        input[type="checkbox"][id^="cb"] {
            display: none;
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
                <h3>Vendor Page</h3>
            </div>
            <div class="pull-right mt-4">
                <a class="btn btn-xs btn-secondary" href="{{ route('vendors.index') }}">Back</a>
                <a href="{{route('vendors.payments', $vendor->id)}}" class="btn btn-secondary btn-xs" title="Vendor Payments" target="_blank">Payments </a>
                {{-- <a class="btn btn-xs btn-secondary" href="#" id="quick_add_lead">+ Lead</a>
                <a class="btn btn-xs btn-secondary" href="#" id="quick_add_order">+ Order</a>
                <button type="button" class="btn btn-xs btn-secondary" data-toggle="modal" data-target="#privateViewingModal">Set Up for Private Viewing</button> --}}
            </div>
        </div>
    </div>

    {{-- @include('customers.partials.modal-private-viewing') --}}

    @include('partials.flash_messages')

    @include('vendors.partials.product-modals')

    <div id="exTab2" class="mb-3">
        <ul class="nav nav-tabs">
            <li class="active">
                <a href="#info-tab" data-toggle="tab">Vendor Info</a>
            </li>
            <li>
                <a href="#agents-tab" data-toggle="tab">Agents</a>
            </li>
            <li>
                <a href="#email-tab" data-toggle="tab" data-vendorid="{{ $vendor->id }}" data-type="inbox">Emails</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-xs-12 col-md-4 border">
            <div class="tab-content">
                <div class="tab-pane active mt-3" id="info-tab">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="form-group">
                                <select class="form-control input-sm" name="category_id" id="vendor_category">
                                    <option value="">Select a Category</option>

                                    @foreach ($vendor_categories as $category)
                                        <option value="{{ $category->id }}" {{ $category->id == $vendor->category_id ? 'selected' : '' }}>{{ $category->title }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group form-inline">
                                <input type="text" name="name" id="vendor_name_name" class="form-control input-sm" placeholder="Vendor" value="{{ $vendor->name }}">

                                {{-- @if ($supplier->is_flagged == 1)
                                  <button type="button" class="btn btn-image flag-supplier" data-id="{{ $supplier->id }}"><img src="/images/flagged.png" /></button>
                                @else
                                  <button type="button" class="btn btn-image flag-supplier" data-id="{{ $supplier->id }}"><img src="/images/unflagged.png" /></button>
                                @endif --}}
                            </div>

                            <div class="form-group form-inline">
                                <input type="number" id="vendor_phone" name="phone" class="form-control input-sm" placeholder="910000000000" value="{{ $vendor->phone }}">
                            </div>

                            <div class="form-group">
                                <select class="form-control input-sm" name="default_phone" id="vendor_default_phone">
                                    <option value="">Select Default Phone</option>
                                    @if ($vendor->phone != '')
                                        <option value="{{ $vendor->phone }}" {{ $vendor->phone == $vendor->default_phone ? 'selected' : '' }}>{{ $vendor->phone }} - Vendor's Phone</option>
                                    @endif

                                    @if ($vendor->agents)
                                        @foreach ($vendor->agents as $agent)
                                            <option value="{{ $agent->phone }}" {{ $agent->phone == $vendor->default_phone ? 'selected' : '' }}>{{ $agent->phone }} - {{ $agent->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            <div class="form-group">
                                <select name="whatsapp_number" id="vendor_whatsapp_number" class="form-control input-sm">
                                    <option value>Whatsapp Number</option>
                                    <option value="971569119192" {{ '971569119192' == $vendor->whatsapp_number ? ' selected' : '' }}>971569119192 Indian</option>
                                    <option value="971502609192" {{ '971502609192' == $vendor->whatsapp_number ? ' selected' : '' }}>971502609192 Dubai</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <textarea name="address" id="vendor_address" class="form-control input-sm" rows="3" cols="80" placeholder="Address">{{ $vendor->address }}</textarea>
                            </div>

                            {{-- @if (Auth::user()->hasRole('Admin') || Auth::user()->hasRole('HOD of CRM')) --}}
                            <div class="form-group">
                                <input type="email" name="email" id="vendor_email" class="form-control input-sm" placeholder="Email" value="{{ $vendor->email }}">
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

                            {{-- <div class="form-group">
                              <input type="text" name="instagram_handle" id="supplier_instagram_handle" class="form-control input-sm" placeholder="Instagram Handle" value="{{ $supplier->instagram_handle }}">
                            </div> --}}

                            <div class="form-group">
                                <input type="text" name="social_handle" id="vendor_social_handle" class="form-control input-sm" placeholder="Social Handle" value="{{ $vendor->social_handle }}">
                            </div>

                            <div class="form-group">
                                <input type="text" name="website" id="vendor_website" class="form-control input-sm" placeholder="Website" value="{{ $vendor->website }}">
                            </div>

                            <div class="form-group">
                                <input type="text" name="login" id="vendor_login" class="form-control input-sm" placeholder="Login" value="{{ $vendor->login }}">
                            </div>

                            <div class="form-group">
                                <input type="password" name="password" id="vendor_password" class="form-control input-sm" placeholder="Password" value="{{ $vendor->password }}">
                            </div>

                            {{-- <div class="form-group">
                                      <select name="whatsapp_number" class="form-control input-sm" id="whatsapp_change">
                                          <option value>Whatsapp Number</option>

                                @foreach ($api_keys as $api_key)
                                              <option value="{{ $api_key->number }}" {{ $customer->whatsapp_number == $api_key->number ? 'selected' : '' }}>{{ $api_key->number }}</option>
                                          @endforeach
                                      </select>

                              <span class="text-success change_status_message" style="display: none;">Successfully changed whatsapp number</span>
                                  </div> --}}
                            {{-- @endif --}}

                            {{-- <div class="form-group">
                              <input type="text" name="website" id="supplier_website" class="form-control input-sm" placeholder="Website" value="{{ $supplier->website }}">
                            </div> --}}

                            <div class="form-group">
                                <input type="text" name="gst" id="vendor_gst" class="form-control input-sm" placeholder="GST" value="{{ $vendor->gst }}">
                            </div>

                            <div class="form-group">
                                <input type="text" name="account_name" id="vendor_account_name" class="form-control input-sm" placeholder="Account Name" value="{{ $vendor->account_name }}">
                            </div>

                            <div class="form-group">
                                <input type="text" name="account_iban" id="vendor_account_iban" class="form-control input-sm" placeholder="IBAN" value="{{ $vendor->account_iban }}">
                            </div>

                            <div class="form-group">
                                <input type="text" name="account_swift" id="vendor_account_swift" class="form-control input-sm" placeholder="SWIFT" value="{{ $vendor->account_swift }}">
                            </div>

                            <div class="form-group">
                                <input type="text" name="frequency_of_payment" id="vendor_frequency_of_payment" class="form-control" value="{{ $vendor->frequency_of_payment }}" placeholder="Frequency of Payment">
                            </div>
                            <div class="form-group">
                                <input type="text" name="bank_name" id="vendor_bank_name" class="form-control" value="{{ $vendor->bank_name }}" placeholder="Bank Name">
                            </div>
                            <div class="form-group">
                                <textarea name="bank_address" id="vendor_bank_address" class="form-control" placeholder="Bank Address">{{ $vendor->bank_address }}</textarea>
                            </div>
                            <div class="form-group">
                                <input type="text" name="city" id="vendor_city" class="form-control" value="{{ $vendor->city }}" placeholder="City">
                            </div>
                            <div class="form-group">
                                <input type="text" name="country" id="vendor_country" class="form-control" value="{{ $vendor->country }}" placeholder="Country">
                            </div>
                            <div class="form-group">
                                <input type="text" name="ifsc_code" id="vendor_ifsc_code" class="form-control" value="{{ $vendor->ifsc_code }}" placeholder="IFSC">
                            </div>
                            <div class="form-group">
                                <textarea name="remark" id="vendor_remark" class="form-control" placeholder="Remark">{{ $vendor->remark }}</textarea>
                            </div>

                            <div class="form-group">
                                <button type="button" id="updateVendorButton" class="btn btn-xs btn-secondary">Save</button>
                            </div>
                        </div>

                    </div>
                </div>

                @include('vendors.partials.agent-modals')


                <div class="tab-pane mt-3" id="agents-tab">
                    <button type="button" class="btn btn-xs btn-secondary mb-3 create-agent" data-toggle="modal" data-target="#createAgentModal" data-id="{{ $vendor->id }}">Add Agent</button>

                    <div id="agentAccordion">
                        @foreach ($vendor->agents as $key => $agent)
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

                                            <input type="hidden" name="model_id" value="{{ $vendor->id }}">
                                            <input type="hidden" name="model_type" value="App\Vendor">

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
                                <a href="#email-inbox" data-toggle="tab" id="email-inbox-tab" data-vendorid="{{ $vendor->id }}" data-type="inbox">Inbox</a>
                            </li>
                            <li>
                                <a href="#email-sent" data-toggle="tab" id="email-sent-tab" data-vendorid="{{ $vendor->id }}" data-type="sent">Sent</a>
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
                <form action="{{ route('whatsapp.send', 'vendor') }}" method="POST" enctype="multipart/form-data">
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
                            <button type="button" id="vendorMessageButton" class="btn btn-image"><img src="/images/support.png"/></button>
                            <textarea class="form-control mb-3 hidden" style="height: 110px;" name="body" placeholder="Received from Vendor"></textarea>
                            <input type="hidden" name="status" value="0"/>
                        </div>
                    </div>

                </form>

                <form action="{{ route('whatsapp.send', 'vendor') }}" method="POST" enctype="multipart/form-data">
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
                
                <h4>Remarks</h4>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group">
                            <textarea class="form-control" name="remark" rows="3" cols="10" placeholder="Remark"></textarea>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <div><b>Created Date:</b> {{ $vendor->created_at }}</div>
                            </div>
                            <div class="col-xs-12">
                                <div><b>Updated Date:</b> {{ $vendor->updated_at }}</div>
                            </div>
                        </div>
                        <div class="row"><div class="col-xs-12">&nbsp;</div></div>
                        <div class="form-inline">
                            <button type="button" class="btn btn-xs btn-secondary" id="sendRemarkButton">Send</button>
                            <button type="button" class="btn btn-xs btn-secondary ml-1" id="hideRemarksButton">Show</button>
                        </div>
                    </div>

                    <div class="col-xs-12">

                        <div id="remarks-container" class="hidden">
                            <ul>

                            </ul>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="col-xs-12 col-md-4">
            <div class="border">
                <div class="row">
                    <div class="col-xs-12 form-inline">
                        <button type="button" class="btn btn-xs btn-image load-communication-modal" data-is_admin="{{ Auth::user()->hasRole('Admin') }}" data-is_hod_crm="{{ Auth::user()->hasRole('HOD of CRM') }}" data-object="vendor" data-id="{{$vendor->id}}" data-load-type="text" data-all="1" title="Load messages"><img src="/images/chat.png" alt=""></button>
                        <button type="button" class="btn btn-xs btn-image load-communication-modal" data-is_admin="{{ Auth::user()->hasRole('Admin') }}" data-is_hod_crm="{{ Auth::user()->hasRole('HOD of CRM') }}" data-object="vendor" data-id="{{$vendor->id}}" data-attached="1" data-load-type="images" data-all="1" title="Load Auto Images attacheds"><img src="/images/archive.png" alt=""></button>
                        <button type="button" class="btn btn-xs btn-image load-communication-modal" data-is_admin="{{ Auth::user()->hasRole('Admin') }}" data-is_hod_crm="{{ Auth::user()->hasRole('HOD of CRM') }}" data-object="vendor" data-id="{{$vendor->id}}" data-attached="1" data-load-type="pdf" data-all="1" title="Load PDF"><img src="/images/icon-pdf.svg" alt=""></button>
                        <input type="text" name="search_chat_pop"  class="form-control search_chat_pop" placeholder="Search Message" style="width: 200px;">
                    </div>
                </div>
                <div class="row">
                    <div class="load-communication-modal js-load-communication-modal" data-is_admin="{{ Auth::user()->hasRole('Admin') }}" data-is_hod_crm="{{ Auth::user()->hasRole('HOD of CRM') }}" style="display: none;" data-object="vendor" data-attached="1" data-id="{{ $vendor->id }}"></div>
                    <div class="col-12" id="chat-history"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col">
            <div class="pull-left">
                <h4>Products</h4>
            </div>

            <div class="pull-right">
                <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#productCreateModal">+</a>
            </div>

            <div class="table-responsive mt-3">
                <table class="table table-bordered m-0">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Date of Order</th>
                        <th>Name</th>
                        <th>Qty</th>
                        <th>Price</th>
                        <th>Total Price</th>
                        <th>Payments Terms</th>
                        <th>Recurring Type</th>
                        <th>Delivery Date</th>
                        <th>Received By</th>
                        <th>Approved By</th>
                        <th>Payment Details</th>
                        <th>Action</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach ($vendor->products()->orderBy('created_at', 'DESC')->offset(0)->limit(3)->get() as $product)
                        <tr>
                            <td>{{ $product->id }}</td>
                            <td>{{ \Carbon\Carbon::parse($product->date_of_order)->format('d-m') }}</td>
                            <td>
                                @if ($product->hasMedia(config('constants.media_tags')))
                                    @foreach ($product->getMedia(config('constants.media_tags')) as $image)
                                        <img src="{{ $image->getUrl() }}" class="img-responsive m-1" width="50px" alt="">
                                    @endforeach

                                    <br>
                                @endif

                                {{ $product->name }}
                                <br>

                                {{-- <span class="text-muted">
                                  <strong>Vendor: </strong>{{ $product->vendor->name ?? 'No Vendor' }}
                                </span> --}}
                            </td>
                            <td>{{ $product->qty }}</td>
                            <td>{{ $product->price }}</td>
                            <td>{{ $product->qty * $product->price }}</td>
                            <td>{{ $product->payment_terms }}</td>
                            <td>{{ $product->recurring_type }}</td>
                            <td>{{ $product->delivery_date ? \Carbon\Carbon::parse($product->delivery_date)->format('d-m') : '' }}</td>
                            <td>{{ $product->received_by }}</td>
                            <td>{{ $product->approved_by }}</td>
                            <td>{{ $product->payment_details }}</td>
                            <td>
                                <button type="button" class="btn btn-image edit-product" data-toggle="modal" data-target="#productEditModal" data-product="{{ $product }}"><img src="/images/edit.png"/></button>

                                {!! Form::open(['method' => 'DELETE','route' => ['vendors.product.destroy', $product->id],'style'=>'display:inline']) !!}
                                <button type="submit" class="btn btn-image"><img src="/images/delete.png"/></button>
                                {!! Form::close() !!}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div id="vendorAccordion">
                <div class="card mb-5">
                    <div class="card-header" id="headingVendor">
                        <h5 class="mb-0">
                            <button class="btn btn-link collapsed collapse-fix" data-toggle="collapse" data-target="#vendorAcc" aria-expanded="false" aria-controls="">
                                Rest of Products
                            </button>
                        </h5>
                    </div>

                    <div id="vendorAcc" class="collapse collapse-element" aria-labelledby="headingVendor" data-parent="#vendorAccordion">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tbody>
                                    @foreach ($vendor->products()->orderBy('created_at', 'DESC')->offset(3)->limit(100)->get() as $key => $product)
                                        <tr>
                                            <td>{{ $product->id }}</td>
                                            <td>{{ \Carbon\Carbon::parse($product->date_of_order)->format('d-m') }}</td>
                                            <td>
                                                @if ($product->hasMedia(config('constants.media_tags')))
                                                    @foreach ($product->getMedia(config('constants.media_tags')) as $image)
                                                        <img src="{{ $image->getUrl() }}" class="img-responsive m-1" width="50px" alt="">
                                                    @endforeach

                                                    <br>
                                                @endif

                                                {{ $product->name }}
                                                <br>

                                                {{-- <span class="text-muted">
                                                  <strong>Vendor: </strong>{{ $product->vendor->name ?? 'No Vendor' }}
                                                </span> --}}
                                            </td>
                                            <td>{{ $product->qty }}</td>
                                            <td>{{ $product->price }}</td>
                                            <td>{{ $product->qty * $product->price }}</td>
                                            <td>{{ $product->payment_terms }}</td>
                                            <td>{{ $product->recurring_type }}</td>
                                            <td>{{ $product->delivery_date ? \Carbon\Carbon::parse($product->delivery_date)->format('d-m') : '' }}</td>
                                            <td>{{ $product->received_by }}</td>
                                            <td>{{ $product->approved_by }}</td>
                                            <td>{{ $product->payment_details }}</td>
                                            <td>
                                                <button type="button" class="btn btn-image edit-product" data-toggle="modal" data-target="#productEditModal" data-product="{{ $product }}"><img src="/images/edit.png"/></button>

                                                {!! Form::open(['method' => 'DELETE','route' => ['vendors.product.destroy', $product->id],'style'=>'display:inline']) !!}
                                                <button type="submit" class="btn btn-image"><img src="/images/delete.png"/></button>
                                                {!! Form::close() !!}
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- @include('suppliers.partials.modal-email') --}}
    @include('vendors.partials.modal-email')

    @include('customers.partials.modal-reply')

    <form action="" method="POST" id="product-remove-form">
        @csrf
    </form>

    {{-- @include('customers.partials.modal-forward') --}}

@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.5/js/bootstrap-select.min.js"></script> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js"></script>
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.min.js"></script> --}}

    <script type="text/javascript">
        $(document).ready(function () {
            $('.js-load-communication-modal').trigger('click');
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
            // var sendBtn = $("#waMessageSend");
            var vendorId = "{{ $vendor->id }}";
            var addElapse = false;

            function errorHandler(error) {
                console.error("error occured: ", error);
            }

            function approveMessage(element, message) {
                if (!$(element).attr('disabled')) {
                    $.ajax({
                        type: "POST",
                        url: "/whatsapp/approve/vendor",
                        data: {
                            _token: "{{ csrf_token() }}",
                            messageId: message.id
                        },
                        beforeSend: function () {
                            $(element).attr('disabled', true);
                            $(element).text('Approving...');
                        }
                    }).done(function (data) {
                        element.remove();
                    }).fail(function (response) {
                        $(element).attr('disabled', false);
                        $(element).text('Approve');

                        console.log(response);
                        alert(response.responseJSON.message);
                    });
                }
            }


            $(document).on('click', '.send-communication', function (e) {
                e.preventDefault();

                var thiss = $(this);
                var url = $(this).closest('form').attr('action');
                var token = "{{ csrf_token() }}";
                var file = $($(this).closest('form').find('input[type="file"]'))[0].files[0];
                var status = $(this).closest('form').find('input[name="status"]').val();
                var screenshot_path = $('#screenshot_path').val();
                var vendor_id = {{ $vendor->id }};
                var formData = new FormData();

                formData.append("_token", token);
                formData.append("image", file);
                formData.append("message", $(this).closest('form').find('textarea').val());
                // formData.append("moduletype", $(this).closest('form').find('input[name="moduletype"]').val());
                formData.append("vendor_id", vendor_id);
                formData.append("assigned_to", $(this).closest('form').find('select[name="assigned_to"]').val());
                formData.append("status", status);
                formData.append("screenshot_path", screenshot_path);

                if ($(this).closest('form')[0].checkValidity()) {
                    if (!$(thiss).is(':disabled')) {
                        $.ajax({
                            type: 'POST',
                            url: url,
                            data: formData,
                            processData: false,
                            contentType: false,
                            beforeSend: function () {
                                $(thiss).attr('disabled', true);
                            }
                        }).done(function (response) {
                            $('.load-communication-modal').trigger('click');
                            //pollMessages();
                            $(thiss).closest('form').find('textarea').val('');
                            $('#paste-container').empty();
                            $('#screenshot_path').val('');
                            $(thiss).closest('form').find('.dropify-clear').click();

                            if ($(thiss).hasClass('received-customer')) {
                                $(thiss).closest('form').find('#supplierMessageButton').removeClass('hidden');
                                $(thiss).closest('form').find('textarea').addClass('hidden');
                            }

                            $(thiss).attr('disabled', false);
                        }).fail(function (response) {
                            console.log(response);
                            alert('Error sending a message');

                            $(thiss).attr('disabled', false);
                        });
                    }
                } else {
                    $(this).closest('form')[0].reportValidity();
                }

            });

            var can_load_more = true;


            $(document).on('click', '#load-more-messages', function () {
                var current_page = $(this).data('nextpage');
                $(this).data('nextpage', current_page + 1);
                var next_page = $(this).data('nextpage');
                $('#load-more-messages').text('Loading...');

                //pollMessages(next_page, true);
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
                        moduletype: "vendor",
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

        $(document).on('click', '.forward-btn', function () {
            var id = $(this).data('id');
            $('#forward_message_id').val(id);
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

        $('#vendorMessageButton').on('click', function () {
            $(this).siblings('textarea').removeClass('hidden');
            $(this).addClass('hidden');
        });

        $('#updateVendorButton').on('click', function () {
            var id = {{ $vendor->id }};
            var thiss = $(this);
            var name = $('#vendor_name_name').val();
            var category = $('#vendor_category').val();
            var phone = $('#vendor_phone').val();
            var default_phone = $('#vendor_default_phone').val();
            var whatsapp_number = $('#vendor_whatsapp_number').val();
            var address = $('#vendor_address').val();
            var email = $('#vendor_email').val();
            var social_handle = $('#vendor_social_handle').val();
            var website = $('#vendor_website').val();
            var login = $('#vendor_login').val();
            var password = $('#vendor_password').val();
            var gst = $('#vendor_gst').val();
            var account_name = $('#vendor_account_name').val();
            var account_iban = $('#vendor_account_iban').val();
            var account_swift = $('#vendor_account_swift').val();
            var frequency_of_payment = $('#vendor_frequency_of_payment').val();
            var bank_name = $('#vendor_bank_name').val();
            var bank_address = $('#vendor_bank_address').val();
            var city = $('#vendor_city').val();
            var country = $('#vendor_country').val();
            var ifsc_code = $('#vendor_ifsc_code').val();
            var remark = $('#vendor_remark').val();

            $.ajax({
                type: "POST",
                url: "{{ url('vendors') }}/" + id,
                data: {
                    _token: "{{ csrf_token() }}",
                    _method: "PUT",
                    category_id: category,
                    name: name,
                    phone: phone,
                    default_phone: default_phone,
                    whatsapp_number: whatsapp_number,
                    address: address,
                    email: email,
                    social_handle: social_handle,
                    website: website,
                    login: login,
                    password: password,
                    gst: gst,
                    account_name: account_name,
                    account_swift: account_swift,
                    account_iban: account_iban,
                    frequency_of_payment: frequency_of_payment,
                    bank_name: bank_name,
                    bank_address: bank_address,
                    city: city,
                    country: country,
                    ifsc_code: ifsc_code,
                    remark: remark,
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
                alert('Could not update vendor');
            });
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

        $(document).on('click', '.edit-product', function () {
            var product = $(this).data('product');
            var url = "{{ url('vendors/product') }}/" + product.id;

            $('#productEditModal form').attr('action', url);
            $('#vendor_vendor_id').val(product.vendor_id);
            $('#vendor_date_of_order').val(product.date_of_order);
            $('#vendor_name').val(product.name);
            $('#vendor_qty').val(product.qty);
            $('#vendor_price').val(product.price);
            $('#vendor_payment_terms').val(product.payment_terms);
            $('#vendor_recurring_type option[value="' + product.recurring_type + '"]').prop('selected', true);
            $('#vendor_delivery_date').val(product.delivery_date);
            $('#vendor_received_by').val(product.received_by);
            $('#vendor_approved_by').val(product.approved_by);
            $('#vendor_payment_details').val(product.payment_details);
        });

        $('#date-of-order, #vendor-date-of-order, #delivery-date, #vendor-delivery-date').datetimepicker({
            format: 'YYYY-MM-DD HH:mm'
        });

        $('#sendRemarkButton').on('click', function () {
            var id = {{ $vendor->id }};
            var remark = $(this).parent('div').siblings('.form-group').find('textarea').val();
            var thiss = $(this);

            $.ajax({
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('task.addRemark') }}',
                data: {
                    id: id,
                    remark: remark,
                    module_type: 'vendor'
                },
            }).done(response => {
                $(thiss).parent('div').siblings('.form-group').find('textarea').val('');
                var comment = '<li> ' + remark + ' <br> <small>By updated on ' + moment().format('DD-M H:mm') + ' </small></li>';

                $('#remarks-container').find('ul').prepend(comment);
            }).fail(function (response) {
                console.log(response);
                alert('Could not add remark');
            });
        });

        $.ajax({
            type: 'GET',
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            },
            url: '{{ route('task.gettaskremark') }}',
            data: {
                id: "{{ $vendor->id }}",
                module_type: "vendor"
            },
        }).done(response => {
            var html = '';

            $.each(response, function (index, value) {
                html += ' <li> ' + value.remark + ' <br> <small>By ' + value.user_name + ' updated on ' + moment(value.created_at).format('DD-M H:mm') + ' </small></li>';
            });
            $("#remarks-container").find('ul').html(html);
        });

        $.ajax({
            type: 'GET',
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            },
            url: '{{ route('task.gettaskremark') }}',
            data: {
                id: "{{ $vendor->id }}",
                module_type: "vendor"
            },
        }).done(response => {
            var html = '';

            $.each(response, function (index, value) {
                html += ' <li> ' + value.remark + ' <br> <small>By ' + value.user_name + ' updated on ' + moment(value.created_at).format('DD-M H:mm') + ' </small></li>';
            });
            $("#remarks-container").find('ul').html(html);
        });

        $('#hideRemarksButton').on('click', function () {
            $('#remarks-container').toggleClass('hidden');
        });

        $('a[href="#email-tab"], #email-inbox-tab, #email-sent-tab').on('click', function () {
            var vendor_id = $(this).data('vendorid');
            var type = $(this).data('type');

            $.ajax({
                url: "{{ route('vendors.email.inbox') }}",
                type: "GET",
                data: {
                    vendor_id: vendor_id,
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

        //

    </script>
@endsection
