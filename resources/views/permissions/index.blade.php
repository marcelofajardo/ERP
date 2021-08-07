@extends('layouts.app')

@section('title', 'Permission Info')

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
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <h2 class="page-heading">Permission Management (<span id="permission_count">{{ $permissions->total() }}</span>)</h2>
            <div class="pull-left">
                <div class="form-group">
                        <div class="row">
                            <div class="col-md-8">
                                <input name="term" type="text" class="form-control"
                                       value="{{ isset($term) ? $term : '' }}"
                                       placeholder="Search Permission" id="term">
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
                 @if(auth()->user()->checkPermission('roles-create'))
                    <a class="btn btn-secondary" href="{{ route('permissions.create') }}">+</a>
                @endif
            </div>
        </div>
    </div>


    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered" id="permission-table">
        <thead>    
        <tr>
            <th>No</th>
            <th>Name</th>
            <th>Route</th>
            <th width="280px">Action</th>
        </tr>
        </thead>
        <tbody>
        @include('permissions.partials.list-permission')
        </tbody>
    </table>
    </div>


    {!! $permissions->render() !!}


@endsection

@section('scripts')
 <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.min.js"></script>
<script type="text/javascript">
    $('.select-multiple').select2({width: '100%'});

    function submitSearch(){
        src = '/permissions'
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
            $("#permission-table tbody").empty().html(data.tbody);
            $("#permission_count").text(data.count);
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
        src = '/permissions'
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
            $('#user-select').val('')
            $("#permission-table tbody").empty().html(data.tbody);
            $("#permission_count").text(data.count);
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
