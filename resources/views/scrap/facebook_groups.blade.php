@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h2 class="page-heading">Facebook Group Data</h2>
        </div>
        <div class="col-md-12">
            <table id="table" class="table table-striped">
                <thead>
                    <tr>
                        <th>S.N</th>
                        <th>Group</th>
                        <th>Group URL</th>
                        <th>User</th>
                        <th>Created date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($groups as $key=>$group)
                        <tr>
                            <td>{{$key+1}}</td>
                            <td>{{ $group->group_name }}</td>
                            <td><a href="{{ $group->group_url }}">Visit Group</a></td>
                            <td><a href="{{ $group->profile_url }}">{{ $group->username }}</a></td>
                            <td>{{ $group->created_at }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
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
            var table = $('#table').DataTable({
                orderCellsTop: true,
                fixedHeader: true,
                pageLength: 500
            });
        });
    </script>
    @if (Session::has('message'))
        <script>
            toastr["success"]("{{ Session::get('message') }}", "Message")
        </script>
    @endif
@endsection