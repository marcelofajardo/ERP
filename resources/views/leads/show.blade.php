@extends('layouts.app')


@section('content')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.5/css/bootstrap-select.min.css">

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Edit Leads</h2>
                <label class="badge {{ $leads->customer ? 'badge-secondary' : 'text-warning' }}">{{ $leads->customer ? 'Has Customer' : 'No Customer' }}</label>
                @if ($leads->customer)
                  <a href="{{ route('customer.show', $leads->customer->id) }}">Customer</a>
                @endif
            </div>
            <div class="pull-right">
                <a class="btn btn-secondary" href="{{ route('leads.index') }}"> Back</a>
            </div>
        </div>
    </div>

@if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    <div id="exTab2" class="container">
           <ul class="nav nav-tabs">
              <li class="active">
                 <a  href="#1" data-toggle="tab">Lead Info</a>
              </li>
              {{-- <li><a href="#2" data-toggle="tab">WhatsApp Conversation</a>
              </li> --}}
              <li><a href="#3" data-toggle="tab">Call Recordings</a>
              </li>
           </ul>
        </div>
        <div class="tab-content ">
            <!-- Pending task div start -->
            <div class="tab-pane active" id="1">
              <form action="{{ route('leads.update',$leads['id']) }}" method="POST" enctype="multipart/form-data">
                  @csrf
                  @method('PUT')
                <div class="row">
                  @if ($leads->customer)
                    <div class="col-xs-12 col-sm-8 col-sm-offset-2">
                      <h4>Customer Details</h4>

                      <strong>Name: </strong> {{ $leads->customer->name }} <br>
                      <strong>Email: </strong> {{ $leads->customer->email }} <br>
                      @if (strlen($leads->customer->phone) != 12 || preg_match('/^[91]{2}/', $leads->customer->phone))
                        <span class="badge badge-danger" data-toggle="tooltip" data-placement="top" title="Number must be 12 digits and start with 91">!</span>
                      @endif
                      <strong>Phone: </strong> {{ $leads->customer->phone }} <br>
                      <strong>Instagram Handle: </strong> {{ $leads->customer->instahandler }}
                    </div>
                  @endif

                  <div class="col-xs-12 col-sm-8 col-sm-offset-2">
      							 <div class="form-group">
      									 <strong>Customer:</strong>
      									 <select class="selectpicker form-control" data-live-search="true" data-size="15" name="customer_id" title="Choose a Customer" required>
      										 @foreach ($leads['customers'] as $customer)
      										  <option data-tokens="{{ $customer->name }} {{ $customer->email }}  {{ $customer->phone }} {{ $customer->instahandler }}" value="{{ $customer->id }}" {{ isset($leads->customer) && $leads->customer->id == $customer->id ? 'selected' : '' }}>{{ $customer->name }} - {{ $customer->phone }}</option>
      										@endforeach
      									</select>

      									 @if ($errors->has('customer_id'))
      											 <div class="alert alert-danger">{{$errors->first('customer_id')}}</div>
      									 @endif
      							 </div>
      					 </div>
                     {{-- <div class="col-xs-12 col-sm-8 col-sm-offset-2">
                        <div class="form-group">
                            <strong>Client Name:</strong>
                            <input type="text" class="form-control" name="client_name" placeholder="client_name" value="{{$leads->client_name}}"/>

                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-8 col-sm-offset-2">
                        <div class="form-group">
                            <strong>Address:</strong>
                            <input type="text" class="form-control" name="address" placeholder="address" value="{{$leads->address}}"/>
                        </div>
                    </div> --}}

                     <div class="col-xs-12 col-sm-8 col-sm-offset-2 mt-5">
                        <div class="form-group">
                          @if (strlen($leads->contactno) != 12 || preg_match('/^[91]{2}/', $leads->contactno))
                            <span class="badge badge-danger" data-toggle="tooltip" data-placement="top" title="Number must be 12 digits and start with 91">!</span>
                          @endif
                            <strong>Contact No:</strong>
                            <input type="number" class="form-control" name="contactno" placeholder="contactno" data-twilio-call data-context="leads" data-id="{{$leads->id}}" value="{{$leads->contactno}}" />
                        </div>
                    </div>

                    {{-- <div class="col-xs-12 col-sm-8 col-sm-offset-2">
                        <div class="form-group">
                            <strong>Email:</strong>
                            <input type="text" class="form-control" name="email" placeholder="email" value="{{$leads->email}}"/>

                        </div>
                    </div> --}}

                    <div class="col-xs-12 col-sm-8 col-sm-offset-2">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-xs-12 col-sm-12">
                                    <strong>Source:</strong><br>
                                </div>
                            </div>
                            <div class="row">
                                 <div class="col-sm-6 ol-xs-12">

                                 <Select name="source" class="form-control" id="leadsource">
                                    <option value="database" {{'database' == $leads->source ? 'Selected=Selected':''}}>Database</option>
                                    <option value="instagram" {{'instagram' == $leads->source ? 'Selected=Selected':''}}>Instagram</option>
                                    <option value="facebook" {{'facebook' == $leads->source ? 'Selected=Selected':''}}>Facebook</option>
                                    <option value="new" {{'new' == $leads->source ? 'Selected=Selected':''}}>New Lead</option>
                                    </Select>
                                 </div>
                                 <div class="col-sm-6 ol-xs-12">
                                     <input type="text" class="form-control" id="leadsourcetxt" name="source" placeholder="Comments" value="{{$leads->leadsourcetxt}}"/>
                                </div>
                            </div>

                        </div>
                    </div>



                      {{-- <div class="col-xs-12 col-sm-8 col-sm-offset-2">
                        <div class="form-group">
                            <strong>City:</strong>
                            <input type="text" class="form-control" name="city" placeholder="city" value="{{$leads->city}}"/>

                        </div>
                    </div> --}}

                    <div class="col-xs-12 col-sm-8 col-sm-offset-2">
                        <div class="form-group">
                            <strong>Solo Phone:</strong>
                         <Select name="whatsapp_number" class="form-control">
                                   <option value>None</option>
                                    <option value="919167152579" {{'919167152579' == $leads->whatsapp_number ? 'Selected=Selected':''}}>00</option>
                                    <option value="918291920452" {{'918291920452'== $leads->whatsapp_number ? 'Selected=Selected':''}}>02</option>
                                    <option value="918291920455" {{'918291920455'== $leads->whatsapp_number ? 'Selected=Selected':''}}>03</option>
                                    <option value="919152731483" {{'919152731483'== $leads->whatsapp_number ? 'Selected=Selected':''}}>04</option>
                                    <option value="919152731484" {{'919152731484'== $leads->whatsapp_number ? 'Selected=Selected':''}}>05</option>
                                    <option value="971562744570" {{'971562744570'== $leads->whatsapp_number ? 'Selected=Selected':''}}>06</option>
                                    <option value="918291352520" {{'918291352520'== $leads->whatsapp_number ? 'Selected=Selected':''}}>08</option>
                                    <option value="919004008983" {{'919004008983'== $leads->whatsapp_number ? 'Selected=Selected':''}}>09</option>
                            </Select>

                        </div>
                    </div>



                      <div class="col-xs-12 col-sm-8 col-sm-offset-2">
                        <div class="form-group">
                            <strong>Rating:</strong>
                            <Select name="rating" class="form-control">
                                    <option value="1" {{1== $leads->rating ? 'Selected=Selected':''}}>1</option>
                                    <option value="2" {{2== $leads->rating ? 'Selected=Selected':''}}>2</option>
                                    <option value="3" {{3== $leads->rating ? 'Selected=Selected':''}}>3</option>
                                    <option value="4" {{4== $leads->rating ? 'Selected=Selected':''}}>4</option>
                                    <option value="5" {{5== $leads->rating ? 'Selected=Selected':''}}>5</option>
                                    <option value="6" {{6== $leads->rating ? 'Selected=Selected':''}}>6</option>
                                    <option value="7" {{7== $leads->rating ? 'Selected=Selected':''}}>7</option>
                                    <option value="8" {{8== $leads->rating ? 'Selected=Selected':''}}>8</option>
                                    <option value="9" {{9== $leads->rating ? 'Selected=Selected':''}}>9</option>
                                    <option value="10" {{10== $leads->rating ? 'Selected=Selected':''}}>10</option>
                            </Select>


                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-8 col-sm-offset-2">
                      <?php $images = $leads->getMedia(config('constants.media_tags')) ?>
                      @foreach ($images as $key => $image)
                        <div class="old-image{{ $key }}" style="
                             @if ($errors->has('image'))
                                display: none;
                             @endif
                        ">
                          <p>
                            <img src="{{ $image->getUrl() }}" class="img-responsive" style="max-width: 200px;"  alt="">
                            <button class="btn btn-image removeOldImage" data-id="{{ $key }}" media-id="{{ $image->id }}"><img src="/images/delete.png" /></button>

                            <input type="text" hidden name="oldImage[{{ $key }}]" value="{{ $images ? '0' : '-1' }}">
                         </p>
                      </div>
                      @endforeach

                      @if (count($images) == 0)
                        <input type="text" hidden name="oldImage[0]" value="{{ $images ? '0' : '-1' }}">
                      @endif

                       <div class="form-group new-image" style="">
                           <strong>Upload Image:</strong>
                           <input  type="file" enctype="multipart/form-data" class="form-control" name="image[]" multiple />
                           @if ($errors->has('image'))
                               <div class="alert alert-danger">{{$errors->first('image')}}</div>
                           @endif
                       </div>
                    </div>

                    <div class="col-xs-12 col-sm-8 col-sm-offset-2">
                        <div class="form-group">
                            <strong>Brand:</strong>
                            <select id="multi_brand" multiple="" name="multi_brand[]" class="form-control">
                                @foreach($leads['brands'] as $brand_item)
                                    <option value="{{$brand_item['id']}}" {{ in_array($brand_item['id'] ,$leads['multi_brand']) ? 'Selected=Selected':''}}>{{$brand_item['name']}}</option>
                                @endforeach
                            </select>

                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-8 col-sm-offset-2">
                        <div class="form-group">
                            <strong>Categories</strong>
                            {!! $data['category_select']  !!}
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-8 col-sm-offset-2">
                        <div class="form-group">
                            <strong>Comments:</strong>
                            <textarea  class="form-control" name="comments" placeholder="comments">{{$leads->comments}} </textarea>


                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-8 col-sm-offset-2">
                        <div class="form-group">
                            <strong> Selected Product :</strong>
                            {{--<input type="text" class="form-control" name="selected_product" placeholder="Selected Product" value="{{ old('selected_product') ? old('selected_product') : $selected_product }}"/>--}}
                            <?php
                            //                  echo Form::select('allocated_to',$products_array, ( old('selected_products_array') ? old('selected_products_array') : $selected_products_array ), ['multiple'=>'multiple','name'=>'selected_product[]','class' => 'form-control select2']);?>

                            <select name="selected_product[]" class="select2 form-control" multiple="multiple" id="select2"></select>

                            @if ($errors->has('selected_product'))
                                <div class="alert alert-danger">{{$errors->first('selected_product')}}</div>
                            @endif
                        </div>

                        <script type="text/javascript">
                            jQuery(document).ready(function() {

                                jQuery('#multi_brand').select2({
                                    placeholder: 'Brand',
                                });


                                jQuery('#multi_category').select2({
                                    placeholder: 'Categories',
                                });


                                jQuery('#select2, #select2Order').select2({
                                    ajax: {
                                        url: '/productSearch/',
                                        dataType: 'json',
                                        delay: 750,
                                        data: function (params) {
                                            return {
                                                q: params.term, // search term
                                            };
                                        },
                                        processResults: function (data,params) {

                                            params.page = params.page || 1;

                                            return {
                                                results: data,
                                                pagination: {
                                                    more: (params.page * 30) < data.total_count
                                                }
                                            };
                                        },
                                    },
                                    placeholder: 'Search for Product by id, Name, Sku',
                                    escapeMarkup: function (markup) { return markup; },
                                    minimumInputLength: 5,
                                    templateResult: formatProduct,
                                    templateSelection:function(product) {
                                         return product.text || product.name;
                                     },

                                });



                                @if(!empty($data['products_array'] ))
                                    let data = [
                                            @forEach($data['products_array'] as $key => $value)
                                        {
                                            'id': '{{ $key }}',
                                            'text': '{{$value  }}',
                                        },
                                        @endforeach
                                    ];
                                @endif

                                let productSelect = jQuery('#select2, #select2Order');
                                // create the option and append to Select2

                                data.forEach(function (item) {

                                    var option = new Option(item.text,item.id , true, true);
                                    productSelect.append(option).trigger('change');

                                    // manually trigger the `select2:select` event
                                    productSelect.trigger({
                                        type: 'select2:select',
                                        params: {
                                            data: item
                                        }
                                    });

                                });

                                function formatProduct (product) {
                                    if (product.loading) {
                                        return product.sku;
                                    }

                                    return "<p> <b>Id:</b> " +product.id  + (product.name ? " <b>Name:</b> "+product.name : "" ) +  " <b>Sku:</b> "+product.sku+" </p>";
                                }

                                /*function boilerPlateCode() {
                                    //boilerplate
                                    jQuery('ul.select2-selection__rendered li').each(function (item) {
                                        $( this ).append($( this ).attr('title'));
                                    });
                                }
                                boilerPlateCode();*/

                            });


                        </script>
                    </div>

                    <div class="col-xs-12 col-sm-8 col-sm-offset-2">
        							<div class="form-group">
        								<strong>Sizes:</strong>
        								<input type="text" name="size" value="{{ $leads->size }}" class="form-control" placeholder="S, M, L">
        							</div>
        						</div>

                    <div class="col-xs-12 col-sm-8 col-sm-offset-2">
                        <div class="form-group">
                            <strong>Assigned To:</strong>
                            <Select name="assigned_user" class="form-control">

                                    @foreach($leads['users'] as $users)
                                  <option value="{{$users['id']}}" {{$users['id']== $leads->assigned_user ? 'Selected=Selected':''}}>{{$users['name']}}</option>
                                  @endforeach
                            </Select>


                        </div>
                    </div>


                     <div class="col-xs-12 col-sm-8 col-sm-offset-2">
                        <div class="form-group">
                            <strong>status:</strong>
                            <Select name="status" class="form-control" id="change_status">
                                 @foreach($leads['statusid'] as $key => $value)
                                  <option value="{{$value}}" {{$value == $leads->status ? 'Selected=Selected':''}}>{{$key}}</option>
                                  @endforeach
                            </Select>
                            <span id="change_status_message" class="text-success" style="display: none;">Successfully changed status</span>

                            <input type="hidden" class="form-control" name="userid" placeholder="status" value="{{$leads->userid}}"/>

                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-8 col-sm-offset-2">
                       <div class="form-group">
                           <strong>Created by:</strong>

                           <input type="text" class="form-control" name="" placeholder="Created by" value="{{ App\Helpers::getUserNameById($leads->userid) }}" readonly/>
                       </div>
                   </div>

                   <div class="col-xs-12 col-sm-8 col-sm-offset-2">
       							 <div class="form-group">
       								 <strong>Created at:</strong>
       								 <div class='input-group date' id='created_at'>
       									 <input type='text' class="form-control" name="created_at" value="{{ $leads->created_at }}" />

       									 <span class="input-group-addon">
       										 <span class="glyphicon glyphicon-calendar"></span>
       									 </span>
       								 </div>

       								 @if ($errors->has('created_at'))
       										 <div class="alert alert-danger">{{$errors->first('created_at')}}</div>
       								 @endif
       							 </div>
       					 </div>

                    <div class="col-xs-12 col-sm-8 col-sm-offset-2">
                        <div class="form-group">
                            <strong>Remark:</strong>
                            {{ $leads['remark'] }}
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-8 col-sm-offset-2">
                        <div class="form-group">
                            <button type="button" class="btn btn-secondary mb-3" data-toggle="modal" data-target="#orderModal" id="addOrderButton">Convert to Order</button>
                            @if ($leads->customer)
                              <button type="button" class="btn btn-secondary mb-3" data-toggle="modal" data-target="#instructionModal">Add Instruction</button>
                            @endif
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-8 col-sm-offset-2 text-center">
                        <div class="form-group">
                            <button type="submit" class="btn btn-secondary" id="submitButton">Update</button>
                        </div>
                    </div>

                </div>
         </form>

         @if ($leads->customer)
           <div id="instructionModal" class="modal fade" role="dialog">
             <div class="modal-dialog">

               <!-- Modal content-->
               <div class="modal-content">
                 <form action="{{ route('instruction.store') }}" method="POST">
                   @csrf
                   <input type="hidden" name="customer_id" value="{{ $leads->customer->id }}">

                   <div class="modal-header">
                     <h4 class="modal-title">Create Instruction</h4>
                     <button type="button" class="close" data-dismiss="modal">&times;</button>
                   </div>
                   <div class="modal-body">
                     <div class="form-group">
                       <strong>Instruction:</strong>
                       <textarea type="text" class="form-control" name="instruction" placeholder="Instructions" required>{{ old('instruction') }}</textarea>
                       @if ($errors->has('instruction'))
                           <div class="alert alert-danger">{{$errors->first('instruction')}}</div>
                       @endif
                     </div>
                   </div>
                   <div class="modal-footer">
                     <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                     <button type="submit" class="btn btn-secondary">Create</button>
                   </div>
                 </form>
               </div>

             </div>
           </div>
         @endif

         <div id="orderModal" class="modal fade" role="dialog">
           <div class="modal-dialog">

             <!-- Modal content-->
             <div class="modal-content">
               <div class="modal-header">
                 <h4 class="modal-title">Convert to Order</h4>
                 <button type="button" class="close" data-dismiss="modal">&times;</button>
               </div>

               <form action="{{ route('order.store') }}" method="POST" enctype="multipart/form-data">
                 @csrf

                 <input type="hidden" name="convert_order" value="convert_order">

                 <div class="modal-body">
                   <div class="form-group">
                       <strong> Order Type :</strong>
   			        <?php

   	                $order_types = [
   	                	'offline' => 'offline',
                           'online' => 'online'
                       ];

   			        echo Form::select('order_type',$order_types, old('order_type'), ['class' => 'form-control']);?>
                       @if ($errors->has('order_type'))
                           <div class="alert alert-danger">{{$errors->first('order_type')}}</div>
                       @endif
                   </div>

                   <div class="form-group">
                       <strong>Order Date:</strong>
                       <input type="date" class="form-control" name="order_date" placeholder="Order Date"
                              value="{{ old('order_date') ? old('order_date') : date('Y-m-d') }}"/>
                       @if ($errors->has('order_date'))
                           <div class="alert alert-danger">{{$errors->first('order_date')}}</div>
                       @endif
                   </div>

                   <div class="form-group">
                       <strong>Date of Delivery:</strong>
                       <input type="date" class="form-control" name="date_of_delivery" placeholder="Date of Delivery"
                              value="{{ old('date_of_delivery') ? old('date_of_delivery') : date('Y-m-d') }}"/>
                       @if ($errors->has('date_of_delivery'))
                           <div class="alert alert-danger">{{$errors->first('date_of_delivery')}}</div>
                       @endif
                   </div>

                   <div class="form-group">
                       <strong>Client Name:</strong>
                       <input type="text" class="form-control" name="client_name" placeholder="Client Name"
                              value="{{ old('client_name') ? old('client_name') : ($leads->client_name ? $leads->client_name : '') }}"/>
                       @if ($errors->has('client_name'))
                           <div class="alert alert-danger">{{$errors->first('client_name')}}</div>
                       @endif
                   </div>

                   {{-- <div class="form-group">
                       <strong>City:</strong>
                       <input type="text" class="form-control" name="city" placeholder="City"
                              value="{{ old('city') ? old('city') : ($leads->city ? $leads->city : '') }}"/>
                       @if ($errors->has('city'))
                           <div class="alert alert-danger">{{$errors->first('city')}}</div>
                       @endif
                   </div> --}}

                   {{-- <div class="form-group">
                       <strong>Contact Detail:</strong>
                       <input type="text" class="form-control" name="contact_detail" placeholder="Contact Detail"
                              value="{{ old('contact_detail') ? old('contact_detail') : ($leads->contactno ? $leads->contactno : '') }}"/>
                       @if ($errors->has('contact_detail'))
                           <div class="alert alert-danger">{{$errors->first('contact_detail')}}</div>
                       @endif
                   </div> --}}

                   <div class="form-group">
                       <strong>Office Phone Number:</strong>
                       <Select name="whatsapp_number" class="form-control">
                                 <option value>None</option>
                                  <option value="919167152579" {{'919167152579' == $leads->whatsapp_number ? 'Selected=Selected':''}}>00</option>
                                  <option value="918291920452" {{'918291920452'== $leads->whatsapp_number ? 'Selected=Selected':''}}>02</option>
                                  <option value="918291920455" {{'918291920455'== $leads->whatsapp_number ? 'Selected=Selected':''}}>03</option>
                                  <option value="919152731483" {{'919152731483'== $leads->whatsapp_number ? 'Selected=Selected':''}}>04</option>
                                  <option value="919152731484" {{'919152731484'== $leads->whatsapp_number ? 'Selected=Selected':''}}>05</option>
                                  <option value="971562744570" {{'971562744570'== $leads->whatsapp_number ? 'Selected=Selected':''}}>06</option>
                                  <option value="918291352520" {{'918291352520'== $leads->whatsapp_number ? 'Selected=Selected':''}}>08</option>
                                  <option value="919004008983" {{'919004008983'== $leads->whatsapp_number ? 'Selected=Selected':''}}>09</option>
                          </Select>
                       @if ($errors->has('whatsapp_number'))
                           <div class="alert alert-danger">{{$errors->first('whatsapp_number')}}</div>
                       @endif
                   </div>

                   <div class="form-group">
                       <strong> Selected Product :</strong>

                       <select name="selected_product[]" class="select2 form-control" multiple="multiple" id="select2Order" style="width: 100%;"></select>

                       @if ($errors->has('selected_product'))
                           <div class="alert alert-danger">{{$errors->first('selected_product')}}</div>
                       @endif
                   </div>

                   <div class="form-group">
                       <strong>Advance Amount:</strong>
                       <input type="text" class="form-control" name="advance_detail" placeholder="Advance Detail"
                              value="{{ old('advance_detail') }}"/>
                       @if ($errors->has('advance_detail'))
                           <div class="alert alert-danger">{{$errors->first('advance_detail')}}</div>
                       @endif
                   </div>

                   <div class="form-group">
                       <strong>Advance Date:</strong>
                       <input type="date" class="form-control" name="advance_date" placeholder="Advance Date"
                              value="{{ old('advance_date') }}"/>
                       @if ($errors->has('advance_date'))
                           <div class="alert alert-danger">{{$errors->first('advance_date')}}</div>
                       @endif
                   </div>

                   <div class="form-group">
                       <strong>Balance Amount:</strong>
                       <input type="text" class="form-control" name="balance_amount" placeholder="Balance Amount"
                              value="{{ old('balance_amount') }}"/>
                       @if ($errors->has('balance_amount'))
                           <div class="alert alert-danger">{{$errors->first('balance_amount')}}</div>
                       @endif
                   </div>

                   <div class="form-group">
                       <strong> Name of Order Handler :</strong>
   			        <?php
   			        echo Form::select('sales_person',$sales_persons, (old('sales_person') ? old('sales_person') : ($leads->assigned_user ? $leads->assigned_user : '')), ['placeholder' => 'Select a name','class' => 'form-control']);?>
                       @if ($errors->has('sales_person'))
                           <div class="alert alert-danger">{{$errors->first('sales_person')}}</div>
                       @endif
                   </div>

                   <div class="form-group">
                       <strong>Office Phone Number:</strong>
                       <input type="text" class="form-control" name="office_phone_number" placeholder="Office Phone Number"
                              value="{{ old('office_phone_number') }}"/>
                       @if ($errors->has('office_phone_number'))
                           <div class="alert alert-danger">{{$errors->first('office_phone_number')}}</div>
                       @endif
                   </div>




                   <div class="form-group">
                       <strong> Status :</strong>
   			        <?php
   			        $orderStatus = new \App\ReadOnly\OrderStatus;

   			        echo Form::select('order_status',$orderStatus->all(), old('order_status'), ['placeholder' => 'Select a status','class' => 'form-control']);?>

                       @if ($errors->has('order_status'))
                           <div class="alert alert-danger">{{$errors->first('order_status')}}</div>
                       @endif
                   </div>

                   <div class="form-group">
                       <strong>Estimated Delivery Date:</strong>
                       <input type="date" class="form-control" name="estimated_delivery_date" placeholder="Advance Date"
                              value="{{ old('estimated_delivery_date') }}"/>
                       @if ($errors->has('estimated_delivery_date'))
                           <div class="alert alert-danger">{{$errors->first('estimated_delivery_date')}}</div>
                       @endif
                   </div>

                   <div class="form-group">
                       <strong>Received By:</strong>
                       <input type="text" class="form-control" name="received_by" placeholder="Received By"
                              value="{{ old('received_by') }}"/>
                       @if ($errors->has('received_by'))
                           <div class="alert alert-danger">{{$errors->first('received_by')}}</div>
                       @endif
                   </div>

                   <div class="form-group">
                       <strong> Payment Mode :</strong>
   			        <?php
   			        $paymentModes = new \App\ReadOnly\PaymentModes();

   			        echo Form::select('payment_mode',$paymentModes->all(), old('payment_mode'), ['placeholder' => 'Select a mode','class' => 'form-control']);?>

                       @if ($errors->has('payment_mode'))
                           <div class="alert alert-danger">{{$errors->first('payment_mode')}}</div>
                       @endif
                   </div>

                   <div class="form-group">
                       <strong>Note if any:</strong>
                       <input type="text" class="form-control" name="note_if_any" placeholder="Note if any"
                              value="{{ old('note_if_any') }}"/>
                       @if ($errors->has('note_if_any'))
                           <div class="alert alert-danger">{{$errors->first('note_if_any')}}</div>
                       @endif
                   </div>
                 </div>
                 <div class="modal-footer">
                   <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                   <button type="submit" class="btn btn-secondary">Create</button>
                 </div>
               </form>
             </div>

           </div>
         </div>

         <div class="col-xs-12 col-sm-12">
            <hr>
         </div>

        </div>
        {{-- <div class="tab-pane" id="2">
            <div class="chat-frame">
                <div class="col-xs-12 col-sm-12">
                    <h3 style="text-center">WhatsApp Messages</h3>
                 </div>
                <div class="col-xs-12 col-sm-12">
                    <div class="row">
                       {{-- <div class="col-md-12" id="waMessages">
                       </div>
                    </div>
                </div>
            </div>
            {{-- <div class="col-xs-10">
                    <textarea id="waNewMessage" class="form-control" placeholder="Type new message.."></textarea>
                    <br/>
                    <label>Attach Media</label>
                    <input id="waMessageMedia" type="file" name="media" />
            </div>
            <div class="col-xs-2">
                <button id="waMessageSend" class="btn btn-image"><img src="/images/filled-sent.png" /></button>
                <a href="/leads?type=multiple" class="btn btn-secondary">Send Multiple</a>
            </div>
        </div> --}}
        <div class="tab-pane" id="3">
            <div class="col-xs-12 col-sm-12">
                <h3 style="text-center">Call Recordings</h3>
             </div>

            <div class="col-xs-12 col-sm-12">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <td>Recording</td>
                                <td>Created At</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($leads['recordings'] as $recording)
                                <tr>
                                  {{-- <td><a href="{{$recording['recording_url']}}" target="_blank">{{$recording['recording_url']}}</a></td> --}}
                                    <td><button type="button" class="btn btn-xs btn-secondary play-recording" data-url="{{$recording['recording_url']}}" data-id="{{ $recording['id'] }}">Play Recording</button></td>
                                    <td>{{$recording['created_at']}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    <div id="taskModal" class="modal fade" role="dialog">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Create Task</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>

          <form action="{{ route('task.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <input type="hidden" name="task_type" value="quick_task">
            <input type="hidden" name="model_type" value="leads">
            <input type="hidden" name="model_id" value="{{ $leads['id'] }}">

            <div class="modal-body">
              <div class="form-group">
                  <strong>Task Subject:</strong>
                   <input type="text" class="form-control" name="task_subject" placeholder="Task Subject" id="task_subject" required />
                   @if ($errors->has('task_subject'))
                       <div class="alert alert-danger">{{$errors->first('task_subject')}}</div>
                   @endif
              </div>
              <div class="form-group">
                  <strong>Task Details:</strong>
                   <textarea class="form-control" name="task_details" placeholder="Task Details" required></textarea>
                   @if ($errors->has('task_details'))
                       <div class="alert alert-danger">{{$errors->first('task_details')}}</div>
                   @endif
              </div>

              <div class="form-group" id="completion_form_group">
                <strong>Completion Date:</strong>
                <div class='input-group date' id='completion-datetime'>
                  <input type='text' class="form-control" name="completion_date" value="{{ date('Y-m-d H:i') }}" required />

                  <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                  </span>
                </div>

                @if ($errors->has('completion_date'))
                    <div class="alert alert-danger">{{$errors->first('completion_date')}}</div>
                @endif
              </div>

              <div class="form-group">
                  <strong>Assigned To:</strong>
                  <select name="assign_to[]" class="form-control" multiple required>
                    @foreach($leads['users'] as $user)
                      <option value="{{$user['id']}}">{{$user['name']}}</option>
                    @endforeach
                  </select>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-secondary">Create</button>
            </div>
          </form>
        </div>

      </div>
    </div>
  <div class="col-xs-12 col-sm-12 mb-3">
    <button type="button" class="btn btn-secondary mb-3" data-toggle="modal" data-target="#taskModal" id="addTaskButton">Add Task</button>

    @if (count($tasks) > 0)
      <table class="table">
          <thead>
            <tr>
                <th>Sr No</th>
                <th>Date</th>
                <th class="category">Category</th>
                <th>Task Subject</th>
                <th>Est Completion Date</th>
                <th>Assigned From</th>
                <th>&nbsp;</th>
                {{-- <th>Remarks</th> --}}
                <th>Action</th>
            </tr>
          </thead>
          <tbody>
              <?php
                $i = 1; $users_array = \App\Helpers::getUserArray(\App\User::all());
                $categories = \App\Http\Controllers\TaskCategoryController::getAllTaskCategory();
              ?>
            @foreach($tasks as $task)
          <tr class="{{ \App\Http\Controllers\TaskModuleController::getClasses($task) }}" id="task_{{ $task['id'] }}">
              <td>{{$i++}}</td>
              <td>{{ Carbon\Carbon::parse($task['created_at'])->format('d-m H:i') }}</td>
              <td> {{ isset( $categories[$task['category']] ) ? $categories[$task['category']] : '' }}</td>
              <td class="task-subject" data-subject="{{$task['task_subject'] ? $task['task_subject'] : 'Task Details'}}" data-details="{{$task['task_details']}}" data-switch="0">{{ $task['task_subject'] ? $task['task_subject'] : 'Task Details' }}</td>
              <td> {{ Carbon\Carbon::parse($task['completion_date'])->format('d-m H:i')  }}</td>
              <td>{{ $users_array[$task['assign_from']] }}</td>
              @if( $task['assign_to'] == Auth::user()->id )
                @if ($task['is_completed'])
                  <td>{{ Carbon\Carbon::parse($task['is_completed'])->format('d-m H:i') }}</td>
                @else
                  <td><a href="/task/complete/{{$task['id']}}">Complete</a></td>
                @endif
              @else
                @if ($task['is_completed'])
                  <td>{{ Carbon\Carbon::parse($task['is_completed'])->format('d-m H:i') }}</td>
                @else
                  <td>Assigned to  {{ $task['assign_to'] ? $users_array[$task['assign_to']] : 'Nil'}}</td>
                @endif
              @endif
              {{-- <td> --}}
                <!-- @include('task-module.partials.remark',$task)  -->
              {{-- </td> --}}
              <td>
                  <a href id="add-new-remark-btn" class="add-task" data-toggle="modal" data-target="#add-new-remark_{{$task['id']}}" data-id="{{$task['id']}}">Add</a>
                  <span> | </span>
                  <a href id="view-remark-list-btn" class="view-remark" data-toggle="modal" data-target="#view-remark-list" data-id="{{$task['id']}}">View</a>
                <!--<button class="delete-task" data-id="{{$task['id']}}">Delete</button>-->
              </td>
          </tr>

          <!-- Modal -->
          <div id="add-new-remark_{{$task['id']}}" class="modal fade" role="dialog">
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
                    <textarea id="remark-text_{{$task['id']}}" rows="1" name="remark" class="form-control"></textarea>
                    <button type="button" class="mt-2 " onclick="addNewRemark({{$task['id']}})">Add Remark</button>
                </form>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
              </div>

            </div>
          </div>

          <!-- Modal -->
          <div id="view-remark-list" class="modal fade" role="dialog">
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
         @endforeach
          </tbody>
        </table>
      @endif
 </div>

 {{-- <div class="col-xs-12">
   <div class="row">
     <div class="col-xs-12 col-sm-6">
       <form action="{{ route('message.store') }}" method="POST" enctype="multipart/form-data" class="d-flex">
           @csrf

             <div class="form-group">
               <div class="upload-btn-wrapper btn-group">
                 <button class="btn btn-image px-1"><img src="/images/upload.png" /></button>
                 <input type="file" name="image" />
                 <button type="submit" class="btn btn-image px-1 send-communication"><img src="/images/filled-sent.png" /></button>
               </div>
             </div>

               <div class="form-group flex-fill">
                 <textarea  class="form-control" name="body" placeholder="Received from Customer"></textarea>

                 <input type="hidden" name="moduletype" value="leads" />
                 <input type="hidden" name="moduleid" value="{{$leads['id']}}" />
                 <input type="hidden" name="assigned_user" value="{{$leads['assigned_user']}}" />
                 <input type="hidden" name="status" value="0" />
               </div>

        </form>
      </div>

      <div class="col-xs-12 col-sm-6">
        <form action="{{ route('message.store') }}" method="POST" enctype="multipart/form-data" class="d-flex">
           @csrf

             <div class="form-group">
               <div class="upload-btn-wrapper btn-group pr-0 d-flex">
                 <button class="btn btn-image px-1"><img src="/images/upload.png" /></button>
                 <input type="file" name="image" />

                 @php
                 $brand = '';
                   foreach($leads['brands'] as $brand_item) {
                     if (in_array($brand_item['id'], $leads['multi_brand'])) {
                       $brand = $brand_item['id'];
                     }
                   }
                 @endphp

                 <a href="{{ route('attachImages', ['leads', $leads['id'], 1, $leads['assigned_user']]) . ($brand != '' ? "?brand=$brand" : '') . (($brand != '' && $selected_categories != 'null') ? "&category=$selected_categories" : (($selected_categories != 'null') ? "?category=$selected_categories" : '')) }}" class="btn btn-image px-1"><img src="/images/attach.png" /></a>
                 <button type="submit" class="btn btn-image px-1 send-communication"><img src="/images/filled-sent.png" /></button>
               </div>
             </div>

               <div class="form-group flex-fill">
                 <textarea id="message-body" class="form-control mb-3" name="body" placeholder="Send for approval"></textarea>

                 <input type="hidden" name="moduletype" value="leads" />
                 <input type="hidden" name="moduleid" value="{{$leads['id']}}" />
                 <input type="hidden" name="assigned_user" value="{{$leads['assigned_user']}}" />
                 <input type="hidden" name="status" value="1" />

                 <p class="pb-4" style="display: block;">
                     <select name="quickCategory" id="quickCategory" class="form-control mb-3">
                       <option value="">Select Category</option>
                       @foreach($reply_categories as $category)
                           <option value="{{ $category->approval_leads }}">{{ $category->name }}</option>
                       @endforeach
                     </select>

                     <select name="quickComment" id="quickComment" class="form-control">
                       <option value="">Quick Reply</option>
                     </select>
                 </p>

                 <button type="button" class="btn btn-xs btn-secondary mb-3" data-toggle="modal" data-target="#ReplyModal" id="approval_reply">Create Quick Reply</button>
               </div>

        </form>
      </div>

      <div id="ReplyModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title"></h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <form action="{{ route('reply.store') }}" method="POST" enctype="multipart/form-data" id="approvalReplyForm">
              @csrf

              <div class="modal-body">
                <div class="form-group">
                    <strong>Select Category:</strong>
                    <select class="form-control" name="category_id" id="category_id_field">
                      @foreach ($reply_categories as $category)
                        <option value="{{ $category->id }}" {{ $category->id == old('category_id') ? 'selected' : '' }}>{{ $category->name }}</option>
                      @endforeach
                    </select>
                    @if ($errors->has('category_id'))
                        <div class="alert alert-danger">{{$errors->first('category_id')}}</div>
                    @endif
                </div>

                <div class="form-group">
                    <strong>Quick Reply:</strong>
                    <textarea class="form-control" id="reply_field" name="reply" placeholder="Quick Reply" required>{{ old('reply') }}</textarea>
                    @if ($errors->has('reply'))
                        <div class="alert alert-danger">{{$errors->first('reply')}}</div>
                    @endif
                </div>

                <input type="hidden" name="model" id="model_field" value="">

              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-secondary">Create</button>
              </div>
            </form>
          </div>

        </div>
      </div><div id="ReplyModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title"></h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <form action="{{ route('reply.store') }}" method="POST" enctype="multipart/form-data" id="approvalReplyForm">
              @csrf

              <div class="modal-body">

                <div class="form-group">
                    <strong>Quick Reply:</strong>
                    <textarea class="form-control" id="reply_field" name="reply" placeholder="Quick Reply" required>{{ old('reply') }}</textarea>
                    @if ($errors->has('reply'))
                        <div class="alert alert-danger">{{$errors->first('reply')}}</div>
                    @endif
                </div>

                <input type="hidden" name="model" id="model_field" value="">

              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-secondary">Create</button>
              </div>
            </form>
          </div>

        </div>
      </div>

      <div class="col-xs-12 col-sm-6">
          <form action="{{ route('message.store') }}" method="POST" enctype="multipart/form-data" class="d-flex">
             @csrf

               <div class="form-group">
                 <div class="upload-btn-wrapper btn-group">
                    <button class="btn btn-image px-1"><img src="/images/upload.png" /></button>
                     <input type="file" name="image" />
                     <button type="submit" class="btn btn-image px-1 send-communication"><img src="/images/filled-sent.png" /></button>
                   </div>
               </div>

                 <div class="form-group flex-fill">
                   <textarea class="form-control mb-3" name="body" placeholder="Internal Communications" id="internal-message-body"></textarea>

                   <input type="hidden" name="moduletype" value="leads" />
                   <input type="hidden" name="moduleid" value="{{$leads['id']}}" />
                   <input type="hidden" name="status" value="4" />

                   <strong>Assign to</strong>
                   <select name="assigned_user" class="form-control mb-3" required>
                     <option value="">Select User</option>
                     <option value="{{$leads['assigned_user']}}">Assigned User</option>
                     @foreach($leads['users'] as $user)
                       <option value="{{$user['id']}}">{{$user['name']}}</option>
                     @endforeach
                   </select>

                   <p class="pb-4" style="display: block;">
                       <select name="quickCategoryInternal" id="quickCategoryInternal" class="form-control mb-3">
                         <option value="">Select Category</option>
                         @foreach($reply_categories as $category)
                             <option value="{{ $category->internal_leads }}">{{ $category->name }}</option>
                         @endforeach
                       </select>

                       <select name="quickCommentInternal" id="quickCommentInternal" class="form-control">
                           <option value="">Quick Reply</option>
                       </select>
                   </p>

                   <button type="button" class="btn btn-xs btn-secondary mb-3" data-toggle="modal" data-target="#ReplyModal" id="internal_reply">Create Quick Reply</button>
                 </div>

          </form>
        </div>

        <div class="col-xs-12 col-sm-6">
          <div class="d-flex">
            <div class="form-group">
              <a href="/leads?type=multiple" class="btn btn-xs btn-secondary">Send Multiple</a>
              <a href="{{ route('attachImages', ['leads', $leads['id'], 9, $leads['assigned_user']]) . ($brand != '' ? "?brand=$brand" : '') . (($brand != '' && $selected_categories != 'null') ? "&category=$selected_categories" : (($selected_categories != 'null') ? "?category=$selected_categories" : '')) }}" class="btn btn-image px-1"><img src="/images/attach.png" /></a>
              <button id="waMessageSend" class="btn btn-sm btn-image"><img src="/images/filled-sent.png" /></button>
            </div>

            <div class="form-group flex-fill">
              <textarea id="waNewMessage" class="form-control" placeholder="Whatsapp message"></textarea>
            </div>
          </div>

          <label>Attach Media</label>
          <input id="waMessageMedia" type="file" name="media" />
        </div>
   </div>
 </div> --}}

 {{-- <h3>Messages</h3>
 <div class="col-xs-12 col-sm-12" id="message-container">

 </div>

   <div class="col-xs-12 text-center">
     <button type="button" id="load-more-messages" data-nextpage="1" class="btn btn-secondary">Load More</button>
   </div> --}}



@endsection

@section('scripts')
  {{-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.bundle.min.js"></script> --}}
  {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/popper.min.js"></script> --}}
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.5/js/bootstrap-select.min.js"></script>

  <script type="text/javascript">
  $('#completion-datetime, #created_at').datetimepicker({
    format: 'YYYY-MM-DD HH:mm'
  });



  // $(document).on('click', '.edit-message', function(e) {
  //   e.preventDefault();
  //   var message_id = $(this).data('messageid');
  //
  //   $('#message_body_' + message_id).css({'display': 'none'});
  //   $('#edit-message-textarea' + message_id).css({'display': 'block'});
  //
  //   $('#edit-message-textarea' + message_id).keypress(function(e) {
  //     var key = e.which;
  //
  //     if (key == 13) {
  //       e.preventDefault();
  //       var token = "{{ csrf_token() }}";
  //       var url = "{{ url('message') }}/" + message_id;
  //       var message = $('#edit-message-textarea' + message_id).val();
  //
  //       $.ajax({
  //         type: 'POST',
  //         url: url,
  //         data: {
  //           _token: token,
  //           body: message
  //         },
  //         success: function(data) {
  //           $('#edit-message-textarea' + message_id).css({'display': 'none'});
  //           $('#message_body_' + message_id).text(message);
  //           $('#message_body_' + message_id).css({'display': 'block'});
  //         }
  //       });
  //     }
  //   });
  // });

  $(document).on('change', '.is_statutory', function () {
      if ($(".is_statutory").val() == 1) {
          $("#completion_form_group").hide();
          $('#recurring-task').show();
      }
      else {
          $("#completion_form_group").show();
          $('#recurring-task').hide();
      }

  });

  // $(document).on('click', ".collapsible-message", function() {
  //   var selection = window.getSelection();
  //   if (selection.toString().length === 0) {
  //     var short_message = $(this).data('messageshort');
  //     var message = $(this).data('message');
  //     var status = $(this).data('expanded');
  //
  //     if (status == false) {
  //       $(this).addClass('expanded');
  //       $(this).html(message);
  //       $(this).data('expanded', true);
  //       // $(this).siblings('.thumbnail-wrapper').remove();
  //       $(this).closest('.talktext').find('.message-img').removeClass('thumbnail-200');
  //       $(this).closest('.talktext').find('.message-img').parent().css('width', 'auto');
  //     } else {
  //       $(this).removeClass('expanded');
  //       $(this).html(short_message);
  //       $(this).data('expanded', false);
  //       $(this).closest('.talktext').find('.message-img').addClass('thumbnail-200');
  //       $(this).closest('.talktext').find('.message-img').parent().css('width', '200px');
  //     }
  //   }
  // });

  // $(document).ready(function() {
  //  var container = $("div#message-container");
  //  var sendBtn = $("#waMessageSend");
  //  var leadId = "{{$leads->id}}";
  //      var addElapse = false;
  //      function errorHandler(error) {
  //          console.error("error occured: " , error);
  //      }
  //      function approveMessage(element, message) {
  //          $.post( "/whatsapp/approve/leads", { messageId: message.id })
  //            .done(function( data ) {
  //              if (data != 'success') {
  //                data.forEach(function(id) {
  //                  $('#waMessage_' + id).find('.btn-approve').remove();
  //                });
  //              }
  //
  //              element.remove();
  //            }).fail(function(response) {
  //              console.log(response);
  //              alert(response.responseJSON.message);
  //            });
  //      }
  //      function createMessageArgs() {
  //           var data = new FormData();
  //          var text = $("#waNewMessage").val();
  //          var files = $("#waMessageMedia").prop("files");
  //          var text = $("#waNewMessage").val();
  //
  //          data.append("lead_id", leadId);
  //          if (files && files.length>0){
  //              for ( var i = 0; i != files.length; i ++ ) {
  //                data.append("media[]", files[ i ]);
  //              }
  //              return data;
  //          }
  //          if (text !== "") {
  //              data.append("message", text);
  //              return data;
  //          }
  //
  //          alert("please enter a message or attach media");
  //        }
  //
  //  function renderMessage(message, tobottom = null) {
  //      var domId = "waMessage_" + message.id;
  //      var current = $("#" + domId);
  //      var is_admin = "{{ Auth::user()->hasRole('Admin') }}";
  //      var is_hod_crm = "{{ Auth::user()->hasRole('HOD of CRM') }}";
  //      var users_array = {!! json_encode($users_array) !!};
  //      if ( current.get( 0 ) ) {
  //        return false;
  //      }
  //
  //      if (message.body) {
  //        var leads_assigned_user = "{{ $leads['assigned_user'] }}";
  //
  //        var text = $("<div class='talktext'></div>");
  //        var p = $("<p class='collapsible-message'></p>");
  //
  //        if ((message.body).indexOf('<br>') !== -1) {
  //          var splitted = message.body.split('<br>');
  //          var short_message = splitted[0].length > 150 ? (splitted[0].substring(0, 147) + '...<br>' + splitted[1]) : message.body;
  //          var long_message = message.body;
  //        } else {
  //          var short_message = message.body.length > 150 ? (message.body.substring(0, 147) + '...') : message.body;
  //          var long_message = message.body;
  //        }
  //
  //        var images = '';
  //        if (message.images !== null) {
  //          message.images.forEach(function (image) {
  //            images += image.product_id !== '' ? '<a href="/products/' + image.product_id + '" data-toggle="tooltip" data-html="true" data-placement="top" title="<strong>Special Price: </strong>' + image.special_price + '<br><strong>Size: </strong>' + image.size + '">' : '';
  //            images += '<div class="thumbnail-wrapper"><img src="' + image.image + '" class="message-img thumbnail-200" /><span class="thumbnail-delete" data-image="' + image.key + '">x</span></div>';
  //            images += image.product_id !== '' ? '</a>' : '';
  //          });
  //          images += '<br>';
  //        }
  //
  //        p.attr("data-messageshort", short_message);
  //        p.attr("data-message", long_message);
  //        p.attr("data-expanded", "false");
  //        p.attr("data-messageid", message.id);
  //        p.html(short_message);
  //
  //        if (message.status == 0 || message.status == 5 || message.status == 6) {
  //          var row = $("<div class='talk-bubble'></div>");
  //
  //          var meta = $("<em>Customer " + moment(message.created_at).format('DD-MM H:m') + " </em>");
  //          var mark_read = $("<a href data-url='/message/updatestatus?status=5&id=" + message.id + "&moduleid=" + message.moduleid + "&moduletype=leads' style='font-size: 9px' class='change_message_status'>Mark as Read </a><span> | </span>");
  //          var mark_replied = $('<a href data-url="/message/updatestatus?status=6&id=' + message.id + '&moduleid=' + message.moduleid + '&moduletype=leads" style="font-size: 9px" class="change_message_status">Mark as Replied </a>');
  //
  //          row.attr("id", domId);
  //
  //          p.appendTo(text);
  //          $(images).appendTo(text);
  //          meta.appendTo(text);
  //
  //          if (message.status == 0) {
  //            mark_read.appendTo(meta);
  //          }
  //          if (message.status == 0 || message.status == 5) {
  //            mark_replied.appendTo(meta);
  //          }
  //
  //          text.appendTo(row);
  //
  //          if (tobottom) {
  //            row.appendTo(container);
  //          } else {
  //            row.prependTo(container);
  //          }
  //
  //        } else if (message.status == 4) {
  //          var row = $("<div class='talk-bubble' data-messageid='" + message.id + "'></div>");
  //          var chat_friend =  (message.assigned_to != 0 && message.assigned_to != leads_assigned_user && message.userid != message.assigned_to) ? ' - ' + users_array[message.assigned_to] : '';
  //          var meta = $("<em>" + users_array[message.userid] + " " + chat_friend + " " + moment(message.created_at).format('DD-MM H:m') + " <img id='status_img_" + message.id + "' src='/images/1.png' /> &nbsp;</em>");
  //
  //          row.attr("id", domId);
  //
  //          p.appendTo(text);
  //          $(images).appendTo(text);
  //          meta.appendTo(text);
  //
  //          text.appendTo(row);
  //          if (tobottom) {
  //            row.appendTo(container);
  //          } else {
  //            row.prependTo(container);
  //          }
  //        } else {
  //          var row = $("<div class='talk-bubble' data-messageid='" + message.id + "'></div>");
  //          var body = $("<span id='message_body_" + message.id + "'></span>");
  //          var edit_field = $('<textarea name="message_body" rows="8" class="form-control" id="edit-message-textarea' + message.id + '" style="display: none;">' + message.body + '</textarea>');
  //          var meta = "<em>" + users_array[message.userid] + " " + moment(message.created_at).format('DD-MM H:m') + " <img id='status_img_" + message.id + "' src='/images/" + message.status + ".png' /> &nbsp;";
  //
  //          if (message.status == 2 && is_admin == false) {
  //            meta += '<a href data-url="/message/updatestatus?status=3&id=' + message.id + '&moduleid=' + message.moduleid + '&moduletype=leads" style="font-size: 9px" class="change_message_status">Mark as sent </a>';
  //          }
  //
  //          if (message.status == 1 && (is_admin == true || is_hod_crm == true)) {
  //            meta += '<a href data-url="/message/updatestatus?status=2&id=' + message.id + '&moduleid=' + message.moduleid + '&moduletype=leads" style="font-size: 9px" class="change_message_status wa_send_message" data-messageid="' + message.id + '">Approve</a>';
  //            meta += ' <a href="#" style="font-size: 9px" class="edit-message" data-messageid="' + message.id + '">Edit</a>';
  //          }
  //
  //          meta += "</em>";
  //          var meta_content = $(meta);
  //
  //
  //
  //          row.attr("id", domId);
  //
  //          p.appendTo(body);
  //          body.appendTo(text);
  //          edit_field.appendTo(text);
  //          $(images).appendTo(text);
  //          meta_content.appendTo(text);
  //
  //          if (message.status == 2 && is_admin == false) {
  //            var copy_button = $('<button class="copy-button btn btn-secondary" data-id="' + message.id + '" moduleid="' + message.moduleid + '" moduletype="orders" data-message="' + message.body + '"> Copy message </button>');
  //            copy_button.appendTo(text);
  //          }
  //
  //
  //          text.appendTo(row);
  //
  //          if (tobottom) {
  //            row.appendTo(container);
  //          } else {
  //            row.prependTo(container);
  //          }
  //        }
  //      } else {
  //        var row = $("<div class='talk-bubble'></div>");
  //        var text = $("<div class='talktext'></div>");
  //        var p = $("<p class='collapsible-message'></p>");
  //
  //        if (!message.received) {
  //          var meta = $("<em>" + (parseInt(message.user_id) !== 0 ? users_array[message.user_id] : "Unknown") + " " + moment(message.created_at).format('DD-MM H:m') + " </em>");
  //        } else {
  //          var meta = $("<em>Customer " + moment(message.created_at).format('DD-MM H:m') + " </em>");
  //        }
  //
  //        row.attr("id", domId);
  //
  //        p.attr("data-messageshort", message.message);
  //        p.attr("data-message", message.message);
  //        p.attr("data-expanded", "true");
  //        p.attr("data-messageid", message.id);
  //        // console.log("renderMessage message is ", message);
  //        if ( message.message ) {
  //            p.html( message.message );
  //        } else if ( message.media_url ) {
  //            var splitted = message.content_type[1].split("/");
  //            if (splitted[0]==="image") {
  //                var a = $("<a></a>");
  //                a.attr("target", "_blank");
  //                a.attr("href", message.media_url);
  //                var img = $("<img></img>");
  //                img.attr("src", message.media_url);
  //                img.attr("width", "100");
  //                img.attr("height", "100");
  //                img.appendTo( a );
  //                a.appendTo( p );
  //                // console.log("rendered image message ", a);
  //            } else if (splitted[0]==="video") {
  //                $("<a target='_blank' href='" + message.media_url+"'>"+ message.media_url + "</a>").appendTo(p);
  //            }
  //        } else if (message.images) {
  //          var images = '';
  //          message.images.forEach(function (image) {
  //            images += image.product_id !== '' ? '<a href="/products/' + image.product_id + '" data-toggle="tooltip" data-html="true" data-placement="top" title="<strong>Special Price: </strong>' + image.special_price + '<br><strong>Size: </strong>' + image.size + '">' : '';
  //            images += '<div class="thumbnail-wrapper"><img src="' + image.image + '" class="message-img thumbnail-200" /><span class="thumbnail-delete whatsapp-image" data-image="' + image.key + '">x</span></div>';
  //            images += image.product_id !== '' ? '</a>' : '';
  //          });
  //          images += '<br>';
  //          $(images).appendTo(p);
  //        }
  //
  //        p.appendTo( text );
  //        meta.appendTo(text);
  //        if (!message.received) {
  //          if (!message.approved) {
  //              var approveBtn = $("<button class='btn btn-xs btn-secondary btn-approve ml-3'>Approve</button>");
  //              approveBtn.click(function() {
  //                  approveMessage( this, message );
  //              } );
  //              if (is_admin || is_hod_crm) {
  //                approveBtn.appendTo( text );
  //              }
  //          }
  //        } else {
  //          var moduleid = "{{ $leads->id }}";
  //          var mark_read = $("<a href data-url='/whatsapp/updatestatus?status=5&id=" + message.id + "&moduleid=" + moduleid+ "&moduletype=leads' style='font-size: 9px' class='change_message_status'>Mark as Read </a><span> | </span>");
  //          var mark_replied = $('<a href data-url="/whatsapp/updatestatus?status=6&id=' + message.id + '&moduleid=' + moduleid + '&moduletype=leads" style="font-size: 9px" class="change_message_status">Mark as Replied </a>');
  //
  //          if (message.status == 0) {
  //            mark_read.appendTo(meta);
  //          }
  //          if (message.status == 0 || message.status == 5) {
  //            mark_replied.appendTo(meta);
  //          }
  //        }
  //
  //        text.appendTo( row );
  //
  //
  //        if (tobottom) {
  //          row.appendTo(container);
  //        } else {
  //          row.prependTo(container);
  //        }
  //      }
  //
  //              return true;
  //  }
  //  function pollMessages(page = null, tobottom = null, addElapse = null) {
  //          var qs = "";
  //          qs += "/leads?leadId=" + leadId;
  //          if (page) {
  //            qs += "&page=" + page;
  //          }
  //          if (addElapse) {
  //              qs += "&elapse=3600";
  //          }
  //          var anyNewMessages = false;
  //          return new Promise(function(resolve, reject) {
  //              $.getJSON("/whatsapp/pollMessages" + qs, function( data ) {
  //
  //                  data.data.forEach(function( message ) {
  //                      var rendered = renderMessage( message, tobottom );
  //                      if ( !anyNewMessages && rendered ) {
  //                          anyNewMessages = true;
  //                      }
  //                  } );
  //
  //                  if ( anyNewMessages ) {
  //                      scrollChatTop();
  //                      anyNewMessages = false;
  //                  }
  //                  if (!addElapse) {
  //                      addElapse = true; // load less messages now
  //                  }
  //
  //
  //                  resolve();
  //              });
  //          });
  //  }
  //      function scrollChatTop() {
  //          // console.log("scrollChatTop called");
  //          // var el = $(".chat-frame");
  //          // el.scrollTop(el[0].scrollHeight - el[0].clientHeight);
  //      }
  //  function startPolling() {
  //    setTimeout( function() {
  //              pollMessages(null, null, addElapse).then(function() {
  //                  startPolling();
  //              }, errorHandler);
  //          }, 1000);
  //  }
  //  function sendWAMessage() {
  //    var data = createMessageArgs();
  //          //var data = new FormData();
  //          //data.append("message", $("#waNewMessage").val());
  //          //data.append("lead_id", leadId );
  //    $.ajax({
  //      url: '/whatsapp/sendMessage/leads',
  //      type: 'POST',
  //              "dataType"    : 'text',           // what to expect back from the PHP script, if anything
  //              "cache"       : false,
  //              "contentType" : false,
  //              "processData" : false,
  //              "data": data
  //    }).done( function(response) {
  //      $('#waNewMessage').val('');
  //      pollMessages();
  //      // console.log("message was sent");
  //    }).fail(function(errObj) {
  //      alert("Could not send message");
  //    });
  //  }
  //
  //  sendBtn.click(function() {
  //    sendWAMessage();
  //  } );
  //  startPolling();
  //
  //  $(document).on('click', '.send-communication', function(e) {
  //    e.preventDefault();
  //
  //    var thiss = $(this);
  //    var url = $(this).closest('form').attr('action');
  //    var token = "{{ csrf_token() }}";
  //    var file = $($(this).closest('form').find('input[type="file"]'))[0].files[0];
  //    var status = $(this).closest('form').find('input[name="status"]').val();
  //    var formData = new FormData();
  //
  //    formData.append("_token", token);
  //    formData.append("image", file);
  //    formData.append("body", $(this).closest('form').find('textarea').val());
  //    formData.append("moduletype", $(this).closest('form').find('input[name="moduletype"]').val());
  //    formData.append("moduleid", $(this).closest('form').find('input[name="moduleid"]').val());
  //    formData.append("assigned_user", $(this).closest('form').find('input[name="assigned_user"]').val());
  //    formData.append("status", status);
  //
  //    if (status == 4) {
  //      formData.append("assigned_user", $(this).closest('form').find('select[name="assigned_user"]').val());
  //    }
  //
  //    if ($(this).closest('form')[0].checkValidity()) {
  //      $.ajax({
  //        type: 'POST',
  //        url: url,
  //        data: formData,
  //        processData: false,
  //        contentType: false
  //      }).done(function() {
  //        pollMessages();
  //        $(thiss).closest('form').find('textarea').val('');
  //      }).fail(function(response) {
  //        // console.log(response);
  //        alert('Error sending a message');
  //      });
  //    } else {
  //      $(this).closest('form')[0].reportValidity();
  //    }
  //
  //  });

  //  $(document).on('click', '#load-more-messages', function() {
  //    var current_page = $(this).data('nextpage');
  //    $(this).data('nextpage', current_page + 1);
  //    var next_page = $(this).data('nextpage');
  //    $('#load-more-messages').text('Loading...');
  //    pollMessages(next_page, true);
  //    $('#load-more-messages').text('Load More');
  //  });
  // });



  $('#addTaskButton').on('click', function () {
   var client_name = "{{ $leads->client_name }} ";

   $('#task_subject').val(client_name);
  });

  $('#change_status').on('change', function() {
   var token = "{{ csrf_token() }}";
   var status = $(this).val();
   var id = {{ $leads['id'] }};

   $.ajax({
     url: '/leads/' + id + '/changestatus',
     type: 'POST',
     data: {
       _token: token,
       status: status
     }
   }).done( function(response) {
     $('#change_status_message').fadeIn(400);
     setTimeout(function () {
       $('#change_status_message').fadeOut(400);
     }, 2000);
   }).fail(function(errObj) {
     alert("Could not change status");
   });
  });

  // $(document).on('click', '.change_message_status', function(e) {
  //  e.preventDefault();
  //  var url = $(this).data('url');
  //  var token = "{{ csrf_token() }}";
  //  var thiss = $(this);
  //
  //  if ($(this).hasClass('wa_send_message')) {
  //    var message_id = $(this).data('messageid');
  //    var message = $('#message_body_' + message_id).find('p').data('message').trim();
  //
  //    $.ajax({
  //      url: "{{ url('whatsapp/updateAndCreate') }}",
  //      type: 'POST',
  //      data: {
  //        _token: token,
  //        moduletype: "leads",
  //        message_id: message_id
  //      },
  //      beforeSend: function() {
  //        $(thiss).text('Loading');
  //      }
  //    }).done( function(response) {
  //      // $(thiss).remove();
  //      // console.log(response);
  //    }).fail(function(errObj) {
  //      console.log(errObj);
  //      alert("Could not create whatsapp message");
  //    });
  //    // $('#waNewMessage').val(message);
  //    // $('#waMessageSend').click();
  //  }
  //    $.ajax({
  //      url: url,
  //      type: 'GET'
  //      // beforeSend: function() {
  //      //   $(thiss).text('Loading');
  //      // }
  //    }).done( function(response) {
  //      $(thiss).remove();
  //    }).fail(function(errObj) {
  //      alert("Could not change status");
  //    });
  //
  //
  //
  // });

  $(document).on('click', '.task-subject', function() {
   if ($(this).data('switch') == 0) {
     $(this).text($(this).data('details'));
     $(this).data('switch', 1);
   } else {
     $(this).text($(this).data('subject'));
     $(this).data('switch', 0);
   }
  });

  function addNewRemark(id){

   var formData = $("#add-new-remark").find('#add-remark').serialize();
   var remark = $('#remark-text_'+id).val();
   $.ajax({
       type: 'POST',
       headers: {
           'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
       },
       url: '{{ route('task.addRemark') }}',
       data: {id:id,remark:remark},
   }).done(response => {
       alert('Remark Added Success!')
       window.location.reload();
   });
  }

  $(".view-remark").click(function () {

   var taskId = $(this).attr('data-id');

     $.ajax({
         type: 'GET',
         headers: {
             'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
         },
         url: '{{ route('task.gettaskremark') }}',
         data: {id:taskId},
     }).done(response => {
         // console.log(response);

         var html='';

         $.each(response, function( index, value ) {

           html+=' <p> '+value.remark+' <br> <small>By ' + value.user_name + ' updated on '+ moment(value.created_at).format('DD-M H:mm') +' </small></p>';
           html+"<hr>";
         });
         $("#view-remark-list").find('#remark-list').html(html);
         // getActivity();
         //
         // $('#loading_activty').hide();
     });
  });

  // $(document).on('click', '.thumbnail-delete', function(event) {
  //  event.preventDefault();
  //  var thiss = $(this);
  //  var image_id = $(this).data('image');
  //  var message_id = $(this).closest('.talk-bubble').find('.collapsible-message').data('messageid');
  //  // var message = $(this).closest('.talk-bubble').find('.collapsible-message').data('message');
  //  var token = "{{ csrf_token() }}";
  //  var url = "{{ url('message') }}/" + message_id + '/removeImage';
  //  var type = 'message';
  //
  //  if ($(this).hasClass('whatsapp-image')) {
  //    type = "whatsapp";
  //  }
  //
  //  // var image_container = '<div class="thumbnail-wrapper"><img src="' + image + '" class="message-img thumbnail-200" /><span class="thumbnail-delete" data-image="' + image + '">x</span></div>';
  //  // var new_message = message.replace(image_container, '');
  //
  //  // if (new_message.indexOf('message-img') != -1) {
  //  //   var short_new_message = new_message.substr(0, new_message.indexOf('<div class="thumbnail-wrapper">')).length > 150 ? (new_message.substr(0, 147)) : new_message;
  //  // } else {
  //  //   var short_new_message = new_message.length > 150 ? new_message.substr(0, 147) + '...' : new_message;
  //  // }
  //
  //  $.ajax({
  //    type: 'POST',
  //    url: url,
  //    data: {
  //      _token: token,
  //      image_id: image_id,
  //      message_id: message_id,
  //      type: type
  //    },
  //    success: function(data) {
  //      $(thiss).parent().remove();
  //      // $('#message_body_' + message_id).children('.collapsible-message').data('messageshort', short_new_message);
  //      // $('#message_body_' + message_id).children('.collapsible-message').data('message', new_message);
  //    }
  //  });
  // });

  $(document).ready(function() {
   $("body").tooltip({ selector: '[data-toggle=tooltip]' });
  });

  $('.play-recording').on('click', function() {
   var url = $(this).data('url');
   var key = $(this).data('id');
   var recording = new Audio(url);

   recording.play();
  });

  // $('#approval_reply').on('click', function() {
  //  $('#model_field').val('Approval Lead');
  // });
  //
  // $('#internal_reply').on('click', function() {
  //  $('#model_field').val('Internal Lead');
  // });
  //
  // $('#approvalReplyForm').on('submit', function(e) {
  //  e.preventDefault();
  //
  //  var url = "{{ route('reply.store') }}";
  //  var reply = $('#reply_field').val();
  //  var category_id = $('#category_id_field').val();
  //  var model = $('#model_field').val();
  //
  //  $.ajax({
  //    type: 'POST',
  //    url: url,
  //    headers: {
  //        'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
  //    },
  //    data: {
  //      reply: reply,
  //      category_id: category_id,
  //      model: model
  //    },
  //    success: function(reply) {
  //      // $('#ReplyModal').modal('hide');
  //      $('#reply_field').val('');
  //      if (model == 'Approval Lead') {
  //        $('#quickComment').append($('<option>', {
  //          value: reply,
  //          text: reply
  //        }));
  //      } else {
  //        $('#quickCommentInternal').append($('<option>', {
  //          value: reply,
  //          text: reply
  //        }));
  //      }
  //
  //    }
  //  });
  // });

  // $('#quickCategory').on('change', function() {
  //  var replies = JSON.parse($(this).val());
  //  $('#quickComment').empty();
  //
  //  $('#quickComment').append($('<option>', {
  //    value: '',
  //    text: 'Quick Reply'
  //  }));
  //
  //  replies.forEach(function(reply) {
  //    $('#quickComment').append($('<option>', {
  //      value: reply.reply,
  //      text: reply.reply
  //    }));
  //  });
  // });

  // $('#quickCategoryInternal').on('change', function() {
  //  var replies = JSON.parse($(this).val());
  //  $('#quickCommentInternal').empty();
  //
  //  $('#quickCommentInternal').append($('<option>', {
  //    value: '',
  //    text: 'Quick Reply'
  //  }));
  //
  //  replies.forEach(function(reply) {
  //    $('#quickCommentInternal').append($('<option>', {
  //      value: reply.reply,
  //      text: reply.reply
  //    }));
  //  });
  // });

  $('#submitButton').on('click', function(e) {
   e.preventDefault();

   var phone = $('input[name="contactno"]').val();

   if (phone.length != 0) {
     if (/^[91]{2}/.test(phone) != true) {
       $('input[name="contactno"]').val('91' + phone);
     }
   }

   $(this).closest('form').submit();
  });
  </script>
@endsection
