@extends('layouts.app')


@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2> View Sale</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-secondary" href="{{ route('sales.index') }}"> Back</a>
            </div>
        </div>
    </div>

    <div class="row">

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Date of request:</strong> {{ $date_of_request }}
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong> Name of Sales Person :</strong> {{ $users[$sales_person_name] }}
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Client Name:</strong> {{ $client_name }}
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong> Client Phone :</strong> {{ $client_phone }}
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong> Instagram Handle :</strong> {{ $instagram_handle }}
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong> Description :</strong> {{ $description }}
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                {{--<strong>Image:</strong>--}}
                <?php

	            $image = $sale->getMedia(config('constants.media_tags'))->first();
                ?>
                <img src="{{ $image ? $image->getUrl() : '' }}" class="img-responsive" style="max-width: 200px;"  alt="">
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong> Products Attacted:</strong>
                <table class="table table-bordered">
                    <tr>
                        <th>ID</th>
                        <th>Name or Sku</th>
                        <th>Action</th>
                    </tr>
                    @foreach($products_array  as $key => $value)
                        <tr>
                            <th>{{ $key }}</th>
                            <th>{{ $value }}</th>
                            <th>
                                <a class="btn btn-image" href="{{ route('products.show',$key) }}"><img src="/images/view.png" /></a>
                            </th>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>

        {{--<div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong> Selected Product :</strong> {{ $selected_product}}
            </div>
        </div>--}}

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong> Work Allocated :</strong>{{ $users[$allocated_to] }}
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong> Start Time :</strong>
                {{ Carbon\Carbon::parse($created_at)->format('d-m-Y H:i') }}
            </div>
        </div>


        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Finished Time:</strong> {{ $finished_at }}
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Check 1:</strong>
			    <?php echo Form::checkbox('check_1','1',old('check_1') ? old('check_1') : $check_1,['disabled' => true]); ?>
                @if ($errors->has('finished_at'))
                    <div class="alert alert-danger">{{$errors->first('finished_at')}}</div>
                @endif
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Check 2:</strong>
			    <?php echo Form::checkbox('check_2','1',old('check_2') ? old('check_2') : $check_2,['disabled' => true]); ?>
                @if ($errors->has('finished_at'))
                    <div class="alert alert-danger">{{$errors->first('finished_at')}}</div>
                @endif
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Check 3:</strong>
			    <?php echo Form::checkbox('check_3','1',old('check_3') ? old('check_3') : $check_3,['disabled' => true]); ?>
                @if ($errors->has('finished_at'))
                    <div class="alert alert-danger">{{$errors->first('finished_at')}}</div>
                @endif
            </div>
        </div>


        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong> Sent To Client :</strong> {{ $sent_to_client }}
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong> Remark :</strong> {{ $remark }}
            </div>
        </div>
    </div>
@endsection
