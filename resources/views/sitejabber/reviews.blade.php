@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h2 class="page-heading">Sitejabber Reviews</h2>
        </div>
        <div class="col-md-12 mb-5">
            <table class="table table-striped">
                <tr>
                    <th>S.N</th>
                    <th style="width: 500px;">Review</th>
                    <th>Approval</th>
                    <th>Post Status</th>
                    <th>Posted By/At</th>
{{--                    <th>Action</th>--}}
                </tr>
                @foreach($reviews as $key=>$review)
                    <tr>
                        <td>{{ $key+1 }}</td>
                        <td>{{ $review->review }}</td>
                        <td>{{ $review->is_approved ? 'Approved' : 'Not Approved' }}</td>
                        <td>{{ $review->status }}</td>
                        <td>
                            @if ($review->account)
                                <strong>{{ $review->account->first_name }} {{ $review->account->last_name }}</strong> on {{ substr($review->posted_date, 0, 10) }}
                            @elseif ($review->customer)
                                <strong>{{ $review->customer->name }}</strong> on {{ $review->posted_date }}
                            @else
                                <span class="badge badge-info">Not Posted</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
@endsection