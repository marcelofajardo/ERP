@extends('layouts.app')
@section('favicon' , 'translation.png')

@section('title', 'Translation Listing')

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
    <div id="myDiv">
        <img id="loading-image" src="/images/pre-loader.gif" style="display:none;"/>
    </div>

    <div id="addGooleSetting" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content ">
                <form class="add_translation_language" action="{{ route('google-traslation-settings.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title">Add Goole Translation Setting</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                         <!-- email , account_json , status, last_note , created_at -->
                        <div class="form-group">
                            <strong>Email:</strong>
                            <input type="text" name="email" class="form-control" value="{{ old('email') }}">

                            @if ($errors->has('email'))
                            <div class="alert alert-danger">{{$errors->first('email')}}</div>
                            @endif
                        </div>

                        <div class="form-group">
                            <strong>Account JSON:</strong>
                            <textarea class="form-control" name="account_json" required>
                            </textarea>
                            @if ($errors->has('account_json'))
                            <div class="alert alert-danger">{{$errors->first('account_json')}}</div>
                            @endif
                        </div>

                        <div class="form-group">
                            <strong>Status:</strong>
                            <select name="status" class="form-control">
                                <option value="1">Enable</option>
                                <option value="0">Disable</option>
                            </select>
                            <!-- <input type="text" name="status" class="form-control" value="{{ old('status') }}" required> -->

                            @if ($errors->has('status'))
                            <div class="alert alert-danger">{{$errors->first('status')}}</div>
                            @endif
                        </div>

                        <div class="form-group">
                            <strong>Last Note:</strong>
                            <input type="text" name="last_note" class="form-control" value="{{ old('last_note') }}" required>

                            @if ($errors->has('last_note'))
                            <div class="alert alert-danger">{{$errors->first('last_note')}}</div>
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-secondary">Store</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <h2 class="page-heading">Translation Listing (<span id="translation_count">{{ $data->total() }}</span>)</h2>
            <div class="pull-left">
                <div class="form-group">
                        <div class="row">
                            <div class="col-md-4">
                                <input name="term" type="text" class="form-control"
                                       value="{{ isset($term) ? $term : '' }}"
                                       placeholder="Search Referral Program" id="term">
                            </div>
                            <div class="col-md-2">
                               <button type="button" class="btn btn-image" onclick="submitSearch()"><img src="/images/filter.png"/></button>
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-image" id="resetFilter" onclick="resetSearch()"><img src="/images/resend2.png"/></button>    
                            </div>
                        </div>
                    </div>
            </div>
            <div class="pull-right">
                <button type="button" class="btn btn-secondary" data-target="#addGooleSetting" data-toggle="modal">
                    Add New Setting
                </button>
                <a class="btn btn-secondary" href="{{ route('translation.add') }}">+</a>
            </div>
        </div>
    </div>

    @include('partials.flash_messages')

    <div class="table-responsive">
        <table class="table table-bordered" id="translation-table">
              <thead>
            <tr>
                <th>No</th>
                <th>Translation From</th>
                <th>Translation To</th>
                <th>Original Text</th>
                <th>Translated Text</th>
                <th>Updated At</th>
                <th>Created At</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
                @include('translation.partials.list-translation')
            </tbody>
        </table>
    </div>

    {!! $data->render() !!}


@endsection

@section('scripts')
 <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.min.js"></script>
<script type="text/javascript">
    $('.select-multiple').select2({width: '100%'});

    function submitSearch(){
        src = "{{route('translation.list')}}"
        term = $('#term').val()
        $.ajax({
            url: src,
            dataType: "json",
            data: {
                term : term,
            },
            beforeSend: function () {
                $("#loading-image").show();
            },

        }).done(function (data) {
            $("#loading-image").hide();
            $("#translation-table tbody").empty().html(data.tbody);
            $("#translation_count").text(data.count);
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
        src = "{{route('translation.list')}}"
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
            $('#term').val('')
            $('#translation-select').val('')
            $("#translation-table tbody").empty().html(data.tbody);
            $("#translation_count").text(data.count);
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
