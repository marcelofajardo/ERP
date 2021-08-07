@extends('layouts.app')

@section('styles')
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css" rel="stylesheet">
    <style src="https://cdnjs.cloudflare.com/ajax/libs/prettify/r298/prettify.min.css"></style>
    <style>
        .mt-3, .mt-2 {
            margin-top: 0.1rem !important;
        }
    </style>
@endsection
@section('content')

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <h2 class="page-heading">Send Password Through Whats App</h2>
            <div class="pull-left">

            </div>
            <div class="pull-right">
                <a href="{{ route('password.manage') }}"><button class="btn btn-info">Back To Password Manager</button></a>
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
    {{ Form::open(array('url' => route('password.sendwhatsapp'), 'method' => 'post', 'id' => 'myForm')) }}
    <div class="row">

    @foreach($data as $key => $value)
        <div class="col-md-3">
            <div class="card border-info mx-sm-1 p-3">
                <div class="text-center mt-3"><h4>{{ \App\User::findorfail($key)->name }}</h4></div>
                <div class="text-center mt-2"><h4>{{ $value }}</h4></div>
                <div class="text-center mt-2"><button  onclick="sendWhatsApp('{{$key}}','{{$value}}',1) " class="btn btn-primary btn-sm">Send WhatsApp</button></div>
                <div class="text-center mt-2"><small>select</small> <input type="checkbox" class="checkBox" name="user_id[]" value="{{$key}}" /><input type="hidden" name="password[]" value="{{ $value }}"/></div>

            </div>
        </div>
    @endforeach

    </div>
    <div class="pull-right">
        <input id="checkAll" type="button" value="Check All" class="btn btn-info">
        <button type="submit" class="btn btn-primary" id="submitBtn">Send Bulk WhatsApp</button>
        {{ Form::close() }}
    </div>





@endsection

@section('scripts')
    <script type="text/javascript">

            function sendWhatsApp($user_id, $password,$single) {
                event.preventDefault();
                $.ajax({
                    type: "POST",
                    url: "{{ route('password.sendwhatsapp') }}",
                    data: {"_token": "{{ csrf_token() }}", "user_id": $user_id, "password": $password , "single" : $single},
                    dataType: "json",
                    success: function (message) {
                        alert(message.message);
                    }, error: function () {
                        alert('Cannot Send Message');
                    }

                });
            }

            $(document).on('click', '#checkAll', function() {

                if ($(this).val() == 'Check All') {
                    $('.checkBox').prop('checked', true);
                    $(this).val('Uncheck All');
                } else {
                    $('.checkBox').prop('checked', false);
                    $(this).val('Check All');
                }
            });

            $(document).ready(function(){
                $("#submitBtn").click(function(e){
                    e.preventDefault();

                    if($('input[type="checkbox"]').is(":checked")){
                        $("#myForm").submit();
                    }else{
                        alert("Please Select Atleast 1 User");
                    }
                });
            });
 </script>
@endsection