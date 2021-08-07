@extends('layouts.app')


@section('content')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Edit Leads</h2>
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


    <form action="{{ route('leads.update',$leads->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
             <div class="col-xs-12 col-sm-8 col-sm-offset-2">
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
            </div>

             <div class="col-xs-12 col-sm-8 col-sm-offset-2">
                <div class="form-group">
                    <strong>Contact No:</strong>
                    <input type="text" class="form-control" name="contactno" placeholder="contactno" value="{{$leads->contactno}}"/>

                </div>
            </div>

            <div class="col-xs-12 col-sm-8 col-sm-offset-2">
                <div class="form-group">
                    <strong>Email:</strong>
                    <input type="text" class="form-control" name="email" placeholder="email" value="{{$leads->email}}"/>

                </div>
            </div>

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



              <div class="col-xs-12 col-sm-8 col-sm-offset-2">
                <div class="form-group">
                    <strong>City:</strong>
                    <input type="text" class="form-control" name="city" placeholder="city" value="{{$leads->city}}"/>

                </div>
            </div>

            <div class="col-xs-12 col-sm-8 col-sm-offset-2">
                <div class="form-group">
                    <strong>Solo Phone:</strong>
                   <Select name="solophone" class="form-control">
                           <option value>None</option>
                            <option value="01" {{'01' == $leads->solophone ? 'Selected=Selected':''}}>01</option>
                            <option value="02" {{'02'== $leads->solophone ? 'Selected=Selected':''}}>02</option>
                            <option value="03" {{'03'== $leads->solophone ? 'Selected=Selected':''}}>03</option>
                            <option value="04" {{'04'== $leads->solophone ? 'Selected=Selected':''}}>04</option>
                            <option value="05" {{'05'== $leads->solophone ? 'Selected=Selected':''}}>05</option>
                            <option value="06" {{'06'== $leads->solophone ? 'Selected=Selected':''}}>06</option>
                            <option value="07" {{'07'== $leads->solophone ? 'Selected=Selected':''}}>07</option>
                            <option value="08" {{'08'== $leads->solophone ? 'Selected=Selected':''}}>08</option>
                            <option value="09" {{'09'== $leads->solophone ? 'Selected=Selected':''}}>09</option>
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
                    <strong>Comments:</strong>
                    <textarea  class="form-control" name="comments" placeholder="comments">{{$leads->comments}} </textarea>


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
                        let productSelect = jQuery('#select2');
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
                    <Select name="status" class="form-control">
                         @foreach($leads['statusid'] as $key => $value)
                          <option value="{{$value}}" {{$value == $leads->status ? 'Selected=Selected':''}}>{{$key}}</option>
                          @endforeach
                    </Select>

                    <input type="hidden" class="form-control" name="userid" placeholder="status" value="{{$leads->userid}}"/>

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
             <div class="col-xs-12 col-sm-8 col-sm-offset-2 text-center">

                <button type="submit" class="btn btn-secondary">+</button>
            </div>
        </div>
 </form>

 <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>

 <script>

     $('#created_at').datetimepicker({
       format: 'YYYY-MM-DD HH:mm'
     });

 </script>


@endsection
