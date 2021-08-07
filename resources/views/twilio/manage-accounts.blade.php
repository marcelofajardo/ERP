@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <h2 class="page-heading">Manage Twilio Accounts</h2>
        </div>
    </div>
    @include('partials.flash_messages')
    <div class="row mb-3">
        <div class="col-md-10 col-sm-12">
            <div class="row">
                <button class="btn btn-secondary" data-target="#addAccount" data-toggle="modal">+</button>
            </div>
            <div class="row mt-5">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th scope="col" class="text-center">#</th>
                            <th scope="col" class="text-center">Email ID</th>
                            <th scope="col" class="text-center">Account ID</th>
                            <th scope="col" class="text-center">Auth Token</th>
                            <th scope="col" class="text-center">Action</th>
                        </tr>
                        </thead>
                        <tbody class="text-center">
                        @if(isset($all_accounts))
                            @foreach($all_accounts as $accounts)
                                <tr>
                                    <td>#</td>
                                    <td>{{ $accounts->twilio_email }}</td>
                                    <td>{{ $accounts->account_id }}</td>
                                    <td>{{ $accounts->auth_token }}</td>
                                    <td>
                                        <a type="button" data-attr="{{ $accounts->id }}" data-email="{{ $accounts->twilio_email }}" data-account-id="{{ $accounts->account_id }}" data-auth-token="{{ $accounts->auth_token }}" class="btn btn-edit-template edit_account">
                                            <i class="fa fa-edit" aria-hidden="true"></i>
                                        </a>
                                        <a type="button" href="{{ route('twilio-delete-account', $accounts->id) }}" data-id="1" class="btn btn-delete-template" onclick="return confirm('Are you sure you want to delete this account ?');">
                                            <i class="fa fa-trash" aria-hidden="true"></i>
                                        </a>
                                        <a href="{{ route('twilio-manage-numbers', $accounts->id) }}" type="button" class="btn btn-image">
                                            <img src="/images/forward.png" style="cursor: default;" width="2px;">
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!--Add Account Modal -->
        <div class="modal fade" id="addAccount" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add Twilio Account</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form method="post" action="{{ route('twilio-add-account') }}">
                        @csrf
                        <div class="modal-body mb-2">
                            <div class="col-md-12">
                                <div class="col-md-4">
                                    <label>Email</label>
                                    <input type="text" class="form-control" name="email" required/>
                                </div>
                                <div class="col-md-4">
                                    <label>Account ID</label>
                                    <input type="text" class="form-control" name="account_id" required/>
                                </div>
                                <div class="col-md-4">
                                    <label>Auth Token</label>
                                    <input type="text" class="form-control" name="auth_token" required/>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer mt-5">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!--Update Account Modal -->
        <div class="modal fade" id="updateAccount" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Update Twilio Account</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form method="post" action="{{ route('twilio-add-account') }}">
                        @csrf
                        <div class="modal-body mb-2">
                            <div class="col-md-12">
                                <input type="hidden" name="id" id="id" />
                                <div class="col-md-4">
                                    <label>Email</label>
                                    <input type="text" class="form-control" name="email" id="email" required/>
                                </div>
                                <div class="col-md-4">
                                    <label>Account ID</label>
                                    <input type="text" class="form-control" name="account_id" id="account_id" required/>
                                </div>
                                <div class="col-md-4">
                                    <label>Auth Token</label>
                                    <input type="text" class="form-control" name="auth_token" id="auth_token" required/>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer mt-5">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

<script type="text/javascript">
    $(document).ready(function(){
       $('.edit_account').on("click", function(){
            $('#id').val($(this).data('attr'));
            $('#email').val($(this).data('email'));
            $('#account_id').val($(this).data('account-id'));
            $('#auth_token').val($(this).data('auth-token'));
            $('#updateAccount').modal('show');
       }) ;
    });
</script>
@endsection
