@extends('layouts.app')

@section('styles')
    
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
    <style type="text/css">
         #loading-image {
            position: fixed;
            top: 50%;
            left: 50%;
            margin: -50px 0px 0px -50px;
        }
    </style>
@endsection


@section('content')
    <div id="myDiv">
       <img id="loading-image" src="/images/pre-loader.gif" style="display:none;"/>
   </div>
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <h2 class="page-heading">WhatsApp Configs</h2>
            <div class="pull-left">
                <form action="{{ route('whatsapp.config.index') }}" method="GET" class="form-inline align-items-start">
                    <div class="form-group mr-3 mb-3">
                        <input name="term" type="text" class="form-control global" id="term"
                               value="{{ isset($term) ? $term : '' }}"
                               placeholder="number , provider, username">
                    </div>
                    <div class="form-group ml-3">
                        <div class='input-group date' id='filter-date'>
                            <input type='text' class="form-control global" name="date" value="{{ isset($date) ? $date : '' }}" placeholder="Date" id="date" />

                            <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                  </span>
                        </div>
                    </div>


                    <button type="submit" class="btn btn-image"><img src="/images/filter.png" /></button>
                </form>
            </div>
            <div class="pull-right">
              <button type="button" class="btn btn-secondary" onclick="removeBlocked()" style="margin-right: 5px;">Remove 30 Cust From Block No.</a>  
              <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#whatsAppConfigCreateModal">+</a>
            </div>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="table-responsive mt-3">
      <table class="table table-bordered" id="passwords-table">
        <thead>
          <tr>
            <!-- <th style="width: 3% !important;">ID</th> -->
            <th style="width: 3% !important;">User</th>
            <th style="width: 3% !important;">Pass</th>
            <th style="width: 3% !important;">No.</th>
            <th style="width: 3% !important;">Provider</th>
            <th style="width: 3% !important;">Freq</th>
            <th style="width: 3% !important;">Cust Support</th>
            <th style="width: 3% !important;">Start Time</th>
            <th style="width: 3% !important;">End Time</th>
            <th style="width: 1% !important;">Device</th>
           <!--  <th style="width: 3% !important;">Sim No.</th>
            <th style="width: 3% !important;">Sim Owner.</th>
            <th style="width: 3% !important;">Pay</th>
            <th style="width: 14% !important;">Rech</th> -->
            <th style="width: 1% !important;">Sts</th>
            <th style="width: 5% !important;">Started At</th>
            <th style="width: 1% !important;">Instance Id</th>
            <th style="width: 20% !important;">Actions</th>
          </tr>

          <tr>
            <!-- <th style="width: 3% !important;"></th> -->
            <th style="width: 3% !important;"><input type="text" id="username" class="search form-control"></th>
            <th style="width: 3% !important;"></th>
            <th style="width: 3% !important;"><input type="text" id="number" class="search form-control"></th>
            <th style="width: 3% !important;"><input type="text" id="provider" class="search form-control"></th>
            <th style="width: 3% !important;"></th>
            <th style="width: 3% !important;"><select class="form-control search" id="customer_support">
                    <option value="">Select Option</option>
                    <option value="1">Provide Support</option>
                    <option value="0">Does Not Provide Support</option>
                </select></th>
            <th style="width: 3% !important;"></th> 
            <th style="width: 3% !important;"></th> 
            <th style="width: 3% !important;"></th> 
          <!--   <th style="width: 3% !important;"></th>
            <th style="width: 3% !important;"></th>
            <th style="width: 3% !important;"></th>
            <th style="width: 3% !important;"></th> -->
            <th style="width: 1% !important;"></th>
            <th style="width: 5% !important;"></th>
            <th style="width: 5% !important;"></th>   
            <th style="width: 12% !important;"></th>   
            </th>
          </tr>
        </thead>

        <tbody>

       @include('marketing.whatsapp-configs.partials.data') 

          {!! $whatsAppConfigs->render() !!}
          
        </tbody>
      </table>
    </div>

@include('marketing.whatsapp-configs.partials.add-modal')
@include("marketing.whatsapp-configs.partials.image")
   
@endsection


