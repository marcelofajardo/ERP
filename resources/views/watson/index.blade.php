@extends('layouts.app')
@section('title', 'Watson accounts')
@section('large_content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <h2 class="page-heading">Watson Accounts</h2>
        </div>
    </div>
    @include('partials.flash_messages')
    <div class="row">
        <div class="col-md-12">
            <button class="btn btn-secondary pull-right" data-target="#addAccount" data-toggle="modal">+</button>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-12">

            <div class="table-responsive">
                <table class="table table-bordered table-hover" style="border: 1px solid #ddd;table-layout:fixed;">
                    <thead>
                    <tr>
                        <th style="width:5%;" class="text-center">Sl no</th>
                        <th style="width:15%;" class="text-center">Website</th>
                        <th style="width:20%;" class="text-center">Api Key</th>
                        <th style="width:20%;" class="text-center">Instance URL</th>
                        <th style="width:20%;" class="text-center">Workspace id</th>
                        <th style="width:10%;" class="text-center">Assistant id</th>
                        <th style="width:5%;" class="text-center">Active</th>
                        <th style="width:5%;" class="text-center">Action</th>
                    </tr>
                    </thead>
                    <tbody class="text-center">
                    @foreach($accounts as $key => $account)
                        <tr>
                            <td>{{$key+1}}</td>
                            <td>{{ $account->storeWebsite->title }}</td>
                            <td class="expand-row-msg" data-name="api_key" data-id="{{$account->id}}">
                            <span class="show-short-api_key-{{$account->id}}">{{ str_limit($account->api_key, 30, '...')}}</span>
                            <span style="word-break:break-all;" class="show-full-api_key-{{$account->id}} hidden">{{ $account->api_key }}</span>
                            </td>
                            <td class="expand-row-msg" data-name="url" data-id="{{$account->id}}">
                            <span class="show-short-url-{{$account->id}}">{{ str_limit($account->url, 30, '...')}}</span>
                            <span style="word-break:break-all;" class="show-full-url-{{$account->id}} hidden">{{ $account->url }}</span>
                            </td>
                            <td class="expand-row-msg" data-name="work_space_id" data-id="{{$account->id}}">
                            <span class="show-short-work_space_id-{{$account->id}}">{{ str_limit($account->work_space_id, 30, '...')}}</span>
                            <span style="word-break:break-all;" class="show-full-work_space_id-{{$account->id}} hidden">{{ $account->work_space_id }}</span>
                            </td>
                            <td class="expand-row-msg" data-name="assistant_id" data-id="{{$account->id}}">
                            <span class="show-short-assistant_id-{{$account->id}}">{{ str_limit($account->assistant_id, 30, '...')}}</span>
                            <span style="word-break:break-all;" class="show-full-assistant_id-{{$account->id}} hidden">{{ $account->assistant_id }}</span>
                            </td>
                            <td>{{ $account->is_active ? 'Yes' : 'No' }}</td>
                            <td>
                               <div class="d-flex">
                               <a data-id="{{ $account->id }}" class="btn btn-sm edit_account" style="padding:3px;">
                                    <i class="fa fa-edit" aria-hidden="true"></i>
                                </a>
                               
                                <a href="{{ route('watson-accounts.delete', $account->id) }}" data-id="1"
                                   class="btn btn-delete-template"
                                   onclick="return confirm('Are you sure you want to delete this account ?');" style="padding:3px;">
                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                </a>
                                @if(!$account->watson_push)
                                <a data-id="{{ $account->id }}"
                                   class="btn upload-data-watson"
                                    style="padding:3px;">
                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                </a>
                                @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!--Add Account Modal -->
        <div class="modal fade" id="addAccount" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title" id="exampleModalLabel">Add Watson Account</h3>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form method="post" id="submit-watson-account" action="">
                        @csrf
                        <div class="modal-body mb-2">
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="mt-3">Website</label>
                                    <select name="store_website_id" id="" class="form-control" required>
                                        <option value="">Select</option>
                                        @foreach($store_websites as $website)
                                            <option value="{{$website->id}}">{{$website->title}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-12">
                                    <label class="mt-3">Api Key</label>
                                    <input type="text" class="form-control" name="api_key" required/>
                                </div>
                                <div class="col-md-12">
                                    <label class="mt-3">Workspace ID</label>
                                    <input type="text" class="form-control" name="work_space_id"
                                           placeholder="Enter workspace ID" required/>
                                </div>
                                <div class="col-md-12">
                                    <label class="mt-3">Asistant ID</label>
                                    <input type="text" class="form-control" name="assistant_id"
                                           placeholder="Enter assistant ID" required/>
                                </div>
                                <div class="col-md-12">
                                    <label class="mt-3">Instance Url</label>
                                    <input type="text" class="form-control" name="url" required/>
                                </div>
                                <div class="col-md-12">
                                    <label class="mt-3">Usre name</label>
                                    <input type="text" class="form-control" name="user_name" required/>
                                </div>
                                <div class="col-md-12">
                                    <label class="mt-3">Password</label>
                                    <input type="text" class="form-control" name="password" required/>
                                </div>

                            </div>
                        </div>
                        <br>
                        <div class="modal-footer mt-3">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-secondary save-account">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!--Update Account Modal -->
        <div class="modal fade" id="updateAccount" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Update Watson Account</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form method="post" action="" id="edit-watson-account">
                        @csrf
                        <input type="hidden" id="account_id">
                        <div class="modal-body mb-2">
                            <div class="row">
                                <div class="col-md-12 mt-3">
                                    <label>Website</label>
                                    <select name="store_website_id" id="store_website_id" class="form-control" required>

                                    </select>
                                </div>
                                <div class="col-md-12 mt-3">
                                    <label>Api Key</label>
                                    <input type="text" class="form-control" id="api_key" name="api_key" required/>
                                </div>
                                <div class="col-md-12 mt-3">
                                    <label class="mt-3">Workspace ID</label>
                                    <input type="text" id="work_space_id" class="form-control" name="work_space_id" placeholder="Enter workspace ID" required/>
                                </div>
                                <div class="col-md-12 mt-3">
                                    <label class="mt-3">Asistant ID</label>
                                    <input type="text" id="assistant_id" class="form-control" name="assistant_id" placeholder="Enter assistant ID" required/>
                                </div>
                                <div class="col-md-12 mt-3">
                                    <label>Instance Url</label>
                                    <input type="text" class="form-control" id="instance_url" name="url" required/>
                                </div>
                                <div class="col-md-12">
                                    <label class="mt-3">Usre name</label>
                                    <input type="text" class="form-control" id="user_name_field" name="user_name" required/>
                                </div>
                                <div class="col-md-12">
                                    <label class="mt-3">Password</label>
                                    <input type="text" class="form-control" id="password" name="password" required/>
                                </div>
                                <div class="col-md-12 mt-3">
                                    <label>Is active</label>
                                    <input type="checkbox" class="form-control" id="is_active" name="is_active"/>
                                </div>
                            </div>
                        </div>

                        <br>
                        <div class="modal-footer mt-3">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-secondary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
    <script>
        $(document).on("submit", "#submit-watson-account", function (e) {
            e.preventDefault();
            var postData = $(this).serialize();
            $.ajax({
                method: "post",
                url: "{{action('WatsonController@store')}}",
                data: postData,
                dataType: "json",
                success: function (response) {
                    if (response.code == 200) {
                        toastr["success"]("Status updated!", "Message")
                        $("#addAccount").modal("hide");
                        $("#submit-watson-account").trigger('reset');
                        location.reload();
                    } else {
                        toastr["error"](response.message, "Message");
                    }
                },
                error: function (error) {
                    toastr["error"](error.responseJSON.message, "Message");
                }
            });
        });

        $(document).on("click", ".edit_account", function (e) {
            e.preventDefault();
            var id = $(this).data('id');
            $.ajax({
                method: "GET",
                url: "/watson/account/" + id,
                dataType: "json",
                success: function (response) {
                    var option = '<option value="" >Select</option>';
                    $.each(response.store_websites, function (i, item) {
                        if (item['id'] == response.account.store_website_id) {
                            var selected = 'selected';
                        } else {
                            var selected = '';
                        }
                        option = option + '<option value="' + item['id'] + '" ' + selected + ' >' + item['title'] + '</option>';
                    });
                    $('#store_website_id').html(option);
                    $('#api_key').val(response.account.api_key);
                    $('#instance_url').val(response.account.url);
                    $('#account_id').val(response.account.id);
                    $('#work_space_id').val(response.account.work_space_id);
                    $('#assistant_id').val(response.account.assistant_id);
                    $('#user_name_field').val(response.account.user_name);
                    $('#password').val(response.account.password);
                    if (response.account.is_active) {
                        $("#is_active").prop("checked", true);
                    } else {
                        $("#is_active").prop("checked", false);
                    }

                    $('#updateAccount').modal('show');
                },
                error: function (error) {
                    toastr["error"](error.responseJSON.message, "Message");
                }
            });
        });

        $(document).on("submit", "#edit-watson-account", function (e) {
            e.preventDefault();
            var postData = $(this).serialize();
            var id = $('#account_id').val();
            $.ajax({
                method: "post",
                url: "/watson/account/" + id,
                data: postData,
                dataType: "json",
                success: function (response) {
                    if (response.code == 200) {
                        toastr["success"]("Status updated!", "Message")
                        $("#updateAccount").modal("hide");
                    } else {
                        toastr["error"](response.message, "Message");
                    }
                },
                error: function (error) {
                    toastr["error"](error.responseJSON.message, "Message");
                }
            });
        });

        $(document).on('click', '.expand-row-msg', function () {
            var name = $(this).data('name');
			var id = $(this).data('id');
            var full = '.expand-row-msg .show-short-'+name+'-'+id;
            var mini ='.expand-row-msg .show-full-'+name+'-'+id;
            $(full).toggleClass('hidden');
            $(mini).toggleClass('hidden');
        });

        $(document).on('click', '.upload-data-watson', function () {
			var id = $(this).data('id');
            $.ajax({
                method: "post",
                url: "/watson/add-intents/" + id,
                data: {
                    _token: "{{csrf_token()}}",
                },
                dataType: "json",
                success: function (response) {
                    if (response.code == 200) {
                        toastr["success"](response.message, "Message")
                        location.reload();
                    } else {
                        toastr["error"](response.message, "Message");
                    }
                },
                error: function (error) {
                    toastr["error"](error.responseJSON.message, "Message");
                }
            });
        });
    </script>

@endsection
