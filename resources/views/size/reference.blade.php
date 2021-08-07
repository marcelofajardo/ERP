@extends('layouts.app')
@section('title')
    Sizes Reference 
@endsection
@section('content')
<style type="text/css">
    .small-field { 
        margin-bottom: 0px;
     }
     .small-field-btn {
        padding: 0px 13px;
     }   
</style>
<div class="row">
    <div class="col-md-12">
        <h2 class="page-heading">Sizes Reference ({{ $unknownSizes->total() }})</h2>
    </div>
    <div class="col-md-12">
        <form>
            <div class="form-group col-md-3">
                <input type="search" name="search" class="form-control" value="{{ request('search') }}">
            </div>
            <div class="form-group col-md-2">
                <button type="submit" class="btn btn-secondary">Search</button>
            </div>
        </form>
<!--         <div class="form-group small-field col-md-3">
            <select class="select2 form-control change-list-categories">
                @foreach($sizes as $size)
                    <option value="{{ $size->id }}">{{ $size->name }}</option>
                @endforeach
            </select>
        </div> -->
<!--         <div class="form-group col-md-4">
            <button type="button" class="btn btn-secondary update-category-selected col-md-3">Update</button>
        </div> -->
    </div>
    <div class="col-md-12 mt-5">
        <table class="table table-bordered">
            <tr>
                <th width="10%"><input type="checkbox" class="check-all-btn">&nbsp;SN</th>
                <th width="30%">Size</th>
                <th width="40%">Reference</th>
               <!--  <th width="20%">Action</th> -->
            </tr>
            <?php $count = 1; ?>
            @foreach($unknownSizes as $unknownSize)
                <tr>
                    <td><input type="checkbox" name="categories[]" value="{{ $unknownSize->size }}" class="categories-checkbox">&nbsp;{{ $count }}</td>
                    <td><span class="call-used-product"  data-type="name" data-id="{{ $unknownSize->id }}">{{ $unknownSize->size }}</span> <button type="button" class="btn btn-image add-list-compostion" data-name="{{ $unknownSize->size }}" ><img src="/images/add.png"></button></td>
                    <td>
                        <select class="select2 form-control change-list-size" data-name="{{ $unknownSize->size }}" id="{{ $unknownSize->size }}">
                            @foreach($sizes as $size)
                                <option value="{{ $size->id }}">{{ $size->name }}</option>
                            @endforeach
                        </select>
                   </td>
                </tr>
                <?php $count++; ?>
            @endforeach
        </table>
        {{ $unknownSizes->appends(request()->except('page')) }}
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

            $(document).on("click",".call-used-product",function() {
                var $this = $(this);
                $.ajax({
                    type: 'GET',
                    url: '/sizes/'+$this.data("id")+'/used-products',
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





            $(document).on("click",".add-list-compostion",function() {
                var $this = $(this);
                name = $this.data("name")
                selected = $('#'+name).val();
                $.ajax({
                    type: 'POST',
                    url: '/sizes/references/chamge',
                    beforeSend: function () {
                        $("#loading-image").show();
                    },
                    data: {
                        _token: "{{ csrf_token() }}",
                        'from' : name,
                        'to' : selected,
                    },
                    dataType: "json"
                }).done(function (response) {
                    
                }).fail(function (response) {
                    
                });
            });

            $(document).on("click",".add-list-compostion",function() {
                var $this = $(this);
                name = $this.data("name")
                selected = $('#'+name).val();
                $.ajax({
                    type: 'GET',
                    url: '/compositions/affected-product',
                    beforeSend: function () {
                        $("#loading-image").show();
                    },
                    data: {
                        _token: "{{ csrf_token() }}",
                        'from' : name,
                        'to' : selected,
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
                $(".categories-checkbox").trigger("click");
            });

            
            $(document).on("change",".change-list-size",function() {
                var $this = $(this);
                $.ajax({
                    type: 'GET',
                    url: '/sizes/affected-product',
                    beforeSend: function () {
                        $("#loading-image").show();
                    },
                    data: {
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
                    url: '/sizes/update-sizes',
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



          
    </script>
@endsection
@endsection