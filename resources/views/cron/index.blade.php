@extends('layouts.app')

@section('styles')
    <style>
        .users {
            display: none;
        }

    </style>

    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">

@endsection


@section('content')

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <h2 class="page-heading">Cron Job</h2>
            <div class="pull-left">
                <form action="{{ route('cron.index') }}" method="GET" class="form-inline align-items-start">
                    <div class="form-group mr-3 mb-3">
                        <input name="term" type="text" class="form-control" id="product-search"
                               value="{{ isset($term) ? $term : '' }}"
                               placeholder="signature">
                    </div>
                    <div class="form-group ml-3">
                        <div class='input-group date' id='filter-date'>
                            <input type='text' class="form-control" name="date" value="{{ isset($date) ? $date : '' }}" placeholder="Date" />

                            <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                  </span>
                        </div>
                    </div>


                    <button type="submit" class="btn btn-image"><img src="/images/filter.png" /></button>
                </form>
            </div>
            <div class="pull-right">
           <!--    <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#passwordCreateModal">+</a> -->
            </div>
        </div>
    </div>

    

    <div class="table-responsive mt-3">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>ID</th>
            <th>Signature</th>
            <th>Schedule</th>
            <th>Error Count</th>
            <th>Last Updated Count</th>
            
            <th>History</th>
          </tr>
        </thead>

        <tbody>

        @if($crons->isEmpty())

            <tr>
                <td>
                    No Result Found
                </td>
            </tr>
        @else

          @foreach ($crons as $cron)

            <tr>
            	<td>
            		{{ $cron->id }}
            	</td>
              <td>
                {{ $cron->signature }}
              </td>
              <td>{{ $cron->schedule }}</td>
             
              <td>{{ $cron->error_count }}</td>
              <td>{{ $cron->updated_at->format('Y-m-d H:i:s') }}</td>
              <td>
                <a href="/cron/history/{{ $cron->signature }}"class="btn btn-secondary btn-sm">Click</button></a>
                | <a href="javascript:;" data-r="{{ $cron->signature }}" class="btn btn-secondary btn-sm btn-run-command">Run</button></a>
            </tr>


          @endforeach
          {!! $crons->appends(Request::except('page'))->links() !!}
          @endif
        </tbody>
      </table>
    </div>

@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
    <script>

        $(document).ready(function() {
            $(".select-multiple").multiselect();
            $(".select-multiple2").select2();
        });

        $('#filter-date').datetimepicker({
            format: 'YYYY-MM-DD'
        });

        $(document).on("click",".btn-run-command",function() {
            var commandName = $(this).data("r");
            $.ajax({
              type: 'GET',
              url: "/cron/run",
              data: {name: commandName}
            }).done(function(response) {
               if(response.code == 200) {
                 toastr['success'](response.output, 'success');
               }else{
                 toastr['error'](response.output, '');
               } 
            }).fail(function(response) {
               toastr['error']('oops,something went wrong', '');
            });
        });
    </script>
@endsection
