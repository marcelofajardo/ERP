@extends('layouts.app')

@section('title', 'Plesk mail accounts')

@section('styles')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css">
@endsection

@section('content')

    <div class="row">
        <div class="col-12">
            <h2 class="page-heading">Mail accounts</h2>
          </div>
          <div class="col-12 mb-3">
            <div class="pull-left">
            </div>
            <div class="pull-right">
                <a class="btn btn-secondary add-new-btn" href="{{ route('plesk.domains') }}">Back</a>
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
			    @foreach ($mailAccount as $key => $account)
            <tr>
            <td>{{$account['name']}}</td>
            <td>
            @if($account['name'])
            <button  class="btn btn-xs btn-secondary delete-mail-ac" data-name="{{$account['name']}}" data-site_name="{{$site_name}}" data-id="{{$id}}"><i class="fa fa-trash" aria-hidden="true"></i> </button>
            <button class="btn btn-xs btn-secondary change-password" data-name="{{$account['name']}}" data-site_name="{{$site_name}}" data-id="{{$id}}"><i class="fa fa-lock" aria-hidden="true"></i> </button>
            @endif
            </td>
            </tr>
            @endforeach
        </tbody>
      </table>

	</div>
    </div>



    <div id="change-password-model" class="modal fade" role="dialog">
        <div class="modal-dialog">
        <form action="{{ route('plesk.domains.mail-accounts.change-password') }}" method="POST">
        @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Change Password</h4>
                </div>
                <div class="modal-body">
                <input type="hidden" id="hidden-site-id" name="hidden_site_id" value="">
                <input type="hidden" id="hidden-mail-name" name="hidden_mail_name" value="">
                <input type="hidden" id="hidden-domain-name" name="hidden_domain_name" value="">

                <div class="form-group">
                <label for="password">Password</label>
                    <input type="text" class="form-control" title="Should have at least 1 lowercase AND 1 uppercase AND 1 number AND 1 special character and minimum 6 character long" name="password" placeholder="New password" value="" required>
                </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-secondary">Save</button>
                </div>
            </div>
        </div>
        </form>
    </div>

    <script>
        $(document).ready(function () {
          $(document).on('click', '.delete-mail-ac', function () {
            name = $(this).data('name');
            id = $(this).data('id');
            site_name = $(this).data('site_name');

            if(window.confirm("Are you sure ?")) {
              $.ajax({
                url: "/plesk/domains/mail/delete/"+id,
                type: 'POST',
                data: {
                  name : name,
                  site_name: site_name,
                  "_token": "{{csrf_token()}}"
                },
                success: function (response) {
                  console.log(response);
                    toastr['success'](response.message, 'success');
                    location.reload();
                },
                error: function (error) {
                  console.log(error);
                  toastr['error']('error', 'error');
                }
            });
            }
            });


            $(document).on('click', '.change-password', function () {
            name = $(this).data('name');
            id = $(this).data('id');
            site_name = $(this).data('site_name');
            $("#hidden-mail-name").val(name);
            $("#hidden-site-id").val(id);
            $("#hidden-domain-name").val(site_name);
            $("#change-password-model").modal("show");
            });
        });
        </script>
   
@endsection
