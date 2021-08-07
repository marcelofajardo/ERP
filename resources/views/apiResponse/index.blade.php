@extends('layouts.app')

@section('link-css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.jqueryui.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/scroller/2.0.1/css/scroller.jqueryui.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
<style>



/* */


.panel-default>.panel-heading {
  color: #333;
  background-color: #fff;
  border-color: #e4e5e7;
  padding: 0;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
}

.panel-default>.panel-heading a {
  display: block;
  padding: 10px 15px;
}

.panel-default>.panel-heading a:after {
  content: "";
  position: relative;
  top: 1px;
  display: inline-block;
  font-family: 'Glyphicons Halflings';
  font-style: normal;
  font-weight: 400;
  line-height: 1;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  float: right;
  transition: transform .25s linear;
  -webkit-transition: -webkit-transform .25s linear;
}

.panel-default>.panel-heading a[aria-expanded="true"] {
  background-color: #eee;
}

.panel-default>.panel-heading a[aria-expanded="true"]:after {
  content: "\2212";
  -webkit-transform: rotate(180deg);
  transform: rotate(180deg);
}

.panel-default>.panel-heading a[aria-expanded="false"]:after {
  content: "\002b";
  -webkit-transform: rotate(90deg);
  transform: rotate(90deg);
}
.full-rep {
    padding-bottom: 15px;
    width: 100%;
    display: inline-block;
}

form label.required:after{
    color: red;
    content: ' *';
}

/*PRELOADING------------ */
#overlayer {
  width:100%;
  height:100%;  
  position:absolute;
  z-index:1;
  background:#4a4a4a33;
}
.loader {
  display: inline-block;
  width: 30px;
  height: 30px;
  position: absolute;
  z-index:3;
  border: 4px solid #Fff;
  top: 50%;
  animation: loader 2s infinite ease;
  margin-left : 50%;
}

.loader-inner {
  vertical-align: top;
  display: inline-block;
  width: 100%;
  background-color: #fff;
  animation: loader-inner 2s infinite ease-in;
}

@keyframes loader {
  0% {
    transform: rotate(0deg);
  }
  
  25% {
    transform: rotate(180deg);
  }
  
  50% {
    transform: rotate(180deg);
  }
  
  75% {
    transform: rotate(360deg);
  }
  
  100% {
    transform: rotate(360deg);
  }
}

@keyframes loader-inner {
  0% {
    height: 0%;
  }
  
  25% {
    height: 0%;
  }
  
  50% {
    height: 100%;
  }
  
  75% {
    height: 100%;
  }
  
  100% {
    height: 0%;
  }
}

</style>
@endsection
@section('content')

<!-- <div id="overlayer"></div>
<span class="loader">
  <span class="loader-inner"></span>
</span> -->

<div class="row">
    <div class="col-lg-12 margin-tb">
        <h2 class="page-heading">Api Response Management</h2>
    </div>
</div>

<!-- Hidden content used to generate dynamic elements (start) -->
<div id="response-alert" style="display:none;" class="alert alert-success">
    <span>You should check in on some of those fields below.</span>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>


<!-- Hidden content used to generate dynamic elements (end) -->


<div id="response-alert-container"></div>

<div style="text-align: right; margin-bottom: 10px;">
    <button type="button" class="btn btn-primary" onclick="openAddModal();">
        Add Response Message
    </button>
</div>



<!-- COUPON DETAIL MODAL -->
<div class="modal fade" id="responseModal" tabindex="-1" role="dialog" aria-labelledby="responseModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <!-- <form id="coupon-form" method="POST" onsubmit="return executeCouponOperation();"> -->
        <form id="response-form" method="POST" action="{{ route('api-response-message.store') }}">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="responseModalLabel">New Api Response</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @csrf

                        <div class="form-group row">
                            <label for="start" class="col-sm-3 col-form-label required">Store Website</label>
                            <div class="col-sm-8">
                                <select class="form-control select select2 required" name="store_website_id"  >
                                    <option value="">Please select</option>
                                    @foreach($store_websites as $web)
                                        <option value="{{ $web->id }}">{{ $web->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="code" class="col-sm-3 col-form-label required">Key</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control required" name="res_key" placeholder="Key" value="" id="key" />
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="code" class="col-sm-3 col-form-label required">Value</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control required" name="res_value" placeholder="Value" value="" id="message" />
                            </div>
                        </div>

                      
                    </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary save-button">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Edit MODAL -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <!-- <form id="coupon-form" method="POST" onsubmit="return executeCouponOperation();"> -->
        <form id="edit-form" method="POST" >
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Api Response</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body edit-body">
                    @csrf

                        

                      
                    </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary update-button">Update</button>
                </div>
            </div>
        </form>
    </div>
</div>


@if(Session::has('message'))
<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
@endif


<div class="row">
    <div class="table-responsive">
        <table class="table table-striped table-bordered" style="width: 99%" id="api_response_table">
            <thead>
                <tr>
                    <th width="15%">ID</th>
                    <th width="20%">Store Website</th>
                    <th>Key</th>
                    <th>Value</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @php $i = 1; @endphp
                @foreach($api_response as $res)
                    <tr>
                        <td>{{ $i }}</td>
                        <td>{{ isset($res->storeWebsite->title) ? $res->storeWebsite->title : '' }}</td>
                        <td>{{ $res->key }}</td>
                        <td>{{ $res->value }}</td>
                        <td>
                            <a class="btn btn-warning" onclick="editModal({{ $res->id}});" href="javascript:void(0);">Edit</a>
                            <a class="btn btn-danger" href="{{ route('api-response-message.responseDelete',['id' => $res->id]) }}">Delete</a>
                        </td>
                    </tr>
                    @php $i = $i+1; @endphp
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script>
<script type="text/javascript">
    /* beautify preserve:end */
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#api_response_table').dataTable();
        
    });


    $('#response-form').validate();
    function openAddModal(){
        $('#responseModal').modal('show');
    }
    
    function editModal(id){
        $.ajax({
            url : "{{ route('getEditModal') }}",
            data : {
                id : id
            },
            type : "POST",
            success : function(response){
                if(response.type == "success"){
                    $('.edit-body').html('');
                    $('.edit-body').append(response.data);
                    $('#editModal').modal('show');
                }
            },
            error : function(response){

            }
        });
        $('#editModal').show();
    }

    $('.update-button').on('click',function(){
        if($(document).find('#edit-form').valid()){
            $.ajax({
                url : "{{ route('api-response-message.updateResponse') }}",
                data : {
                    id : $(document).find('#id').val(),
                    key : $(document).find('#edit_key').val(),
                    value : $(document).find('#edit_value').val(),
                    store_website_id : $(document).find('#edit_store_website_id').val(),
                },
                type : "POST",
                success : function(response){
                    location.reload();
                },
                error : function(response){

                }
            });
        }
    })
</script>
@endsection