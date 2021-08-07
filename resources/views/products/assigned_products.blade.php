@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h2 class="page-heading">User-Products Assignment</h2>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <form action="{{ action('ProductController@showListigByUsers') }}" method="get">
                <strong>Date:</strong> <input name="date" type="date" class="d-inline form-control" style="width: 160px;" placeholder="Date" value="{{ Request::get('date') ?? date('Y-m-d') }}"> <button class="d-inline btn btn-secondary">Filter</button>
            </form>
        </div>
        <div class="col-md-12 mt-4">
            <table class="table table-striped table-bordered">
                <tr>
                    <td>User</td>
                    <td>Product Assigned</td>
                    <td>Product Approved/Rejected</td>
                    <td>Product Remaining</td>
                    <td>Un-Assign</td>
                    <td>View</td>
                </tr>
                @foreach($users as $user)
                    <tr>
                        <td>{{ $user->user->name }}</td>
                        <td>{{ $user->total_assigned }}</td>
                        <td>{{ $user->total_acted }}</td>
                        <td>{{ $user->total_assigned - $user->total_acted }}</td>
                        <td>
                            <form method="post" action="{{ action('UserController@unassignProducts', $user->user_id) }}">
                                @csrf
                                <input style="width: 50px;" name="number" type="number" min="1" max="{{ $user->total_assigned - $user->total_acted }}" value="{{ $user->total_assigned - $user->total_acted }}" placeholder="Number...">
                                <button class="btn btn-xs btn-secondary">Unassign</button>
                            </form>
                        </td>
                        <td>
                            <a href="{{ action('UserController@showAllAssignedProductsForUser', $user->user_id) }}">View History</a>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            @if(Session::has('success'))
                toastr['success']("{{ Session::get('success') }}", 'Success');
            @endif
        });
    </script>
@endsection