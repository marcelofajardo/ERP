@extends('layouts.app')

@section('content')

@section('styles')
    <!-- START - Purpose : Add CSS - DEVTASK-4289 -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style type="text/css">
        table tr td {
            overflow-wrap: break-word;
        }

        .page-note {
            font-size: 14px;
        }

        .flex {
            display: flex;
        }

    </style>
    <!-- END - DEVTASK-4289 -->
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.18/css/dataTables.bootstrap4.min.css">
@section('content')

    <div class="row">
        <div class="col-md-12">
            <h2 class="page-heading"> ListingApproved - SOP</h2>
        </div>
        <div class="col-lg-12 margin-tb" >

            <div class="pull-left">
                <div class="form-group" style="margin-bottom: 0px;">
                    <div class="row">
                        <form method="get" action="{{ route('sop.index') }}">
                            <div class="flex">
                                <div class="col" id="search-bar">

                                    <input type="text" value="{{ old('search') }}" name="search" class="form-control"
                                        placeholder="Search Here..">
                                    {{-- <input type="text" name="search" id="search" class="form-control search-input" placeholder="Search Here Text.." autocomplete="off"> --}}
                                </div>

                                <button type="submit" style="display: inline-block;width: 10%"
                                    class="btn btn-sm btn-image search-button">
                                    <img src="/images/search.png" style="cursor: default;">
                                </button>

                                <a href="{{ route('sop.add') }}" type="button" class="btn btn-image" id=""><img
                                        src="/images/resend2.png"></a>

                            </div>
                        </form>
                    </div>

                </div>
            </div>

            <div class="pull-right">
                <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#exampleModal">+</button>
            </div>
        </div>
    </div>

    <!-- Button trigger modal -->

    <!--------------------------------------------------- Add Data Modal ------------------------------------------------------->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Name</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="FormModal">
                        @csrf
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name" name="name" required />
                        </div>

                        <div class="form-group">
                            <label for="content">Content</label>
                            <input type="text" class="form-control" id="content" required />
                        </div>


                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary btnsave" id="btnsave">Submit</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
 <!--------------------------------------------------- end Add Data Modal ------------------------------------------------------->
    <div class="col-md-12">
        <div class="pagenote-scroll">
            <!-- Purpose : Add Div for Scrolling - DEVTASK-4289 -->
            <div class="table-responsive">
                <table cellspacing="0" role="grid"
                    class="page-notes table table-bordered datatable mdl-data-table dataTable page-notes" style="width:100%"
                    id="NameTable">
                    <thead>
                        <tr>
                            <th width="5%">ID</th>

                            <th width="20%">Name</th>
                            <th width="50%">Content</th>
                            <th width="10%">Created at</th>
                           
                            <th width="15%">Action</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($usersop as $key => $value)
                            <tr id="sid{{ $value->id }}" class="parent_tr" data-id="{{ $value->id }}">
                                <td class="sop_table_id">{{ $value->id }}</td>
                                <td class="sop_table_name">{{ $value->name }}</td>
                                <td>{!! $value->content !!}</td>

                                <td>{{ date('yy-m-d', strtotime($value->created_at)) }}</td>
                              
                                <td>
                                   
                                    <a href="javascript:;" data-id="{{ $value->id }}"
                                        class="editor_edit btn-xs btn btn-image p-2">
                                        <img src="/images/edit.png"></a>
                                    {{-- <a onclick="editname({{$value->id}})" class="btn btn-image"> <img src="/images/edit.png"></a> --}}

                                    <a class="btn btn-image deleteRecord" data-id="{{ $value->id }}"><img
                                            src="/images/delete.png" /></a>
                                        
                                            <a class="fa fa-info-circle view_log" style="font-size:18px; margin-left:15px;" title="status-log" data-name=
                                            "{{ $value->purchaseProductOrderLogs ? $value->purchaseProductOrderLogs->header_name : '' }}" data-id="{{ $value->id }}" data-toggle="modal" data-target="#ViewlogModal"></a>
                                  
                                </td>
                        @endforeach
                       
                    </tbody>
                </table>
{{                $usersop->appends(request()->input())->links()}}
            </div>

        </div>
    </div>

    {{-- ------------------ View Log ----------------------- --}}

    <div class="modal fade log_modal" id="ViewlogModal" tabindex="-1" role="dialog" aria-labelledby="log_modal" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Log Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive mt-2">
                    <table class="table table-bordered log-table" style="border: 1px solid #ddd !important; color:black;table-layout:fixed">
                        <thead>
                            <tr>
                                <th width="28%">From</th>
                                <th width="28%">To</th>
                                <th width="14%">Created By</th>
                                <th width="30%">Created At</th>
                                
                            </tr>
                        </thead>
                        
                        <tbody class="log_data" id="log_data">
                            
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
            
            </div>
            </div>
        </div>
    </div>

{{-- -------------------------- end view log --------------------------- --}}


{{-- --------------------------------------------- Update Data start----------------------------------------- --}}

    <div id="erp-notes" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form action="<?php echo route('updateName'); ?>" id="sop_edit_form">
                        <input type="text" hidden name="id" id="sop_edit_id">
                        @csrf
                        <div class="form-group">
                            <label for="name">Notes:</label>
                            <input type="hidden" class="form-control sop_old_name" name="sop_old_name" id="sop_old_name" value="">
                            <input type="text" class="form-control sopname" name="name" id="sop_edit_name">
                        </div>

                        <div class="form-group">
                            <label for="content">Content</label>
                            <textarea class="form-control sop_edit_class" name="content" id="sop_edit_content"></textarea>
                        </div>

                        <button type="submit" class="btn btn-success ml-3 updatesopnotes" >Update</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

