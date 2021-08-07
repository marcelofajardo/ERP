@extends('layouts.app')
@section('favicon' , 'task.png')
@section('styles')
<style type="text/css">
    #loading-image {
            position: fixed;
            top: 50%;
            left: 50%;
            margin: -50px 0px 0px -50px;
            z-index: 60;
        }
</style>
@endsection
@section('content')
    <div class="container-fluid" style="margin-top: 10px">
    <h4>Google AdWords - Campaigns ( <span id="ads_campaign_count">{{$totalNumEntries}} </span>)</h4>

    <div class="pull-left">
        <div class="form-group">
            <div class="row">
                
            <div class="col-md-2">
                    <input name="googlecampaign_id" type="text" class="form-control" value="{{ isset($googlecampaign_id) ? $googlecampaign_id : '' }}" placeholder="Campaign Id" id="googlecampaign_id">
                </div>
                
                <div class="col-md-2">
                    <input name="googlecampaign_name" type="text" class="form-control" value="{{ isset($googlecampaign_name) ? $googlecampaign_name : '' }}" placeholder="Campaign Name" id="googlecampaign_name">
                </div>

                <div class="col-md-1">
                    <input name="googlecampaign_budget" type="text" class="form-control" value="{{ isset($googlecampaign_budget) ? $googlecampaign_budget : '' }}" placeholder="Budget" id="googlecampaign_budget">
                </div>

                <div class="col-md-1">
                    <input name="start_date" type="text" class="form-control" value="{{ isset($start_date) ? $start_date : '' }}" placeholder="Start Date" id="start_date">
                </div>

                <div class="col-md-1">
                    <input name="end_date" type="text" class="form-control" value="{{ isset($end_date) ? $end_date : '' }}" placeholder="End Date" id="end_date">
                </div>

                <div class="col-md-2">
                    <input name="budget_uniq_id" type="text" class="form-control" value="{{ isset($budget_uniq_id) ? $budget_uniq_id : '' }}" placeholder="Budget Uniq Id" id="budget_uniq_id">
                </div>

                <div class="col-md-1">
                    <select class="browser-default custom-select" id="campaign_status" name="campaign_status" style="height: auto">
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

        <form method="get" action="/google-campaigns/create">
        <input type="hidden" value="<?php echo $_GET['account_id']; ?>" id="accountID" name="account_id"/>
            <button type="submit" class="float-right mb-3">New Campaign</button>
        </form>
        <div class="mt-3">
        <table class="table table-bordered" id="adscampaign-table">
            <thead>
            <tr>
                <th>#ID</th>
                <th>Google Campaign Id</th>
                <th>Campaign Name</th>
                <th>Budget</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Budget Uniq Id</th>
                <th>Status</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
            </thead>

            <tbody>
            @foreach($campaigns as $campaign)
                <tr>
                    <td>{{$campaign->id}}</td>
                    <td>{{$campaign->google_campaign_id}}</td>
                    <td>{{$campaign->campaign_name}}</td>
                    <td>{{$campaign->budget_amount}}</td>
                    <td>{{$campaign->start_date}}</td>
                    <td>{{$campaign->end_date}}</td>
                    <td>{{$campaign->budget_uniq_id}}</td>
                    <td>{{$campaign->status}}</td>
                    <td>{{$campaign->created_at}}</td>
                    <td>
                    <form method="GET" action="/google-campaigns/{{$campaign['google_campaign_id']}}/adgroups">
                        <button type="submit" class="btn btn-sm btn-link">Ad Groups</button>
                    </form>
                    {!! Form::open(['method' => 'DELETE','route' => ['googlecampaigns.deleteCampaign',$campaign['google_campaign_id']],'style'=>'display:inline']) !!}
                    <input type="hidden" id="delete_account_id" name="delete_account_id" value='{{$campaign->account_id}}'/>
                        <button type="submit" class="btn-image"><img src="/images/delete.png"></button>
                    {!! Form::close() !!}
                    {!! Form::open(['method' => 'GET','route' => ['googlecampaigns.updatePage',$campaign['google_campaign_id']],'style'=>'display:inline']) !!}
                    <input type="hidden" id="account_id" name="account_id" value='{{$campaign->account_id}}'/>
                    <button type="submit" class="btn-image"><img src="/images/edit.png"></button>
                    {!! Form::close() !!}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        </div>
        {{ $campaigns->links() }}

    </div>
@endsection

@section('scripts')
 <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.min.js"></script>
<script type="text/javascript">
    $('.select-multiple').select2({width: '100%'});

    function submitSearch(){
        src = '/google-campaigns?account_id=<?php echo $_GET['account_id']; ?>';
        googlecampaign_id = $('#googlecampaign_id').val();
        googlecampaign_name = $('#googlecampaign_name').val();
        googlecampaign_budget = $('#googlecampaign_budget').val();
        start_date = $('#start_date').val();
        end_date = $('#end_date').val();
        budget_uniq_id = $('#budget_uniq_id').val();
        campaign_status = $('#campaign_status').val();

        $.ajax({
            url: src,
            dataType: "json",
            data: {
                googlecampaign_id : googlecampaign_id,
                googlecampaign_name :googlecampaign_name,
                googlecampaign_budget :googlecampaign_budget,
                start_date :start_date,
                end_date :end_date,
                budget_uniq_id :budget_uniq_id,
                campaign_status :campaign_status,
            },
            beforeSend: function () {
                $("#loading-image").show();
            },

        }).done(function (data) {
            $("#loading-image").hide();
            $("#adscampaign-table tbody").empty().html(data.tbody);
            $("#ads_campaign_count").text(data.count);
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
        src = '/google-campaigns?account_id=<?php echo $_GET['account_id']; ?>';
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
            $('#googlecampaign_id').val('');
            $('#googlecampaign_name').val('');
            $('#googlecampaign_budget').val('');
            $('#start_date').val('');
            $('#end_date').val('');
            $('#budget_uniq_id').val('');
            $('#campaign_status').val('');

            $("#adscampaign-table tbody").empty().html(data.tbody);
            $("#ads_campaign_count").text(data.count);
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
