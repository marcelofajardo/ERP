@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h2 class="page-heading">OTHER EMAIL ACCOUNTS ({{count($accounts)}})</h2>
        </div>
    </div>
    <div class="row">
{{--        <div class="p-5" style="background: #dddddd">--}}
{{--            <form action="{{ action('PreAccountController@store') }}" method="post">--}}
{{--                @csrf--}}
{{--                <div class="row">--}}
{{--                    <div class="col-md-4">--}}
{{--                        <div class="form-group">--}}
{{--                            <label for="first_name">First name</label>--}}
{{--                            <input value="{{$firstName}}" class="form-control" type="text" id="first_name" name="first_name" placeholder="First name">--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="col-md-4">--}}
{{--                        <div class="form-group">--}}
{{--                            <label for="first_name">Last name</label>--}}
{{--                            <input value="{{ $lastName }}" class="form-control" type="text" id="last_name" name="last_name" placeholder="Last Name">--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="col-md-4">--}}
{{--                        <label for="email">Phone/Email</label>--}}
{{--                        <input class="form-control" type="text" name="email" id="email" placeholder="Email/phone">--}}
{{--                    </div>--}}
{{--                    <div class="col-md-4">--}}
{{--                        <div class="form-group">--}}
{{--                            <label for="password">Password</label>--}}
{{--                            <input type="text" name="password" id="password" class="form-control" placeholder="Password">--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="col-md-4">--}}
{{--                        <label for="country">Country</label>--}}
{{--                        <select class="form-control" name="country" id="country">--}}
{{--                            <option value="">All</option>--}}
{{--                            @foreach($countries as $country)--}}
{{--                                <option value="{{$country->region}}">{{$country->region}}</option>--}}
{{--                            @endforeach--}}
{{--                        </select>--}}
{{--                    </div>--}}
{{--                    <div class="col-md-12 text-right">--}}
{{--                        <div class="form-group">--}}
{{--                            <button class="btn btn-default">Add Account</button>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </form>--}}
{{--        </div>--}}
        <div class="col-md-12 mt-4">
            <form action="{{ action('PreAccountController@store') }}" method="post">
                @csrf
                <table class="table table-striped table-bordered">
                    <tr>
                        <th>S.N</th>
                        <th>First name</th>
                        <th>Last name</th>
                        <th>Email</th>
                        <th>Password</th>
                    </tr>
                    @foreach($firstName as $key=>$fn)
                        <tr>
                            <th>{{$key+1}}</th>
                            <th><input type="hidden" name="first_name[{{$key}}]" value="{{$fn->name}}">
                                {{ $fn->name }}
                            </th>
                            <th>
                                {{ $lastName[$key]['name'] }}
                                <input type="hidden"name="last_name[{{$key}}]" value="{{ $lastName[$key]['name'] }}">
                            </th>
                            <th><input type="text" name="email[{{$key}}]" placeholder="E-mail" class="form-control"></th>
                            <th><input type="text" name="password[{{$key}}]" placeholder="Password" class="form-control"></th>
                        </tr>
                        @php $key++ @endphp
                    @endforeach
                    @foreach($accounts as $account)
                        <tr>
                            <th>{{$key+1}}</th>
                            <th>{{ $account->first_name }}</th>
                            <th>{{ $account->last_name }}</th>
                            <th>{{ $account->email }}</th>
                            <th>{{ $account->password }}</th>
                        </tr>
                        @php $key++ @endphp
                    @endforeach
                </table>
                <div class="text-center">
                    <button class="btn btn-default">Add Accounts</button>
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
            // var table = $('#table').dataTable({
            //     // orderCellsTop: true,
            //     fixedHeader: true
            // });
            // $('#table thead tr').clone(true).appendTo( '#table thead' );
            // $('#table thead tr:eq(1) th').each( function (i) {
            //     var title = $(this).text();
            //     $(this).html( '<input type="text" placeholder="Search '+title+'" />' );
            //
            //     $( 'input', this ).on( 'keyup change', function () {
            //         if ( table.column(i).search() !== this.value ) {
            //             table
            //                 .column(i)
            //                 .search( this.value )
            //                 .draw();
            //         }
            //     } );
            // } );

            // $('#table thead tr').clone(true).appendTo( '#table thead' );
            // $('#table thead tr:eq(1) th').each( function (i) {
            //     var title = $(this).text();
            //     $(this).html( '<input type="text" placeholder="Search '+title+'" />' );
            //
            //     $( 'input', this ).on( 'keyup change', function () {
            //         if ( table.column(i).search() !== this.value ) {
            //             table
            //                 .column(i)
            //                 .search( this.value )
            //                 .draw();
            //         }
            //     } );
            // } );
            //
            // var table = $('#table').DataTable({
            //     orderCellsTop: true,
            //     fixedHeader: true
            // });
            //
            //
            // $("#table").addClass('table-bordered');
        });
    </script>
    @if (Session::has('message'))
        <script>
            toastr["success"]("{{ Session::get('message') }}", "Message")
        </script>
    @endif
@endsection