{{-- -------------------------- end Update Data start-------------------------- --}}


   
@endsection

@section('scripts')
    <script src="https://cdn.ckeditor.com/4.11.4/standard/ckeditor.js"></script>
    <script>
        CKEDITOR.replace('content');
        CKEDITOR.replace('sop_edit_content');
    </script>

<script>
$(document).on("click",".view_log",function(e) {    
        
    var id = $(this).data('id');

    var purchase_order_products_id = $(this).data('data-id');
    var header_name = $(this).attr('data-name');
  
    $.ajax({
        type: "GET",
        url: "{{ route('sopname.logs') }}",
        data: {
            _token: "{{ csrf_token() }}",
            id: id,
            purchase_order_products_id: purchase_order_products_id,
            header_name : header_name,
        },
        dataType : "json",
        success: function (response) {

            var html_content = ''
            $.each( response.log_data, function( key, value ) {
                html_content += '<tr>';
                html_content += '<td>'+ (value.replace_from == null ? '-' : value.replace_from )+'</td>';
                html_content += '<td>'+ value.replace_to+'</td>';
                html_content += '<td>'+ value.name+'</td>';
                html_content += '<td>'+ value.log_created_at+'</td>';
                html_content += '</tr>';
            });

            $("#log_data").html(html_content);
            $('#log_modal').modal('show');
        },
        error: function () {
            toastr['error']('Message not sent successfully!');
        }
    });
});
</script>

    <script>
        $('#FormModal').submit(function(e) {
            e.preventDefault();
            let name = $("#name").val();
            let content = CKEDITOR.instances['content'].getData(); //$('#cke_content').html();//$("#content").val();

            let _token = $("input[name=_token]").val();


            $.ajax({
                url: "{{ route('sop.add') }}",
                type: "POST",
                data: {
                    name: name,
                    content: content,

                    _token: _token
                },
                success: function(response) {
                    console.log('response', response);
                    if (response) {
                      
                        $("#NameTable tbody").append('<tr class="parent_tr"><td>'+response.sop.id+'</td><td> '+response.sop.name+' </td><td> '+response.sop.content+' </td><td> '+response.only_date+' </td><td>'+
                                
                                '<a href="javascript:;" data-id = "'+response.sop.id+'" class="editor_edit btn-xs btn btn-image p-2">'+
                                            '<img src="/images/edit.png"></a>'+
                                           

                                        '<a class="btn btn-image deleteRecord" data-id="'+response.sop.id+'" ><img src="/images/delete.png" /></a>'+

                                        ' <a class="fa fa-info-circle view_log" style="font-size:18px; margin-left:15px;" title="status-log" data-id="'+response.sop.id+'" data-toggle="modal" data-target="#ViewlogModal" data-name="'+response.params.header_name+'"></a>'+
                                
                                
                                '</td></tr>');

                      
                        $("#FormModal")[0].reset();
                        $('.cke_editable p').text(' ')
                        CKEDITOR.instances['content'].setData('')
                        $("#exampleModal").modal('hide');
                        toastr["success"]("Data Inserted Successfully!", "Message")
                    }
                }


            });
        });
    </script>
    <script>
           
        $(document).on('click', '.deleteRecord', function() {
          
            let $this = $(this)
            console.log($this)

            alert('Are You Sure Want To Delete This Records?');
            var id = $(this).data("id");
            var token = $("meta[name='csrf-token']").attr("content");

            $.ajax({
                url: "/sop/" + id,
                type: 'DELETE',
                data: {
                    "id": id,
                    "_token": token,
                },
                success: function(response) {

                  
                    $this.closest('.parent_tr').remove()
                    toastr["success"](response.message, "Message")
                    
                }
            });

        });
    
</script>
    <script>
        
        $(document).on('click', '.editor_edit', function() {

            var $this = $(this);

            $.ajax({
                type: "GET",
                data: {
                    id: $this.data("id")

                },
                url: "{{ route('editName') }}"
            }).done(function(data) {
                
                console.log(data.sopedit);
              
                $('#sop_edit_id').val(data.sopedit.id)
                $('#sop_edit_name').val(data.sopedit.name)
                $('#sop_old_name').val(data.sopedit.name)
                console.log($('#sop_edit_class'), 'aaa')
               
                CKEDITOR.instances['sop_edit_content'].setData(data.sopedit.content)

                $("#erp-notes #sop_edit_form").attr('data-id', $($this).attr('data-id'));
                $("#erp-notes").modal("show");

            }).fail(function(response) {
                console.log(response);
            });
        });
</script>
<script>
        $(document).on('submit', '#sop_edit_form', function(e) {
            e.preventDefault();
            const $this = $(this)
            $(this).attr('data-id', );
            console.log($(this))
        
            $.ajax({
                type: "POST",
                data: $(this).serialize(),
                url: "{{ route('updateName') }}",
                datatype: "json"
            }).done(function(data) {
                console.log(data)
              
                let id = $($this).attr('data-id');
             
                console.log($('#sid' + id + ' td:nth-child(5) a:nth-child(3)').attr('data-name', data.params.header_name),  111111);
                $('#sid' + id + ' td:nth-child(2)').html(data.sopedit.name);
                $('#sid' + id + ' td:nth-child(3)').html(data.sopedit.content);
               
                $("#erp-notes").modal("hide");
                toastr["success"]("Data Updated Successfully!", "Message")

            }).fail(function(response) {
                console.log(response);
            });
        });
    </script>
      
@endsection
