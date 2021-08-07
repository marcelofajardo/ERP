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
    <div class="container" style="margin-top: 10px">
    <h4>Google Ads (<span id="ads_count">{{$totalNumEntries}}</span>) for {{$groupname}} AdsGroup <button class="btn-image" onclick="window.location.href='/google-campaigns/{{$campaignId}}/adgroups';">Back to Ad groups</button></h4>


    <div class="pull-left">
        <div class="form-group">
            <div class="row">
                
            <div class="col-md-2">
                    <input name="headline" type="text" class="form-control" value="{{ isset($headline) ? $headline : '' }}" placeholder="Headline" id="headline">
                </div>
                
                <div class="col-md-2">
                    <input name="description" type="text" class="form-control" value="{{ isset($description) ? $description : '' }}" placeholder="Description" id="description">
                </div>

                <div class="col-md-2">
                    <input name="final_url" type="text" class="form-control" value="{{ isset($final_url) ? $final_url : '' }}" placeholder="Final URL" id="final_url">
                </div>

                <div class="col-md-2">
                    <input name="path" type="text" class="form-control" value="{{ isset($path) ? $path : '' }}" placeholder="Path" id="path">
                </div>

                <div class="col-md-2">
                    <select class="browser-default custom-select" id="ads_status" name="ads_status" style="height: auto">
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

    
    <form method="get" action="/google-campaigns/{{$campaignId}}/adgroups/{{$adGroupId}}/ads/create">
        <button type="submit" class="float-right mb-3">New Ads</button>
    </form>    

        <table class="table table-bordered" id="ads-table">
            <thead>
            <tr>
                <th>#ID</th>
                <th>Headline 1</th>
                <th>Headline 2</th>
                <th>Headline 3</th>
                <th>Description 1</th>
                <th>Description 2</th>
                <th>Final Url</th>
                <th>Path 1</th>
                <th>Path 2</th>
                <th>Status</th>
                <th>Created At</th>
                <th>Action</th>
            </tr>
            </thead>

            <tbody>
            @foreach($ads as $ad)
                <tr>
                    <td>{{$ad->id}}</td>
                    <td>{{$ad->headline1}}</td>
                    <td>{{$ad->headline2}}</td>
                    <td>{{$ad->headline3}}</td>
                    <td>{{$ad->description1}}</td>
                    <td>{{$ad->description2}}</td>
                    <td>{{$ad->final_url}}</td>
                    <td>{{$ad->path1}}</td>
                    <td>{{$ad->path2}}</td>
                    <td>{{$ad->status}}</td>
                    <td>{{$ad->created_at}}</td>
                    <td>
                    {!! Form::open(['method' => 'DELETE','route' => ['ads.deleteAd',$campaignId,$adGroupId,$ad['google_ad_id']],'style'=>'display:inline']) !!}
                <button type="submit" class="btn btn-image"><img src="/images/delete.png"></button>
                {!! Form::close() !!}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{ $ads->links() }}
    </div>
@endsection

@section('scripts')
 <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.min.js"></script>
<script type="text/javascript">
    $('.select-multiple').select2({width: '100%'});

    function submitSearch(){
        src = '/google-campaigns/<?php echo $campaignId; ?>/adgroups/<?php echo $adGroupId; ?>/ads';
        headline = $('#headline').val();
        description = $('#description').val();
        final_url = $('#final_url').val();
        path = $('#path').val();
        ads_status = $('#ads_status').val();

        $.ajax({
            url: src,
            dataType: "json",
            data: {
                headline : headline,
                description :description,
                final_url :final_url,
                path :path,
                ads_status :ads_status,
            },
            beforeSend: function () {
                $("#loading-image").show();
            },

        }).done(function (data) {
            $("#loading-image").hide();
            $("#ads-table tbody").empty().html(data.tbody);
            $("#ads_count").text(data.count);
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
        src = '/google-campaigns/<?php echo $campaignId; ?>/adgroups/<?php echo $adGroupId; ?>/ads';
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
            headline = $('#headline').val('');
            description = $('#description').val('');
            final_url = $('#final_url').val('');
            path = $('#path').val('');
            ads_status = $('#ads_status').val('');

            $("#ads-table tbody").empty().html(data.tbody);
            $("#ads_count").text(data.count);
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
