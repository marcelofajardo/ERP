@extends('layouts.app')

@section('title', 'Store status list')

@section('styles')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css">
@endsection

@section('content')

    <div class="row">
        <div class="col-12">
            <h2 class="page-heading">Orders Status</h2>
          </div>

          <div class="col-12 mb-3">
            <div class="pull-left">
            <form class="form-inline" action="{{ route('store-website.all.status') }}" method="GET">


                  <div class="form-group ml-4">
                    <select class="form-control select2" name="order_status_id">
                      <option value="">Select ERP Status</option>

                      @foreach ($order_statuses as $id => $status)
                        <option value="{{ $status->id }}" {{ $status->id == $erp_status ? 'selected' : '' }}>{{ $status->status }}</option>
                      @endforeach
                    </select>
                  </div>

                  <div class="form-group ml-4">
                    <select class="form-control select2" name="store_website_id">
                      <option value="">Select a store</option>

                      @foreach ($store_website as $id => $website)
                        <option value="{{ $website->id }}" {{ $website->id == $store ? 'selected' : '' }}>{{$website->title}} ({{$website->website_source}})</option>
                      @endforeach
                    </select>
                  </div>

                  <button type="submit" class="btn btn-image ml-3"><img src="/images/filter.png" /></button>
                </form>
            </div>
            <div class="pull-right">
                <a class="btn btn-secondary fetch-store-status">Fetch Store Status</a>
                <a class="btn btn-secondary add-new-btn">+</a>
            </div>
        </div>
    </div>
    @include('partials.flash_messages')
    
	</br> 
    <div class="infinite-scroll">
	<div class="table-responsive mt-2">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>ERP status</th>
            <th>Website</th>
            <th>Website status</th>
            <th>Action</th>
          </tr>
        </thead>

        <tbody>
			  @foreach ($store_order_statuses as $key => $status)
            <tr>
            <td>{{$status->order_status->status}}</td>
            <td>{{$status->store_website->title}} ({{$status->store_website->website_source}})</td>
            <td>{{($status->store_master_status) ? $status->store_master_status->label : "N/A"}}</td>
            <td>
              <a class="btn btn-image edit-btn" data-id="{{ $status->id }}"><img src="/images/edit.png" /></a>
            </td>
            </tr>
          @endforeach
        </tbody>
      </table>

	{!! $store_order_statuses->appends(Request::except('page'))->links() !!}
	</div>
    </div>
    <div id="loading-image" style="position: fixed;left: 0px;top: 0px;width: 100%;height: 100%;z-index: 9999;background: url('/images/pre-loader.gif') 50% 50% no-repeat;display:none;">
   </div>
@endsection

<div id="addNew" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
      <!-- Modal content-->
      <div class="modal-content ">
        <div id="add-new-content">
        
        </div>
      </div>
    </div>
</div>

<div id="fetchStoreStatus" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
      <!-- Modal content-->
      <div class="modal-content ">
        <div id="fetch-store-status-content">
        
        </div>
      </div>
    </div>
</div>


<div id="edit-status" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
      <!-- Modal content-->
      <div class="modal-content ">
        <div id="edit-new-content">
        
        </div>
      </div>
    </div>
</div>

@section('scripts')
  <script type="text/javascript">


$('select.select2').select2({
    width: "100%"
});

    $(document).on("click",".edit-btn",function(e){
       e.preventDefault();
       var $this = $(this);
       $.ajax({
          url: "/store-website/status/edit/"+$(this).data("id"),
          type: "get"
        }).done(function(response) {
          $('select.select2').select2({
              width: "100%"
          });
          $('#edit-status').modal('show');
           $("#edit-new-content").html(response); 
        }).fail(function(errObj) {
           $("#edit-status").hide();
        });
    });

    $(document).on("click",".add-new-btn",function(e){
       e.preventDefault();
       var $this = $(this);
       $.ajax({
          url: "/store-website/status/create",
          type: "get"
        }).done(function(response) {
          $('select.select2').select2({
              width: "100%"
          });
          $('#addNew').modal('show');
           $("#add-new-content").html(response); 
        }).fail(function(errObj) {
           $("#addNew").hide();
        });
    });

    $(document).on("click",".fetch-store-status",function(e){
       e.preventDefault();
       var $this = $(this);
       $.ajax({
          url: "/store-website/status/fetch",
          type: "get"
        }).done(function(response) {
          $('#fetchStoreStatus').modal('show');
           $("#fetch-store-status-content").html(response); 
        }).fail(function(errObj) {
           $("#fetchStoreStatus").hide();
        });
    });

    $(document).on("change",".store_website_id",function(e){
      console.log("changed");
       e.preventDefault();
       var id = $(this).val();
       $.ajax({
          url: "/store-website/status/fetchMasterStatus/"+id,
          type: "get"
        }).done(function(response) {
          var option = '';
          for(var i=0;i<response.length;i++) {
            option = option + '<option value="'+response[i].id+'">'+response[i].label+'</option>';
          }
          console.log(option);
          if(option != '') {
            $("#store_master_status_id").html(option);
          }
        }).fail(function(errObj) {
           $("#fetchStoreStatus").hide();
        });
    });

    


  </script>
@endsection
