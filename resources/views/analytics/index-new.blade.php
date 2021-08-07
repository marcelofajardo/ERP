@extends('layouts.app')
@section('title', 'Analytics Data')
@section('content')

<style>
    /** only for the body of the table. */
    table.table tbody td {
        padding:5px;
    }
</style>
<!-- COUPON Rule Edit Modal -->
<div class="modal fade" id="fullUrlModal" tabindex="-1" role="dialog" aria-labelledby="couponModalLabel" aria-hidden="true">
<div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="couponModalLabel">Full URL</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div><strong><p class="url"></p></strong></div>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>


<div class="row ">
    <div class="col-lg-12 margin-tb">
        <h2 class="page-heading">New Google Analytics</h2>
    </div>
</div>
<form action="" method="get">
    <div class="row ">
        <div class="col-md-2">
            <label > Select website: </label>
            <div class="form-group">
                <select name="website" class="form-control">
                    <option value="">Select website</option>
                    @foreach ($website_list as $item)
                        <option value="{{ $item['id'] }}" {{ request('website') == $item['id'] ? 'selected' : null }}> {{ $item['website'] }} </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-2">
            <label >Start date : </label>
            <div class="form-group">
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control">
            </div>
        </div>
        <div class="col-md-2">
            <label >End date : </label>
            <div class="form-group">
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control">
            </div>
        </div>
        <div class="form-group col-md-3 pt-4" style="">
            <label >  </label>
            <button class="btn btn-secondary">Search</button>
            <a href="{{ url('/display/analytics-data') }}" class="btn btn-secondary">
                Clear
            </a>
        </div>
    </div>
</form>

<div class="col-lg-12 mb-5">
    <button class="btn btn-secondary show-history"> Show history </button>
</div>

@include('partials.flash_messages')
@include('analytics.history')

<div class="col-md-12">
    <div id="exTab2" >
        <ul class="nav nav-tabs">
            <li class="{{ request('geo-network') || request('audience-per-page') || request('user-per-page') || request('tracking-per-page') ? '' : 'active' }}"><a  href="#browser" data-toggle="tab">Platform or Device</a></li>
            <li class="{{ request('geo-network') ? 'active' : '' }}"><a href="#geoNetworkData" data-toggle="tab">Geo Network</a>
            <li class="{{ request('user-per-page') ? 'active' : '' }}"><a href="#usersData" data-toggle="tab">Users</a>
            <li class="{{ request('tracking-per-page') ? 'active' : '' }}"><a href="#pageTrackingData" data-toggle="tab">Page Tracking</a>
            <li class="{{ request('audience-per-page') ? 'active' : '' }}"><a href="#Audience" data-toggle="tab">Audience</a>
            </li>
        </ul>
    </div>
</div>

