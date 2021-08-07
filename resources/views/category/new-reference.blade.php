@extends('layouts.app')
@section('title')
    New Category Reference 
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
        <h2 class="page-heading">New Category Reference ({{ $scrapped_category_mapping->total() }})</h2>
    </div>
    @if ($message = Session::get('success'))
         <div class="col-md-12">
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
         </div>   
    @endif
    <div class="col-md-12">
        <form>
            <div class="form-group col-md-3">
                <input type="search" name="search" class="form-control" value="{{ request('search') }}">
            </div>
            <div class="form-group col-md-2">
                <button type="submit" class="btn btn-secondary">Search</button>
            </div>
        </form>
        <div class="form-group small-field col-md-3">
            <select class="select2 form-control change-list-categories">
                @foreach($categoryAll as $cat)
                    <option value="{{ $cat['id'] }}">{{ $cat['value'] }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-md-4">
            <button type="button" class="btn btn-secondary update-category-selected col-md-3">Update</button>&nbsp;
            <a target="__blank" href="{{ route('category.delete.unused') }}">
                <button type="button" class="btn btn-secondary delete-not-used">Delete not used</button>
            </a>

            {{-- START - Purpose : Display Chcekbox regarding need_to_skip_status - #DEVTASK-4143 --}}
            @if($need_to_skip_status == true)
                <input type="checkbox" id="show_skipeed" name="show_skipeed"> <label>Show Skipped</label>
            @endif
            {{-- END - #DEVTASK-4143 --}}
        
            <a href="{{ route('category.fix-autosuggested',request()->all()) }}" class="fix-autosuggested">
                <button type="button" class="btn btn-secondary">Fix Auto Suggested</button>
            </a>
        </div>
        <div class="form-group col-md-4">
            <a href="{{ route('category.fix-autosuggested-via-str',request()->all()) }}" class="fix-autosuggested-auto">
                <button type="button" class="btn btn-secondary">Auto fix</button>
            </a>
            <a href="{{ route('category.fix-autosuggested',['show_auto_fix'=>true]) }}" class="fix-autosuggested">
                <button type="button" class="btn btn-secondary">Show auto fix</button>
            </a>
        </div>
        {{-- <div class="form-group col-md-4"> --}}
        {{-- </div> --}}
    </div>
    <div class="col-md-12 mt-5">
        <table class="table table-bordered">
            <tr>
                <th width="10%"><input type="checkbox" class="check-all-btn">&nbsp;SN</th>
                <th width="30%">Category</th>
                <th width="30%">Website</th>
                <th width="10%">Count</th>
                <th width="40%">Erp Category</th>
               <!--  <th width="20%">Action</th> -->
            </tr>
            <?php $count = 1; ?>
            {{-- @dd($unKnownCategories->items()); --}}
            @foreach($scrapped_category_mapping as $key => $unKnownCategory)

                
                @if($unKnownCategory->name != '')
                    <?php 
                        //getting name 
                        $nameArray  = explode('/',$unKnownCategory->name);
                        $name = end($nameArray);

                    ?>
                    <tr>
                        <td>
                            <input type="checkbox" name="categories[]" value="{{ $unKnownCategory }}" class="categories-checkbox">&nbsp;{{ $count }}
                        </td>
                        
                        <td>
                            <span class="call-used-product" data-id="{{ $unKnownCategory->name }}"  data-type="name">{{ $unKnownCategory->name }}</span> <!-- <button type="button" class="btn btn-image add-list-compostion" data-name="{{ $unKnownCategory }}" ><img src="/images/add.png"></button> -->
                        </td>
                        
                        <td class="website-popup" data-website="{{ $unKnownCategory->all_websites }}">
                            {{ explode('<br>', $unKnownCategory->all_websites)[0] }} <br>
                            {{ explode('<br>', $unKnownCategory->all_websites)[1] ?? '' }} <br>
                            {{ explode('<br>', $unKnownCategory->all_websites)[2] ?? ''}}
                        </td>
                           
                        <td>
                            {{$unKnownCategory->total_products}}
                        </td>

                        <td>
                            <select class="select2 form-control change-list-category" data-old-id={{ $unKnownCategory->id  }} data-name="{{ $name }}" data-whole="{{ $unKnownCategory->name }}">
                                @foreach($categoryAll as $cat)
                                    <option value="{{ $cat['id'] }}">{{ $cat['value'] }}</option>
                                @endforeach
                            </select>
                       </td>
                    </tr>
                    <?php $count++; ?>
                @endif
            @endforeach
        </table>
        {!! $scrapped_category_mapping->render() !!}
    </div>
</div>
<div id="loading-image" style="position: fixed;left: 0px;top: 0px;width: 100%;height: 100%;z-index: 9999;background: url('/images/pre-loader.gif') 
          50% 50% no-repeat;display:none;">
</div>
<div class="common-modal modal show-listing-exe-records" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
    </div>  
</div>
    <div class="container">
        <h2>Modal Example</h2>
        <!-- Trigger the modal with a button -->
        <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#website-popup-model">Open Modal</button>
    
        <!-- Modal -->
        <div class="modal fade" id="website-popup-model" role="dialog">
            <div class="modal-dialog">
            
                <!-- Modal content-->
                <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Website</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <p></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
                </div>
                
            </div>
        </div>
        
    </div>
@section('scripts')
    <script type="text/javascript">
            $(".select2").select2({"tags" : true});

            $(document).on("click",".call-used-product",function() {
                var $this = $(this);
                $.ajax({
                    type: 'GET',
                    url: '/category/references/used-products',
                    beforeSend: function () {
                        $("#loading-image").show();
                    },
                    data: {
                        _token: "{{ csrf_token() }}",
                        q : $this.data("id")
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

            $(document).on("change",".change-list-category",function() {
                var $this = $(this);
               // var oldCatid = {{ $unKnownCategoryId }};

                $.ajax({
                    type: 'POST',
                    url: '/category/references/affected-product-new',
                    beforeSend: function () {
                        $("#loading-image").show();
                    },
                    data: {
                        _token: "{{ csrf_token() }}",
                        'cat_name' : $this.data("name"),
                        'new_cat_id' : $this.val(),
                        'old_cat_id' : $this.data("old-id"),
                        'wholeString': $this.data("whole"),
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
                // var oldCatid = {{ $unKnownCategoryId }};
                $.ajax({
                    type: 'POST',
                    url: '/category/references/update-category',
                    beforeSend: function () {
                        $("#loading-image").show();
                    },
                    data: {
                        _token: "{{ csrf_token() }}",
                        'old_cat_id' :  $this.data("from-id"),
                        'new_cat_id' : $this.data("to"),
                        'cat_name' : $this.data("from"),
                        'with_product':$this.data('with-product'),
                        'wholeString': $this.data("whole"),
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
                console.log(to)
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
                $(".categories-checkbox").trigger("click");
            });

            $(document).on("click",".fix-autosuggested",function(e) {
                var $this = $(this);
                
                var show_skipeed_btn_value = $('#show_skipeed').prop('checked')// Purpose : Check Skip Button value - #DEVTASK-4143
               
                if(show_skipeed_btn_value == undefined)
                    show_skipeed_btn_value = true;
                    
                e.preventDefault();
                $.ajax({
                    type: 'GET',
                    url: $this.attr("href"),
                    beforeSend: function () {
                        $("#loading-image").show();
                    },
                    //START - Send Skip button value - #DEVTASK-4143
                    data: {
                        _token: "{{ csrf_token() }}",
                        show_skipeed_btn_value : show_skipeed_btn_value,
                    },
                    //END - #DEVTASK-4143
                    dataType: "json"
                }).done(function (response) {
                    $("#loading-image").hide();
                    if (response.code == 200) {
                        if(response.html != "") {
                            $(".show-listing-exe-records").find('.modal-dialog').html(response.html);
                            $(".show-listing-exe-records").modal('show');
                            $(".show-listing-exe-records").find(".select2").select2({"tags" : false});
                        }else{
                            toastr['error']('Sorry no response fetched', 'error');
                        }
                    }
                }).fail(function (response) {
                    $("#loading-image").hide();
                    toastr['error']('Sorry, something went wrong', 'error');
                    $(".show-listing-exe-records").modal('hide');
                });
            });

            $(document).on("click",".fix-autosuggested-auto",function(e) {
                var $this = $(this);
                e.preventDefault();
                $.ajax({
                    type: 'GET',    
                    url: $this.attr("href"),
                    beforeSend: function () {
                        $("#loading-image").show();
                    },
                    data: {
                        _token: "{{ csrf_token() }}",
                    },
                    dataType: "json"
                }).done(function (response) {
                    console.log(response)
                    $("#loading-image").hide();
                    if (response.code == 200) {
                        if(response.count){
                        toastr['success'](response.count + ' Data updated successfully', 'success');
                        }else{
                        toastr['success']( 'All data already updated', 'success');
                        }
                    }
                }).fail(function (response) {
                    $("#loading-image").hide();
                    toastr['error']('Sorry, something went wrong', 'error');
                });
            });

            $(document).on('click','.update-category-selected',function() {
                var changeto = $(".change-list-categories").val();
                var changesFrom = $(".categories-checkbox:checked");
                var ids = [];
                $.each(changesFrom,function(k,v) {
                    ids.push($(v).val());
                });
                var oldCatid = {{ $unKnownCategoryId }};
                $.ajax({
                    type: 'POST',
                    url: '/category/references/update-multiple-category',
                    beforeSend: function () {
                        $("#loading-image").show();
                    },
                    data: {
                        _token: "{{ csrf_token() }}",
                        from : ids,
                        to : changeto,
                        old_cat_id: oldCatid
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


            $(document).on("submit",".update-reference-category-form",function(event){
                event.preventDefault();
                var $this = $(this);
                $.ajax({
                    type: $this.attr("method"),
                    url: $this.attr("action"),
                    beforeSend: function () {
                        $("#loading-image").show();
                    },
                    data: $this.serialize(),
                    dataType: "json"
                }).done(function (response) {
                    $("#loading-image").hide();
                    if (response.code == 200) {
                        if(response.html != "") {
                            toastr['success'](response.message, 'success');
                            // location.reload();
                        }else{
                            toastr['error']('Sorry, something went wrong', 'error');
                        }
                        $(".show-listing-exe-records").modal('hide');
                        // location.reload();
                    }
                }).fail(function (response) {
                    $("#loading-image").hide();
                    toastr['error']('Sorry, something went wrong', 'error');
                    $(".show-listing-exe-records").modal('hide');
                });
            });

            $(document).on('click','.website-popup',function(){
                $('#website-popup-model').find('p').text('');
                var website = $(this).data('website');
                $('#website-popup-model').modal('show');
                $('#website-popup-model').find('p').append(website);
            })

    </script>
@endsection
@endsection