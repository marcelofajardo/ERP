@extends('layouts.app')


@section('large_content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Product Details</h2>
            </div>
            <div class="pull-right">
                {{--<a class="btn btn-secondary" href="{{ route('home') }}"> Back</a>--}}
            </div>
        </div>
    </div>

    @if (  $isApproved == -1 )
        <div class="alert alert-danger alert-block mt-2">
            <button type="button" class="close" data-d ismiss="alert">×</button>
            <p><strong>Product has been rejected</strong></p>
            <p><strong>Reason : </strong> {{ $rejected_note }}</p>
        </div>
    @endif

    @if ($message = Session::get('rejected'))
        <div class="alert alert-danger alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ $message }}</strong>
        </div>
    @endif

    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ $message }}</strong>
        </div>
    @endif

    @if ($message = Session::get('error'))
        <div class="alert alert-danger alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ $message }}</strong>
        </div>
    @endif

    {{--<form action="{{ route('productattribute.update',$id) }}" method="POST" enctype="multipart/form-data">--}}
        {{--@csrf--}}
        {{--@method('PUT')--}}

    <div class="row">
        <div class="col-xs-12 col-md-12">
            <table class="table table-bordered table-striped">
                <tr>
                    <td colspan="3">ID: <strong>{{ $id }}</strong></td>
                    <td colspan="2">Name: <strong>{{ $name }}</strong></td>
                    <td colspan="3">Scraped: <strong>@if($scraped) {{ $scraped->created_at ? $scraped->created_at->format('Y-m-d') : 'N/A' }} @else N/A @endif</strong></td>
                </tr>
                <tr>
                    <td colspan="3"></td>
                    <td colspan="2"></td>
                    <td colspan="3"></td>
                </tr>
                <tr>
                    <td colspan="3">Size: <strong>{{ $size ?? 'N/A' }}</strong></td>
                    <td colspan="2" rowspan="4">
                        <div style="width: 250px;">
                            <strong>Short Description:</strong> <br>{{ $short_description }}
                        </div>
                    </td>
                    <td colspan="3">Cropped: <strong>{{ 'N/A' }}</strong></td>
                </tr>
                <tr>
                    <td colspan="3"></td>
                    <td colspan="3"></td>
                </tr>
                <tr>
                    <td colspan="3">Made In: <strong>{{ $made_in ?? 'N/A' }}</strong></td>
                    <td>Crop Approval</td>
                    <td>Date & Time</td>
                    <td>User</td>
                </tr>
                <tr>
                    <td colspan="3"></td>
                    <td>{{ $product->is_crop_approved ? 'Yes' : 'No' }}</td>
                    <td>{{ $product->crop_approved_at ?? 'N/A' }}</td>
                    <td>{{ $product->cropApprover ? $product->cropApprover->name : 'N/A' }}</td>
                </tr>
                <tr>
                    <td colspan="3">Brand: {{ \App\Http\Controllers\BrandController::getBrandName($brand)}}</td>
                    <td colspan="2">
                        Measurement <br>
                        L : {{$lmeasurement ?? 'N/A'}} &nbsp;
                        H : {{$hmeasurement ?? 'N/A'}} &nbsp;
                        D : {{$dmeasurement ?? 'N/A'}} &nbsp;
                    </td>
                    <td>Sequence Approval</td>
                    <td>Date</td>
                    <td>User</td>
                </tr>
                <tr>
                    <td colspan="5"></td>
                    <td>{{ $product->is_crop_ordered ? 'Yes' : 'No' }}</td>
                    <td>{{ $product->crop_ordered_at ?? 'N/A' }}</td>
                    <td>{{ $product->cropOrderer ? $product->cropOrderer->name : 'N/A' }}</td>
                </tr>
                <tr>
                    <td colspan="3">Color: <strong>{{ $color }}</strong></td>
                    <td colspan="2">Composition: <strong>{{ $composition }}</strong></td>
                    <td>Attribute Approval</td>
                    <td>Date</td>
                    <td>User</td>
                </tr>
                <tr>
                    <td colspan="5"></td>
                    <td>{{ $product->is_approved ? 'Yes' : 'No' }}</td>
                    <td>{{ $product->listing_approved_at ?? 'N/A' }}</td>
                    <td>{{ $product->approver ? $product->approver->name : 'N/A' }}</td>
                </tr>
                <tr>
                    <td colspan="3">Price (In Euro): <strong>{{$price}}</strong></td>
                    <td colspan="2">SKU: <strong>{{$sku}}</strong></td>
                    <td colspan="3"></td>
                </tr>
                <tr>
                    <td colspan="8"></td>
                </tr>
                <tr>
                    <td colspan="3">Price (in INR): <strong>{{$price_inr}}</strong></td>
                    <td colspan="2">Sku+color: {{ $sku.$color }}</td>
                    <td colspan="3">Activities</td>
                </tr>
                <tr>
                    <td colspan="3"></td>
                    <td colspan="2"></td>
                    <td colspan="3" rowspan="6">
                        <div style="max-height: 400px; overflow: auto">
                            <table class="table table-striped table-bordered">
                                <tr>
                                    <th>Action</th>
                                    <th>Date</th>
                                    <th>User</th>
                                </tr>
                                @foreach($activities as $activity)
                                    <tr>
                                        <td>{{ $activity->action }}</td>
                                        <td>{{ $activity->created_at ? $activity->created_at->format('Y-m-d') : 'N/A' }}</td>
                                        <td>{{ $activity->user ? $activity->user->name : '' }}</td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="3">Price Special (in INR): <strong>{{ $price_inr_special }}</strong></td>
                    <td colspan="2">
                        Category:
                        <strong>
                            @if(isset($categories) && $categories != null)
                            @for( $i = 0 ; $i < count($categories) - 1 ; $i++)
                                {{ $categories[$i] }}->
                            @endfor
                            {{ $categories[$i] }}
                            @endif
                        </strong>
                    </td>
                </tr>
                <tr>
                    <td colspan="5"></td>
                </tr>
                <tr>
                    <td colspan="3">Supplier: <strong>{{ $supplier }} | {{ $suppliers }}</strong></td>
                    <td colspan="2">Supplier Link: <a target="_new" href="{{ $supplier_link }}"><strong>Open</strong></a>
                        <br>
                        On Stock Supplier Link: 
                        <?php if(!empty($more_suppliers)) { ?>
                            <?php foreach ($more_suppliers as $more_supplier)  { ?>    
                                <br>
                                <a target="_new" href="{{ $more_supplier->link }}"><strong>{{ $more_supplier->name }}</strong></a>
                            <?php } ?>
                        <?php } ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="5"></td>
                </tr>
                <tr>
                    <td colspan="3">Location: {{ $location ?? 'N/A' }}</td>
                    <td colspan="2">Description Link: <strong><a href="{{ $description_link }}" target="_new">Open</a></strong></td>
                </tr>
            </table>
        </div>
    </div>

    <div class="row">


	    <?php $i = 0 ?>

        @for(  ; $i < count($images) ; $i++ )

            <div class="col-xs-12 col-sm-6 col-md-3">
                <strong>Image {{ $i+1 }}:</strong>
                <div class="old-image{{$i}}" style="
                @if ($errors->has('image.'.$i))
                        display: none;
                @endif
                        ">
                    <p>
                        <img src="{{$images[$i]->getUrl()}}" class="img-responsive" style="max-width: 200px;" alt="">
                    </p>
                </div>
            </div>

        @endfor


        <div class="col-xs-12 col-sm-12 col-md-12 ">

            <div class="row">

                <div class="col-xs-12 col-sm-6 col-md-6">
                    <div class="form-group">

                        <a href="{{ route('productattribute.edit',$id) }}">
                            <button type="button" class="btn btn-image">
                                <img src="/images/edit.png" />
                            </button>
                        </a>

                        @if ($has_reference)
                          <span class="badge">Has Reference</span>
                        @endif

                        {{-- @can('supervisor-edit')
                            <form method="POST" action="{{ route('productsupervisor.approve',$id) }}"
                                  style="display: inline;">
                                @csrf
                                <button data-id="{{ $id }}"
                                        class="btn btn-approve btn-secondary {{ ( $isApproved  ==  '1' ) ? 'btn-success' : ''  }} ">
                                    {{ ( $isApproved  ==  '1' ) ? 'Approved' : 'Approve'  }}
                                </button>
                            </form>
                            <button type="button"
                                    class="btn btn-reject btn-success {{ ( $isApproved  ==  '-1' ) ? '' : 'btn-danger'  }} ">
                                {{ ( $isApproved  ==  '-1' ) ? 'Rejected' : 'Reject'  }}
                            </button>
                            <script> jQuery(document).ready(() => {
                                    attachRejectEvent()
                                }); </script>
                        @endcan --}}
                    </div>
                    <div class="form-group">
                        @if(auth()->user()->checkPermission('productlister-edit'))
                            <form method="POST" action="{{ route('productlister.isuploaded',$id) }}"
                                  style="display: inline;">
                                @csrf
                                <button type="submit" data-id="{{ $id }}"
                                        class="btn btn-secondary {{ ( $isUploaded  ==  '1' ) ? 'btn-success' : ''  }} ">
                                    {{ ( $isUploaded  ==  '1' ) ? 'Uploaded' : 'Upload'  }}
                                </button>
                            </form>
                        @endif
                        @if(auth()->user()->checkPermission('productapprover-edit'))
                            <form method="POST" action="{{ route('productapprover.isfinal',$id) }}"
                                  style="display: inline;">
                                @csrf
                                <button type="submit" data-id="{{ $id }}"
                                        class="btn {{ ( $isFinal  ==  '1' ) ? 'btn-success' : 'btn-secondary'  }} ">
                                    {{ ( $isFinal  ==  '1' ) ? 'Final Approved' : 'Final Approve'  }}
                                </button>
                            </form>
                            @if($status == 26)
                            <button type="buttom" class="btn btn-secondary" onclick="approveProduct('{{ $id }}')">Image Search Approve</button>
                            <button type="buttom" class="btn btn-danger" onclick="rejectProduct('{{ $id }}')">Image Search Reject</button>
                           @endif
                            @if($status == 31)
                            <button type="buttom" class="btn btn-secondary" onclick="approveTextProduct('{{ $id }}')">Text Search Approve</button>
                            <button type="buttom" class="btn btn-danger" onclick="rejectTextProduct('{{ $id }}')">Text Search Reject</button>
                           @endif
                        @endcan
                    </div>
                    {{-- <div class="form-group">
                         @if(auth()->user()->checkPermission('productsupervisor-edit'))
                            <form method="POST" action="{{ route('productsupervisor.reject',$id) }}">
                                @csrf
                                <div class="row" id="rejectWhom" style="
                                @if( !$errors->has('reason') && !$errors->has('role') && empty($reason ))
                                display: none;
                                @endif
                                ">
                                    <div class="col-xs-12 col-sm-12 col-md-12 text-left">
                                        <div class="form-group">
                                            <strong>Select which role to pass</strong>
                                            {!! Form::select('role', ['Selectors'=>'Selectors',
                                                                     'Searchers' => 'Searchers',
                                                                     'Attribute' => 'Attribute',
                                                                     'ImageCropers' => 'ImageCropers'
                                                                     ]
                                                                     ,old('role'),
                                                                      ['class' => 'form-control'])
                                            !!}
                                            @if($errors->has('role'))
                                                <div class="alert alert-danger">{{ $errors->first('role') }}</div>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <strong>Reason :</strong>
                                            <textarea type="text" name="reason" class="form-control"
                                                      placeholder="Reason">{{ old('reason') ? old('reason') : $reason }}</textarea>
                                            @if($errors->has('reason'))
                                                <div class="alert alert-danger">{{ $errors->first('reason') }}</div>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            {{--<input type="text" hidden name="stage" value="2">
                                            <button type="submit" class="btn btn-secondary">Submit</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        @endif
                    </div> --}}
                    @if ($isUploaded == 1)
                      <div class="form-group">
                           @if(auth()->user()->checkPermission('productinventory-edit'))
                              <form method="POST" action="{{ route('productinventory.stock',$id) }}"
                                    style="display: inline;">
                                  @csrf
                                  <div class="form-group">
                                      <strong>Stock</strong>
                                      <input type="number" class="form-control" name="stock" value="{{ old('stock') ? old('stock') : $stock }}" />
                                  </div>
                                  @if($errors->has('stock'))
                                      <div class="alert alert-danger">{{$errors->first('stock')}}</div>
                                  @endif
                                  <button type="submit" class="btn btn-secondary">+</button>
                              </form>
                          @endif
                      </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{--</form>--}}


@endsection

@section('scripts')

<script>

function approveProduct(id){
    $.ajax({
            url: "{{ route('google.product.status') }}",
            type: 'POST',
            beforeSend: function () {
                $("#loading-image").show();
            },
            success: function (response) {
                $("#loading-image").hide();
                alert('Product Approved');
            },
            data: {
                id: id,
                type: "approve",
                _token: "{{ csrf_token() }}",
            }
    });
}

function rejectProduct(id){
    $.ajax({
            url: "{{ route('google.product.status') }}",
            type: 'POST',
            beforeSend: function () {
                $("#loading-image").show();
            },
            success: function (response) {
                $("#loading-image").hide();
                alert('Product Rejected');
            },
            data: {
                id: id,
                type: "reject",
                _token: "{{ csrf_token() }}",
            }
    });
}

function approveTextProduct(id){
    $.ajax({
            url: "{{ route('google.product.status') }}",
            type: 'POST',
            beforeSend: function () {
                $("#loading-image").show();
            },
            success: function (response) {
                $("#loading-image").hide();
                alert('Product Approved');
            },
            data: {
                id: id,
                type: "textapprove",
                _token: "{{ csrf_token() }}",
            }
    });
}

function rejectTextProduct(id){
    $.ajax({
            url: "{{ route('google.product.status') }}",
            type: 'POST',
            beforeSend: function () {
                $("#loading-image").show();
            },
            success: function (response) {
                $("#loading-image").hide();
                alert('Product Rejected');
            },
            data: {
                id: id,
                type: "textreject",
                _token: "{{ csrf_token() }}",
            }
    });
}

</script>

@endsection
