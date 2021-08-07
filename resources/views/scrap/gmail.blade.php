@extends('layouts.app')

@section('favicon' , 'scrapergmail.png')

@section('title', 'Gmail Scrapper Info')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/media-card.css') }}">
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
    <div id="myDiv">
        <img id="loading-image" src="/images/pre-loader.gif" style="display:none;"/>
    </div>
    <div class="row">
        <div class="col-md-12">
            <h2 class="page-heading">Gmail Data (<span id="count">{{ $data->total() }}</span>)</h2>
        </div>
        <div class="col-md-4">
            <select class="form-control select-multiple2" id="sender">
                <option value="0">Select Sender</option>
                @foreach($senders as $sender)
                <option value="{{ $sender->sender }}">{{ $sender->sender }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-12">
            <table class="table table-striped" id="log-table">
                <thead>
                <tr>
                    <th width="2%">SN</th>
                    <th width="2%">Page Url</th>
                    <th width="15%">Sender</th>
                    <th width="20%">Date Sent</th>
                    <th>Images</th>
                    <th>tags</th>
                </tr>
            </thead>
                {{ $data->render() }}
                 <tbody id="content_data">
                @include('scrap.partials.list-gmail')
            </tbody>
            </table>
        </div>
    </div>
@endsection



@section('scripts')
    <script>
     $(".select-multiple2").select2();

     $('#sender').on('change', function () {
            sender = $(this).val();
            $.ajax({
                url: '/scrap/gmail',
                dataType: "json",
                data: {
                    sender: sender,
                },
                beforeSend: function () {
                    $("#loading-image").show();
                },
            }).done(function (data) {
                $("#loading-image").hide();
                $("#count").text(data.total);
                $("#log-table tbody").empty().html(data.tbody);
                if (data.links.length > 10) {
                    $('ul.pagination').replaceWith(data.links);
                } else {
                    $('ul.pagination').replaceWith('<ul class="pagination"></ul>');
                }
            }).fail(function (jqXHR, ajaxOptions, thrownError) {
                $("#loading-image").hide();
                alert('No response from server');
            });
        });
    </script>
@endsection
