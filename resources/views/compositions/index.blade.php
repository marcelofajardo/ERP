@extends('layouts.app')
@section('title')
    Compositions 
@endsection
@section('content')
<style type="text/css">
    .form-inline label {
        display: inline-block;
    }
    .form-control {
        height: 25px !important;
    }
    .small-field { 
        margin-bottom: 0px;
     }
     .small-field-btn {
        padding: 0px 13px;
     }
     .composition .select2-container{
         max-width: 100%;
         width: 100% !important;
     }
    .composition  .btn-group-sm>.btn, .btn-sm {
        padding: 3px 8px !important;
    }
    .composition .small-field-btn {
        padding: 2px 7px !important;
    }
    .composition .btn {
        display: inline-block;
        padding: 3px 8px !important;
    }
    .composition input[type="text"]{
        border: 1px solid #ccc;
        border-radius: 4px;
        height: 34px;
    }
    .btn.compositions{
        padding: 4px 12px !important;
    }
    .composition .select2-container .select2-selection--single, .compositions .select2-container .select2-selection--single{
        height: 34px !important;
        border: 1px solid #ccc !important;
    }
    .composition .select2-container--default .select2-selection--single .select2-selection__rendered, .compositions .select2-container--default .select2-selection--single .select2-selection__rendered{
        line-height: 32px !important;
    }
    .composition .composition-assign-update, .composition .small-field-btn{
        background: #fff;
        padding: 0 3px !important;
    }
    .composition .composition-assign-update i,.composition .small-field-btn i{
        color: #757575;
        font-size: 18px;
    }
    .compositions .btn-secondary{
        color: #757575;
        border: 1px solid #ccc;
        background: #fff;
    }
    .change-data input {
        height: 30px !important;
    }
    @media(max-width:1400px){
        .change-data input{
            /*width: 100%;*/
            /*max-width: 100%;*/
            /*flex: 0 0 100%;*/
            /*margin-top: 15px !important;*/
            height: 30px !important;
            width: 150px !important;
        }
    }
</style>
<div class="row">
    <div class="col-md-12">
        <h2 class="page-heading">Compositions ({{$compositions->total()}})</h2>
    </div>
     @if ($message = Session::get('success'))
         <div class="col-md-12">
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
         </div>   
    @endif
</div>
  <div class="row change-data">
      <div class="col-md-6 mt-5">
          {!! Form::open(["class" => "form-inline" , "route" => 'compositions.store',"method" => "POST"]) !!}
          <div class="form-group">
              <label for="name">Name:</label>
              <input type="text" name="name" class="form-control" id="name" placeholder="Enter Name" value="{{ old('name') ? old('name') : request('name') }}"/>
          </div>
          <div class="form-group ml-2">
              <label for="replace_with">Erp Name:</label>
              <input type="text" name="replace_with" class="form-control" placeholder="Enter Erp Name" value="{{ old('replace_with') ? old('replace_with') : request('replace_with') }}" id="replace_with">
          </div>
          <button type="submit" class="btn btn-default ml-2 small-field-btn compositions">Submit</button>
          </form>
      </div>
      <div class="col-md-6 mt-5">
          {!! Form::open(["class" => "form-inline" , "route" => 'compositions.replace',"method" => "POST"]) !!}
          <div class="form-group">
              <label for="name">Keyword:</label>
              <input type="text" name="name" class="form-control" id="name" placeholder="Enter Name" value=""/>
          </div>
          <div class="form-group ml-2">
              <label for="replace_with">Replace With:</label>
              <input type="text" name="replace_with" class="form-control" placeholder="Enter Erp Name" value="" id="">
          </div>
          <button type="submit" class="btn btn-default ml-2 small-field-btn compositions">Replace</button>
          </form>
      </div>
  </div>
