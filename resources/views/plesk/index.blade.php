@extends('layouts.app')

@section('title', 'Plesk domains')

@section('styles')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css">
@endsection

@section('content')

    <div class="row">
        <div class="col-12">
            <h2 class="page-heading">Plesk domains</h2>
          </div>

          <div class="col-12 mb-3">
            <div class="pull-left">
            </div>
            <div class="pull-right">
                <!-- <a title="add new domain" class="btn btn-secondary add-new-btn">+</a> -->
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
            <th>Name</th>
            <th>Action</th>
          </tr>
        </thead>

        <tbody>
			  @foreach ($domains as $key => $domain)
            <tr>
            <td>{{$domain['name']}}</td>
            <td>
              <!-- <a class="btn btn-image view-btn" data-id="{{ $domain['id'] }}"><img src="/images/view.png" /></a> -->
              <a class="btn btn-image add-mail-btn" data-name="{{$domain['name']}}" data-id="{{ $domain['id'] }}">+</a>
              <a class="btn" href="/plesk/domains/mail/accounts/{{$domain['id']}}?name={{$domain['name']}}">Accounts</a>
            </td>
            </tr>
            @endforeach
        </tbody>
      </table>

	</div>
    </div>
    <div id="loading-image" style="position: fixed;left: 0px;top: 0px;width: 100%;height: 100%;z-index: 9999;background: url('/images/pre-loader.gif') 50% 50% no-repeat;display:none;">
   </div>
@endsection

<div id="addMail" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
      <!-- Modal content-->
      <div class="modal-content ">
        <div id="add-mail-content">
        
        </div>
      </div>
    </div>
</div>


<div id="view-domain" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
      <!-- Modal content-->
      <div class="modal-content ">
        <div id="view-domain-content">
        
        </div>
      </div>
    </div>
</div>

@section('scripts')
  <script type="text/javascript">

    $(document).on("click",".view-btn",function(e){
      $('#loading-image').show();
       e.preventDefault();
       var $this = $(this);
       $.ajax({
          url: "/plesk/domains/view/"+$(this).data("id"),
          type: "get"
        }).done(function(response) {
          $('#loading-image').hide();
          $('#view-domain').modal('show');
           $("#view-domain-content").html(response); 
        }).fail(function(errObj) {
           $("#view-domain").hide();
           $('#loading-image').hide();
        });
    });

    $(document).on("click",".add-mail-btn",function(e){
       e.preventDefault();
       var $this = $(this);
       $.ajax({
          url: "/plesk/domains/mail/create/"+$(this).data("id"),
          type: "get",
          data: {
            sitename : $(this).data("name")
          }
        }).done(function(response) {
          $('#loading-image').hide();
          $('#addMail').modal('show');
           $("#add-mail-content").html(response); 
        }).fail(function(errObj) {
          $('#loading-image').hide();
           $("#addMail").hide();
        });
    });


  </script>
@endsection
