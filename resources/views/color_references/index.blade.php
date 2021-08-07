@extends('layouts.app')
@section('title')
    Color References
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <h2 class="page-heading">Color References</h2>
        </div>
        <div class="col-md-12">
            <div class="col-md-4 mt-5">
                <a href="{{ route('erp-color-fix-cmd') }}" class="btn btn-default ml-2 small-field-btn">Fix Color Issue</a>

            
            </div>
            <div class="col-md-4 mt-5 float-right">
                {!! Form::open(["class" => "form-inline" , "route" => 'color-reference.index',"method" => "GET"]) !!}    
                  <div class="form-group">
                    <input type="text" name="keyword" class="form-control" id="keyword" placeholder="Enter keyword" value="{{ old('keyword') ? old('keyword') : request('keyword') }}"/>
                  </div>
                  <div class="form-group ml-2">
                    <input type="checkbox" name="no_ref" class="form-control" id="no_ref" @if(request('no_ref') == 1) checked="checked" @endif value="1"/> No Ref
                  </div>
                  <button type="submit" class="btn btn-default ml-2 small-field-btn"><i class="fa fa-search"></i></button>
                </form>
            </div>
        </div>    
        <div class="col-md-12 mt-5">
            <form action="{{ action('ColorReferenceController@store') }}" method="post">
                @csrf
                <table class="table table-striped">
                    <tr>
                        <th>SN</th>
                        <th>Color</th>
                        <th>Erp Colo Name</th>
                        <th>Color Name</th>
                    </tr>
                    @foreach($colors as $key=>$color)
                        <tr>
                            <td>{{ $key+1 }}</td>
                            <td style="background-color: #{{$color->color_code}}">{{ $color->color_code }}</td>
                            <td>
                                <select class="form-control color-change-event" data-name="{{ $color->color_name }}" name="colors[{{$color->id}}]" id="color_{{$color->id}}">
                                    <option value="">Select Color</option>
                                    @foreach((new \App\Colors())->all() as $col)
                                        <option {{ $col==$color->erp_name ? 'selected' : '' }} value="{{ $col }}">{{ $col }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="call-used-product" data-name="{{ $color->color_name }}">{{ $color->color_name }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="3">
                            <button class="btn btn-secondary">SAVE</button>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
    <div id="loading-image" style="position: fixed;left: 0px;top: 0px;width: 100%;height: 100%;z-index: 9999;background: url('/images/pre-loader.gif') 
          50% 50% no-repeat;display:none;">
    </div>
    <div class="common-modal modal show-listing-exe-records" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
        </div>  
    </div>
    @section('scripts')
     <script>

            $(document).on("change",".color-change-event",function() {
                var $this = $(this);
                var form  = $this.closest("form").find("#old-cat-id").val()

                $.ajax({
                    type: 'GET',
                    url: '/color-reference/affected-product',
                    beforeSend: function () {
                        $("#loading-image").show();
                    },
                    data: {
                        _token: "{{ csrf_token() }}",
                        from : $this.data("name"),
                        to : $this.val()
                    },
                    dataType: "json"
                }).done(function (response) {
                    $("#loading-image").hide();
                    if (response.code == 200) {
                        if(response.html != "") {
                            $(".show-listing-exe-records").find('.modal-dialog').html(response.html);
                            $(".show-listing-exe-records").modal('show');
                        }else{
                            //toastr['error']('Sorry no product founds', 'error');
                        }
                    }
                }).fail(function (response) {
                    $("#loading-image").hide();
                    console.log("Sorry, something went wrong");
                });
            });

            $(document).on("click",".btn-change-color",function() {
                var $this = $(this);
                $.ajax({
                    type: 'POST',
                    url: '/color-reference/update-color',
                    beforeSend: function () {
                        $("#loading-image").show();
                    },
                    data: {
                        _token: "{{ csrf_token() }}",
                        from : $this.data("from"),
                        to : $this.data("to"),
                        with_product:$this.data('with-product')
                    },
                    dataType: "json"
                }).done(function (response) {
                    $("#loading-image").hide();
                    if (response.code == 200) {
                        if(response.html != "") {
                            toastr['success'](response.message, 'success');
                        }else{
                            toastr['error']('Sorry, something went wrong', 'error');
                        }
                        $(".show-listing-exe-records").modal('hide');
                    }
                }).fail(function (response) {
                    $("#loading-image").hide();
                    toastr['error']('Sorry, something went wrong', 'error');
                    $(".show-listing-exe-records").modal('hide');
                });
            });


            $(document).on("click",".call-used-product",function() {
                var $this = $(this);
                $.ajax({
                    type: 'GET',
                    url: '/color-reference/used-products',
                    beforeSend: function () {
                        $("#loading-image").show();
                    },
                    data: {
                        _token: "{{ csrf_token() }}",
                        q : $this.data("name")
                    },
                    dataType: "json"
                }).done(function (response) {
                    $("#loading-image").hide();
                    if (response.code == 200) {
                        if(response.html != "") {
                            $(".show-listing-exe-records").find('.modal-dialog').html(response.html);
                            $(".show-listing-exe-records").modal('show');
                        }else{
                            toastr['error']('Sorry no product founds', 'error');
                        }
                    }
                }).fail(function (response) {
                    $("#loading-image").hide();
                    console.log("Sorry, something went wrong");
                });
            });
        </script>
    @endsection
@endsection