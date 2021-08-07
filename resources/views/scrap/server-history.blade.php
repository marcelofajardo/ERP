@extends('layouts.app')
@section('favicon' , 'scrapproduct.png')
@section('title', 'Server History')
@section('large_content')
    <div class="row">
       <div class="col-lg-12 margin-tb">
          <h2 class="page-heading">Server history</h2>
          <div class="pull-left">
             <form action="?" class="form-inline" method="GET">
                <div class="form-group ml-3">
                   <div class='input-group date' id='planned-datetime'>
                      <input type='text' class="form-control input-sm date-type" name="planned_at" value="{{ $requestedDate }}" />
                      <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                      </span>
                   </div>
                </div>
                <button type="submit" class="btn btn-image"><img src="/images/filter.png" /></button>
             </form>
          </div>
       </div>
    </div>
    <div class="row no-gutters mt-3">
       <div class="col-xs-12 col-md-12" id="plannerColumn">
          <div class="table-responsive">
             <table class="table table-bordered table-sm">
                <thead>
                   <tr>
                      <th width="5%">Time</th>
                      <?php foreach($totalServers as $totalServer){ ?>
                            <th>{{ $totalServer }}</th>
                      <?php } ?>
                   </tr>
                </thead>
                <tbody>
                   <?php foreach($timeSlots as $k => $timeSlot) { ?> 
                       <tr>
                          <td>{{ date("g:i A",strtotime($timeSlot.":00")) }}</td>
                          <?php foreach($totalServers as $s => $totalServer){ ?>
                              <td class="p-2">
                                  <?php
                                    if(isset($listOfServerUsed[$k]) && isset($listOfServerUsed[$k][$totalServer])) {
                                        $loops = $listOfServerUsed[$k][$totalServer];
                                        foreach($loops as $l) {
                                            $deleteBtn = "";
                                            if(!empty($l['pid'])) {
                                                $deleteBtn = '&nbsp;<i data-server-id="'.$totalServer.'" data-p-id="'.$l['pid'].'" class="fa fa-window-close stop-job" aria-hidden="true"></i>';
                                            }
                                            echo '<span class="badge badge-secondary">'.$l['scraper_name']." ".$l['memory_string'].$deleteBtn.'</span><br>';
                                        }
                                    }
                                  ?>
                              </td>
                          <?php } ?>
                       </tr>
                   <?php } ?> 
                </tbody>
             </table>
          </div>
       </div>
    </div>
@endsection
@section('styles')
    <link rel="stylesheet" href="{{ asset('css/media-card.css') }}">
@endsection
@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.date-type').datetimepicker({
                format: 'YYYY-MM-DD'
            });
        });

        $(document).on("click",".stop-job",function(e) {
            e.preventDefault();
            var $this = $(this);
            var serverID = $this.data("server-id");
            var pID = $this.data("p-id");

            if(serverID == "" || pID == "") {
              toastr['error']("Server id or PID is not setup", 'error');
              return false;
            }

            if(confirm("Are you sure you want to do kill job?")) {
                
                $.ajax({
                    type: 'GET',
                    url: '{{ route('statistics.server-history.close-job') }}',
                    data: {
                      pid : pID,
                      server_id : serverID
                    },
                    dataType:"json"
                }).done(response => {
                    toastr['success'](response.message, 'success');
                });
            }
        });

    </script>
@endsection