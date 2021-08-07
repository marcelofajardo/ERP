@extends('layouts.app')

@section('styles')
<style>
.ajax-loader{
    position: fixed;
    left: 0;
    right: 0;
    top: 0;
    bottom: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.2);
    z-index: 1060;
}
.inner_loader {
	top: 30%;
    position: absolute;
    left: 40%;
    width: 100%;
    height: 100%;
}
</style>
@endsection

@section('large_content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
    <div class="ajax-loader" style="display: none;">
      <div class="inner_loader">
      <img src="{{ asset('/images/loading2.gif') }}">
      </div>
    </div>
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>View Order</h2>
                <label class="badge {{ $has_customer ? 'badge-secondary' : 'text-warning' }}">{{ $has_customer ? 'Has Customer' : 'No Customer' }}</label>
                @if ($has_customer != false)
                  <a href="{{ route('customer.show', $has_customer) }}">Customer</a>
                @endif
            </div>
            <div class="pull-right">
                <a class="btn btn-secondary" href="{{ route('order.index') }}"> Back</a>
            </div>
        </div>
    </div>


    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    @if ($message = Session::get('error'))
        <div class="alert alert-danger">
            <p>{{ $message }}</p>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $msg)
              <li>{{ $msg }}</li>
            @endforeach
        </div>
    @endif

    <div id="exTab2" >
           <ul class="nav nav-tabs">
              <li class="active">
                 <a  href="#1" data-toggle="tab">Order Info</a>
              </li>
              {{-- <li><a href="#2" data-toggle="tab">WhatsApp Conversation</a>
              </li> --}}
              <li><a href="#3" data-toggle="tab">Call Recordings</a>
              <li><a href="#delivery_approval" data-toggle="tab">Delivery Approval</a>
              <li><a href="#customer-address" data-toggle="tab">Customer Address</a>
              </li>
           </ul>
        </div>

    <!-- New order Layout -->
    <div class="tab-content ">
            <!-- Pending task div start -->
            <div class="tab-pane active" id="1">

              <form action="{{ route('order.update',$id) }}" method="POST" enctype="multipart/form-data">
                  @csrf
                  @method('PUT')

                <div class="row">


                    <div class="col-md-6 col-12">
                        @if ($has_customer)
                            <div class="card mt-5">
                              <div class="card-header bg-secondary text-white">
                                Customer Details
                              </div>
                              <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="card-text"><strong>Name: </strong> {{ $customer->name }}</p>        
                                    </div>
                                    <div class="col-md-6">
                                        <p class="card-text"><strong>Email: </strong> {{ $customer->email }}</p>        
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="card-text"><strong>Phone: </strong> {{ $customer->phone }} 
                                            @if (strlen($customer->phone) != 12 || preg_match('/^[91]{2}/', $customer->phone) == false)
                                              <span class="badge badge-danger" data-toggle="tooltip" data-placement="top" title="Number must be 12 digits and start with 91">!</span>
                                            @endif
                                        </p>        
                                    </div>
                                    <div class="col-md-6">
                                        <p class="card-text"><strong>Instagram Handle: </strong> {{ $customer->instahandler }} </p>        
                                    </div>
                                </div>
                                 <div class="row">
                                    <div class="col-md-6">
                                        <p class="card-text"><strong>Address: </strong> {{ $customer->address }}</p>        
                                    </div>
                                    <div class="col-md-6">
                                        <p class="card-text"><strong>City: </strong> {{ $customer->city }} </p>        
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="card-text"><strong>Country: </strong> {{ $customer->country }}</p>        
                                    </div>
                                    <div class="col-md-6">
                                        <p class="card-text"><strong>Pincode: </strong> {{ $customer->pincode }}</p>        
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="card-text"><strong>Site Name: </strong> <a href="{{ @$customer->storeWebsite->website_url }}" target="_blank">{{$customer->storeWebsite->website ?? ''}}</a></p>        
                                    </div>
                                </div>
                                
                              </div>
                            </div>
                        @endif
                      <br>

                      <div class="card">
                          <div class="card-header bg-secondary text-white">
                            Order details
                          </div>
                          <div class="card-body">
                            <p class="card-text"> 
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                        <strong> Order Type :</strong>
                                        <?php
                                            $order_types = [
                                              'offline' => 'offline',
                                                  'online' => 'online'
                                              ];
                                            echo Form::select('order_type',$order_types, ( old('order_type') ? old('order_type') : $order_type ), ['class' => 'form-control']);?>
                                              @if ($errors->has('order_type'))
                                                  <div class="alert alert-danger">{{$errors->first('order_type')}}</div>
                                              @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <strong>Sale Order No. </strong> 
                                            <input type="text" value="{{ $order_id }}" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <strong>Order Date:</strong>
                                            <input type="date" class="form-control" name="order_date" placeholder="Order Date"
                                                   value="{{ old('order_date') ? old('order_date') : $order_date }}"/>
                                            @if ($errors->has('order_date'))
                                                <div class="alert alert-danger">{{$errors->first('order_date')}}</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </p>
                            <p class="card-text">
                                <div class="row">
                                    <div class="col-md-4">
                                    <div class="form-group">
                                        <strong> Price :</strong>
                                        <input type="text" value="{{ $total_price }}" class="form-control">
                                    </div>
                                    </div>
                                    <div class="col-md-4">
                                          <div class="form-group">
                                          <strong>Date of Delivery:</strong>
                                          <input type="date" class="form-control" name="date_of_delivery" placeholder="Date of Delivery"
                                                value="{{ old('date_of_delivery') ? old('date_of_delivery') : $date_of_delivery }}"/>
                                          @if ($errors->has('date_of_delivery'))
                                              <div class="alert alert-danger">{{$errors->first('date_of_delivery')}}</div>
                                          @endif
                                      </div>
                                        </div>
                                        <div class="col-md-4">
                                          @php $status = ( new \App\ReadOnly\OrderStatus )->getNameById( $order_status ); @endphp
                                          <div class="form-group">
                                              <strong>status:</strong>
                                              <Select name="order_status_id" class="form-control" id="order_status">
                                                    @foreach($order_statuses as $key => $value)
                                                    <option value="{{$key}}" {{$order_status_id == $key ? 'Selected=Selected':''}}>{{$value->status}}</option>
                                                    @endforeach
                                              </Select>
                                              <span id="change_status_message" class="text-success" style="display: none;">Successfully changed status</span>
                                          </div>
                                        </div>
                                  </div>
                            </p>
                            <p class="card-text">
                                <div class="row">
                                    <div class="col-md-4">
                                      <div class="form-group">
                                      <strong>Estimated Delivery Date:</strong>
                                      <input type="date" class="form-control" name="estimated_delivery_date" placeholder="Advance Date"
                                              value="{{ old('estimated_delivery_date') ? old('estimated_delivery_date') : $estimated_delivery_date }}"/>
                                      @if ($errors->has('estimated_delivery_date'))
                                          <div class="alert alert-danger">{{$errors->first('estimated_delivery_date')}}</div>
                                      @endif
                                    </div>
                                    </div>
                                    <div class="col-md-4">
                                    <div class="form-group">
                                        <strong>Shoe Size:</strong>
                                        <input type="text" class="form-control" name="shoe_size" placeholder="Shoe Size"
                                                value="{{ old('shoe_size') ? old('shoe_size') : $shoe_size }}"/>
                                    </div>
                                    </div>
                                    <div class="col-md-4">
                                      <div class="form-group">
                                      <strong>Clothing Size:</strong>
                                      <input type="text" class="form-control" name="clothing_size" placeholder="Clothing Size"
                                              value="{{ old('clothing_size') ? old('clothing_size') : $clothing_size }}"/>
                                    </div>
                                    </div>
                                </div>
                            </p>
                            <p class="card-text">
                                <div class="row">
                                    <div class="col-md-4">
                                      <div class="form-group">
                                      <strong>Note if any:</strong>
                                      <input type="text" class="form-control" name="note_if_any" placeholder="Note if any"
                                              value="{{ old('note_if_any') ? old('note_if_any') : $note_if_any }}"/>
                                      @if ($errors->has('note_if_any'))
                                          <div class="alert alert-danger">{{$errors->first('note_if_any')}}</div>
                                      @endif
                                      </div>
                                    </div>
                                    <div class="col-md-4">
                                          <div class="form-group">
                                              <strong>Created by:</strong>
                                              <input class="form-control" type="text" value="{{ $user_id != 0 ? App\Helpers::getUserNameById($user_id) : 'Unknown' }}">
                                          </div>
                                    </div>
                                    <div class="col-md-4">
                                    <div class="form-group">
                                      @if (strlen($contact_detail) < 12)
                                        <span class="badge badge-danger" data-toggle="tooltip" data-placement="top" title="Number must be 12 digits and start with 91">!</span>
                                      @endif
                                        <strong>Contact Detail:</strong>
                                        <input type="text" class="form-control" name="contact_detail" placeholder="Contact Detail"
                                              value="{{ old('contact_detail') ? old('contact_detail') : $contact_detail }}"/>
                                        @if ($errors->has('contact_detail'))
                                            <div class="alert alert-danger">{{$errors->first('contact_detail')}}</div>
                                        @endif
                                    </div>
                                  </div>
                                </div>
                            </p>
                            <p class="card-text">
                                <div class="row">
                                    <div class="col-md-12">
                                    <div id="tracking-wrapper-{{ $id }}" style="display: {{ $order_status == 'Product shiped to Client' ? 'block' : 'none' }}">
                                      <div class="form-group">
                                        <strong>AWB Number:</strong>
                                        <input type="text" name="awb" class="form-control" id="awb_field_{{ $id }}" value="{{ $awb }}" placeholder="00000000000">
                                        <button type="button" class="btn btn-xs btn-secondary mt-1 track-shipment-button" data-id="{{ $id }}">Track</button>
                                      </div>

                                      <div class="form-group" id="tracking-container-{{ $id }}">

                                      </div>
                                    </div>
                                    </div>
                                </div>
                            </p>
                            <p class="card-text">
                                <div class="row">
                                  <div class="col-md-12">
                                    <div class="form-group">
                                        <strong>Remark</strong>
                                        <p>{{ $remark }}</p>
                                    </div>
                                  </div>
                                </div>
                            </p>
                          </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-12 mt-5">
                        <div class="card">
                          <div class="card-header bg-secondary text-white">Payment Details</div>
                          <div class="card-body">
                            <p class="card-text">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                          <strong>Balance Amount:</strong>
                                          <input type="text" class="form-control" name="balance_amount" placeholder="Balance Amount"
                                                value="{{ old('balance_amount') ? old('balance_amount') : $balance_amount }}"/>
                                          @if ($errors->has('balance_amount'))
                                              <div class="alert alert-danger">{{$errors->first('balance_amount')}}</div>
                                          @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                    <div class="form-group">
                                    <strong> Payment Mode :</strong>
                                    <?php
                                    $paymentModes = new \App\ReadOnly\PaymentModes();

                                    echo Form::select('payment_mode',$paymentModes->all(), ( old('payment_mode') ? old('payment_mode') : $payment_mode ), ['placeholder' => 'Select a mode','class' => 'form-control']);?>

                                          @if ($errors->has('payment_mode'))
                                              <div class="alert alert-danger">{{$errors->first('payment_mode')}}</div>
                                          @endif
                                      </div>
                                    </div>
                                    <div class="col-md-4">
                                    <div class="form-group">
                                      <strong>Advance Amount:</strong>
                                      <input type="text" class="form-control" name="advance_detail" placeholder="Advance Detail"
                                            value="{{ old('advance_detail') ? old('advance_detail') : $advance_detail }}"/>
                                      @if ($errors->has('advance_detail'))
                                          <div class="alert alert-danger">{{$errors->first('advance_detail')}}</div>
                                      @endif
                                  </div>
                                  </div>
                                </div>
                            </p>
                            <p class="card-text">
                                <div class="row">
                                  <div class="col-md-6">
                                    <div class="form-group">
                                        <strong>Received By:</strong>
                                        <input type="text" class="form-control" name="received_by" placeholder="Received By"
                                              value="{{ old('received_by') ? old('received_by') : $received_by }}"/>
                                        @if ($errors->has('received_by'))
                                            <div class="alert alert-danger">{{$errors->first('received_by')}}</div>
                                        @endif
                                      </div>
                                    </div>
                                    <div class="col-md-6">
                                    <div class="form-group">
                                        <strong>Advance Date:</strong>
                                        <input type="date" class="form-control" name="advance_date" placeholder="Advance Date"
                                               value="{{ old('advance_date') ? old('advance_date') : $advance_date }}"/>
                                        @if ($errors->has('advance_date'))
                                            <div class="alert alert-danger">{{$errors->first('advance_date')}}</div>
                                        @endif
                                    </div>
                                    </div>
                                </div>
                            </p>
                            <p class="card-text">
                                @if ($has_customer)
                                    <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#instructionModal">Add Instruction</button>
                                @endif
                                @if (isset($waybills) && !$waybills->isEmpty())
                                  @foreach($waybills as $way)
                                    <div class="form-group">
                                      <strong>AWB: </strong> {{ $way->awb }}
                                      <br>
                                      <a href="{{ route('order.download.package-slip', $way->id) }}" class="btn-link">Download Package Slip</a>
                                      <a href="javascript:;" data-id="{{ $way->id }}" data-awb="{{ $way->awb }}" class="btn-link track-package-slip">Track Package Slip</a>
                                    </div>
                                  @endforeach
                                @else
                                    <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#generateAWBMODAL">Generate AWB</button>
                                @endif
                            </p>
                          </div>
                        </div>
                        <br>
                        <div class="card">
                          <div class="card-header bg-secondary text-white">Store details</div>
                          <div class="card-body">
                            <div class="row">
                                <div class="col-md-6"><strong>Store name: </strong> {{ $order->store_name ?? '--'}}</div>
                                <div class="col-md-2"><strong>Store id: </strong> {{ $order->store_id ?? '--'}}</div>
                                <div class="col-md-6"><strong>store currency code: </strong> {{ $order->store_currency_code ?? '--'}}</div>
                            </div>
                          </div>
                        </div>
                        <br>
                        <div class="card">
                          <div class="card-header bg-secondary text-white">Shipping address</div>
                          <div class="card-body">
                            <div class="row">
                                <div class="col-md-6"><strong>First name: </strong> {{ $shipping_address->firstname ?? '--'}}</div>
                                <div class="col-md-6"><strong>Last name: </strong> {{ $shipping_address->lastname ?? '--'}}</div>
                                
                            </div>
                            <div class="row">
                                <div class="col-md-12"><strong>street: </strong> {{ $shipping_address->street ?? '--'}}</div>
                            </div>
                            <div class="row">
                                <div class="col-md-4"><strong>city: </strong> {{ $shipping_address->city ?? '--'}}</div>
                                <div class="col-md-4"><strong>country: </strong> {{ $shipping_address->country_id ?? '--'}}</div>
                                <div class="col-md-4"><strong>postcode: </strong> {{ $shipping_address->postcode ?? '--'}}</div>
                            </div>
                            <div class="row">
                                <div class="col-md-6"><strong>telephone: </strong> {{ $shipping_address->telephone ?? '--' }}</div>
                                <div class="col-md-6"><strong>Email : </strong> {{ $shipping_address->email ?? '--'}}</div>
                            </div>
                          </div>
                        </div>
                        <br>
                        <div class="card">
                          <div class="card-header bg-secondary text-white">Billing address</div>
                          <div class="card-body">
                            <div class="row">
                                <div class="col-md-6"><strong>First name: </strong> {{ $billing_address->firstname ?? '--'}}</div>
                                <div class="col-md-6"><strong>Last name: </strong> {{ $billing_address->lastname ?? '--'}}</div>
                                
                            </div>
                            <div class="row">
                                <div class="col-md-12"><strong>street: </strong> {{ $billing_address->street ?? '--' }}</div>
                            </div>
                            <div class="row">
                                <div class="col-md-4"><strong>city: </strong> {{ $billing_address->city ?? '--'}}</div>
                                <div class="col-md-4"><strong>country: </strong> {{ $billing_address->country_id ?? '--'}}</div>
                                <div class="col-md-4"><strong>postcode: </strong> {{ $billing_address->postcode ?? '--' }}</div>
                            </div>
                            <div class="row">
                                <div class="col-md-6"><strong>telephone: </strong> {{ $billing_address->telephone ?? '--' }}</div>
                                <div class="col-md-6"><strong>Email : </strong> {{ $billing_address->email ?? '--'}}</div>
                            </div>
                          </div>
                        </div>

                    </div>


                    <div class="col-xs-12">
                      <div class="">
                            <div class="card">
                              <div class="card-header bg-secondary text-white">Product Details</div>
                              <div class="card-body">
                                <p class="card-text">
                                    <div class="form-group">
                                      <table class="table table-bordered" id="products-table">
                                          <tr>
                                              <th>Image</th>
                                              <th>Name</th>
                                              <th>Sku</th>
                                              <th>Color</th>
                                              <th>Brand</th>
                                              <th style="width: 100px">Price</th>
                                              <th style="width: 100px">Size</th>
                                              <th style="width: 80px">Qty</th>
                                              <th>Action</th>
                                          </tr>
                                          @foreach($order_products  as $order_product)
                                              <tr>
                                                  @if(isset($order_product['product']))
                                                    <th><img width="150" src="{{ $order_product['product']['image'] }}" /></th>
                                                    <th>{{ $order_product['product']['name'] }}</th>
                                                    <th>{{ $order_product['product']['sku'] }}</th>
                                                    <th>{{ $order_product['product']['color'] }}</th>
                                                    <th>{{ \App\Http\Controllers\BrandController::getBrandName($order_product['product']['brand']) }}</th>
                                                  @else
                                                    <th></th>
                                                    <th></th>
                                                    <th>{{$order_product['sku']}}</th>
                                                    <th></th>
                                                    <th></th>
                                                  @endif

                                                  <th>
                                                      <input class="form-control" type="text" value="{{ $order_product['product_price'] }}" name="order_products[{{ $order_product['id'] }}][product_price]">
                                                  </th>
                                                  <th>
                                                      @if(!empty($order_product['product']['size']))
                                                        <?php

                                                        $sizes = \App\Helpers::explodeToArray($order_product['product']['size']);
                                                        $size_name = 'order_products['.$order_product['id'].'][size]';

                                                        echo Form::select($size_name,$sizes,( $order_product['size'] ), ['class' => 'form-control', 'placeholder' => 'Select a size'])
                                                        ?>
                                                      @else
                                                          <select hidden class="form-control" name="order_products[{{ $order_product['id'] }}][size]">
                                                              <option selected="selected" value=""></option>
                                                          </select>
                                                          nil
                                                      @endif
                                                  </th>
                                                  <th>
                                                      <input class="form-control" type="number" value="{{ $order_product['qty'] }}" name="order_products[{{ $order_product['id'] }}][qty]">
                                                  </th>
                                                  @if(isset($order_product['product']))
                                                      <th>
                                                          <a class="btn btn-image" href="{{ route('products.show',$order_product['product']['id']) }}"><img src="/images/view.png" /></a>
                                                          <a class="btn btn-image remove-product" href="#" data-product="{{ $order_product['id'] }}"><img src="/images/delete.png" /></a>
                                                      </th>
                                                  @else
                                                      <th></th>
                                                  @endif
                                              </tr>
                                          @endforeach
                                      </table>
                                  </div>
                                </p>
                                <p class="card-text">
                                     <div class="form-group btn-group">
                                          <a href="{{ route('attachProducts',['order',$id]) }}" class="btn btn-image"><img src="/images/attach.png" /></a>
                                          <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#productModal">+</button>&nbsp;&nbsp;
                                          <button type="submit" class="btn btn-secondary" id="submitButton">Update</button>
                                      </div>
                                </p>
                                <p class="card-text"></p>
                              </div>
                            </div>
                      </div>
                      <div id="productModal" class="modal fade" role="dialog">
                        <div class="modal-dialog">

                          <!-- Modal content-->
                          <div class="modal-content">
                            <div class="modal-header">
                              <h4 class="modal-title">Create Product</h4>
                              <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body">
                              <div class="form-group">
                                  <strong>Image:</strong>
                                  <input type="file" class="form-control" name="image"
                                         value="{{ old('image') }}" id="product-image"/>
                                  @if ($errors->has('image'))
                                      <div class="alert alert-danger">{{$errors->first('image')}}</div>
                                  @endif
                              </div>

                              <div class="form-group">
                                  <strong>Name:</strong>
                                  <input type="text" class="form-control" name="name" placeholder="Name"
                                         value="{{ old('name') }}"  id="product-name"/>
                                  @if ($errors->has('name'))
                                      <div class="alert alert-danger">{{$errors->first('name')}}</div>
                                  @endif
                              </div>

                              <div class="form-group">
                                  <strong>SKU:</strong>
                                  <input type="text" class="form-control" name="sku" placeholder="SKU"
                                         value="{{ old('sku') }}"  id="product-sku"/>
                                  @if ($errors->has('sku'))
                                      <div class="alert alert-danger">{{$errors->first('sku')}}</div>
                                  @endif
                              </div>

                              <div class="form-group">
                                  <strong>Color:</strong>
                                  <input type="text" class="form-control" name="color" placeholder="Color"
                                         value="{{ old('color') }}"  id="product-color"/>
                                  @if ($errors->has('color'))
                                      <div class="alert alert-danger">{{$errors->first('color')}}</div>
                                  @endif
                              </div>

                              <div class="form-group">
                                  <strong>Brand:</strong>
                                  <?php
                	                $brands = \App\Brand::getAll();
                	                echo Form::select('brand',$brands, ( old('brand') ? old('brand') : '' ), ['placeholder' => 'Select a brand','class' => 'form-control', 'id'  => 'product-brand']);?>
                                    {{--<input type="text" class="form-control" name="brand" placeholder="Brand" value="{{ old('brand') ? old('brand') : $brand }}"/>--}}
                                    @if ($errors->has('brand'))
                                        <div class="alert alert-danger">{{$errors->first('brand')}}</div>
                                    @endif
                              </div>

                              <div class="form-group">
                                  <strong>Special Price (INR):</strong>
                                  <input type="number" class="form-control" name="price_inr_special" placeholder="Special Price (INR):"
                                         value="{{ old('price_inr_special') }}" step=".01"  id="product-price"/>
                                  @if ($errors->has('price_inr_special'))
                                      <div class="alert alert-danger">{{$errors->first('price_inr_special')}}</div>
                                  @endif
                              </div>

                              <div class="form-group">
                                  <strong>Size:</strong>
                                  <input type="text" class="form-control" name="size" placeholder="Size"
                                         value="{{ old('size') }}"  id="product-size"/>
                                  @if ($errors->has('size'))
                                      <div class="alert alert-danger">{{$errors->first('size')}}</div>
                                  @endif
                              </div>

                              <div class="form-group">
                                  <strong>Quantity:</strong>
                                  <input type="number" class="form-control" name="quantity" placeholder="Quantity"
                                         value="{{ old('quantity') }}"  id="product-quantity"/>
                                  @if ($errors->has('quantity'))
                                      <div class="alert alert-danger">{{$errors->first('quantity')}}</div>
                                  @endif
                              </div>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                              <button type="button" class="btn btn-success" id="createProduct">Create</button>
                            </div>
                          </div>

                        </div>
                      </div>
                    </div>

                    <!-- <div class="col-xs-12 text-center">
                    </div> -->
                </div>
              </form>

              @if ($has_customer)
                <div id="instructionModal" class="modal fade" role="dialog">
                  <div class="modal-dialog">

                    <!-- Modal content-->
                    <div class="modal-content">
                      <form action="{{ route('instruction.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="customer_id" value="{{ $customer->id }}">

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

              <div id="statusModal" class="modal fade" role="dialog">
                <div class="modal-dialog">

                  <!-- Modal content-->
                  <div class="modal-content">
                    <div class="modal-header">
                      <h4 class="modal-title">Create Action</h4>
                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <form action="{{ route('status.store') }}" method="POST" enctype="multipart/form-data">
                      @csrf

                      <div class="modal-body">
                        <div class="form-group">
                            <strong>Order Status:</strong>
                             <input type="text" class="form-control" name="status" placeholder="Order Status" id="status" required />
                             @if ($errors->has('status'))
                                 <div class="alert alert-danger">{{$errors->first('status')}}</div>
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

              <div class="row">
                <div class="col-md-12 col-12 mb-3">
                  <form action="{{ route('status.report.store') }}" method="POST">
                    @csrf

                    <input type="hidden" name="order_id" value="{{ $id }}">

                    <div class="row">
                        <div class="col-md-4">
                          <div class="form-group">
                          <strong>Next action due</strong>
                          <a href="#" data-toggle="modal" data-target="#statusModal" class="btn-link">Add Action</a>

                          <select class="form-control" name="status_id" required>
                            <option value="">Select action</option>
                            @foreach ($order_status_report as $status)
                              <option value="{{ $status->id }}">{{ $status->status }}</option>
                            @endforeach
                          </select>
                        </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group" id="completion_form_group">
                            <strong>Completion Date:</strong>
                            <div class='input-group date' id='completion-datetime'>
                              <input type='text' class="form-control" name="completion_date" value="{{ date('Y-m-d H:i') }}" />

                              <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                              </span>
                            </div>

                            @if ($errors->has('completion_date'))
                                <div class="alert alert-danger">{{$errors->first('completion_date')}}</div>
                            @endif
                          </div>
                        
                        </div>
                        <div class="col-md-4">
                          <button style="margin-top: 20px;" type="submit" class="btn btn-secondary">Add Report</button>
                          </div>
                    </div>
                  </form>

                  @if (isset($order_reports) && count($order_reports) > 0)
                    <h4>Order Reports</h4>

                    <table class="table table-bordered mt-4">
                      <thead>
                        <tr>
                          <th>Status</th>
                          <th>Created at</th>
                          <th>Creator</th>
                          <th>Due date</th>
                        </tr>
                      </thead>

                      <tbody>
                        @php $users_array = \App\Helpers::getUserArray(\App\User::all()); @endphp
                        @foreach ($order_reports as $report)
                          <tr>
                            <td>{{ $report->status }}</td>
                            <td>{{ Carbon\Carbon::parse($report->created_at)->format('d-m H:i') }}</td>
                            <td>{{ $users_array[$report->user_id] }}</td>
                            <td>{{ Carbon\Carbon::parse($report->completion_date)->format('d-m H:i') }}</td>
                          </tr>
                        @endforeach
                      </tbody>
                    </table>
                  @endif
                </div>
              </div>


                {{-- <div class="form-group">
                    <div class="text-center">
                        <h3> Products Details:</h3>
                    </div>
                    <table class="table table-bordered mt-4">
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Sku</th>
                            <th>Color</th>
                            <th>Brand</th>
                            <th>Price</th>
                            <th>Size</th>
                            <th>Qty</th>
                            <th style="width: 100px">Action</th>
                        </tr>
                        @foreach($order_products  as $order_product)
                            <tr>
                                @if(isset($order_product['product']))
                                    <th><img width="200" src="{{ $order_product['product']['image'] }}" /></th>
                                    <th>{{ $order_product['product']['name'] }}</th>
                                    <th>{{ $order_product['product']['sku'] }}</th>
                                    <th>{{ $order_product['product']['color'] }}</th>
                                    <th>{{ \App\Http\Controllers\BrandController::getBrandName($order_product['product']['brand']) }}</th>
                                @else
                                    <th></th>
                                    <th></th>
                                    <th>{{$order_product['sku']}}</th>
                                    <th></th>
                                    <th></th>
                                @endif

                                <th>{{ $order_product['product_price']}}</th>
                                <th>{{$order_product['size']}}</th>
                                <th>{{ $order_product['qty'] }}</th>

                                @if(isset($order_product['product']))
                                    <th>
                                        <a class="btn btn-image"
                                           href="{{ route('products.show',$order_product['product']['id']) }}"><img src="/images/view.png" /></a>
                                    </th>
                                @else
                                    <th></th>
                                @endif
                            </tr>
                        @endforeach
                    </table>
                </div> --}}

                {{-- <div class="row">
                    <div class="col-md-6 col-12"> --}}


                        </div>
                    {{-- </div>
                </div> --}}
        {{-- <div class="tab-pane" id="2">
            <div class="chat-frame">
                <div class="col-xs-12 col-sm-12">
                    <h3 style="text-center">WhatsApp Messages</h3>
                 </div>
                <div class="col-xs-12 col-sm-12">
                    <div class="row">
                       <div class="col-md-12" id="waMessages">
                       </div>
                    </div>
                </div>
            </div>

            <div class="row">
              <div class="col-xs-10">
                      <textarea id="waNewMessage" class="form-control" placeholder="Type new message.."></textarea>
                      <br/>
                      <label>Attach Media</label>
                      <input id="waMessageMedia" type="file" name="media" />
              </div>
              <div class="col-xs-2">
                  <button id="waMessageSend" class="btn btn-image"><img src="/images/filled-sent.png" /></button>
              </div>
            </div>


        </div> --}}

        <div class="tab-pane" id="3">
          <div class="row">
            <div class="col-xs-12 col-sm-12">
                <h3 style="text-center">Call Recordings</h3>
             </div>

            <div class="col-xs-12 col-sm-12">
                <div class="table-responsive">
                    <table class="table table-bordered" style="border:1px solid #ddd">
                        <thead>
                            <tr>
                                <td>Recording</td>
                                <td>Created At</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order_recordings as $recording)
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

        <div class="tab-pane" id="delivery_approval">
          <div class="row">
            <div class="col-xs-12 col-sm-12">
              <h3 style="text-center">Delivery Approval</h3>

              <form class="form-inline" action="{{ route('order.upload.approval', $id) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                  <input type="file" name="images[]" required multiple>
                </div>

                <button type="submit" class="btn btn-secondary ml-3">Upload for Approval</button>
              </form>
             </div>



            <div class="col-xs-12 col-sm-12">
              <div class="table-responsive">
                <table class="table table-bordered" style="border:1px solid #ddd">
                  <thead>
                    <tr>
                      <th>Product</th>
                      <th>Uploaded Photos</th>
                      <th>Approved</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>
                        @foreach($order_products  as $order_product)
                          @if(isset($order_product['product']))
                            <img width="150" src="{{ $order_product['product']['image'] }}" />
                          @endif
                        @endforeach
                      </td>
                      <td>
                        @if (isset($delivery_approval))
                          @foreach ($delivery_approval->getMedia(config('constants.media_tags')) as $image)
                            <img width="150" src="{{ $image->getUrl() }}" />
                          @endforeach
                        @endif
                      </td>
                      <td>
                        @if (isset($delivery_approval))
                          @if ($delivery_approval->approved == 1)
                            Approved
                          @else
                            <form action="{{ route('order.delivery.approve', $delivery_approval->id) }}" method="POST">
                              @csrf

                              <button type="submit" class="btn btn-xs btn-secondary">Approve</button>
                            </form>
                          @endif
                        @endif
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

        </div>
        <div class="tab-pane" id="customer-address">
          <div class="row">
            <div class="col-xs-12 col-sm-12">
              <h3 style="text-center">Customer address</h3>

            <div class="col-xs-12 col-sm-12">
              <div class="table-responsive">
                <table class="table fixed_header table-hover" id="latest-remark-records">
					<thead class="thead-dark">
					<tr>
						<th scope="col">Address type</th>
						<th scope="col">City</th>
						<th scope="col">Country</th>
						<th scope="col">email</th>
						<th scope="col">First name</th>
						<th scope="col">Last name</th>
						<th scope="col">Postcode</th>
						<th scope="col">Street</th>
						<th scope="col">Telephone</th>
					</tr>
					</thead>
					<tbody class="show-list-records" >
						@foreach ($customerAddress as $item)
							<tr>
								<td>{{ $item->address_type }}</td>
								<td>{{ $item->city }}</td>
								<td>{{ $item->country_id }}</td>
								<td>{{ $item->email }}</td>
								<td>{{ $item->firstname }}</td>
								<td>{{ $item->lastname }}</td>
								<td>{{ $item->postcode }}</td>
								<td>{{ $item->street }}</td>
								<td>{{ $item->telephone }}</td>
							</tr>
						@endforeach
					</tbody>
				</table>
              </div>
            </div>
          </div>

        </div>

      </div>

      <div id="taskModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Create Task</h4>
            </div>

            <form action="{{ route('task.store') }}" method="POST" enctype="multipart/form-data">
              @csrf

              <input type="hidden" name="task_type" value="quick_task">
              <input type="hidden" name="model_type" value="order">
              <input type="hidden" name="model_id" value="{{$id}}">

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
                    <input type='text' class="form-control" name="completion_date" value="{{ date('Y-m-d H:i') }}" required/>

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
                      @foreach($users as $user)
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

        <div class="row">
            <div class="col-xs-12 col-sm-12 mb-3">
              <button type="button" class="btn btn-secondary mb-3" data-toggle="modal" data-target="#taskModal" id="addTaskButton">Add Task</button>

              @if (count($tasks) > 0)
                <table class="table table-bordered" style="border:1px solid #ddd">
                    <thead>
                      <tr>
                          <th>Sr No</th>
                          <th>Date</th>
                          <th class="category">Category</th>
                          <th>Task Subject</th>
                          <th>Est Completion Date</th>
                          <th>Assigned From</th>
                          <th>&nbsp;</th>
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
                              <button class="btn btn-image px-1"><img src="/images/upload.png"/></button>
                              <input type="file" name="image"/>
                              <button type="submit" class="btn btn-image px-1 send-communication"><img src="/images/filled-sent.png" /></button>
                            </div>
                          </div>

                            <div class="form-group flex-fill">
                                <textarea class="form-control" name="body"
                                          placeholder="Received from Customer"></textarea>

                                <input type="hidden" name="moduletype" value="order"/>
                                <input type="hidden" name="moduleid" value="{{$id}}"/>
                                <input type="hidden" name="assigned_user" value="{{$sales_person}}" />
                                <input type="hidden" name="status" value="0"/>
                            </div>


                    </form>
                  </div>

                  <div class="col-xs-12 col-sm-6">
                    <form action="{{ route('message.store') }}" method="POST" enctype="multipart/form-data" class="d-flex">
                        @csrf

                          <div class="form-group">
                            <div class="upload-btn-wrapper btn-group">
                              <button class="btn btn-image px-1"><img src="/images/upload.png"/></button>
                              <input type="file" name="image"/>

                              <a href="{{ route('attachImages', ['order', $id, 1, $sales_person ? $sales_person : 6]) }}" class="btn btn-image px-1"><img src="/images/attach.png" /></a>

                              <button type="submit" class="btn btn-image px-1 send-communication"><img src="/images/filled-sent.png"/></button>
                            </div>
                          </div>

                            <div class="form-group flex-fill">
                                <textarea class="form-control mb-3" name="body"
                                          placeholder="Send for Approval" id="message-body"></textarea>

                                <input type="hidden" name="moduletype" value="order"/>
                                <input type="hidden" name="moduleid" value="{{$id}}"/>
                                <input type="hidden" name="status" value="1"/>
                                <input type="hidden" name="assigned_user" value="{{$sales_person}}" />

                                <p class="pb-4" style="display: block;">
                                    <select name="quickCategory" id="quickCategory" class="form-control mb-3">
                                      <option value="">Select Category</option>
                                      @foreach($reply_categories as $category)
                                          <option value="{{ $category->approval_orders }}">{{ $category->name }}</option>
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
                            <select class="form-control" name="category_id" id="category_id_field">
                              @foreach ($reply_categories as $category)
                                <option value="{{ $category->id }}" {{ $category->id == old('category_id') ? 'selected' : '' }}>{{ $category->name }}</option>
                              @endforeach
                            </select>

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
                              <button class="btn btn-image px-1"><img src="/images/upload.png"/></button>
                              <input type="file" name="image"/>
                              <button type="submit" class="btn btn-image px-1 send-communication"><img src="/images/filled-sent.png"/></button>
                            </div>
                          </div>
                            <div class="form-group flex-fill">
                                <textarea class="form-control mb-3" name="body"
                                          placeholder="Internal Communications" id="internal-message-body"></textarea>

                                <input type="hidden" name="moduletype" value="order"/>
                                <input type="hidden" name="moduleid" value="{{$id}}"/>
                                <input type="hidden" name="status" value="4"/>

                                <strong>Assign to</strong>
                                <select name="assigned_user" class="form-control mb-3" required>
                                  <option value="">Select User</option>
                                  @if (isset($sales_person))
                                    <option value="{{ $sales_person }}">Order Handler</option>
                                  @endif
                                  @foreach($users as $user)
                                    <option value="{{$user['id']}}">{{$user['name']}}</option>
                                  @endforeach
                                </select>

                                <p class="pb-4" style="display: block;">
                                    <select name="quickCategoryInternal" id="quickCategoryInternal" class="form-control mb-3">
                                      <option value="">Select Category</option>
                                      @foreach($reply_categories as $category)
                                          <option value="{{ $category->internal_orders }}">{{ $category->name }}</option>
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
                        <a href="{{ route('attachImages', ['order', $id, 9, $sales_person ? $sales_person : 6]) }}" class="btn btn-image px-1"><img src="/images/attach.png" /></a>
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
            </div>



            </div> --}}

            {{-- <div class="row">
              <h3>Messages</h3>
              <div class="col-xs-12" id="message-container">

              </div>
            </div>

            <div class="col-xs-12 text-center">
              <button type="button" id="load-more-messages" data-nextpage="1" class="btn btn-secondary">Load More</button>
            </div> --}}

    {{-- </div> --}}

    <form action="" method="POST" id="product-remove-form">
      @csrf
    </form>

    @if ($has_customer)
      @include("partials.modals.generate-awb-modal")
    @endif

