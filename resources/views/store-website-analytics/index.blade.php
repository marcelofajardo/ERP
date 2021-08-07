@extends('layouts.app')


@section('title', 'Store Website Analytics Index')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
@section('content')
<div class="row mb-5">
    <div class="col-lg-12 margin-tb">
        <h2 class="page-heading">Store Website Analytics List</h2>

        <div class="pull-left">
            <input id="filter" type="text" class="form-control" placeholder="Type here...">
        </div>

        <div class="pull-right">
            <a class="btn btn-secondary" href="{{url('/store-website-analytics/create')}}">+</a>
        </div>
    </div>
</div>

@include('partials.flash_messages')

<div class="table-responsive">
    <table class="table table-bordered" id="store_website-analytics-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Website</th>
                <th>Email</th>
                <th>Account Id</th>
                <th>View Id</th>
                <th>Store Website</th>
                <th width="280px">Action</th>
            </tr>
        </thead>
        <tbody class="searchable">
            @foreach($storeWebsiteAnalyticsData as $key => $record)
            @php
            $count = $key + 1;
            @endphp
            <tr>
                <td>{{$count}}</td>
                <td>{{$record->website ?? '--'}}</td>
                <td>{{$record->email}}</td>
                <td>{{$record->account_id}}</td>
                <td>{{$record->view_id}}</td>
                <td>{{$record->storeWebsiteDetails->title ?? $record->storeWebsiteDetails->website ?? '--' }}</td>
                <td>
                    <a href="{{url('/store-website-analytics/edit/'.$record->id)}}" class="btn btn-xs btn-image" title="Edit Record"><img src="/images/edit.png" ></a>
                    <a href="{{url('/store-website-analytics/delete/'.$record->id)}}" class="btn btn-image" title="Delete Record"><img src="/images/delete.png"></a>
                    <a href="javascript:;" class="btn btn-image find-records" data-id="{{$record->id}}" title="Check Report"><img src="/images/archive.png"></a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="modal fade bd-report-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-body">

       </div> 
    </div>
  </div>
</div>

@endsection

<script>
$(document).ready(function () {
    (function ($) {
        $('#filter').keyup(function () {
            var rex = new RegExp($(this).val(), 'i');
            $('.searchable tr').hide();
            $('.searchable tr').filter(function () {
                return rex.test($(this).text());
            }).show();
        })

        $(document).on("click",".find-records",function(e){
            e.preventDefault();
            var id = $(this).data("id");
            $.ajax({
                url: "/store-website-analytics/report/"+id,
                beforeSend: function () {
                    $("#loading-image").show();
                }
            }).done(function (data) {
                $("#loading-image").hide();
                $(".bd-report-modal-lg .modal-body").empty().html(data);
                $(".bd-report-modal-lg").modal("show");
            }).fail(function (jqXHR, ajaxOptions, thrownError) {
                alert('No response from server');
            });
        });

    }(jQuery));
});
</script>
