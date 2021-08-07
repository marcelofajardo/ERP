<div style="margin-bottom:10px;"><a class="btn btn-danger" href="{{ route('delete-rules',['id' => $result->id]) }}">Delete</a></div>

<div class="panel-group" id="accordion1" role="tablist" aria-multiselectable="true">
                            <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="headingOne">
                                <h4 class="panel-title">
                                <a role="button" data-toggle="collapse" data-parent="#accordion1" href="#collapseOne1" aria-expanded="true" aria-controls="collapseOne">
                                Rule Information 
                                </a>
                            </h4>
                            </div>
                            <div id="collapseOne1" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                                <div class="panel-body">
                                        <div class="form-group row">
                                            <label for="code" class="col-sm-3 col-form-label required">Rule Name</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control required" name="name_edit" placeholder="Name" value="{{$result->name}}"  />
                                                @if ($errors->has('name'))
                                                <div class="alert alert-danger">{{$errors->first('name')}}</div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="description" class="col-sm-3 col-form-label">Description</label>
                                            <div class="col-sm-8">
                                                <textarea type="text" class="form-control" name="description_edit" placeholder="Description" id="description">{{ $result->description }}</textarea>
                                                @if ($errors->has('description'))
                                                <div class="alert alert-danger">{{$errors->first('description')}}</div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="start" class="col-sm-3 col-form-label required">Active</label>
                                            <div class="col-sm-8">
                                                    <select class="form-control select select2 required" name="active_edit" id="is_active">
                                                            <option value="1" {{ $result->is_active == true ? "selected" : ""}}>Yes</option>
                                                            <option value="0" {{ $result->is_active == false ? "selected" : ""}}>No</option>
                                                    </select>
                                            </div>
                                        </div>


                                        <div class="form-group row">
                                                <label for="start" class="col-sm-3 col-form-label required">Store Websites</label>
                                                <div class="col-sm-8">
                                                        <select class="form-control select select2" name="store_website_id_edit" onchange="getWebsitesByStoreId(this);">
                                                            <option value="">Please select</option>
                                                            @foreach($store_websites as $ws)
                                                                <option value="{{ $ws->id }}" {{ $result->store_website_id == $ws->id ? "selected" : ""}}>{{ $ws->title }}</option>
                                                            @endforeach
                                                        </select>
                                                </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="start" class="col-sm-3 col-form-label required">Websites</label>
                                            <div class="col-sm-8">
                                                    <select class="form-control select select2 required websites_edit" name="website_ids_edit" multiple="true" id="website_ids_edit">
                                                        <option value="">Please select</option>
                                                        @foreach($websites as $website)
                                                            <option value="{{ $website->platform_id }}" {{ in_array($website->platform_id,$web_ids) ? "selected" : ""}}>{{ $website->name }}</option>
                                                        @endforeach
                                                    </select>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="start" class="col-sm-3 col-form-label required">Customer Groups</label>
                                            <div class="col-sm-8">
                                                    @php 
                                                        $groups = explode(',',$result->customer_group_ids);
                                                    @endphp
                                                    <select class="form-control select select2 required customers_edit" name="customer_groups_edit" multiple="true" id="customer_groups_edit">
                                                        <option data-title="NOT LOGGED IN" value="0" {{ in_array(0,$groups) ? "selected" : ""}}>NOT LOGGED IN</option>
                                                        <option data-title="General" value="1" {{ in_array(1,$groups) ? "selected" : ""}}>General</option>
                                                        <option data-title="Wholesale" value="2" {{ in_array(2,$groups) ? "selected" : ""}}>Wholesale</option>
                                                        <option data-title="Retailer" value="3" {{ in_array(3,$groups) ? "selected" : ""}}>Retailer</option>
                                                    </select>
                                            </div>
                                        </div>

                                        

                                        <div class="form-group row">
                                            <label for="start" class="col-sm-3 col-form-label required">Coupon</label>
                                            <div class="col-sm-8">
                                                    <select class="form-control select select2 required" name="coupon_type_edit" id="coupon_type_edit" >
                                                        <option  value="NO_COUPON" {{  $result->coupon_type == "NO_COUPON" ? "selected" : ""}}>No Coupon</option>
                                                        <option  value="SPECIFIC_COUPON" {{ $result->coupon_type == "SPECIFIC_COUPON" ? "selected" : ""}}>Specific Coupon</option>
                                                    </select>
                                            </div>
                                        </div>

                                        @php
                                            $class = $result->coupon_type == "SPECIFIC_COUPON" ? "" : "hide_div_edit";
                                        @endphp

                                        <div class="form-group row {{ $class }}">
                                            <label for="start" class="col-sm-3 col-form-label">Coupon Code</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" name="code_edit" placeholder="Code" id="coupon_code_edit" value="{{ $result->coupon_code }}" {{ $result->use_auto_generation ? "disabled" : "" }} />
                                                @if ($errors->has('code'))
                                                <div class="alert alert-danger">{{$errors->first('code')}}</div>
                                                @endif
                                            </div>
                                        </div>

                                        
                                        <div class="form-group row {{ $class }}">
                                        <label for="start" class="col-sm-3 col-form-label"></label>
                                            <div class="col-sm-8">
                                                <input type="checkbox" class="form-control" value="1" style="height:20px;width:20px;" id="disable_coupon_code_edit" name="auto_generate_edit" {{ $result->use_auto_generation ? "checked" : "" }} />
                                                <div class="">If you select and save the rule you will be able to generate multiple coupon codes.</div>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="start" class="col-sm-3 col-form-label">Uses per Coupon</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" name="uses_per_coupon_edit" placeholder="" id="use_per_coupon" value="{{ $result->uses_per_coupon}}" />
                                                @if ($errors->has('uses_per_coupon'))
                                                <div class="alert alert-danger">{{$errors->first('uses_per_coupon')}}</div>
                                                @endif
                                            </div>
                                        </div>

                                        @if($result->coupon_type == "SPECIFIC_COUPON")
                                            <div class="form-group row">
                                        @else
                                            <div class="form-group row hide_div_edit">
                                        @endif
                                            <label for="start" class="col-sm-3 col-form-label">Uses per Coustomer</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" name="uses_per_coustomer_edit" placeholder="" id="use_per_coustomer" value="{{ $result->uses_per_customer}}" />
                                                <div class="">Usage limit enforced for logged in customers only.</div>
                                                @if ($errors->has('uses_per_coustomer'))
                                                <div class="alert alert-danger">{{$errors->first('uses_per_coustomer')}}</div>
                                                @endif
                                            </div>
                                        </div>
                                        

                                        <div class="form-group row">
                                            <label for="start" class="col-sm-3 col-form-label">Start</label>
                                            <div class="col-sm-8">
                                                <div class='input-group date' id='start'>
                                                    <input type='text' class="form-control" name="start_edit" value="{{isset($result->from_date) ? $result->from_date : ''}}" id="start_input" />
                                                    <span class="input-group-addon">
                                                        <span class="glyphicon glyphicon-calendar"></span>
                                                    </span>
                                                </div>
                                                @if ($errors->has('start'))
                                                <div class="alert alert-danger">{{$errors->first('start')}}</div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="expiration" class="col-sm-3 col-form-label">Expiration</label>
                                            <div class="col-sm-8">
                                                <div class='input-group date' id='expiration'>
                                                    <input type='text' class="form-control" name="expiration_edit" value="{{isset($result->to_date) ? $result->to_date : ''}}" id="to_input" />
                                                    <span class="input-group-addon">
                                                        <span class="glyphicon glyphicon-calendar"></span>
                                                    </span>
                                                </div>
                                                @if ($errors->has('expiration'))
                                                <div class="alert alert-danger">{{$errors->first('expiration')}}</div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="start" class="col-sm-3 col-form-label">Priority</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" name="priority_edit" placeholder="" id="" value="{{ $result->priority }}" />
                                                @if ($errors->has('priority'))
                                                <div class="alert alert-danger">{{$errors->first('priority')}}</div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                        <label for="start" class="col-sm-3 col-form-label">Public In RSS Feed</label>
                                            <div class="col-sm-8">
                                                <input type="checkbox" class="form-control" style="height:20px;width:20px;" name="rss_edit" {{ $result->is_rss == 1 ? "checked" : ""}} />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="headingTwo">
                                <h4 class="panel-title">
                                <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion1" href="#collapseTwo2" aria-expanded="false" aria-controls="collapseTwo">
                                Labels
                                </a></h4>
                                <a href="javascript:void(0);"  style="margin-top:-40px;margin-left: 60px;"><i class="fa fa-question" onclick="https://docs.magento.com/user-guide/configuration/scope.html"></i></a>
                            </div>
                                <div id="collapseTwo2" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                                    <div class="panel-body" style="overflow:auto;max-height:250px;">

                                            <div class="form-group row">
                                                <label for="code" class="col-sm-3 col-form-label text-right">Default Rule Label for All Store Views</label>
                                                <div class="col-sm-9">
                                                    @foreach($result->store_labels as $res)
                                                        @if($res->store_view_id == 0)
                                                            <input type="text" class="form-control" name="store_labels[0]" placeholder="" value="{{ $res->value }}" />
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                            <hr>

                                            @php
                                                    $web_arr = [];
                                                    foreach($result->store_labels as $label){
                                                        $web_arr[$label->store_view_id] = $label->value;
                                                    }
                                            @endphp
                                            @foreach($website_stores as $store)
                                            <div class="form-group row" style="align-items: center;">
                                                <div class="col-sm-3">
                                                    <label for="code" class="col-sm-12 col-form-label">{{ $store->name }}</label>
                                                    <label for="code" class="col-sm-12 col-form-label">{{ $store->name }} Store</label>
                                                </div>                                           
                                                <div class="col-sm-9">
                                                    @foreach($store->storeView as $view)
                                                    <div class="full-rep">
                                                        <label for="code" class="col-sm-3 col-form-label text-right">{{ $view->name }}</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" class="form-control" name="store_labels[{{$view->id}}]" placeholder="" value="{{ isset($web_arr[$view->id]) ? $web_arr[$view->id] : "" }}" />
                                                        </div>
                                                    </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                            @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default">


                            <div class="panel-heading" role="tab" id="headingThree">
                                <h4 class="panel-title">
                                <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion1" href="#collapseThree3" aria-expanded="false" aria-controls="collapseThree">
                                    Manage Coupon Codes
                                </a>
                            </h4>
                            </div>
                            <div id="collapseThree3" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
                            <div class="panel-body">
                                        <div class="form-group row">
                                            <label for="code" class="col-sm-3 col-form-label">Coupon Qty</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" name="coupon_qty" placeholder="" value="{{old('coupon_qty')}}" id="coupon_qty_edit"  />
                                                @if ($errors->has('coupon_qty'))
                                                <div class="alert alert-danger">{{$errors->first('coupon_qty')}}</div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="description" class="col-sm-3 col-form-label">Code Length</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" name="code_length" placeholder="" value="{{old('code_length')}}" id="coupon_length_edit"   />
                                                <div class="">Excluding prefix, suffix and separators.</div>
                                                @if ($errors->has('code_length'))
                                                <div class="alert alert-danger">{{$errors->first('code_length')}}</div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="start" class="col-sm-3 col-form-label">Code Format</label>
                                            <div class="col-sm-8">
                                                    <select class="form-control select select2" name="format" id="format_edit"  >
                                                            <option value="1">Alphanumeric</option>
                                                            <option value="2">Alphabetical</option>
                                                            <option value="3">Numeric</option>
                                                    </select>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="start" class="col-sm-3 col-form-label">Code Prefix</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" name="prefix" placeholder="" value="{{old('prefix')}}" id="prefix_edit"   />
                                                
                                                @if ($errors->has('prefix'))
                                                <div class="alert alert-danger">{{$errors->first('prefix')}}</div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="start" class="col-sm-3 col-form-label">Code Suffix</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" name="suffix" placeholder="" value="{{old('suffix')}}" id="suffix_edit"  />
                                                
                                                @if ($errors->has('suffix'))
                                                <div class="alert alert-danger">{{$errors->first('suffix')}}</div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="start" class="col-sm-3 col-form-label">Dash Every X Characters</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" name="dash" placeholder="" value="{{old('dash')}}" id="dash_edit"  />
                                                
                                                @if ($errors->has('dash'))
                                                <div class="alert alert-danger">{{$errors->first('dash')}}</div>
                                                @endif
                                            </div>
                                        </div>

                                        
                                        <button type="button" class="btn btn-primary generate-code" style="margin-left:50%;" {{ $result->use_auto_generation ? "" : "disabled" }}>Generate</button>

                                        <div class="" style="margin-top:10px;"> 
                                                <h3>Coupon Codes</h3>
                                                <hr>
                                            <div class="table-responsive">
                                                <table class="table table-striped table-bordered" style="width: 99%" id="coupon_table1">
                                                    <thead>
                                                        <tr>
                                                            <th width="15%">ID</th>
                                                            <th>Code</th>
                                                            <th>Created</th>
                                                            <th>Expiration Date</th>
                                                            <th>uses</th>
                                                            <th>Times Used</th>
                                                            <th>Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($codes as $code)
                                                                <tr>
                                                                    <td>{{ $code->id }}</td>
                                                                    <td>{{ $code->code }}</td>
                                                                    <td>{{ $code->start }}</td>
                                                                    <td>{{ $code->expiration }}</td>
                                                                    <td>{{ $code->uses }}</td>
                                                                    <td>{{ $code->usage_count }}</td>
                                                                    <td><a class="btn btn-danger" onclick="deleteCoupon(this);" data-id="{{ $code->id }}">Delete</a></td>
                                                                </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="headingFour">
                                    <h4 class="panel-title">
                                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion1" href="#collapseFour4" aria-expanded="false" aria-controls="collapseFour4">
                                        Actions
                                    </a>
                                    </h4>
                                </div>
                                <div id="collapseFour4" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFour">
                                    <div class="panel-body">
                                            <div class="form-group row">
                                                <label for="start" class="col-sm-3 col-form-label ">Apply</label>
                                                <div class="col-sm-8">
                                                        <select class="form-control select select2 " name="simple_action" id="simple_action">
                                                            <option data-title="Percent of product price discount" value="by_percent" {{ $result->simple_action == "by_percent" ? "selected" : ""}}>Percent of product price discount</option>
                                                            <option data-title="Fixed amount discount" value="by_fixed" {{ $result->simple_action == "by_fixed" ? "selected" : ""}}>Fixed amount discount</option>
                                                            <option data-title="Fixed amount discount for whole cart" value="cart_fixed" {{ $result->simple_action == "cart_fixed" ? "selected" : ""}}>Fixed amount discount for whole cart</option>
                                                            <option data-title="Buy X get Y free (discount amount is Y)" value="buy_x_get_y" {{ $result->simple_action == "buy_x_get_y" ? "selected" : ""}}>Buy X get Y free (discount amount is Y)</option>
                                                        </select>
                                                </div>
                                            </div>

                                        

                                            <div class="form-group row">
                                                <label for="start" class="col-sm-3 col-form-label required">Discount Amount</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control required" name="discount_amount" placeholder="Discount amount" id="discount_amount" value="{{ $result->discount_amount}}" />
                                                    @if ($errors->has('discount_amount'))
                                                    <div class="alert alert-danger">{{$errors->first('discount_amount')}}</div>
                                                    @endif
                                                </div>
                                            </div>


                                            <div class="form-group row">
                                                <label for="start" class="col-sm-3 col-form-label">Maximum Qty Discount is Applied To</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" name="discount_qty" placeholder="" id="discount_qty" value="" />
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="start" class="col-sm-3 col-form-label">Discount Qty Step (Buy X)</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" name="discount_step" placeholder="" id="discount_step" value="{{ $result->discount_step }}" />
                                                </div>
                                            </div>
                                            

                                            <div class="form-group row">
                                                <label for="start" class="col-sm-3 col-form-label">Apply to Shipping Amount</label>
                                                <div class="col-sm-8">
                                                        <select class="form-control select select2 " name="apply_to_shipping" id="apply_to_shipping">
                                                            <option data-title="Yes" value="true" {{ $result->apply_to_shipping ? "selected" : ""}}>Yes</option>
                                                            <option data-title="No" value="false" {{ !$result->apply_to_shipping  ? "selected" : ""}}>No</option>
                                                        </select>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="expiration" class="col-sm-3 col-form-label">Discard subsequent rules</label>
                                                <div class="col-sm-8">
                                                        <select class="form-control select select2 " name="stop_rules_processing" id="stop_rules_processing">
                                                            <option data-title="Yes" value="true" {{ $result->stop_rules_processing ? "selected" : ""}}>Yes</option>
                                                            <option data-title="No" value="false" {{ !$result->stop_rules_processing ? "selected" : ""}}>No</option>
                                                        </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>