@endsection

@include("partials.modals.tracking-event-modal")

@section('scripts')
  {{-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.bundle.min.js"></script> --}}
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
  <script src="{{ asset('/js/order-awb.js') }}"></script>

  <script type="text/javascript">
    $(document).ready(function() {
      $("body").tooltip({ selector: '[data-toggle=tooltip]' });
    });

    $('#completion-datetime, #pickup-datetime').datetimepicker({
        format: 'YYYY-MM-DD HH:mm'
    });

    $(document).on('click', '.remove-product', function(e) {
      e.preventDefault();

      var product_id = $(this).data('product');
      var url = "{{ url('deleteOrderProduct') }}/" + product_id;
      // var token = "{{ csrf_token() }}";

      $('#product-remove-form').attr('action', url);
      $('#product-remove-form').submit();
    });

    $('#createProduct').on('click', function() {
      var token = "{{ csrf_token() }}";
      var url = "{{ route('products.store') }}";
      var order_id = {{ $id }};
      var image = $('#product-image').prop('files')[0];
      var name = $('#product-name').val();
      var sku = $('#product-sku').val();
      var color = $('#product-color').val();
      var brand = $('#product-brand').val();
      var price = $('#product-price').val();
      var size = $('#product-size').val();
      var quantity = $('#product-quantity').val();

      var form_data = new FormData();
      form_data.append('_token', token);
      form_data.append('order_id', order_id);
      form_data.append('image', image);
      form_data.append('name', name);
      form_data.append('sku', sku);
      form_data.append('color', color);
      form_data.append('brand', brand);
      form_data.append('price_inr_special', price);
      form_data.append('size', size);
      form_data.append('quantity', quantity);

      $.ajax({
        type: 'POST',
        url: url,
        processData: false,
        contentType: false,
        enctype: 'multipart/form-data',
        data: form_data,
        success: function(response) {
          var brands_array = {!! json_encode(\App\Helpers::getUserArray(\App\Brand::all())) !!};
          var show_url = "{{ url('products') }}/" + response.product.id;
          var delete_url = "{{ url('deleteOrderProduct') }}/" + response.order.id;
          var product_row = '<tr><th><img width="200" src="' + response.product_image + '" /></th>';
              product_row += '<th>' + response.product.name + '</th>';
              product_row += '<th>' + response.product.sku + '</th>';
              product_row += '<th>' + response.product.color + '</th>';
              product_row += '<th>' + brands_array[response.product.brand] + '</th>';
              product_row += '<th><input class="table-input" type="text" value="' + response.product.price_inr_special + '" name="order_products[' + response.order.id + '][product_price]"></th>';
              // product_row += '<th>' + response.product.size + '</th>';

              if (response.product.size != null) {
                var exploded = response.product.size.split(',');

                product_row += '<th><select class="form-control" name="order_products[' + response.order.id + '][size]">';
                product_row += '<option selected="selected" value="">Select</option>';

                $(exploded).each(function(index, value) {
                  product_row += '<option value="' + value + '">' + value + '</option>';
                });

                product_row += '</select></th>';

              } else {
                  product_row += '<th><select hidden class="form-control" name="order_products[' + response.order.id + '][size]"><option selected="selected" value=""></option></select>nil</th>';
              }

              product_row += '<th><input class="table-input" type="number" value="' + response.order.qty + '" name="order_products[' + response.order.id + '][qty]"></th>';
              product_row += '<th><a class="btn btn-image" href="' + show_url + '"><img src="/images/view.png" /></a>';
              product_row += '<a class="btn btn-image remove-product" href="#" data-product="' + response.order.id + '"><img src="/images/delete.png" /></a></th>';
              product_row += '</tr>';

          $('#products-table').append(product_row);
          $('#productModal').modal('hide');
        }
      });
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

    $('#addTaskButton').on('click', function () {
      var client_name = "{{ $client_name }} ";

      $('#task_subject').val(client_name);
    });

    // $('#change_status').on('change', function() {
    //   var token = "{{ csrf_token() }}";
    //   var status = $(this).val();
    //   var id = {{ $id }};
    //
    //   $.ajax({
    //     url: '/order/' + id + '/changestatus',
    //     type: 'POST',
    //     data: {
    //       _token: token,
    //       status: status
    //     }
    //   }).done( function(response) {
    //     $('#tracking-wrapper-' + id).css({'display' : 'block'});
    //     $('#change_status_message').fadeIn(400);
    //     setTimeout(function () {
    //       $('#change_status_message').fadeOut(400);
    //     }, 2000);
    //   }).fail(function(errObj) {
    //     alert("Could not change status");
    //   });
    // });

    // $(document).on('click', '.change_message_status', function(e) {
    //   e.preventDefault();
    //   var url = $(this).data('url');
    //   var token = "{{ csrf_token() }}";
    //   var thiss = $(this);
    //
    //   if ($(this).hasClass('wa_send_message')) {
    //     var message_id = $(this).data('messageid');
    //     var message = $('#message_body_' + message_id).find('p').data('message').trim();
    //
    //     $.ajax({
    //       url: "{{ url('whatsapp/updateAndCreate') }}",
    //       type: 'POST',
    //       data: {
    //         _token: token,
    //         moduletype: "orders",
    //         message_id: message_id
    //       },
    //       beforeSend: function() {
    //         $(thiss).text('Loading');
    //       }
    //     }).done( function(response) {
    //       // $(thiss).remove();
    //       console.log(response);
    //     }).fail(function(errObj) {
    //       console.log(errObj);
    //       alert("Could not create whatsapp message");
    //     });
    //
    //     // $('#waNewMessage').val(message);
    //     // $('#waMessageSend').click();
    //   }
    //
    //   $.ajax({
    //     url: url,
    //     type: 'GET',
    //     beforeSend: function() {
    //       $(thiss).text('Loading');
    //     }
    //   }).done( function(response) {
    //     $(thiss).remove();
    //   }).fail(function(errObj) {
    //     alert("Could not change status");
    //   });
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
            console.log(response);

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
    //   event.preventDefault();
    //   var thiss = $(this);
    //   var image_id = $(this).data('image');
    //   var message_id = $(this).closest('.talk-bubble').find('.collapsible-message').data('messageid');
    //   var token = "{{ csrf_token() }}";
    //   var url = "{{ url('message') }}/" + message_id + '/removeImage';
    //   var type = 'message';
    //
    //   if ($(this).hasClass('whatsapp-image')) {
    //     type = "whatsapp";
    //   }
    //
    //   $.ajax({
    //     type: 'POST',
    //     url: url,
    //     data: {
    //       _token: token,
    //       image_id: image_id,
    //       message_id: message_id,
    //       type: type
    //     },
    //     success: function(data) {
    //       $(thiss).parent().remove();
    //     }
    //   });
    // });

  //   $(document).ready(function() {
  //     var container = $("div#message-container");
  // var sendBtn = $("#waMessageSend");
  // var orderId = "{{$id}}";
  //
  //     var addElapse = false;
  //     function errorHandler(error) {
  //         console.error("error occured: " , error);
  //     }
  //     function approveMessage(element, message) {
  //         $.post( "/whatsapp/approve/orders", { messageId: message.id })
  //           .done(function( data ) {
  //             // if (data != 'success') {
  //             //   data.forEach(function(id) {
  //             //     $('#waMessage_' + id).find('.btn-approve').remove();
  //             //   });
  //             // }
  //
  //             element.remove();
  //           }).fail(function(response) {
  //             console.log(response);
  //             alert(response.responseJSON.message);
  //           });
  //     }
  //     function createMessageArgs() {
  //          var data = new FormData();
  //         var text = $("#waNewMessage").val();
  //         var files = $("#waMessageMedia").prop("files");
  //         var text = $("#waNewMessage").val();
  //
  //         data.append("order_id", orderId);
  //         if (files && files.length>0){
  //             for ( var i = 0; i != files.length; i ++ ) {
  //               data.append("media[]", files[ i ]);
  //             }
  //             return data;
  //         }
  //         if (text !== "") {
  //             data.append("message", text);
  //             return data;
  //         }
  //
  //         alert("please enter a message or attach media");
  //       }
  //
  // function renderMessage(message, tobottom = null) {
  //     var domId = "waMessage_" + message.id;
  //     var current = $("#" + domId);
  //     var is_admin = "{{ Auth::user()->hasRole('Admin') }}";
  //     var is_hod_crm = "{{ Auth::user()->hasRole('HOD of CRM') }}";
  //     var users_array = {!! json_encode($users_array) !!};
  //     if ( current.get( 0 ) ) {
  //       return false;
  //     }
  //
  //     if (message.body) {
  //       var orders_assigned_user = "{{ $sales_person }}";
  //
  //       var text = $("<div class='talktext'></div>");
  //       var p = $("<p class='collapsible-message'></p>");
  //
  //       if ((message.body).indexOf('<br>') !== -1) {
  //         var splitted = message.body.split('<br>');
  //         var short_message = splitted[0].length > 150 ? (splitted[0].substring(0, 147) + '...<br>' + splitted[1]) : message.body;
  //         var long_message = message.body;
  //       } else {
  //         var short_message = message.body.length > 150 ? (message.body.substring(0, 147) + '...') : message.body;
  //         var long_message = message.body;
  //       }
  //
  //       var images = '';
  //       if (message.images !== null) {
  //         message.images.forEach(function (image) {
  //           images += '<div class="thumbnail-wrapper"><img src="' + image.image + '" class="message-img thumbnail-200" /><span class="thumbnail-delete" data-image="' + image.key + '">x</span></div>';
  //         });
  //         images += '<br>';
  //       }
  //
  //       p.attr("data-messageshort", short_message);
  //       p.attr("data-message", long_message);
  //       p.attr("data-expanded", "false");
  //       p.attr("data-messageid", message.id);
  //       p.html(short_message);
  //
  //       if (message.status == 0 || message.status == 5 || message.status == 6) {
  //         var row = $("<div class='talk-bubble'></div>");
  //
  //         var meta = $("<em>Customer " + moment(message.created_at).format('DD-MM H:m') + " </em>");
  //         var mark_read = $("<a href data-url='/message/updatestatus?status=5&id=" + message.id + "&moduleid=" + message.moduleid + "&moduletype=order' style='font-size: 9px' class='change_message_status'>Mark as Read </a><span> | </span>");
  //         var mark_replied = $('<a href data-url="/message/updatestatus?status=6&id=' + message.id + '&moduleid=' + message.moduleid + '&moduletype=order" style="font-size: 9px" class="change_message_status">Mark as Replied </a>');
  //
  //         row.attr("id", domId);
  //
  //         p.appendTo(text);
  //         $(images).appendTo(text);
  //         meta.appendTo(text);
  //
  //         if (message.status == 0) {
  //           mark_read.appendTo(meta);
  //         }
  //         if (message.status == 0 || message.status == 5) {
  //           mark_replied.appendTo(meta);
  //         }
  //
  //         text.appendTo(row);
  //
  //         if (tobottom) {
  //           row.appendTo(container);
  //         } else {
  //           row.prependTo(container);
  //         }
  //
  //       } else if (message.status == 4) {
  //         var row = $("<div class='talk-bubble' data-messageid='" + message.id + "'></div>");
  //         var chat_friend =  (message.assigned_to != 0 && message.assigned_to != orders_assigned_user && message.userid != message.assigned_to) ? ' - ' + users_array[message.assigned_to] : '';
  //         var meta = $("<em>" + users_array[message.userid] + " " + chat_friend + " " + moment(message.created_at).format('DD-MM H:m') + " <img id='status_img_" + message.id + "' src='/images/1.png' /> &nbsp;</em>");
  //
  //         row.attr("id", domId);
  //
  //         p.appendTo(text);
  //         $(images).appendTo(text);
  //         meta.appendTo(text);
  //
  //         text.appendTo(row);
  //         if (tobottom) {
  //           row.appendTo(container);
  //         } else {
  //           row.prependTo(container);
  //         }
  //       } else {
  //         var row = $("<div class='talk-bubble' data-messageid='" + message.id + "'></div>");
  //         var body = $("<span id='message_body_" + message.id + "'></span>");
  //         var edit_field = $('<textarea name="message_body" rows="8" class="form-control" id="edit-message-textarea' + message.id + '" style="display: none;">' + message.body + '</textarea>');
  //         var meta = "<em>" + users_array[message.userid] + " " + moment(message.created_at).format('DD-MM H:m') + " <img id='status_img_" + message.id + "' src='/images/" + message.status + ".png' /> &nbsp;";
  //
  //         if (message.status == 2 && is_admin == false) {
  //           meta += '<a href data-url="/message/updatestatus?status=3&id=' + message.id + '&moduleid=' + message.moduleid + '&moduletype=order" style="font-size: 9px" class="change_message_status">Mark as sent </a>';
  //         }
  //
  //         if (message.status == 1 && (is_admin == true || is_hod_crm)) {
  //           meta += '<a href data-url="/message/updatestatus?status=2&id=' + message.id + '&moduleid=' + message.moduleid + '&moduletype=order" style="font-size: 9px" class="change_message_status wa_send_message" data-messageid="' + message.id + '">Approve</a>';
  //           meta += ' <a href="#" style="font-size: 9px" class="edit-message" data-messageid="' + message.id + '">Edit</a>';
  //         }
  //
  //         meta += "</em>";
  //         var meta_content = $(meta);
  //
  //
  //
  //         row.attr("id", domId);
  //
  //         p.appendTo(body);
  //         body.appendTo(text);
  //         edit_field.appendTo(text);
  //         $(images).appendTo(text);
  //         meta_content.appendTo(text);
  //
  //         if (message.status == 2 && is_admin == false) {
  //           var copy_button = $('<button class="copy-button btn btn-secondary" data-id="' + message.id + '" moduleid="' + message.moduleid + '" moduletype="orders" data-message="' + message.body + '"> Copy message </button>');
  //           copy_button.appendTo(text);
  //         }
  //
  //
  //
  //         text.appendTo(row);
  //         if (tobottom) {
  //           row.appendTo(container);
  //         } else {
  //           row.prependTo(container);
  //         }
  //       }
  //     } else {
  //       var row = $("<div class='talk-bubble'></div>");
  //       var text = $("<div class='talktext'></div>");
  //       var p = $("<p class='collapsible-message'></p>");
  //
  //       if (!message.received) {
  //         var meta = $("<em>" + (parseInt(message.user_id) !== 0 ? users_array[message.user_id] : "Unknown") + " " + moment(message.created_at).format('DD-MM H:m') + " </em>");
  //       } else {
  //         var meta = $("<em>Customer " + moment(message.created_at).format('DD-MM H:m') + " </em>");
  //       }
  //
  //       row.attr("id", domId);
  //
  //       p.attr("data-messageshort", message.message);
  //       p.attr("data-message", message.message);
  //       p.attr("data-expanded", "true");
  //       p.attr("data-messageid", message.id);
  //
  //       if ( message.message ) {
  //           p.html( message.message );
  //       } else if ( message.media_url ) {
  //           var splitted = message.content_type[1].split("/");
  //           if (splitted[0]==="image") {
  //               var a = $("<a></a>");
  //               a.attr("target", "_blank");
  //               a.attr("href", message.media_url);
  //               var img = $("<img></img>");
  //               img.attr("src", message.media_url);
  //               img.attr("width", "100");
  //               img.attr("height", "100");
  //               img.appendTo( a );
  //               a.appendTo( p );
  //               console.log("rendered image message ", a);
  //           } else if (splitted[0]==="video") {
  //               $("<a target='_blank' href='" + message.media_url+"'>"+ message.media_url + "</a>").appendTo(p);
  //           }
  //       } else if (message.images) {
  //         var images = '';
  //         message.images.forEach(function (image) {
  //           images += image.product_id !== '' ? '<a href="/products/' + image.product_id + '" data-toggle="tooltip" data-html="true" data-placement="top" title="<strong>Special Price: </strong>' + image.special_price + '<br><strong>Size: </strong>' + image.size + '">' : '';
  //           images += '<div class="thumbnail-wrapper"><img src="' + image.image + '" class="message-img thumbnail-200" /><span class="thumbnail-delete whatsapp-image" data-image="' + image.key + '">x</span></div>';
  //           images += image.product_id !== '' ? '</a>' : '';
  //         });
  //         images += '<br>';
  //         $(images).appendTo(p);
  //       }
  //
  //       p.appendTo( text );
  //       meta.appendTo(text);
  //       if (!message.received) {
  //         if (!message.approved) {
  //             var approveBtn = $("<button class='btn btn-xs btn-secondary btn-approve ml-3'>Approve</button>");
  //             approveBtn.click(function() {
  //                 approveMessage( this, message );
  //             } );
  //
  //             if (is_admin || is_hod_crm) {
  //               approveBtn.appendTo( text );
  //             }
  //         }
  //       } else {
  //         var moduleid = "{{ $id }}";
  //         var mark_read = $("<a href data-url='/whatsapp/updatestatus?status=5&id=" + message.id + "&moduleid=" + moduleid+ "&moduletype=order' style='font-size: 9px' class='change_message_status'>Mark as Read </a><span> | </span>");
  //         var mark_replied = $('<a href data-url="/whatsapp/updatestatus?status=6&id=' + message.id + '&moduleid=' + moduleid + '&moduletype=order" style="font-size: 9px" class="change_message_status">Mark as Replied </a>');
  //
  //         if (message.status == 0) {
  //           mark_read.appendTo(meta);
  //         }
  //         if (message.status == 0 || message.status == 5) {
  //           mark_replied.appendTo(meta);
  //         }
  //       }
  //
  //       text.appendTo( row );
  //       if (tobottom) {
  //         row.appendTo(container);
  //       } else {
  //         row.prependTo(container);
  //       }
  //     }
  //             return true;
  // }
  // function pollMessages(page = null, tobottom = null, addElapse = null) {
  //         var qs = "";
  //         qs += "/orders?orderId=" + orderId;
  //         if (page) {
  //           qs += "&page=" + page;
  //         }
  //         if (addElapse) {
  //             qs += "&elapse=3600";
  //         }
  //         var anyNewMessages = false;
  //         return new Promise(function(resolve, reject) {
  //             $.getJSON("/whatsapp/pollMessages" + qs, function( data ) {
  //
  //                 data.data.forEach(function( message ) {
  //                     var rendered = renderMessage( message, tobottom );
  //                     if ( !anyNewMessages && rendered ) {
  //                         anyNewMessages = true;
  //                     }
  //                 } );
  //
  //                 if ( anyNewMessages ) {
  //                     scrollChatTop();
  //                     anyNewMessages = false;
  //                 }
  //                 if (!addElapse) {
  //                     addElapse = true; // load less messages now
  //                 }
  //                 resolve();
  //             });
  //         });
  // }
  //     function scrollChatTop() {
  //         console.log("scrollChatTop called");
  //         // var el = $(".chat-frame");
  //         // el.scrollTop(el[0].scrollHeight - el[0].clientHeight);
  //     }
  // function startPolling() {
  //   setTimeout( function() {
  //             pollMessages(null, null, addElapse).then(function() {
  //                 startPolling();
  //             }, errorHandler);
  //         }, 1000);
  // }
  // function sendWAMessage() {
  //   var data = createMessageArgs();
  //         //var data = new FormData();
  //         //data.append("message", $("#waNewMessage").val());
  //         //data.append("order_id", orderId );
  //   $.ajax({
  //     url: '/whatsapp/sendMessage/orders',
  //     type: 'POST',
  //             "dataType"    : 'text',           // what to expect back from the PHP script, if anything
  //             "cache"       : false,
  //             "contentType" : false,
  //             "processData" : false,
  //             "data": data
  //   }).done( function(response) {
  //     $('#waNewMessage').val('');
  //     pollMessages();
  //     // console.log("message was sent");
  //   }).fail(function(errObj) {
  //     alert("Could not send message");
  //   });
  // }
  //
  // sendBtn.click(function() {
  //   sendWAMessage();
  // } );
  // startPolling();
  //
  // $(document).on('click', '.send-communication', function(e) {
  //   e.preventDefault();
  //
  //   var thiss = $(this);
  //   var url = $(this).closest('form').attr('action');
  //   var token = "{{ csrf_token() }}";
  //   var file = $($(this).closest('form').find('input[type="file"]'))[0].files[0];
  //   var status = $(this).closest('form').find('input[name="status"]').val();
  //   var formData = new FormData();
  //
  //   formData.append("_token", token);
  //   formData.append("image", file);
  //   formData.append("body", $(this).closest('form').find('textarea').val());
  //   formData.append("moduletype", $(this).closest('form').find('input[name="moduletype"]').val());
  //   formData.append("moduleid", $(this).closest('form').find('input[name="moduleid"]').val());
  //   formData.append("assigned_user", $(this).closest('form').find('input[name="assigned_user"]').val());
  //   formData.append("status", status);
  //
  //   if (status == 4) {
  //     formData.append("assigned_user", $(this).closest('form').find('select[name="assigned_user"]').val());
  //   }
  //
  //   if ($(this).closest('form')[0].checkValidity()) {
  //     $.ajax({
  //       type: 'POST',
  //       url: url,
  //       data: formData,
  //       processData: false,
  //       contentType: false
  //     }).done(function() {
  //       pollMessages();
  //       $(thiss).closest('form').find('textarea').val('');
  //     }).fail(function() {
  //       alert('Error sending a message');
  //     });
  //   } else {
  //     $(this).closest('form')[0].reportValidity();
  //   }
  //
  // });

  // $(document).on('click', '#load-more-messages', function() {
  //   var current_page = $(this).data('nextpage');
  //   $(this).data('nextpage', current_page + 1);
  //   var next_page = $(this).data('nextpage');
  //   $('#load-more-messages').text('Loading...');
  //   pollMessages(next_page, true);
  //   $('#load-more-messages').text('Load More');
  // });

  // });

  $('.play-recording').on('click', function() {
  var url = $(this).data('url');
  var key = $(this).data('id');
  var recording = new Audio(url);
  // $(recording).attr('id', 'recording_' + key);
  // console.log(recording);

  // var pause_button = '<button type="button" class="btn btn-xs btn-secondary ml-3 stop-recording" data-id="' + key + '" data-button="' + recording + '">Stop Recording</button>';
  // $(this).parent().append(pause_button);

  recording.play();
  });

  // $('#approval_reply').on('click', function() {
  // $('#model_field').val('Approval Order');
  // });
  //
  // $('#internal_reply').on('click', function() {
  // $('#model_field').val('Internal Order');
  // });
  //
  // $('#approvalReplyForm').on('submit', function(e) {
  // e.preventDefault();
  //
  // var url = "{{ route('reply.store') }}";
  // var category_id = $('#category_id_field').val();
  // var reply = $('#reply_field').val();
  // var model = $('#model_field').val();
  //
  // $.ajax({
  //   type: 'POST',
  //   url: url,
  //   headers: {
  //       'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
  //   },
  //   data: {
  //     reply: reply,
  //     category_id: category_id,
  //     model: model
  //   },
  //   success: function(reply) {
  //     // $('#ReplyModal').modal('hide');
  //     $('#reply_field').val('');
  //     if (model == 'Approval Order') {
  //       $('#quickComment').append($('<option>', {
  //         value: reply,
  //         text: reply
  //       }));
  //     } else {
  //       $('#quickCommentInternal').append($('<option>', {
  //         value: reply,
  //         text: reply
  //       }));
  //     }
  //
  //   }
  // });
  // });

  // $('#quickCategory').on('change', function() {
  //   var replies = JSON.parse($(this).val());
  //   $('#quickComment').empty();
  //
  //   $('#quickComment').append($('<option>', {
  //     value: '',
  //     text: 'Quick Reply'
  //   }));
  //
  //   replies.forEach(function(reply) {
  //     $('#quickComment').append($('<option>', {
  //       value: reply.reply,
  //       text: reply.reply
  //     }));
  //   });
  // });

  // $('#quickCategoryInternal').on('change', function() {
  // var replies = JSON.parse($(this).val());
  // $('#quickCommentInternal').empty();
  //
  // $('#quickCommentInternal').append($('<option>', {
  //   value: '',
  //   text: 'Quick Reply'
  // }));
  //
  // replies.forEach(function(reply) {
  //   $('#quickCommentInternal').append($('<option>', {
  //     value: reply.reply,
  //     text: reply.reply
  //   }));
  // });
  // });

  $('#submitButton').on('click', function(e) {
  e.preventDefault();

  var phone = $('input[name="contact_detail"]').val();

  // if (phone.length != 0) {
  //   if (/^[91]{2}/.test(phone) != true) {
  //     $('input[name="contact_detail"]').val('91' + phone);
  //   }
  // }

  $(this).closest('form').submit();
  });

  
  </script>
@endsection
