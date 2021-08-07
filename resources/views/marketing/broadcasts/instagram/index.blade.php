@extends('layouts.app')

@section('title', 'Instagram Broadcast')


@section('content')

	<div id="myDiv">
        <img id="loading-image" src="/images/pre-loader.gif" style="display:none;"/>
    </div>
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <h2 class="page-heading">Instagram Broadcast</h2>
            <div class="pull-left">
                <form action="/marketing/instagram-broadcast" method="GET" class="form-inline align-items-start">
                    <div class="form-group ml-3">
                        <div class='input-group date' id='filter-date'>
                            <input type='text' class="form-control global" name="date" value="{{ isset($date) ? $date : '' }}" placeholder="Date" id="date" />

                            <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                  </span>
                        </div>
                    </div>


                    <button type="submit" class="btn btn-image"><img src="/images/filter.png" /></button>
                </form>
            </div>
            <div class="pull-right">
              <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#addModal">+</a>
            </div>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="table-responsive mt-3">
      <table class="table table-bordered" id="passwords-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Number Of User</th>
            <th>Instagram Link</th>
            <th>Frequency</th>
            <th>Started At</th>
            <th>Message Send</th>
            <th>Message Pending</th>
            <th>Total Message</th>
          </tr>
		</thead>
		<tbody>

       @include('marketing.broadcasts.instagram.partials.data') 

          {!! $leads->render() !!}
          
        </tbody>
      </table>
    </div>

@include('marketing.broadcasts.instagram.partials.add')


@endsection


@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
 <script type="text/javascript">
 	$('#filter-date').datetimepicker({
        format: 'YYYY-MM-DD',
    });

    $('#schedule-datetime').datetimepicker();

    $(document).ready(function() {
        src = "/marketing/instagram-broadcast";
        $(".global").autocomplete({
        source: function(request, response) {
            date = $('#date').val();
            
          	$.ajax({
                url: src,
                dataType: "json",
                data: {
                    date : date,
                },
                beforeSend: function() {
                       $("#loading-image").show();
                },
            
            }).done(function (data) {
                 $("#loading-image").hide();
                console.log(data);
                $("#passwords-table tbody").empty().html(data.tbody);
                if (data.links.length > 10) {
                    $('ul.pagination').replaceWith(data.links);
                } else {
                    $('ul.pagination').replaceWith('<ul class="pagination"></ul>');
                }
                
            }).fail(function (jqXHR, ajaxOptions, thrownError) {
                alert('No response from server');
            });
        },
        minLength: 1,
       
        });
    });
 </script>     


@endsection
