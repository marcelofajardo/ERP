@extends('layouts.app')

@section('styles')
@section("styles")
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
@endsection
@endsection

@section('content')

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <h2 class="page-heading">Documents Manager</h2>
            <div class="pull-left">
                <form action="{{ route('document.index') }}" method="GET">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-4">
                                <input name="term" type="text" class="form-control global"
                                       value="{{ isset($term) ? $term : '' }}"
                                       placeholder="user,department,filename" id="term">
                            </div>
                          <!--   <div class="col-md-4">
                                <select class="form-control select-multiple2" name="category[]" data-placeholder="Select Category.." multiple>
                                    <option>Select Category</option>
                                    @foreach($category as $cat)
                                        <option value="{{ $cat->id }}" data-list="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div> -->

                            <div class="col-md-3">
                                <div class='input-group date' id='filter-date'>
                                    <input type='text' class="form-control global" name="date" value="{{ isset($date) ? $date : '' }}" placeholder="Date" id="date" />

                                    <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                  </span>
                                </div>
                            </div>

                            <div class="col-md-1">
                            <button type="submit" class="btn btn-image"><img src="/images/filter.png" /></button>
                            </div>

                        </div>
                    </div>
                </form>
            </div>
            <div class="pull-right">
                <a href="{{ route('document.email') }}"><button type="button" class="btn btn-secondary">Pending</button></a>
                <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#documentCreateModal">+</a>

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
        <table class="table table-bordered documnets-manager" id="documents-table">
            <thead>
            <tr>
                <th style="width:8%">Date</th>
                <th>User</th>
                <th>Department</th>
                <th>Document Type</th>
                <th>Category</th>
                <th>Filename</th>
                <th style="width:15%">Actions</th>
            </tr>
            <tr>
            <th></th>    
            <th><input type="text" id="user" class="search form-control"></th>
            <th></th>
            <th><input type="text" id="document_type" class="search form-control"></th>
            <th><input type="text" id="category" class="search form-control"></th>
            <th><input type="text" id="filename" class="search form-control"></th>
            <th></th>
          </tr>
            </thead>

            <tbody>
            @include('documents.data')
            </tbody>
        </table>
    </div>

    {!! $documents->appends(Request::except('page'))->links() !!}
    @include('partials.modals.remarks')
    @include('documents.partials.modal-addCategory')
    @include('documents.partials.modal-documentWhatsApp')
    @include('documents.partials.modal-emailToAll')
    @include('documents.partials.modal-uploadDocument')

    <div id="documentCreateModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <form action="{{ route('document.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="modal-header">
                        <h4 class="modal-title">Store a Document</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <select class="selectpicker form-control" data-live-search="true" data-size="15" name="user_id" title="Choose a User" required>
                                @foreach ($users as $user)
                                    <option data-tokens="{{ $user->name }} {{ $user->email }}" value="{{ $user->id }}"  {{ $user->id == old('user_id') ? 'selected' : '' }}>{{ $user->name }}</option>
                                @endforeach
                            </select>

                            @if ($errors->has('user_id'))
                                <div class="alert alert-danger">{{$errors->first('user_id')}}</div>
                            @endif
                        </div>

                        <div class="form-group">
                            <strong>Document Type:</strong>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>

                            @if ($errors->has('name'))
                                <div class="alert alert-danger">{{$errors->first('name')}}</div>
                            @endif
                        </div>

                        <div class="form-group">
                            <strong>Document category:</strong>
                            <select class="selectpicker form-control category" data-live-search="true" data-size="15" name="category_id" title="Choose a Category" required>

                                @foreach($category as $cat)
                                    <option value="{{ $cat->id }}" data-list="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                                <option value="0">Add Category</option>
                            </select>
                            @if ($errors->has('category'))
                                <div class="alert alert-danger">{{$errors->first('category')}}</div>
                            @endif
                        </div>
                        <input type="hidden" name="status" value="1">
                        <div class="form-group">
                            <strong>File:</strong>
                            <input type="file" name="file[]" class="form-control" value="" multiple required>

                            @if ($errors->has('file'))
                                <div class="alert alert-danger">{{$errors->first('file')}}</div>
                            @endif
                        </div>

                        <div class="form-group">
                            <strong>Version:</strong>
                            <input type="text" name="version" class="form-control" value="1" required>

                            @if ($errors->has('version'))
                                <div class="alert alert-danger">{{$errors->first('version')}}</div>
                            @endif
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-secondary">Upload</button>
                    </div>
                </form>
            </div>

        </div>
    </div>

 {{-- show model  --}}
 <div id="document_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title change-modal-title">Document type</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
            <p style="word-break: break-word;"></p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
    </div>

    </div>
</div>
    <!-- Modal To Add Category-->
    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Add Categroy</h4>
                </div>
                <form id="categories">
                    <div class="modal-body">
                        <p>Enter Category Name</p>
                        <input type="text" name="name" id="name">
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-default">Save</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.5/js/bootstrap-select.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
    <script>
        $('#filter-date').datetimepicker({
            format: 'YYYY-MM-DD'
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function(){
            $(".category").change(function() {
                var id = $(this).find(':selected').val();

                if(id == 0){
                    $("#myModal").modal();
                }
            });
        });


        $(document).on('click', '.make-remark', function(e) {
            e.preventDefault();

            var id = $(this).data('id');
            $('#add-remark input[name="id"]').val(id);

            $.ajax({
                type: 'GET',
                headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('document.gettaskremark') }}',
                data: {
                    id:id,
                    module_type: "document"
                },
            }).done(response => {
                var html='';

                $.each(response, function( index, value ) {
                    html+=' <p> '+value.remark+' <br> <small>By ' + value.user_name + ' updated on '+ moment(value.created_at).format('DD-M H:mm') +' </small></p>';
                    html+"<hr>";
                });
                $("#makeRemarkModal").find('#remark-list').html(html);
            });
        });

        $('#addRemarkButton').on('click', function() {
            var id = $('#add-remark input[name="id"]').val();
            var remark = $('#add-remark').find('textarea[name="remark"]').val();
            $.ajax({
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('document.addRemark') }}',
                data: {
                    id:id,
                    remark:remark,
                    module_type: 'document'
                },
            }).done(response => {
                $('#add-remark').find('textarea[name="remark"]').val('');

                var html =' <p> '+ remark +' <br> <small>By You updated on '+ moment().format('DD-M H:mm') +' </small></p>';

                $("#makeRemarkModal").find('#remark-list').append(html);
            }).fail(function(response) {
                console.log(response);

                alert('Could not fetch remarks');
            });
        });


        $(document).ready(function() {
            $('#categories').on('submit', function(e) {
                e.preventDefault(e);
                var name = $('#name').val();
                $.ajax({
                    url: '{{ route('documentcategory.add') }}',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {'_token': '{{ csrf_token() }}','name' : name},
                })
                    .done(function() {
                        // $('#myModal').modal('hide');
                        alert('Category Saved');
                        location.reload(true);
                    })
                    .fail(function() {
                        console.log("error");
                    })
            });
        });


        $(document).ready(function() {
            $('.sendWhatsapp').on('click', function(e) {
                e.preventDefault(e);
                id = $(this).attr("data-id");
                $('#document_id').val(id);
                $("#whatsappModal").modal();
            });
        });

        $(document).ready(function() {
            $('.sendEmail').on('click', function(e) {
                e.preventDefault(e);
                id = $(this).attr("data-id");
                $('#document_email_id').val(id);
                $("#emailToAllModal").modal();
            });
        });

        $(document).ready(function() {
            $('.uploadDocument').on('click', function(e) {
                e.preventDefault(e);
                id = $(this).attr("data-id");
                $('#document_upload_id').val(id);
                $("#uploadDocumentModal").modal();
            });
        });

        $(document).ready(function() {
            $('.users').multiselect({
                nonSelectedText:'Select User Type',
                buttonWidth:'300px',
                includeSelectAllOption: true,
                enableFiltering: true,
                enableCaseInsensitiveFiltering: true,

                onChange:function(option, checked){

                    $('.user_select_id').html('');
                    $('.user_select_id').multiselect('rebuild');

                    var selected = this.$select.val();
                    if(selected.length > 0)
                    {
                        $.ajax({
                            url:"{{ url('/api/values-as-per-user') }}",
                            method:"POST",
                            data:{selected:selected,'_token':'{{ csrf_token() }}'},
                            success:function(data)
                            {

                                $('.user_select_id').html(data);
                                $('.user_select_id').multiselect('rebuild');

                            }
                        })
                    }
                }
            });
            $('.user_select_id').multiselect({
                nonSelectedText:'Please Select',
                buttonWidth:'300px',
                includeSelectAllOption: true,
                enableFiltering: true,
                enableCaseInsensitiveFiltering: true,
            });
        });

        // cc

        $(document).on('click', '.add-cc', function (e) {
            e.preventDefault();

            if ($('#cc-label').is(':hidden')) {
                $('#cc-label').fadeIn();
            }

            var el = `<div class="row cc-input">
            <div class="col-md-10">
                <input type="text" name="cc[]" class="form-control mb-3">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-image cc-delete-button"><img src="/images/delete.png"></button>
            </div>
        </div>`;

            $('#cc-list').append(el);
        });

        $(document).on('click', '.cc-delete-button', function (e) {
            e.preventDefault();
            var parent = $(this).parent().parent();

            parent.hide(300, function () {
                parent.remove();
                var n = 0;

                $('.cc-input').each(function () {
                    n++;
                });

                if (n == 0) {
                    $('#cc-label').fadeOut();
                }
            });
        });

        // bcc

        $(document).on('click', '.add-bcc', function (e) {
            e.preventDefault();

            if ($('#bcc-label').is(':hidden')) {
                $('#bcc-label').fadeIn();
            }

            var el = `<div class="row bcc-input">
            <div class="col-md-10">
                <input type="text" name="bcc[]" class="form-control mb-3">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-image bcc-delete-button"><img src="/images/delete.png"></button>
            </div>
        </div>`;

            $('#bcc-list').append(el);
        });

        $(document).on('click', '.bcc-delete-button', function (e) {
            e.preventDefault();
            var parent = $(this).parent().parent();

            parent.hide(300, function () {
                parent.remove();
                var n = 0;

                $('.bcc-input').each(function () {
                    n++;
                });

                if (n == 0) {
                    $('#bcc-label').fadeOut();
                }
            });
        });

        $(document).on('click', '.add-contact', function (e) {
            e.preventDefault();

            if ($('#contact-label').is(':hidden')) {
                $('#contact-label').fadeIn();
            }

            var el = `<div class="row contact-input">
            <div class="col-md-10">
                <input type="integer" name="contact[]" class="form-control mb-3" placeholder="Please enter country code with number">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-image contact-delete-button"><img src="/images/delete.png"></button>
            </div>
        </div>`;

            $('#contact-list').append(el);
        });

        $(document).on('click', '.contact-delete-button', function (e) {
            e.preventDefault();
            var parent = $(this).parent().parent();

            parent.hide(300, function () {
                parent.remove();
                var n = 0;

                $('.contact-input').each(function () {
                    n++;
                });

                if (n == 0) {
                    $('#contact-label').fadeOut();
                }
            });
        });

        $(document).on('click', '.add-email-contact', function (e) {
            e.preventDefault();

            if ($('#contact-email-label').is(':hidden')) {
                $('#contact-email-label').fadeIn();
            }

            var el = `<div class="row contact-email-input">
            <div class="col-md-10">
                <input type="email" name="emailcontact[]" class="form-control mb-3" placeholder="Please Enter Email">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-image contact-email-delete-button"><img src="/images/delete.png"></button>
            </div>
        </div>`;

            $('#contact-email-list').append(el);
        });

        $(document).on('click', '.contact-email-delete-button', function (e) {
            e.preventDefault();
            var parent = $(this).parent().parent();

            parent.hide(300, function () {
                parent.remove();
                var n = 0;

                $('.contact-email-input').each(function () {
                    n++;
                });

                if (n == 0) {
                    $('#contact-email-label').fadeOut();
                }
            });
        });



        $(document).ready(function() {
        src = "{{ route('document.index') }}";
        $(".search").autocomplete({
        source: function(request, response) {
            user = $('#user').val();
            document_type = $('#document_type').val();
            category = $('#category').val();
            filename = $('#filename').val();
          

            $.ajax({
                url: src,
                dataType: "json",
                data: {
                    user : user,
                    document_type : document_type,
                    category : category,
                    filename : filename,
                
                },
                beforeSend: function() {
                       $("#loading-image").show();
                },
            
            }).done(function (data) {
                 $("#loading-image").hide();
                console.log(data);
                $("#documents-table tbody").empty().html(data.tbody);
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
        src = "{{ route('document.index') }}";
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
                $("#documents-table tbody").empty().html(data.tbody);
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



        $(document).on('click','.log-message-popup',function(){
            $('.change-modal-title').text($(this).data('modal-title'))
            $('#document_modal').modal('show');
            $('#document_modal p').text($(this).data('log_message'));
        })

    </script>


@endsection
