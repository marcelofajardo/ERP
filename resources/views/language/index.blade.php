@extends('layouts.app')



@section('title', 'Language Manager')

@section('styles')
    <style>
        .users {
            display: none;
        }

    </style>

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
            <h2 class="page-heading">Language Manager</h2>
            <div class="pull-left">
                <form action="{{ route('language.index') }}" method="GET" class="form-inline align-items-start">
                    <div class="form-group mr-3 mb-3">
                        <input name="term" type="text" class="form-control global" id="term"
                               value="{{ isset($term) ? $term : '' }}"
                               placeholder="locale , code, password">
                    </div>
                    <button type="submit" class="btn btn-image"><img src="/images/filter.png" /></button>
                </form>
                  <a href="/languages" class="btn btn-image"><img src="/images/icons-refresh.png" /></a>
            </div>
            <div class="pull-right">
              <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#languageCreateModal">+</a>
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
            <th>Locale</th>
            <th>Magneto Code</th>
            <th>Store View</th>
            <th>Status</th>
            <th>Action</th>
            
          </tr>
        </thead>

        <tbody>

       
           @include('language.data') 
          {!! $languages->render() !!}
          
        </tbody>
      </table>
    </div>

    <div id="languageCreateModal" class="modal fade" role="dialog">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <form action="{{ route('language.store') }}" method="POST">
            @csrf

            <div class="modal-header">
              <h4 class="modal-title">Store a language</h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <strong>Locale:</strong>
                <input type="text" name="locale" class="form-control" value="{{ old('locale') }}">

                @if ($errors->has('locale'))
                  <div class="alert alert-danger">{{$errors->first('locale')}}</div>
                @endif
              </div>

              <div class="form-group">
                <strong>Magento Code:</strong>
                <input type="text" name="code" class="form-control" value="{{ old('code') }}" required>

                @if ($errors->has('code'))
                  <div class="alert alert-danger">{{$errors->first('code')}}</div>
                @endif
              </div>
                
                <div class="form-group">
                <strong>Store View:</strong>
                <input type="text" name="store_view" class="form-control" value="{{ old('store_view') }}" required>

                @if ($errors->has('store_view'))
                  <div class="alert alert-danger">{{$errors->first('store_view')}}</div>
                @endif
              </div>

              

            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-secondary">Store</button>
            </div>
          </form>
        </div>

      </div>
    </div>
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

  function updateDetails(id){
    code = $('#code'+id).val();
    locale = $('#locale'+id).val();
    store_view = $('#store_view'+id).val();
    status = $('#status'+id).val();
    
    $.ajax({
        url: "{{ route('language.update') }}",
        dataType: "json",
        type: 'POST',
        data: {
             id: id,
             locale : locale,
             code : code,
             store_view:store_view,
             status:status,
             _token: "{{ csrf_token() }}",
        },
        beforeSend: function () {
            $("#loading-image").show();
        },

    }).done(function (data) {
      $("#loading-image").hide();
    }).fail(function (jqXHR, ajaxOptions, thrownError) {
        alert('No response from server');
    });  
    
  }

  function deleteLanguage(id) {
    alert(id);
    if (confirm("Are you sure?")) {
        $.ajax({
        url: "{{ route('language.delete') }}",
        dataType: "json",
        type: 'POST',
        data: {
             id: id,
             _token: "{{ csrf_token() }}",
        },
        beforeSend: function () {
            $("#loading-image").show();
        },

      }).done(function (data) {
        $("#loading-image").hide();
        $("#row"+id).hide();
      }).fail(function (jqXHR, ajaxOptions, thrownError) {
          alert('No response from server');
      });  
    }
  }
</script>
@endsection
