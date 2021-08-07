@extends('layouts.app')


@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>{{ $modify ? 'Edit Sale' : 'Create Sale' }}</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-secondary" href="{{ route('sales.index') }}"> Back</a>
            </div>
        </div>
    </div>


    {{--@if(!$modify)--}}
    <form action="{{ $modify ? route('sales.update',$id) : route('sales.store')  }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if($modify)
            @method('PUT')
        @endif
        <div class="row">

            {{--<div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Date of request:</strong>
                    <input type="date" class="form-control" name="date_of_request" placeholder="date_of_request" value="{{old('date_of_request') ? old('date_of_request') : $date_of_request}}"/>
                    @if ($errors->has('date_of_request'))
                        <div class="alert alert-danger">{{$errors->first('date_of_request')}}</div>
                    @endif
                </div>
            </div>--}}

            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong> Name of Sales Person :</strong>
					<?php
					echo Form::select('sales_person_name',$sale_persons, ( old('sales_person_name') ? old('sales_person_name') : $sales_person_name ), ['placeholder' => 'Select a name','class' => 'form-control']);?>
                    @if ($errors->has('sales_person_name'))
                        <div class="alert alert-danger">{{$errors->first('sales_person_name')}}</div>
                    @endif
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Client Name:</strong>
                    <input type="text" class="form-control" name="client_name" placeholder="Client Name"
                           value="{{ old('client_name') ? old('client_name') : $client_name }}"/>
                    @if ($errors->has('client_name'))
                        <div class="alert alert-danger">{{$errors->first('client_name')}}</div>
                    @endif
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong> Client Phone :</strong>
					<?php

					$client_ph_array = [];

					for($i = 0 ;$i < 10;  $i++)
						$client_ph_array['Solo Luxury 0'.$i] = 'Solo Luxury 0'.$i;

					echo Form::select('client_phone',$client_ph_array, ( old('client_phone') ? old('client_phone') : $client_phone ), ['placeholder' => 'Select','class' => 'form-control']);?>
                    {{--<input type="text" class="form-control" name="client_phone" placeholder="Client Phone" value="{{ old('client_phone') ? old('client_phone') : $client_phone }}"/>--}}
                    @if ($errors->has('client_phone'))
                        <div class="alert alert-danger">{{$errors->first('client_phone')}}</div>
                    @endif
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Instagram Handle:</strong>
                    <input type="text" class="form-control" name="instagram_handle" placeholder="Instagram Handle"
                           value="{{ old('instagram_handle') ? old('instagram_handle') : $instagram_handle }}"/>
                    @if ($errors->has('instagram_handle'))
                        <div class="alert alert-danger">{{$errors->first('instagram_handle')}}</div>
                    @endif
                </div>
            </div>


            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong> Description :</strong>
                    <textarea class="form-control" name="description" placeholder="Description">{{ old('description') ? old('description') : $description }}</textarea>
                    @if ($errors->has('description'))
                        <div class="alert alert-danger">{{$errors->first('description')}}</div>
                    @endif
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                @if($modify)
                    <div class="old-image" style="
                    @if ($img_id == "")
                            display: none;
                    @endif
                            ">
                        <p>

                            <img src="{{ $img_url ? $img_url : '' }}"
                                 class="img-responsive" style="max-width: 200px;"  alt="">
                            <input type="text" hidden name="oldImage" value="{{ $img_url ? 0 : 1  }}">
                        </p>
                        <button class="btn btn-danger removeOldImage" data-id="" media-id="{{ $img_id }}">Remove</button>
                    </div>
                @endif
                <div class="form-group new-image" style="
                    @if ( $img_id != "")
                            display: none;
                    @endif
                            ">
                    <div class="form-group">
                        <strong>Upload Image:</strong>
                        <input enctype="multipart/form-data" type="file" class="form-control" name="image"/>
                        @if ($errors->has('image'))
                            <div class="alert alert-danger">{{$errors->first('image')}}</div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong> Selected Product :</strong>
                    {{--<input type="text" class="form-control" name="selected_product" placeholder="Selected Product" value="{{ old('selected_product') ? old('selected_product') : $selected_product }}"/>--}}
					<?php
					//	                echo Form::select('allocated_to',$products_array, ( old('selected_products_array') ? old('selected_products_array') : $selected_products_array ), ['multiple'=>'multiple','name'=>'selected_product[]','class' => 'form-control select2']);?>

                    <select name="selected_product[]" class="select2 form-control" multiple="multiple" id="select2"></select>

                    @if ($errors->has('selected_product'))
                        <div class="alert alert-danger">{{$errors->first('selected_product')}}</div>
                    @endif
                </div>
                @if($modify == 1)
                    <div class="form-group">
                        <a href="/attachProducts/sale/{{$id}}"><button type="button" class="btn btn-image"><img src="/images/attach.png" /></button></a>
                    </div>
                @endif
                <script type="text/javascript">
                    jQuery(document).ready(function() {

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
                            templateSelection: (product) => product.name || product.text,

                        });



                        let data = [
                                @forEach($products_array as $key => $value)
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



            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong> Work Allocated :</strong>
					<?php
					echo Form::select('allocated_to',$users, ( old('allocated_to') ? old('allocated_to') : $allocated_to ), ['placeholder' => 'Select a name','class' => 'form-control']);?>
                    @if ($errors->has('allocated_to'))
                        <div class="alert alert-danger">{{$errors->first('allocated_to')}}</div>
                    @endif
                </div>
            </div>

            @if($modify)
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong> Start Time :</strong>
                        {{ $created_at }}
                    </div>
                </div>
            @endif

            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Finished Time:</strong>
                    <input type="time" class="form-control" name="finished_at" placeholder="finished_at" value="{{old('finished_at') ? old('finished_at') : $finished_at}}"/>
                    @if ($errors->has('finished_at'))
                        <div class="alert alert-danger">{{$errors->first('finished_at')}}</div>
                    @endif
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Check 1:</strong>
					<?php echo Form::checkbox('check_1','1',old('check_1') ? old('check_1') : $check_1); ?>
                    @if ($errors->has('finished_at'))
                        <div class="alert alert-danger">{{$errors->first('finished_at')}}</div>
                    @endif
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Check 2:</strong>
					<?php echo Form::checkbox('check_2','1',old('check_2') ? old('check_2') : $check_2); ?>
                    @if ($errors->has('finished_at'))
                        <div class="alert alert-danger">{{$errors->first('finished_at')}}</div>
                    @endif
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Check 3:</strong>
					<?php echo Form::checkbox('check_3','1',old('check_3') ? old('check_3') : $check_3); ?>
                    @if ($errors->has('finished_at'))
                        <div class="alert alert-danger">{{$errors->first('finished_at')}}</div>
                    @endif
                </div>
            </div>


            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong> Sent To Client :</strong>
                    <input type="time" class="form-control" name="sent_to_client" placeholder="Supplier Link" value="{{ old('sent_to_client') ? old('sent_to_client') : $sent_to_client }}"/>
                    @if ($errors->has('sent_to_client'))
                        <div class="alert alert-danger">{{$errors->first('sent_to_client')}}</div>
                    @endif
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong> Remark :</strong>
					<?php

					$saleStatus = ['Pending'=>'Pending','Accepted by Selector'=>'Accepted by Selector',
					               'Sent by Selector'=>'Sent by Selector','Request Complete'=>'Request Complete'];

					echo Form::select('remark',$saleStatus,
						( old('remark') ? old('remark') : $remark ),
						['placeholder' => 'Select a remark','class' => 'form-control']);?>
                    @if ($errors->has('remark'))
                        <div class="alert alert-danger">{{$errors->first('remark')}}</div>
                    @endif
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <button type="submit" class="btn btn-secondary">+</button>
            </div>

        </div>
    </form>

    {{--@endif--}}


@endsection