@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
      <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>

        $(document).ready(function() {
            $(".select-multiple-default_for").multiselect();
            
            $(".select-multiple").multiselect();
            $(".select-multiple2").select2();
        });

        $('#filter-date').datetimepicker({
            format: 'YYYY-MM-DD',
        });

        $('#filter-whats-date').datetimepicker({
            format: 'YYYY-MM-DD',
        });


        // $('.date').change(function(){
        //     alert('date selected');
        // });


    function changewhatsAppConfig(config_id) {
        $("#whatsAppConfigEditModal"+ config_id +"" ).modal('show');
    }
    
    function deleteConfig(config_id) {
        event.preventDefault();
        if (confirm("Are you sure?")) {
             $.ajax({
            type: "POST",
            url: "{{ route('whatsapp.config.delete') }}",
            data: {"_token": "{{ csrf_token() }}", "id": config_id},
            dataType: "json",
            success: function (message) {
               alert('Deleted Config');
               location.reload(true);
            }, error: function () {
               alert('Something went wrong');
            }

        });
        }
        return false;
       
    }

        $(document).ready(function() {
        src = "{{ route('whatsapp.config.index') }}";
        $(".search").autocomplete({
        source: function(request, response) {
            number = $('#number').val();
            username = $('#username').val();
            provider = $('#provider').val();
            customer_support = $('#customer_support').val();
          

            $.ajax({
                url: src,
                dataType: "json",
                data: {
                    number : number,
                    username : username,
                    provider : provider,
                    customer_support : customer_support,
                
                },
                beforeSend: function() {
                       $("#loading-image").show();
                },
            
            }).done(function (data) {
                 $("#loading-image").hide();
                console.log(data);
                $("#passwords-table tbody").empty().html(data.tbody);
                if (data.links.length > 10) {
                    $('ul.pagination').replaceWith(data.links);
                } else {
                    $('ul.pagination').replaceWith('<ul class="pagination"></ul>');
                }
                
            }).fail(function (jqXHR, ajaxOptions, thrownError) {
                alert('No response from server');
            });
        },
        minLength: 1,
       
        });
    });


         $(document).ready(function() {
        src = "{{ route('whatsapp.config.index') }}";
        $(".global").autocomplete({
        source: function(request, response) {
            term = $('#term').val();
            date = $('#date').val();
            
          

            $.ajax({
                url: src,
                dataType: "json",
                data: {
                    term : term,
                    date : date,
                
                },
                beforeSend: function() {
                       $("#loading-image").show();
                },
            
            }).done(function (data) {
                 $("#loading-image").hide();
                console.log(data);
                $("#passwords-table tbody").empty().html(data.tbody);
                if (data.links.length > 10) {
                    $('ul.pagination').replaceWith(data.links);
                } else {
                    $('ul.pagination').replaceWith('<ul class="pagination"></ul>');
                }
                
            }).fail(function (jqXHR, ajaxOptions, thrownError) {
                alert('No response from server');
            });
        },
        minLength: 1,
       
        });
    });

   function getBarcode(id){
        $.ajax({
        url: '/marketing/whatsapp-config/get-barcode',
        type: 'GET',
        dataType: 'json',
        data: {id: id},
        }).done(function (data) {
            $("#loading-image").hide();
            if(data.success){
                $('#image_crop').attr('src',data.media);
                $('#largeImageModal').modal('show');
            }else if(data.error){
                alert('Check if Barcode Server Is Running');
            }else if(data.nobarcode){
                alert('No Barcode Is Present , Device Is Connected Please Check Internet Connection')
            }
            
        }).fail(function (jqXHR, ajaxOptions, thrownError) {
            alert('No response from server');
        });
   }

   function getScreen(id) {
        $.ajax({
        url: '/marketing/whatsapp-config/get-screen',
        type: 'GET',
        dataType: 'json',
        data: {id: id},
        }).done(function (data) {
            $("#loading-image").hide();
            if(data.success){
                $('#image_crop').attr('src',data.media);
                $('#largeImageModal').modal('show');
            }else if(data.error){
                alert('Check if Barcode Server Is Running');
            }else if(data.nobarcode){
                alert('No Screen Is Present')
            }
            
        }).fail(function (jqXHR, ajaxOptions, thrownError) {
            alert('No response from server');
        });
    } 

    function deleteChrome(id) {
            var result = confirm("Want to delete Chrome Data?");
            if(result){
                $.ajax({
                url: '/marketing/whatsapp-config/delete-chrome',
                type: 'GET',
                dataType: 'json',
                data: {id: id},
                }).done(function (data) {
                    $("#loading-image").hide();
                    if(data.success){
                        $('#image_crop').attr('src',data.media);
                        $('#largeImageModal').modal('show');
                    }else if(data.error){
                        alert('Check if Barcode Server Is Running');
                    }else if(data.nobarcode){
                        alert('No Screen Is Present')
                    }
                    
                }).fail(function (jqXHR, ajaxOptions, thrownError) {
                    alert('No response from server');
                });
            }
            
        }

    function restartScript(id) {
          var result = confirm("Want to restart Script?");
          if (result) {
                $.ajax({
                url: '/marketing/whatsapp-config/restart-script',
                type: 'GET',
                dataType: 'json',
                data: {id: id},
                }).done(function (data) {
                    $("#loading-image").hide();
                    if(data.success){
                        alert('No Process Found');
                    }else if(data.error){
                        alert('Check if Server Is Running');
                    }else if(data.nobarcode){
                        alert('Device restart please checkback in 5-10 mins')
                    }
                    
                }).fail(function (jqXHR, ajaxOptions, thrownError) {
                    alert('No response from server');
                });
            
            }      
    }

    function logoutScript(id) {
          var result = confirm("Want to Logout Script?");
          if (result) {
                $.ajax({
                url: '/marketing/whatsapp-config/logout-script',
                type: 'GET',
                dataType: 'json',
                data: {id: id},
                }).done(function (data) {
                    $("#loading-image").hide();
                    if(data.success){
                        alert(data.message);
                    }else if(data.error){
                        alert('Check if Server Is Running');
                    }
                }).fail(function (jqXHR, ajaxOptions, thrownError) {
                    alert('No response from server');
                });
            }      
    }

    function getInfo(id) {
          $.ajax({
            url: '/marketing/whatsapp-config/get-status-info',
            type: 'GET',
            dataType: 'json',
            data: {id: id},
            }).done(function (data) {
                $("#loading-image").hide();
                if(data.success){
                    alert(data.message);
                }else if(data.error){
                    alert('Check if Server Is Running');
                }
            }).fail(function (jqXHR, ajaxOptions, thrownError) {
                alert('No response from server');
            });      
    }

    

    function removeBlocked() {
        var result = confirm("Want to remove WhatsApp Blocked?");
          if (result) {
                $.ajax({
                url: '/marketing/whatsapp-config/blocked-number',
                type: 'GET',
                dataType: 'json',
                data: {},
                }).done(function (data) {
                    $("#loading-image").hide();
                    if(data.success){
                        alert('Blocked Number Disabled');
                    }
                    
                }).fail(function (jqXHR, ajaxOptions, thrownError) {
                    alert('No response from server');
                });
          }       
    }        
</script>
@endsection