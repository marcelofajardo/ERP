@extends('layouts.app')

@section('title', 'Log List Magento')

@section("styles")
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
  <style type="text/css">

  #loading-image {
    position: fixed;
    top: 50%;
    left: 50%;
    margin: -50px 0px 0px -50px;
  }
  input {
    width: 100px;
  }

</style>
@endsection

@section('content')
  <div id="myDiv">
    <img id="loading-image" src="/images/pre-loader.gif" style="display:none;" />
  </div>
  <div class="row">
    <div class="col-lg-12 margin-tb">
      <h2 class="page-heading">Log List Magento ({{ $total_count }})</h2>
      <div class="pull-right">
        <button type="button" class="btn btn-image" onclick="refreshPage()"><img src="/images/resend2.png" /></button>
        <a href="/logging/magento-product-api-call" target="__blank">
          <button type="button" class="btn btn-image"><img src="/images/details.png" /></button>
        </a>
      </div>
  </div>

  <div class="col-md-12">
    <div class="panel panel-default">

      <div class="panel-body p-0">
        <form action="{{ route('list.magento.logging') }}" method="GET">
          <div class="row p-3">
          <div class="col-md-3">
                <label for="select_date">Date</label>
                <input type="text" name="select_date" class="form-control datepicker" id="select_date" placeholder="Enter Date" value="{{isset($request->select_date) ? $request->select_date : ''}}">
             
            </div>
            <div class="col-md-2">
              <label for="product_id">Product ID</label>
              <input type="text" class="form-control" id="product_id" name="product_id" value="{{ old('queue') }}">
            </div>
            <div class="col-md-2">
              <label for="sku">SKU</label>
              <input type="text" class="form-control" id="sku" name="sku" value="{{ old('sku')}}">
            </div>
            <div class="col-md-2">
              <label for="sku">Brand</label>
              <input type="text" class="form-control" id="brand" name="brand" value="{{ old('brand')}}">
            </div>
            <div class="col-md-2">
              <label for="sku">Category</label>
              <input type="text" class="form-control" id="category" name="category" value="{{ old('category')}}">
            </div>
            <div class="col-md-2">
              <label for="sku">Status</label>
              <select class="form-control" name="status">
                <option value=''>All</option>
                <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>Available</option>
                <option value="out_of_stock" {{ old('status') == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
              </select>

                    </div>
                    <div class="col-md-2">
                        <label for="sku">Sync Status</label>
                        <select class="form-control" name="sync_status">
                            <option value=''>All</option>
                            <option value="success" {{ isset($filters['sync_status']) && $filters['sync_status'] == 'success' ? 'selected' : '' }}>Success</option>
                            <option value="error" {{ isset($filters['sync_status']) && $filters['sync_status'] == 'error' ? 'selected' : '' }}>Error</option>
                        </select>

                    </div>
                </div>
                <div class="row p-3 ">
                  <div class="col-md-2">
                      <label for="sku">Users</label>
                      <select class="form-control" name="user">
                          <option value=''>All</option>
                          @foreach($users as $user)
                            <option value="{{$user->id}}" {{ isset($filters['user']) && $filters['user'] == $user->id ? 'selected' : '' }}>{{$user->name}}</option>
                          @endforeach
                          <option >
                      </select>
                    </div>
                    <div class="col-md-2">
                      <button class="btn btn-light" id="submit">
                          <span class="fa fa-filter"></span> Filter Results
                      </button>
                      
                    </div>
                    <div class="col-md-2">
                       <button class="btn btn-light" id="submit-show-report">
                            Show Error Report
                        </button>
                    </div>
                </div>
            </form>
          </div>
        </form>

        <div class="table-responsive">
          <table id="magento_list_tbl_895" class="table table-bordered table-hover" style="table-layout:fixed;">
            <thead>
              <th style="width:7%">Product ID</th>
              <th style="width:10%">SKU</th>
              <th style="width:9%">Brand</th>
              <th style="width:8%">Category</th>
              <th style="width:7%">Price</th>
              <th style="width:11%">Message</th>
              <th style="width:8%">Date/Time</th>
              <th style="width:9%">Website</th>
              <th style="width:8%">Status</th>
              <th style="width:8%">Language Id</th>
              <th style="width:7%">Sync Status</th>
              <th style="width:5%">Success</th>
              <th style="width:5%">Failure</th>
              <th style="width:6%">User</th>
              <th style="width:6%">Time</th>
              <th style="width:8%">Action</th>
            </thead>
            <tbody>
              @foreach($logListMagentos as $item)
                <tr>

                  <td><a href="/products/{{ $item->product_id }}" target="__blank">{{ $item->product_id }}</a></td>

                  <td class="expand-row-msg" data-name="sku" data-id="{{$item->id}}">
                    <span class="show-short-sku-{{$item->id}}">{{ str_limit($item->sku, 5 ,'...')}}</span>
                    <span style="word-break:break-all;" class="show-full-sku-{{$item->id}} hidden"><a href="{{ $item->website_url }}/default/catalogsearch/result/?q={{ $item->sku }}" target="__blank">{{$item->sku}}</a></span>
                  </td>
                  <td class="expand-row-msg" data-name="brand_name" data-id="{{$item->id}}">
                    <span class="show-short-brand_name-{{$item->id}}">{{ str_limit($item->brand_name, 10, '...')}}</span>
                    <span style="word-break:break-all;" class="show-full-brand_name-{{$item->id}} hidden">{{$item->brand_name}}</span>
                  </td>
                  <td class="expand-row-msg" data-name="category_title" data-id="{{$item->id}}">
                    <span class="show-short-category_title-{{$item->id}}">{{ str_limit($item->category_home, 10, '...')}}</span>
                    <span style="word-break:break-all;" class="show-full-category_title-{{$item->id}} hidden">{{$item->category_home}}</span>
                  </td>
                  <td> {{$item->price}} </td>
                  <td class="expand-row-msg" data-name="message" data-id="{{$item->id}}">
                    <span class="show-short-message-{{$item->id}}">{{ str_limit($item->message, 20, '...')}}</span>
                    <span style="word-break:break-all;" class="show-full-message-{{$item->id}} hidden">{{$item->message}}</span>
                  </td>
                  <td>
                    @if(isset($item->log_created_at))
                      {{ date('M d, Y',strtotime($item->log_created_at))}}
                    @endif
                  </td>
                  <td class="expand-row-msg" data-name="website_title" data-id="{{$item->id}}">
                    <span class="show-short-website_title-{{$item->id}}">{{ str_limit($item->website_title, 10, '...')}}</span>
                    <span style="word-break:break-all;" class="show-full-website_title-{{$item->id}} hidden">{{$item->website_title}}</span>
                  </td>
                  <td>
                    {{ (isset($item->stock) && $item->stock > 0) ? 'Available' : 'Out of Stock' }}
                  </td>
                  <td> {{(!empty($item->languages)) ? implode(", ",json_decode($item->languages)) : ''}} </td>
                  <td> {{$item->sync_status}} </td>
                  <td>{{$item->total_success}} </td>
                  <td> {{$item->total_error}}</td>
                  <td>{{$item->log_user_name}}</td>
                  <td>{{Carbon\Carbon::parse($item->log_created_at)->format('H:i')}}</td>
                  <td style="display:flex;justify-content: space-between;align-items: center;">
                    <button data-toggle="modal" data-target="#update_modal" class="btn btn-xs btn-secondary update_modal" data-id="{{ $item}}"><i class="fa fa-edit"></i></button>
                    <button class="btn btn-xs btn-secondary show_error_logs" data-id="{{ $item->log_list_magento_id}}" data-website="{{ $item->store_website_id}}"><i class="fa fa-eye"></i></button>
                    <input style="width:20px;height:20px" type="checkbox" class="form-control selectProductCheckbox_class" value="{{ $item->sku }}{{ $item->color }}" websiteid="{{$item->store_website_id}}" name="selectProductCheckbox"/>
                  </td>
                </tr>
              @endforeach()
            </tbody>
          </table>

          <div class="text-center">
            {!! $logListMagentos->appends($filters)->links() !!}
          </div>
        </div>
      </div>
    </div>
  </div>

  <div id="ErrorLogModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" style="padding: 0px;width: 90%;max-width: 90%;">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">magento push error log</h4>
        </div>
        <div class="modal-body">
          <table class="table table-bordered table-hover" style="table-layout:fixed;">
            <thead>
              <th style="width:7%">Product ID</th>
              <th style="width:6%">Date</th>
              <th style="width:11%">Website</th>
              <th style="width:20%">Message</th>
              <th style="width:25%">Request data</th>
              <th style="width:25%">Response Data</th>
              <th style="width:6%">Status</th>
            </thead>
            <tbody class="error-log-data">

            </tbody>
          </table>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>

  <div id="update_modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Update Product</h4>
        </div>
        <form role="form" action="{{route('product.update.magento')}}" method="POST" enctype="multipart/form-data">
          <div class="modal-body">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="update_product_id" id="update_product_id" value="">
            <div class="form-group col-md-8">
              <label for="title">Name</label>
              <input name="name" type="text" class="form-control" id="update_name" value="" required>
            </div>
            <div class="form-group col-md-4">
              <img src="" id="single_product_image" class="quick-image-container img-responive" style="width: 70px;" alt="">
            </div>
            <div class="form-group col-md-12">
              <label for="title">Size</label>
              <input name="size" type="text" class="form-control" id="update_size" value="">
            </div>
            <div class="form-group col-md-12">
              <label for="title">Short Description</label>
              <textarea name="short_description" class="form-control" id="update_short_description"></textarea>
            </div>
            <div class="form-group col-md-4">
              <label for="title">Price</label>
              <input name="price" type="text" class="form-control" id="update_price" value="" required>
            </div>
            <div class="form-group col-md-4">
              <label for="title">Price Special</label>
              <input name="price_eur_special" type="text" class="form-control" id="update_price_eur_special" value="" required>
            </div>
            <div class="form-group col-md-4">
              <label for="title">Price Discounted</label>
              <input name="price_eur_discounted" type="text" class="form-control" id="update_price_eur_discounted" value="" required>
            </div>
            <div class="form-group col-md-4">
              <label for="title">Price INR</label>
              <input name="price_inr" type="text" class="form-control" id="update_price_inr" value="" required>
            </div>
            <div class="form-group col-md-4">
              <label for="title">Price Special</label>
              <input name="price_inr_special" type="text" class="form-control" id="update_price_inr_special" value="" required>
            </div>
            <div class="form-group col-md-4">
              <label for="title">Price Discounted</label>
              <input name="price_inr_discounted" type="text" class="form-control" id="update_price_inr_discounted" value="" required>
            </div>
            <div class="form-group col-md-12">
              <label for="title">Measurement Type</label>
              <input name="measurement_size_type" type="text" class="form-control" id="update_measurement_size_type" value="">
            </div>
            <div class="form-group col-md-4">
              <label for="title">L Measurement</label>
              <input name="lmeasurement" type="text" class="form-control" id="update_lmeasurement" value="">
            </div>
            <div class="form-group col-md-4">
              <label for="title">H Measurement</label>
              <input name="hmeasurement" type="text" class="form-control" id="update_hmeasurement" value="">
            </div>
            <div class="form-group col-md-4">
              <label for="title">D Measurement</label>
              <input name="dmeasurement" type="text" class="form-control" id="update_dmeasurement" value="">
            </div>
            <div class="form-group col-md-12">
              <label for="title">Composition</label>
              <input name="composition" type="text" class="form-control" id="update_composition" value="" required>
            </div>
            <div class="form-group col-md-6">
              <label for="title">Made In</label>
              <input name="made_in" type="text" class="form-control" id="update_made_in" value="">
            </div>
            <div class="form-group col-md-6">
              <label for="title">Brand</label>
              <select name="brand" class="form-control" id="update_brand">
                <option value=""></option>
                @foreach($brands as $brand)
                  <option value="{{$brand->id}}">{{$brand->name}}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group col-md-6">
              <label for="title">Category</label>
              <select name="category" class="form-control" id="update_category">
                <option value=""></option>
                @foreach($categories as $cat)
                  <option value="{{$cat->id}}">{{$cat->title}}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group col-md-6">
              <label for="title">Supplier</label>
              <input name="supplier" type="text" class="form-control" id="update_supplier">
            </div>
            <div class="form-group col-md-12">
              <label for="title">Supplier Link</label>
              <input name="supplier_link" type="text" class="form-control" id="update_supplier_link">
            </div>
            <div class="form-group col-md-12">
              <label for="title">Product Link</label>
              <input name="product_link" type="text" class="form-control" id="update_product_link">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary pull-left">Update Product</button>
          </div>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>

  <div id="show-error-count" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Error with count</h4>
        </div>
          <div class="modal-body">
            
            
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
          </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>

@endsection

@section('scripts')
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script>
        $("#select_date").datepicker({
	  	format: 'yyyy-mm-dd'
	});
    </script>
  <script type="text/javascript">
  var product = []
  if (localStorage.getItem("luxury-product-data-asin") !== null) {
     var data = JSON.parse(localStorage.getItem('luxury-product-data-asin'));
     product = data
     $.each(product,function(i,e){
      console.log(e)
      $(".selectProductCheckbox_class[value='"+e.sku+"']").attr("checked",true);
     });
  }

  $('#magento_list_tbl_895 tbody').on('click', 'tr', function () {
    var ref = $(this);
    ref.find('td:eq(15)').children('.selectProductCheckbox_class').change(function(){
      if($(this).is(':checked')){
        var val = $(this).val()
        //if(!product.includes($(this).val())){  
          if(product.length > 0){
            $.each(product,function(i,e){
            if(e.sku == val){
              product.splice(i,1)
            }
          })
          }
          
          var item = {"sku":$(this).val(),"websiteid":$(this).attr("websiteid")}
            product.push(item)
            
        //  }
          localStorage.setItem('luxury-product-data-asin', JSON.stringify(product));
      } else {
        //var index = product.indexOf($(this).val())
        var val = $(this).val()
        $.each(product,function(i,e){
          if(e.sku == val){
            product.splice(i,1)
          }
        })
        // if(index !=-1){
        //   product.splice(index,1)
        // }
        localStorage.setItem('luxury-product-data-asin', JSON.stringify(product));
      }
    })
    console.log(product)
  })
  $(document).on("click", ".show_error_logs", function() {
    var id = $(this).data('id');
    var store_website_id = $(this).data('website');
    $.ajax({
      method: "GET",
      url: "/logging/show-error-log-by-id/" + id,
      data: {
        "_token": "{{ csrf_token() }}"
      },
      dataType: 'html'
    })
    .done(function(result) {
      $('#ErrorLogModal').modal('show');
      $('.error-log-data').html(result);
    });

  });
  $(document).on("click", ".update_modal", function() {
    var data = $(this).data('id');

    var detail = $(this).data('id');
    //alert(JSON.stringify(detail));
    $("#single_product_image").attr("src", detail['image_url']);
    $("#update_product_id").val(detail['product_id']);
    $("#update_name").val(detail['name']);
    $("#update_short_description").val(detail['short_description']);
    $("#update_size").val(detail['size']);
    $("#update_price").val(detail['price']);
    $("#update_price_eur_special").val(detail['price_eur_special']);
    $("#update_price_eur_discounted").val(detail['price_eur_discounted']);

    $("#update_price_inr").val(detail['price_inr']);
    $("#update_price_inr_special").val(detail['price_inr_special']);
    $("#update_price_inr_discounted").val(detail['price_inr_discounted']);

    $("#update_measurement_size_type").val(detail['measurement_size_type']);
    $("#update_lmeasurement").val(detail['lmeasurement']);
    $("#update_hmeasurement").val(detail['hmeasurement']);
    $("#update_dmeasurement").val(detail['dmeasurement']);

    $("#update_composition").val(detail['composition']);
    $("#update_made_in").val(detail['made_in']);
    $("#update_brand").val(detail['brand']);
    $("#update_category").val(detail['category']);
    $("#update_supplier").val(detail['supplier']);
    $("#update_supplier_link").val(detail['supplier_link']);
    $("#update_product_link").val(detail['product_link']);
  });

  $(document).on('click', '.expand-row-msg', function () {
    var name = $(this).data('name');
    var id = $(this).data('id');
    var full = '.expand-row-msg .show-short-'+name+'-'+id;
    var mini ='.expand-row-msg .show-full-'+name+'-'+id;
    $(full).toggleClass('hidden');
    $(mini).toggleClass('hidden');
  });

  function changeMagentoStatus(logId, newStatus) {
    if (!newStatus) {
      return;
    }
    $.ajax({
      method: "POST",
      url: "/logging/list-magento/" + logId,
      data: {
        "_token": "{{ csrf_token() }}",
        status: newStatus
      }
    })
    .done(function(msg) {
      console.log("Data Saved: ", msg);
    });
  }

  $(document).on("click","#submit-show-report",function(e){
    e.preventDefault();
    $.ajax({
      method: "GET",
      url: "/logging/list-magento/error-reporting"
    })
    .done(function(response) {
        $("#show-error-count").find(".modal-body").html(response);
        $("#show-error-count").modal("show");
    });
  });

</script>
@if (Session::has('errors'))
  <script>
  toastr["error"]("{{ $errors->first() }}", "Message")
</script>
@endif
@if (Session::has('success'))
  <script>
  toastr["success"]("{{Session::get('success')}}", "Message")
</script>
@endif

@endsection