<div class="tab-content" >
    <div class="tab-pane {{ request('geo-network') || request('audience-per-page') || request('user-per-page') || request('tracking-per-page') ? '' : 'active' }}" id="browser"> 
        <div class="container-fluid">
            <div class="table-responsive ">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th scope="col" class="text-center">Website</th>
                            <th scope="col" class="text-center">Browser</th>
                            <th class="text-center">Opreation system</th>
                            <th scope="col" class="text-center">Session</th>
                            <th scope="col" class="text-center">Created at</th>
                            
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($PlatformDeviceData as $key => $item)
                        <tr>
                            <td>{{ $item['website'] }}</td>
                            <td>{{ $item['browser'] }}</td>
                            <td width="10%">{{ $item['os'] }}</td>
                            <td>{{ $item['session'] }}</td>
                            <td>{{$item['created_at']}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="col-md-12 text-center">
                    {!! $PlatformDeviceData->appends(request()->query())->links() !!}
                </div>
            </div>
        </div>
    </div>

    <div class="tab-pane {{ request('geo-network') ? 'active' : '' }}" id="geoNetworkData"> 
        <div class="container-fluid">
            <div class="table-responsive ">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th scope="col" class="text-center">Website</th>
                            <th scope="col" class="text-center">Country</th>
                            <th class="text-center">country ISO Code</th>
                            <th scope="col" class="text-center">Sessions</th>
                            <th scope="col" class="text-center">Created at</th>
                            
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($geoNetworkData as $key => $item)
                        <tr>
                            <td>{{ $item['website'] }}</td>
                            <td>{{ $item['country'] }}</td>
                            <td width="10%">{{ $item['iso_code'] }}</td>
                            <td>{{ $item['session'] }}</td>
                            <td>{{$item['created_at']}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="col-md-12 text-center">
                    {!! $geoNetworkData->appends(request()->query())->links() !!}
                </div>
            </div>
        </div>
    </div>

    <div class="tab-pane {{ request('user-per-page') ? 'active' : '' }}" id="usersData"> 
        <div class="container-fluid">
            <div class="table-responsive ">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th scope="col" class="text-center">Website</th>
                            <th scope="col" class="text-center">User type</th>
                            <th scope="col" class="text-center">Sessions</th>
                            <th scope="col" class="text-center">Created at</th>
                            
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($usersData as $key => $item)
                        <tr>
                            <td>{{ $item['website'] }}</td>
                            <td>{{ $item['user_type'] }}</td>
                            <td width="10%">{{ $item['session'] }}</td>
                            <td>{{$item['created_at']}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="col-md-12 text-center">
                    {!! $usersData->appends(request()->query())->links() !!}
                </div>
            </div>
        </div>
    </div>
    
    <div class="tab-pane {{ request('tracking-per-page') ? 'active' : '' }}" id="pageTrackingData"> 
        <div class="container-fluid">
            <div class="">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th scope="col" class="text-center">Website</th>
                            <th scope="" class="text-center">Page</th>
                            <th class="text-center">Avg time page</th>
                            <th scope="col" class="text-center">Page views</th>
                            <th scope="col" class="text-center">Unique page views</th>
                            <th scope="col" class="text-center">Exit rate</th>
                            <th scope="col" class="text-center">Entrances</th>
                            <th scope="col" class="text-center">Entrance Rate</th>
                            <th scope="col" class="text-center">Created at</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($pageTrackingData as $key => $item)
                        <tr>
                            <td>{{ $item['website'] }}</td>
                            <td width="5%" title="{{$item['page']}}">{{ substr($item['page'], 0, 30)}}</td>
                            <td>{{ $item['avg_time_page'] }}</td>
                            <td width="10%">{{ $item['page_views'] }}</td>
                            <td>{{ $item['unique_page_views'] }}</td>
                            <td>{{ $item['exit_rate'] }}</td>
                            <td>{{ $item['entrances'] }}</td>
                            <td>{{ $item['entrance_rate'] }}</td>
                            <td>{{$item['created_at']}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="col-md-12 text-center">
                    {!! $pageTrackingData->appends(request()->query())->links() !!}
                </div>
            </div>
        </div>
    </div>
    
    <div class="tab-pane {{ request('audience-per-page') ? 'active' : '' }}" id="Audience"> 
        <div class="container-fluid">
            <div class="">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th scope="col" class="text-center">Website</th>
                            <th scope="col" class="text-center">Age</th>
                            <th scope="col" class="text-center">Gender</th>
                            <th scope="col" class="text-center">Session</th>
                            <th scope="col" class="text-center">Created at</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($audienceData as $key => $item)
                        <tr>
                            <td>{{ $item['website'] }}</td>
                            <td>{{ $item['age'] }}</td>
                            <td>{{ $item['gender'] }}</td>
                            <td>{{ $item['session'] }}</td>
                            <td>{{$item['created_at']}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="col-md-12 text-center">
                    {!! $audienceData->appends(request()->query())->links() !!}
                </div>
            </div>
        </div>
    </div>

    
</div>
<div class="row mt-5">
    <div class="container-fluid">
        
    </div>
</div>
@endsection


@section('scripts')
<script type="text/javascript">

    $(document).on("click",".show-history",function(e) {
        e.preventDefault();
        $.ajax({
            url: '/display/analytics-history',
            type: 'POST',
            data : { _token: "{{ csrf_token() }}"},
            dataType: 'json',
            beforeSend: function () {
                $("#loading-image").show();
            },
            success: function(result){
                $("#loading-image").hide();
                if(result.code == 200) {
                    var t = '';
                    $.each(result.data,function(k,v) {
                        t += `<tr><td>`+v.id+`</td>`;
                        t += `<td>`+v.website+`</td>`;
                        t += `<td>`+v.title+`</td>`;
                        t += `<td>`+v.description+`</td>`;
                        t += `<td>`+v.created_at+`</td></tr>`;
                    });
                    if( t == '' ){
                        t = '<tr><td colspan="5" class="text-center">No data found</td></tr>';
                    }
                }
                $("#category-history-modal").find(".show-list-records").html(t);
                $("#category-history-modal").modal("show");
            },
            error: function (){
                $("#loading-image").hide();
            }
        });
    });

    $(document).ready(function() {
    });
    function displayFullPath(ele){
        let fullpath = $(ele).attr('data-path');
        $('.url').text(fullpath);
        $('#fullUrlModal').modal('show');
    }
</script>
@endsection