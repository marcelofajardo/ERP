@extends('layouts.app')
@section('content')
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.5/css/bootstrap-select.min.css">
	<div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Add New Lead</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-secondary" href="{{ route('leads.index') }}"> Back</a>

            </div>
        </div>
    </div>
       @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
   		 @endif

     <form action="{{ route('leads.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
         <div class="row">
             {{-- <div class="col-xs-12 col-sm-8 col-sm-offset-2">
                <div class="form-group">
                    <strong>Client Name:</strong>
                    <input type="text" class="form-control" name="client_name" placeholder="Client Name" value="{{old('client_name')}}" id="customer_suggestions"/>
                    @if ($errors->has('client_name'))
                        <div class="alert alert-danger">{{$errors->first('client_name')}}</div>
                    @endif
                </div>
            </div> --}}

						<div class="col-xs-12 col-sm-8 col-sm-offset-2">
							 <div class="form-group">
									 <strong>Client:</strong>
									 <select class="selectpicker form-control" data-live-search="true" data-size="15" name="customer_id" title="Choose a Customer" required>
										 @foreach ($data['customers'] as $customer)
										  <option data-tokens="{{ $customer->name }} {{ $customer->email }}  {{ $customer->phone }} {{ $customer->instahandler }}" value="{{ $customer->id }}">{{ $customer->name }} - {{ $customer->phone }}</option>
										@endforeach
									</select>

									 @if ($errors->has('customer_id'))
											 <div class="alert alert-danger">{{$errors->first('customer_id')}}</div>
									 @endif
							 </div>
					 </div>

              {{-- <div class="col-xs-12 col-sm-8 col-sm-offset-2">
                <div class="form-group">
                    <strong>Address:</strong>
                    <input type="text" class="form-control" name="address" placeholder="address" value="{{old('address')}}"/>
                    @if ($errors->has('address'))
                        <div class="alert alert-danger">{{$errors->first('address')}}</div>
                    @endif
                </div>
            </div> --}}


             {{-- <div class="col-xs-12 col-sm-8 col-sm-offset-2">
                <div class="form-group">
                    <strong>Contact No:</strong>
                    <input type="text" class="form-control" name="contactno" placeholder="contactno" value="{{old('contactno')}}"/>
                    @if ($errors->has('contactno'))
                        <div class="alert alert-danger">{{$errors->first('contactno')}}</div>
                    @endif
										@if ($message = Session::get('phone_error'))
                        <div class="alert alert-danger">{{$message}}</div>
                    @endif
                </div>
            </div> --}}


            {{-- <div class="col-xs-12 col-sm-8 col-sm-offset-2">
                <div class="form-group">
                    <strong>Email:</strong>
                    <input type="text" class="form-control" name="email" placeholder="email" value="{{old('email')}}"/>
                    @if ($errors->has('email'))
                        <div class="alert alert-danger">{{$errors->first('email')}}</div>
                    @endif
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
                            <option value="database">Database</option>
                            <option value="instagram">Instagram</option>
                            <option value="facebook">Facebook</option>
                            <option value="new">New Lead</option>
                            </Select>
                         </div>
                         <div class="col-sm-6 ol-xs-12">
                             <input type="text" class="form-control" id="leadsourcetxt" name="leadsourcetxt" placeholder="Comments" value="{{old('leadsourcetxt')}}"/>
                        </div>
                    </div>
                    @if ($errors->has('source'))
                        <div class="alert alert-danger">{{$errors->first('source')}}</div>
                    @endif
                </div>
            </div>



              {{-- <div class="col-xs-12 col-sm-8 col-sm-offset-2">
                <div class="form-group">
                    <strong>City:</strong>
                    <input type="text" class="form-control" name="city" placeholder="city" value="{{old('city')}}"/>
                    @if ($errors->has('city'))
                        <div class="alert alert-danger">{{$errors->first('city')}}</div>
                    @endif
                </div>
            </div> --}}

            <div class="col-xs-12 col-sm-8 col-sm-offset-2">
                <div class="form-group">
                    <strong>Solo phone:</strong>
                   <Select name="whatsapp_number" class="form-control">
													 	<option value>None</option>
														<option value="919167152579">00</option>
                            <option value="918291920452">02</option>
                            <option value="918291920455">03</option>
                            <option value="919152731483">04</option>
                            <option value="919152731484">05</option>
                            <option value="971562744570">06</option>
                            <option value="918291352520">08</option>
                            <option value="919004008983">09</option>
                    </Select>
                    @if ($errors->has('whatsapp_number'))
                        <div class="alert alert-danger">{{$errors->first('whatsapp_number')}}</div>
                    @endif
                </div>
            </div>

              <div class="col-xs-12 col-sm-8 col-sm-offset-2">
                <div class="form-group">
                    <strong>Rating:</strong>
                    <Select name="rating" class="form-control">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                    </Select>

                    @if ($errors->has('rating'))
                        <div class="alert alert-danger">{{$errors->first('rating')}}</div>
                    @endif
                </div>
            </div>
						<div class="col-xs-12 col-sm-8 col-sm-offset-2">
							<div class="form-group">
								<strong>Upload Images</strong>
								<input type="file" name="image[]" class="form-control" multiple>
							</div>
						</div>
              <div class="col-xs-12 col-sm-8 col-sm-offset-2">
                <div class="form-group">
                    <strong>Comments:</strong>
                    <textarea  class="form-control" name="comments" placeholder="comments">{{old('comments')}} </textarea>

                    @if ($errors->has('comments'))
                        <div class="alert alert-danger">{{$errors->first('comments')}}</div>
                    @endif
                </div>
            </div>

            <div class="col-xs-12 col-sm-8 col-sm-offset-2">
                <div class="form-group">
                    <strong>Brand:</strong>
                    <select id="multi_brand" multiple="" name="multi_brand[]" class="form-control">
                            @foreach($data['brands'] as $brand)
                              <option value="{{$brand['id']}}">{{$brand['name']}}</option>
                          @endforeach
                    </select>

                    @if ($errors->has('brand'))
                        <div class="alert alert-danger">{{$errors->first('brand')}}</div>
                    @endif
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

                        jQuery('#select2').select2({
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
                            templateSelection: (product) => product.name || product.sku,

                        });



                        let data = [
                                @forEach($data['products_array'] as $key => $value)
                            {
                                'id': '{{ $key }}',
                                'text': '{{$value  }}',
                            },
                            @endforeach
                        ];

                        let productSelect = $('#select2');
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
								<input type="text" name="size" value="{{ old('size') }}" class="form-control" placeholder="S, M, L">
							</div>
						</div>

            <div class="col-xs-12 col-sm-8 col-sm-offset-2">
                <div class="form-group">
                    <strong>Assigned To:</strong>
                    <Select name="assigned_user" class="form-control">

                            @foreach($data['users'] as $user)
                              <option value="{{$user['id']}}">{{$user['name']}}</option>
                          @endforeach
                    </Select>

                    @if ($errors->has('assigned_user'))
                        <div class="alert alert-danger">{{$errors->first('assigned_user')}}</div>
                    @endif
                </div>
            </div>


             <div class="col-xs-12 col-sm-8 col-sm-offset-2">
                <div class="form-group">
                    <strong>Status:</strong>
                    <Select name="status" class="form-control">
                         @foreach($data['status'] as $key => $value)
                          <option value="{{$value}}">{{$key}}</option>
                          @endforeach
                    </Select>

                    <input type="hidden" class="form-control" name="userid" placeholder="status" value=""/>
                    @if ($errors->has('status'))
                        <div class="alert alert-danger">{{$errors->first('status')}}</div>
                    @endif
                </div>
            </div>

						<div class="col-xs-12 col-sm-8 col-sm-offset-2">
							 <div class="form-group">
								 <strong>Created at:</strong>
								 <div class='input-group date' id='created_at'>
									 <input type='text' class="form-control" name="created_at" value="{{ date('Y-m-d H:i') }}" />

									 <span class="input-group-addon">
										 <span class="glyphicon glyphicon-calendar"></span>
									 </span>
								 </div>

								 @if ($errors->has('created_at'))
										 <div class="alert alert-danger">{{$errors->first('created_at')}}</div>
								 @endif
							 </div>
					 </div>
             <div class="col-xs-12 col-sm-8 col-sm-offset-2 text-center">

                <button type="submit" class="btn btn-secondary">+</button>
            </div>
        </div>
    </form>

		<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.5/js/bootstrap-select.min.js"></script>

    <script>

        $('#created_at').datetimepicker({
          format: 'YYYY-MM-DD HH:mm'
        });

				var searchSuggestions = {!! json_encode($data['customer_suggestions']) !!};

	      $('#customer_suggestions').autocomplete({
	        source: function(request, response) {
	          var results = $.ui.autocomplete.filter(searchSuggestions, request.term);

	          response(results.slice(0, 10));
	        }
	      });

		</script>

@endsection
