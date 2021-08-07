@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <h2 class="page-heading">Task Types</h2>
            <div class="pull-left">
            </div>
            <div class="pull-right">
                <a class="btn btn-secondary" href="{{ route('task-types.create') }}">+</a>
            </div>
        </div>
    </div>

    @include('partials.flash_messages')

    <div class="table-responsive">
        <table class="table table-bordered">
        <tr>
            <th>No</th>
            <th>Name</th>

{{--            <th width="280px">Action</th>--}}
        </tr>
            <?php $i =0; ?>
        @foreach ($taskTypes as $key => $task_type)
            <tr>
                <td>{{ ++$i }}</td>
                <td>{{ $task_type->name }}</td>


            </tr>
        @endforeach
    </table>
    </div>





@endsection
