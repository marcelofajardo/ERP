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
         #loading-image-model {
            position: fixed;
            top: 50%;
            left: 50%;
            margin: -50px 0px 0px -50px;
        }
        .selectgroup-item {
            -ms-flex-positive: 1;
            flex-grow: 1;
            position: relative;
        }
        .selectgroup-button {
            display: block;
            border: 1px solid rgba(0,40,100,.12);
            text-align: center;
            padding: .375rem 1rem;
            position: relative;
            cursor: pointer;
            border-radius: 3px;
            color: #9aa0ac;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            font-size: .9375rem;
            line-height: 1.5rem;
            min-width: 2.375rem;
        }
    </style>
@endsection


@section('content')
    <div id="myDiv">
       <img id="loading-image" src="/images/pre-loader.gif" style="display:none;"/>
   </div>
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <h2 class="page-heading" style="">@if($type) {{ ucfirst($type) }} @endif Accounts (<span id="count">{{ $accounts->total() }}</span>)</h2>
            <div class="pull-left">
                <form action="{{ route('accounts.index') }}" method="GET" class="form-inline align-items-start">
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
              <select class="form-control global" id="platform">
                  <option val="">Select Platform</option>  
                  @foreach($platforms as $platform) 
                    <option value="{{ $platform->name }}" @if($platform->name == $type) selected @endif>{{ $platform->name }}</option>
                  @endforeach
              </select>  
              <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#instagramConfigCreateModal">+</a>
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
            <th style="width: 3% !important;">Website</th>
            <th style="width: 3% !important;">Pass</th>
            <th style="width: 3% !important;">No.</th>
            <th style="width: 3% !important;">Email.</th>
            <th style="width: 3% !important;">Platform.</th>
            <th style="width: 3% !important;">Provider</th>
            <th style="width: 3% !important;">Freq</th>
            <th style="width: 3% !important;">Cust Support</th>
            <th style="width: 12% !important;">Actions</th>
          </tr>

          <!-- <tr>
            <th style="width: 3% !important;"><input type="text" id="username" class="search form-control"></th>
            <th style="width: 3% !important;"></th>
            <th style="width: 3% !important;"><input type="text" id="number" class="search form-control"></th>
            <th style="width: 3% !important;"></th>
            <th style="width: 3% !important;"></th>
            <th style="width: 3% !important;"><input type="text" id="provider" class="search form-control"></th>
            <th style="width: 3% !important;"></th>
            <th style="width: 3% !important;"><select class="form-control search" id="customer_support">
                    <option value="">Select Option</option>
                    <option value="1">Provide Support</option>
                    <option value="0">Does Not Provide Support</option>
                </select></th>
            <th style="width: 3% !important;"></th> 
            <th style="width: 3% !important;"></th> 
            <th style="width: 22% !important;"></th>   
            </th>
          </tr> -->
        </thead>

        <tbody>

       @include('marketing.accounts.partials.data') 

          {!! $accounts->render() !!}
          
        </tbody>
      </table>
    </div>

@include('marketing.accounts.partials.add-modal')
@include('marketing.accounts.partials.multiple-image')
@include('marketing.accounts.partials.account-history')

   
@endsection


