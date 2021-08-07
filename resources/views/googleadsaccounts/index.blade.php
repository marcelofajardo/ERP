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
    <h4>Google AdWords Account (<span id="ads_account_count">{{ $totalentries }}</span>)</h4>


    <div class="pull-left">
        <div class="form-group">
            <div class="row">
                <div class="col-md-4">
                    <select class="form-control select-multiple" id="website-select">
                        <option value="">Select Store Website</option>
                        @foreach($store_website as $key => $sw)
                        <option value="{{ $sw->website }}">{{ $sw->website }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <input name="accountname" type="text" class="form-control" value="{{ isset($accountname) ? $accountname : '' }}" placeholder="Account Name" id="accountname">
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-image" onclick="submitSearch()"><img src="/images/filter.png" /></button>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-image" id="resetFilter" onclick="resetSearch()"><img src="/images/resend2.png" /></button>
                </div>
            </div>
        </div>
    </div>


    <form method="get" action="/google-campaigns/ads-account/create">
        <button type="submit" class="float-right mb-3">New Account</button>
    </form>

    <div class="table-responsive mt-3">
        <table class="table table-bordered" id="adsaccount-table">
            <thead>
                <tr>
                    <th>#ID</th>
                    <th>Account Name</th>
                    <th>Store Website</th>
                    <th>Config-File</th>
                    <th>Notes</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                @foreach($googleadsaccount as $googleadsac)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$googleadsac->account_name}}</td>
                    <td>{{$googleadsac->store_websites}}</td>
                    <td>{{$googleadsac->config_file_path}}</td>
                    <td>{{$googleadsac->notes}}</td>
                    <td>{{$googleadsac->status}}</td>
                    <td>{{$googleadsac->created_at}}</td>
                    <td>
                        <a href="/google-campaigns/ads-account/update/{{$googleadsac->id}}" class="btn-image"><img src="/images/edit.png"></a>
                        <a href="/google-campaigns?account_id={{$googleadsac->id}}" class="btn btn-sm">create campaign</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{ $googleadsaccount->links() }}
</div>
@endsection

@section('scripts')
 <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.min.js"></script>
<script type="text/javascript">
    $('.select-multiple').select2({width: '100%'});

    function submitSearch(){
        src = '/google-campaigns/ads-account'
        accountname = $('#accountname').val();
        website = $('#website-select').val();
        $.ajax({
            url: src,
            dataType: "json",
            data: {
                accountname : accountname,
                website : website,

            },
            beforeSend: function () {
                $("#loading-image").show();
            },

        }).done(function (data) {
            $("#loading-image").hide();
            $("#adsaccount-table tbody").empty().html(data.tbody);
            $("#ads_account_count").text(data.count);
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
        src = '/google-campaigns/ads-account'
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
            $('#accountname').val('');
            $('#website-select').val('');
            $("#adsaccount-table tbody").empty().html(data.tbody);
            $("#ads_account_count").text(data.count);
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