<div class="row">
    <div class="col-md-4 mt-5">
        {!! Form::open(["class" => "form-inline" , "route" => 'compositions.index',"method" => "GET"]) !!}    
          <div class="form-group change-data">
            <input type="text" name="keyword" class="form-control" id="name" placeholder="Enter keyword" value="{{ old('keyword') ? old('keyword') : request('keyword') }}"/>
          </div>
          <div class="form-group ml-2">
            <input type="checkbox" name="with_ref" class="form-control" id="with_ref" @if(request('with_ref') == 1) checked="checked" @endif value="1"/> With Ref
          </div>
          <button type="submit" class="btn btn-default ml-2 small-field-btn "><i class="fa fa-search"></i></button>
        </form>
    </div>
    <div class="col-md-8 mt-5 compositions">
        <div class="form-group small-field">
            <?php echo Form::select(
                'replace_with', 
                $listcompostions , 
                null, 
                ["class" => "form-control change-list-all-compostion select2", 'style' => 'width:400px']
            ); ?>
            <button type="button" class="btn btn-secondary update-composition-selected">Update Selected</button>
            <a target="__blank" href="{{ route('compositions.delete.unused') }}">
                <button type="button" class="btn btn-secondary delete-not-used">Delete not used</button>
            </a>
                <button class="btn btn-secondary approve-all">Approve all</button>
        </div>
    </div>
{{--    <div class="col-md-2 mt-2">--}}
{{--        <button class="btn btn-secondary approve-all">Approve all</button>--}}
{{--        --}}
{{--    </div>--}}
    <div class="col-md-12 mt-5">
        <table class="table table-bordered composition">
            <tr>
                <th width="3%" ><span><input type="checkbox" class="check-all-btn mr-2">&nbsp;</span></th>
                <th width="7%" >SN</th>
                <th width="25%">Composition</th>
                <th width="5%">Pro Count</th>
                <th width="30%">Original</th>
                <th width="20%">Erp Composition</th>
                <th width="10%">Action</th>
            </tr>
            @foreach($compositions as $key=>$composition)
                <tr>
                    <td><input type="checkbox" name="composition[]" value="{{ $composition->id }}" class="composition-checkbox mr-2"></td>
                    <td>{{ $composition->id }} </td>
                    <td>
                        <div class="d-flex">
                            <input type="text" class="col-10" id="{{ $composition->id }}" value="{{ $composition->name }}"> 
                            <!-- <button class="btn btn-secondary btn-sm composition-name-update" data-id="{{ $composition->id }}" title="Update"><i class="fa fa-save"></i></button> -->

                            <span class="call-used-product d-none"  data-id="{{ $composition->id }}" data-type="name">{{ $composition->name }}</span> 
                            <button type="button" class="btn btn-image add-list-compostion" data-name="{{ $composition->name }}" data-id="{{ $composition->id }}"><img src="/images/add.png"></button>
                        </div>
                    </td>
                    <td class="text-center">{{ $composition->product_counts_count }}</td>
                    <td>
                        <input type="text" class="col-10 compositions-from-org" data-org-name="{{ $composition->name }}" id="compo_{{ $composition->id }}" value="{{ $composition->name }}">
                        <button class="btn btn-secondary btn-sm composition-assign-update ml-2" data-id="{{ $composition->id }}" title="Update"><i class="fa fa-save"></i></button>
                    </td>
                    <td>
                        <div class="form-group small-field">
                            <select name="replace_with" class="form-control change-list-compostion select2" style="width:400px" data-name="{{$composition->name}}" id="select{{$composition->id}}" data-id="{{ $composition->id }}">
                                <option value="">-- Select --</option>
                                @php
                                    $selected = false;
                                    $optionSelected = null;
                                @endphp
                                @foreach ($listcompostions as $item)
                                    @php
                                        $selected = false;
                                        $optionSelected = null;
                                        $itemArr  = array_filter(explode(' ', preg_replace("/[^a-zA-Z]+/", " ", $item)));
                                        $exitsArr = array_filter(explode(' ', preg_replace("/[^a-zA-Z]+/", " ", $composition->replace_with)));
                                        if($exitsArr){
                                            if( sizeof( $exitsArr ) > 2 ){
                                                if( in_array( $exitsArr[2], $itemArr) && $selected == false ){
                                                    $selected = true; 
                                                    $optionSelected =  'selected'; 
                                                }
                                            }else {
                                                if( array_intersect($itemArr, $exitsArr) && $selected == false ){ 
                                                    $selected = true; 
                                                    $optionSelected =  'selected'; 
                                                }

                                                if($composition->name == $item) {
                                                   $selected = true; 
                                                   $optionSelected =  'selected';  
                                                }
                                            }
                                        }
                                    @endphp
                                    <option value="{{ $item }}" {{ $optionSelected }} > {{ $item }} </option>
                                @endforeach
                            </select>
                            <!-- <button class="btn btn-secondary btn-xs change-selectbox" data-id="{{$composition->id}}"><i class="fa fa-save"></i></button> -->
                        </div>
                    </td>
                    <td>
                        <form action="{{ route('compositions.destroy', $composition->id) }}" method="POST">
                            {{ method_field('DELETE') }}
                            {{ csrf_field() }}
                            <button class="btn btn-secondary small-field-btn" onclick="return confirm('Are you sure you want to delete ?')">
                                <i class="fa fa-trash" type="submit"></i>
                            </button>
                            <button data-id="{{$composition->id}}" class="btn btn-secondary show-history-btn small-field-btn">
                                <i class="fa fa-bars"></i>
                            </button>
                        </form>

                    </td>
                </tr>
            @endforeach
        </table>
        {{ $compositions->appends(request()->except('page'))->links() }}
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
    <script type="text/javascript">
            $(".select2").select2({"tags" : true});

            $(document).on("click",".approve-all",function() {

                var changesFrom = $(".composition-checkbox:checked");
                var ids = [];
                var values = [];
                $.each(changesFrom,function(k,v) {
                    ids.push( $(v).val() );
                    values.push( $('#select'+$(v).val()).val() );
                });

                $.ajax({
                    type: 'POST',
                    url: '/compositions/update-all-composition',
                    beforeSend: function () {
                        $("#loading-image").show();
                    },
                    data: {
                        _token: "{{ csrf_token() }}",
                        from : ids,
                        to : values
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

            $(document).on("click",".change-selectbox",function() {
                $('#select'+$(this).data('id')).trigger('change');
            });
            
            $(document).on("click",".call-used-product",function() {
                var $this = $(this);
                $.ajax({
                    type: 'GET',
                    url: '/compositions/'+$this.data("id")+'/used-products',
                    beforeSend: function () {
                        $("#loading-image").show();
                    },
                    data: {
                        _token: "{{ csrf_token() }}"
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
                    toastr['error']('Sorry no product founds', 'error');
                });
            });

            $(document).on("click",".composition-name-update",function() {
                var $this = $(this);
                var id = $this.data('id');
                var text = $('#'+id).val();
                
                if( text == '' || id == '' ){
                    return false;
                }
                $.ajax({
                    type: 'POST',
                    url: '/compositions/update-name',
                    beforeSend: function () {
                        $("#loading-image").show();
                    },
                    data: {
                        _token: "{{ csrf_token() }}",
                        id : id,
                        name : text
                    },
                    dataType: "json"
                }).done(function (response) {
                    $("#loading-image").hide();
                    if (response.code == 200) {
                        toastr['success']('Successfully updated', 'Success');    
                    }else{
                        toastr['error']('Something went wrong', 'Error');    
                    }
                }).fail(function (response) {
                    $("#loading-image").hide();
                    toastr['error']('Sorry, something went wrong', 'Error');
                });
            });

            $(document).on("click",".show-history-btn",function(e) {
                e.preventDefault();
                var $this = $(this);
                $.ajax({
                    type: 'GET',
                    url: '/compositions/'+$this.data("id")+'/history',
                    beforeSend: function () {
                        $("#loading-image").show();
                    }
                }).done(function (response) {
                    $("#loading-image").hide();
                    $(".show-listing-exe-records").find('.modal-dialog').html(response);
                    $(".show-listing-exe-records").modal('show');
                }).fail(function (response) {
                    $("#loading-image").hide();
                    toastr['error']('Sorry no record found', 'error');
                });
            });

            

            $(document).on("change",".change-list-compostion",function() {
                var $this = $(this);
                $.ajax({
                    type: 'GET',
                    url: '/compositions/affected-product',
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

            $(document).on("click",".btn-change-composition",function() {
                var $this = $(this);
                $.ajax({
                    type: 'POST',
                    url: '/compositions/update-composition',
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

            $(document).on("click",".add-list-compostion",function() {
                var $this = $(this);
                id = $this.data("id");
                to = $('#select'+id).val()
                $.ajax({
                    type: 'GET',
                    url: '/compositions/affected-product',
                    beforeSend: function () {
                        $("#loading-image").show();
                    },
                    data: {
                        _token: "{{ csrf_token() }}",
                        from : $this.data("name"),
                        to : $this.data("name"),
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

            $(document).on("click",".check-all-btn",function() {
                $(".composition-checkbox").trigger("click");
            });

            $(document).on("click",".composition-assign-update",function() {
                var $this = $(this);
                $.ajax({
                    type: 'GET',
                    url: '/compositions/affected-product',
                    beforeSend: function () {
                        $("#loading-image").show();
                    },
                    data: {
                        _token: "{{ csrf_token() }}",
                        from : $this.closest("td").find(".compositions-from-org").data("org-name"),
                        to : $this.closest("td").find(".compositions-from-org").val()
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

            $(document).on('click','.update-composition-selected',function() {
                var changeto = $(".change-list-all-compostion").val();
                var changesFrom = $(".composition-checkbox:checked");
                var ids = [];
                $.each(changesFrom,function(k,v) {
                    ids.push($(v).val());
                });
                
                $.ajax({
                    type: 'POST',
                    url: '/compositions/update-multiple-composition',
                    beforeSend: function () {
                        $("#loading-image").show();
                    },
                    data: {
                        _token: "{{ csrf_token() }}",
                        from : ids,
                        to : changeto
                    },
                    dataType: "json"
                }).done(function (response) {
                    $("#loading-image").hide();
                    if (response.code == 200) {
                        if(response.html != "") {
                            toastr['success'](response.message, 'success');
                            location.reload();
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
    </script>
@endsection
@endsection