@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
      <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>

        $(document).ready(function() {
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


    function editAccount(config_id) {
        $("#accountEditModal"+ config_id +"" ).modal('show');
    }
    
    function deleteConfig(config_id) {
        event.preventDefault();
        if (confirm("Are you sure?")) {
             $.ajax({
            type: "POST",
            url: "{{ route('instagram.config.delete') }}",
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

    //     $(document).ready(function() {
    //     src = "{{ route('accounts.index') }}";
    //     $(".search").autocomplete({
    //     source: function(request, response) {
    //         number = $('#number').val();
    //         username = $('#username').val();
    //         provider = $('#provider').val();
    //         customer_support = $('#customer_support').val();
          

    //         $.ajax({
    //             url: src,
    //             dataType: "json",
    //             data: {
    //                 number : number,
    //                 username : username,
    //                 provider : provider,
    //                 customer_support : customer_support,
                
    //             },
    //             beforeSend: function() {
    //                    $("#loading-image").show();
    //             },
            
    //         }).done(function (data) {
    //              $("#loading-image").hide();
    //             console.log(data);
    //             $("#passwords-table tbody").empty().html(data.tbody);
    //             if (data.links.length > 10) {
    //                 $('ul.pagination').replaceWith(data.links);
    //             } else {
    //                 $('ul.pagination').replaceWith('<ul class="pagination"></ul>');
    //             }
                
    //         }).fail(function (jqXHR, ajaxOptions, thrownError) {
    //             alert('No response from server');
    //         });
    //     },
    //     minLength: 1,
       
    //     });
    // });


         $(document).ready(function() {
        src = "{{ route('accounts.index') }}";
        $(".global").autocomplete({
        source: function(request, response) {
            term = $('#term').val();
            date = $('#date').val();
            platform = $('#platform').val();
            
          

            $.ajax({
                url: src,
                dataType: "json",
                data: {
                    term : term,
                    date : date,
                    platform : platform,
                
                },
                beforeSend: function() {
                       $("#loading-image").show();
                },
            
            }).done(function (data) {
                if(platform){
                    $('.platform').val(platform)   
                }
                 $("#loading-image").hide();
                 $('#count').text(data.count)
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

    function addBroadcast(id) {
        $('#account_id').val(id)
        $('#addModal').modal('show');         
    } 

    $(document).on("click",".account-history",function(e) {
        e.preventDefault();
            var account_id = $(this).data("id");
            $.ajax({
                url: '/instagram/history',
                type: 'POST',
                data : { "_token": "{{ csrf_token() }}", account_id : account_id },
                dataType: 'json',
                beforeSend: function () {
                  $("#loading-image").show();
                },
                success: function(result){
                    $("#loading-image").hide();

                    if(result.code == 200) {
                       var t = '';
                       $.each(result.data,function(k,v) {
                          t += `<tr><td>`+v.account_id+`</td>`;
                          t += `<td>`+v.log_title+`</td>`;
                          t += `<td>`+v.log_description+`</td>`;
                          t += `<td>`+v.created_at+`</td>`;
                          t += `<td>`+v.updated_at+`</td></tr>`;
                       });
                    }
                    $("#category-history-modal").find(".show-list-records").html(t);
                    $("#category-history-modal").modal("show");
                },
                error: function (){
                    $("#loading-image").hide();
                }
            });
       });

    function openModel(id) {
        $('#account_id').val(id);
        $('#largeImageModal').modal('show');         
    }

    $('#get-images').click(function (e) { 
        e.preventDefault();
        
        if(!$('input[name="image_text"]').val()){
            $('input[name="image_text"]').focus();
            return false;
        }
        if(!$('input[name="type"]').val()){
            $('input[name="type"]').focus();
            return false;
        }

        console.log( $('input[name="type"]:checked').val(), $('input[name="image_text"]').val() );
        postImage($('#account_id').val(), $('input[name="type"]:checked').val(), $('input[name="image_text"]').val());
        
    });
    function postImage(id, type, text) {
        src = '/instagram/post/getImages'
        $.ajax({
                url: src,
                data: { type: type, keyword: text },
                dataType: "json",
                beforeSend: function() {
                       $("#loading-image-model").show();
                },
            }).done(function (data) {
                if(data != null){

                    html = ''
                    html += '<div class="row">';
                        for(image of data){
                            html += '<div class="col-md-3"><img src="'+image+'" height="200" width="200" class="img-responsive"><br><input type="checkbox" style="margin-bottom: 10px;" class="image-select" value="'+image+'" ></div>';
                        }
                    html += '</div>'
                    $('#images').empty().append(html);
                    $('#account_id').val(id)
                }else{
                    alert('No data found');
                }
                $("#loading-image-model").hide();
            }).fail(function (jqXHR, ajaxOptions, thrownError) {
                $("#loading-image-model").hide();
                alert('No data found');
            });
        } 


    function getCaptions() {
        var selected = [];
        html = ''
        $('.modal-body input:checked').each(function() {
            selected.push($(this).val());
        });

        if(selected.length == 0){
            alert('Please select image');
            return false;
        }else{
            $.ajax({
            url: '/instagram/post/getCaptions',
            dataType: "json",
            beforeSend: function() {
                   $("#loading-image").show();
            },
            }).done(function (data) {
                html = ''
                select = ''
                select += '<select class="form-control selected-caption">' 
                for(image of data){
                    select += '<option value="'+image['id']+'">'+image['caption']+'</option>';
                }
                select += '</select>'

                html += '<div class="row">';
                for(image of selected){
                    html += '<div class="col-md-4"><img src="'+image+'" height="300" width="300" style="margin-bottom;10px;" class="img-responsive">'+select+'</div>';
                }
                html += '</div>'
                $('#images').empty().append(html);
                $('#next_button').hide();
                $('#submit_button').show();
                
            }).fail(function (jqXHR, ajaxOptions, thrownError) {
                alert('No response from server');
            });
                
        }
   }

    function submitPost() {
        imageURI = []
        captions = []
        $(".img-responsive").each(function () { 
            imageURI.push($(this).attr('src'));                 
             
        });
        $(".selected-caption").each(function () {
            captions.push($(this).val() );                  
        });

        account_id = $('#account_id').val();

        $.ajax({
        url: '/instagram/post/multiple',
        dataType: "json",
        type: "POST",
        data: {
            "_token": "{{ csrf_token() }}", 
            "account_id": account_id,
            "captions" : captions,
            "imageURI" : imageURI,
        },
        beforeSend: function() {
               $("#loading-image").show();
        },
        }).done(function (data) {
            $("#loading-image").hide();
            $('#largeImageModal').modal('hide');
        }).fail(function (jqXHR, ajaxOptions, thrownError) {
            alert('No response from server');
        });

    }

    
    function likeUserPost(id) {
    src = '/instagram/post/likeUserPost'
    $.ajax({
            url: src,
            dataType: "json",
            type: "POST",
            data: {
                "_token": "{{ csrf_token() }}",
                "account_id": id,
            },
            beforeSend: function() {
                   $("#loading-image").show();
            },
        }).done(function (data) {
            $("#loading-image").hide();
            alert('Liked User Post Successfully')
        }).fail(function (jqXHR, ajaxOptions, thrownError) {
            alert('No response from server');
        });
    }

    function sendRequest(id) {
    src = '/instagram/post/sendRequest'
    $.ajax({
            url: src,
            dataType: "json",
            type: "POST",
            data: {
                "_token": "{{ csrf_token() }}",
                "account_id": id,
            },
            beforeSend: function() {
                   $("#loading-image").show();
            },
        }).done(function (data) {
            $("#loading-image").hide();
            alert('Liked User Post Successfully')
        }).fail(function (jqXHR, ajaxOptions, thrownError) {
            alert('No response from server');
        });
    } 

    function acceptRequest(id) {
    src = '/instagram/post/acceptRequest'
    $.ajax({
            url: src,
            dataType: "json",
            type: "POST",
            data: {
                "_token": "{{ csrf_token() }}",
                "account_id": id,
            },
            beforeSend: function() {
                   $("#loading-image").show();
            },
        }).done(function (data) {
            $("#loading-image").hide();
            alert('All request accepted Successfully')
        }).fail(function (jqXHR, ajaxOptions, thrownError) {
            alert('No response from server');
        });
    }         

</script>
@endsection