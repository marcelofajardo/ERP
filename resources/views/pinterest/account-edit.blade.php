@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h2 class="page-heading"><a href="{{ action('PinterestAccountAcontroller@index') }}">Back</a> | Edit Account: {{ $account->last_name }}</h2>
        </div>
    </div>
    <div class="row">
        <div class="p-5" style="background: #dddddd">
            <form action="{{ action('PinterestAccountAcontroller@update', $account->id) }}" method="post">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="first_name">Full name</label>
                            <input value="{{$account->first_name}}" class="form-control" type="text" id="first_name" name="first_name" placeholder="Full name">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="first_name">Instagram Username</label>
                            <input value="{{$account->last_name}}" class="form-control" type="text" id="last_name" name="last_name" placeholder="Username">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input value="{{$account->password}}" type="text" name="password" id="password" class="form-control" placeholder="Password">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <input {{ $account->blocked ? 'checked' : '' }} type="checkbox" id="blocked" name="blocked">
                            <label for="blocked">Blocked?</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="email">Phone/Email</label>
                        <input value="{{$account->email}}" class="form-control" type="text" name="email" id="email" placeholder="Email/phone">
                    </div>
                    <div class="col-md-4">
                        <label for="country">Country</label>
                        <select class="form-control" name="country" id="country">
                            <option value="">All</option>
                            @foreach($countries as $country)
                                <option {{ $country->region == $account->country ? 'selected' : '' }} value="{{$country->region}}">{{$country->region}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-12 text-right">
                        <div class="form-group">
                            <button class="btn btn-primary">Update Account</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
    <style>
        thead input {
            width: 100%;
        }
    </style>
@endsection

@section('scripts')
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#table thead tr').clone(true).appendTo( '#table thead' );
            $('#table thead tr:eq(1) th').each( function (i) {
                var title = $(this).text();
                $(this).html( '<input type="text" placeholder="Search '+title+'" />' );

                $( 'input', this ).on( 'keyup change', function () {
                    if ( table.column(i).search() !== this.value ) {
                        table
                            .column(i)
                            .search( this.value )
                            .draw();
                    }
                } );
            } );
            var table = $('#table').dataTable({
                orderCellsTop: true,
                fixedHeader: true
            });
        });
    </script>
    @if (Session::has('message'))
        <script>
            toastr["success"]("{{ Session::get('message') }}", "Message")
        </script>
    @endif
@endsection