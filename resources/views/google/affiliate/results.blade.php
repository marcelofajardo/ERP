@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h2 class="page-heading">Google Affiliate Search Results (<span>{{ $posts->total() }}</span>)</h2>
        </div>
        <div class="col-md-12 mt-4">
            {{ $posts->appends($request->all())->render() }}
            <table id="table" class="table table-striped table-bordered">
                <thead>
                <tr>
                <th width="10%"><a href="">#</a></th>
                    <th width="10%"><a href="/google/affiliate/results{{ ($queryString) ? '?'.$queryString : '?' }}sortby=posted_at&orderby={{ ($orderBy == 'ASC') ? 'DESC' : 'ASC' }}">Date</a></th>
                    <th width="12%"><a href="/google/affiliate/results{{ ($queryString) ? '?'.$queryString : '?' }}sortby=hashtag&orderby={{ ($orderBy == 'ASC') ? 'DESC' : 'ASC' }}">Keyword</a></th>
                    <th width="20%"><a href="/google/affiliate/results{{ ($queryString) ? '?'.$queryString : '?' }}sortby=title&orderby={{ ($orderBy == 'ASC') ? 'DESC' : 'ASC' }}">Title</a></th>
                    <th width="15%">Address</th>
                    <th width="23%">Social Details</th>
                    <th width="20%">Post</th>
                </tr>
                <tr>
                    <th width="10%"><a href=""></a></th>
                    <th><input type="text" id="date" class="form-control" value="{{ isset($_GET['date']) ? $_GET['date'] : '' }}"></th>
                    <th><input type="text" id="hashtag" class="form-control" value="{{ isset($_GET['hashtag']) ? $_GET['hashtag'] : '' }}"></th>
                    <th><input type="text" id="title" class="form-control" value="{{ isset($_GET['title']) ? $_GET['title'] : '' }}"></th>
                    <th></th>
                    <th></th>
                    <th><input type="text" id="post" class="form-control" value="{{ isset($_GET['post']) ? $_GET['post'] : '' }}"></th>
                </tr>
                </thead>
                <tbody>
                @include('google.affiliate.row_results', ['posts' => $posts])
                </tbody>
            </table>
            {{ $posts->appends($request->all())->render() }}
        </div>
    </div>

    <img id="loading-image" src="/images/pre-loader.gif" style="display:none;"/>

    <style type="text/css">
        #loading-image {
            position: fixed;
            top: 50%;
            left: 50%;
            margin: -50px 0px 0px -50px;
        }
    </style>

    <div id="affiliateRemarkModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Remarks</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="list-unstyled" id="affiliate-remark-list">

                    </div>
                    <form id="affiliate-add-remark">
                        <input type="hidden" name="id" value="">
                        <div class="form-group">
                            <textarea rows="2" name="remark" class="form-control" placeholder="Start the Remark"></textarea>
                        </div>
                        <button type="button" class="btn btn-secondary btn-block mt-2" id="buttonAddRemark">Add</button>
                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script type="text/javascript">
$(document).ready(function () {
    $('#date,#hashtag,#title,#post').on('blur', function () {
        var queryString = '';
        if($('#date').val() != ''){
            queryString += 'date=' + $('#date').val() + '&';
        }
        if($('#hashtag').val() != ''){
            queryString += 'hashtag=' + $('#hashtag').val() + '&';
        }
        if($('#title').val() != ''){
            queryString += 'title=' + $('#title').val() + '&';
        }
        if($('#post').val() != ''){
            queryString += 'post=' + $('#post').val() + '&';
        }

        if(queryString != ''){
            queryString = '?' + queryString;
        }

        window.location.href = '/google/affiliate/results' + queryString;
    });

    $(document).on('click', '.expand-row', function() {
        var selection = window.getSelection();
        if (selection.toString().length === 0) {
            $(this).find('.td-mini-container').toggleClass('hidden');
            $(this).find('.td-full-container').toggleClass('hidden');
        }
    });

    $(document).on('click', '.make-remark', function(e) {
        e.preventDefault();

        var id = $(this).data('id');
        $('#affiliate-add-remark input[name="id"]').val(id);
        $.ajax({
            type: 'GET',
            url: '{{ route('task.gettaskremark') }}',
            data: {
                id:id,
                module_type: "googleaffiliatesearch",
                _token: "{{ csrf_token() }}"
            },
        }).done(response => {
            var html='';
            $.each(response, function( index, value ) {
                html+=' <p> '+value.remark+' <br> <small>By ' + value.user_name + ' updated on '+ moment(value.created_at).format('DD-M H:mm') +' </small></p>';
                html+"<hr>";
            });
            $("#affiliateRemarkModal").find('#affiliate-remark-list').html(html);
        });
    });

    $('#buttonAddRemark').on('click', function() {
        var id = $('#affiliate-add-remark input[name="id"]').val();
        var remark = $('#affiliate-add-remark').find('textarea[name="remark"]').val();

        $.ajax({
            type: 'POST',
            url: '{{ route('task.addRemark') }}',
            data: {
                id:id,
                remark:remark,
                module_type: 'googleaffiliatesearch',
                _token: "{{ csrf_token() }}"
            },
        }).done(response => {
            $('#affiliate-add-remark').find('textarea[name="remark"]').val('');
            var html =' <p> '+ remark +' <br> <small>By You updated on '+ moment().format('DD-M H:mm') +' </small></p>';
            $("#affiliateRemarkModal").find('#affiliate-remark-list').append(html);
        }).fail(function(response) {
            console.log(response);
            alert('Could not post remarks');
        });
    });

});
</script>
@endsection