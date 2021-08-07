@extends('layouts.app')

@section('title', 'Vendor - Supplier Form')

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.min.css">
@endsection

@section('content')

<form action="http://pravin.sololux/vendors" method="POST">
        <input type="hidden" name="_token" value="tojivzAhdNcOBzXsKN0r3FgSHyBD6UKJzyNfjdF0">
        <div class="modal-header">
          <h4 class="modal-title">Store a Vendor</h4>
          <button type="button" class="close" data-dismiss="modal">×</button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <strong>Category:</strong>
            <select class="form-control" name="category_id">
              <option value="">Select a Category</option>

                              <option value="1">Some Super vendor</option>
                          </select>

                      </div>

          <div class="form-group">
            <strong>Name:</strong>
            <input type="text" name="name" class="form-control" value="" required="">

                      </div>

          <div class="form-group">
            <strong>Address:</strong>
            <input type="text" name="address" class="form-control" value="">

                      </div>

          <div class="form-group">
            <strong>Phone:</strong>
            <input type="number" name="phone" class="form-control" value="">

                      </div>

          <div class="form-group">
            <strong>Email:</strong>
            <input type="email" name="email" class="form-control" value="">

                      </div>

          <div class="form-group">
            <strong>Social Handle:</strong>
            <input type="text" name="social_handle" class="form-control" value="">

                      </div>

          <div class="form-group">
            <strong>Website:</strong>
            <input type="text" name="website" class="form-control" value="">

                      </div>

          <div class="form-group">
            <strong>Login:</strong>
            <input type="text" name="login" class="form-control" value="">

                      </div>

          <div class="form-group">
            <strong>Password:</strong>
            <input type="password" name="password" class="form-control" value="">

                      </div>

          <div class="form-group">
            <strong>GST:</strong>
            <input type="text" name="gst" class="form-control" value="">

                      </div>

          <div class="form-group">
            <strong>Account Name:</strong>
            <input type="text" name="account_name" class="form-control" value="">

                      </div>

          <div class="form-group">
            <strong>IBAN:</strong>
            <input type="text" name="account_iban" class="form-control" value="">

                      </div>

          <div class="form-group">
            <strong>SWIFT:</strong>
            <input type="text" name="account_swift" class="form-control" value="">

                      </div>

          <div class="form-group">
            <strong>Create User:</strong>
            <input type="checkbox" name="create_user" class="form-control">

                      </div>

          <div class="form-group">
            <strong>Invite (Github):</strong>
            <input type="checkbox" name="create_user_github" class="form-control">

                      </div>

          <div class="form-group">
            <strong>Invite (Hubstaff):</strong>
            <input type="checkbox" name="create_user_hubstaff" class="form-control">

                      </div>


        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-secondary">Add</button>
        </div>
      </form>
    
@endsection

@section('scripts')
    <script type="text/javascript">
    
    </script>
@endsection