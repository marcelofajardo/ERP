@extends('layouts.app')
@section('favicon' , 'task.png')

@section('content')
    <div class="container" style="margin-top: 10px">
    <h4>Google AdGroups (<span id="adsgroup_count">{{$totalNumEntries}}</span>) for {{@$campaign_name}} campaign name

    <div class="pull-left">
        <div class="form-group">
            <div class="row">
                
                <div class="col-md-3">
                    <input name="googlegroup_name" type="text" class="form-control" value="{{ isset($googlegroup_name) ? $googlegroup_name : '' }}" placeholder="Group Name" id="googlegroup_name">
                </div>

                <div class="col-md-2">
                    <input name="googlegroup_id" type="text" class="form-control" value="{{ isset($googlegroup_id) ? $googlegroup_id : '' }}" placeholder="Group ID" id="googlegroup_id">
                </div>
                
                <div class="col-md-2">
                    <input name="bid" type="text" class="form-control" value="{{ isset($bid) ? $bid : '' }}" placeholder="Bid" id="bid">
                </div>

                <div class="col-md-2">
                    <select class="browser-default custom-select" id="adsgroup_status" name="adsgroup_status" style="height: auto">
                    <option value="">--Status--</option>
                    <option value="ENABLED">Enabled</option>
                    <option value="PAUSED">Paused</option>
                    </select>
                </div>

                <div class="col-md-1">
                    <button type="button" class="btn btn-image" onclick="submitSearch()"><img src="/images/filter.png" /></button>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-image" id="resetFilter" onclick="resetSearch()"><img src="/images/resend2.png" /></button>
                </div>
            </div>
        </div>
    </div>


    <button class="btn-image" onclick="window.location.href='/google-campaigns?account_id={{$campaign_account_id}}'">Back to campaigns</button></h4>
        <form method="get" action="/google-campaigns/{{$campaignId}}/adgroups/create">
            <button type="submit" class="btn-sm float-right mb-3">New Ad Group</button>
        </form>
   
        <table class="table table-bordered" id="adsgroup-table">
            <thead>
            <tr>
                <th>#ID</th>
                <th>Ads Group Name</th>
                <th>Google Campaign Id</th>
                <th>Google Adgroupd Id</th>
                <th>Bid</th>
                <th>Status</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
            </thead>

            <tbody>
            @foreach($adGroups as $adGroup)
                <tr>
                    <td>{{$adGroup->id}}</td>
                    <td>{{$adGroup->ad_group_name}}</td>
                    <td>{{$adGroup->adgroup_google_campaign_id}}</td>
                    <td>{{$adGroup->google_adgroup_id}}</td>
                    <td>{{$adGroup->bid}}</td>
                    <td>{{$adGroup->status}}</td>
                    <td>{{$adGroup->created_at}}</td>
                    <td>
                    <form method="GET" action="/google-campaigns/{{$campaignId}}/adgroups/{{$adGroup['google_adgroup_id']}}/ads">
                    <button type="submit" class="btn-image">Ads</button>
                    </form>
                    {!! Form::open(['method' => 'DELETE','route' => ['adgroup.deleteAdGroup',$campaignId,$adGroup['google_adgroup_id']],'style'=>'display:inline']) !!}
                    <button type="submit" class="btn-image"><img src="/images/delete.png"></button>
                    {!! Form::close() !!}
                    {!! Form::open(['method' => 'GET','route' => ['adgroup.updatePage',$campaignId,$adGroup['google_adgroup_id']],'style'=>'display:inline']) !!}
                    <button type="submit" class="btn-image"><img src="/images/edit.png"></i></button>
                    {!! Form::close() !!}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    {{ $adGroups->links() }}
    </div>
@endsection

@section('scripts')
 <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.min.js"></script>
<script type="text/javascript">
    $('.select-multiple').select2({width: '100%'});

    function submitSearch(){
        src = '/google-campaigns/<?php echo $campaignId;  ?>/adgroups';
        googlegroup_name = $('#googlegroup_name').val();
        googlegroup_id = $('#googlegroup_id').val();
        bid = $('#bid').val();
        adsgroup_status = $('#adsgroup_status').val();

        $.ajax({
            url: src,
            dataType: "json",
            data: {
                googlegroup_name : googlegroup_name,
                googlegroup_id :googlegroup_id,
                bid :bid,
                adsgroup_status :adsgroup_status,
            },
            beforeSend: function () {
                $("#loading-image").show();
            },

        }).done(function (data) {
            $("#loading-image").hide();
            $("#adsgroup-table tbody").empty().html(data.tbody);
            $("#adsgroup_count").text(data.count);
            if (data.links.length > 10) {
                $('ul.pagination').replaceWith(data.links);
            } else {
                $('ul.pagination').replaceWith('<ul class="pagination"></ul>');
            }

        }).fail(function (jqXHR, ajaxOptions, thrownError) {
            alert('No response from server');
        });
        
    }

    function resetSearch(){
        src = '/google-campaigns/<?php echo $campaignId;  ?>/adgroups';
        blank = ''
        $.ajax({
            url: src,
            dataType: "json",
            data: {
               
               blank : blank, 

            },
            beforeSend: function () {
                $("#loading-image").show();
            },

        }).done(function (data) {
            $("#loading-image").hide();
            $('#googlegroup_name').val('');
            $('#googlegroup_id').val('');
            $('#bid').val('');
            $('#adsgroup_status').val('');
         

            $("#adsgroup-table tbody").empty().html(data.tbody);
            $("#adsgroup_count").text(data.count);
            if (data.links.length > 10) {
                $('ul.pagination').replaceWith(data.links);
            } else {
                $('ul.pagination').replaceWith('<ul class="pagination"></ul>');
            }

        }).fail(function (jqXHR, ajaxOptions, thrownError) {
            alert('No response from server');
        });
    }
</script>

@endsection
