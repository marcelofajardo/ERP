
    <form>
    @csrf

    <input type="hidden" name="user_id" value="{{$user_id}}">
    <input type="hidden" name="date" value="{{$date}}">
    <input type="hidden" name="isTaskWise" value="{{isset($isTaskWise) ? $isTaskWise : false}}">

    <div class="modal-header">
        <h4 class="modal-title"></h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
    </div>
    <div class="modal-body">
    <div>
        <table class="table table-bordered" >
        <tr>
          <th style="width:12%">Date & time</th>
          <th style="width:8%">Time tracked</th>
          <th style="width:8%">Time Approved</th>
          <th style="width:8%">Time Pending</th>
          <th style="width:18%">Task</th>
          <th style="width:7%">Status</th>
          <th style="width:10%">Efficiency</th>
          <th style="width:10%"></th>
          <th style="width:6%">Status</th>
          <th style="width:2%" class="text-center">Action <input type="checkbox" id="checkAll"> </th>
        </tr>
          @foreach ($activityrecords as $record)
            <tr >
            <td>{{ $record->OnDate }} {{$record->onHour}}:00:00</td>
              <td>{{ number_format($record->total_tracked / 60,2,".",",") }}</td>
              <td>{{ number_format($record->totalApproved / 60,2,".",",") }}</td>
              <td>{{ number_format($record->totalPending / 60,2,".",",") }}</td>
              <td>
                <?php $listOFtask = []; ?>
                @foreach ($record->activities as $a)
                    @if(!empty($a->taskSubject)) 
                      <?php 
                        $listOFtask[] = $a->taskSubject;
                      ?>
                    @endif
                @endforeach
                @foreach (array_unique($listOFtask) as $l)
                  @if(!empty($l)) 
                      <?php 
                        list($type, $id) = explode("-",$l);
                        $task = \App\DeveloperTask::where('id', $id)->first();
                        $estMin = $task->estimate_minutes ?? 0 ;

                        $time_history = \App\DeveloperTaskHistory::where('developer_task_id',$id)->where('attribute','estimation_minute')->where('is_approved',1)->first();
                   	?>
                      <a style="color:#333333;" class="{{ empty($time_history) ? 'not_approve' : ''  }} {{ ( !auth()->user()->isAdmin()  && number_format($record->total_tracked / 60,2,".",",") > $estMin  ) ? 'gone_estimated' : '' }} " data-id="{{$id}}"  href="javascript:;">{{$a->taskSubject}}</a><br>
                    @endif

                @endforeach
              </td>
			  <td>
				  <?php $listOFtask = []; 
                    

                  ?>
					@foreach ($record->activities as $a)
						@if(!empty($a->taskSubject)) 
						<?php 
							$listOFtask[] = $a->taskSubject;
						?>
						@endif
					@endforeach
					@foreach (array_unique($listOFtask) as $l)
					@if(!empty($l)) 
						<?php 
							list($type, $id) = explode("-",$l);
						?>
						{{$a->taskStatus}}<br>
						@endif

					@endforeach


			  </td>
              <td>
              <div class="form-group" style="margin:0px;">
                   @if(isset($member))
                   <?php
                    $eficiency = \App\HubstaffTaskEfficiency::where('user_id',$member->user_id)->where('date',$record->OnDate)->where('time',$record->onHour)->first();
                    $user_input = null;
                    $admin_input = null;
                    if($eficiency) {
                      $user_input = $eficiency->user_input;
                      $admin_input = $eficiency->admin_input;
                    }
                    ?>
                     <p style="margin:0px;"> <strong>Admin : {{$admin_input}}</strong></p>
                     <p style="margin:0px;"> <strong>User : {{$user_input}}</strong></p>
					
                    @endif
                </div>
              </td>
			  <td>
				@if(isset($member))
                   <?php
                    $eficiency = \App\HubstaffTaskEfficiency::where('user_id',$member->user_id)->where('date',$record->OnDate)->where('time',$record->onHour)->first();
                    $user_input = null;
                    $admin_input = null;
                    if($eficiency) {
                      $user_input = $eficiency->user_input;
                      $admin_input = $eficiency->admin_input;
                    }
                    ?>
					@if(Auth::user()->id == $member->user_id) 
						<select name="efficiency" class="task_efficiency form-control"  data-type="user" data-date="{{ $record->OnDate }}" data-hour="{{$record->onHour}}" data-user_id="{{$member->user_id}}">
							<option value="">Select One</option>
							<option value="Excellent" {{$user_input == 'Excellent' ? 'selected' : ''}}>Excellent</option>
							<option value="Good" {{$user_input == 'Good' ? 'selected' : ''}}>Good</option>
							<option value="Average" {{$user_input == 'Average' ? 'selected' : ''}}>Average </option>
							<option value="Poor" {{$user_input == 'Poor' ? 'selected' : ''}}>Poor</option>
						</select>
						@endif
						@if(Auth::user()->isAdmin()) 
						<select name="efficiency" class="task_efficiency form-control"  data-type="admin" data-date="{{ $record->OnDate }}" data-hour="{{$record->onHour}}" data-user_id="{{$member->user_id}}">
							<option value="">Select One</option>
							<option value="Excellent" {{$admin_input == 'Excellent' ? 'selected' : ''}}>Excellent</option>
							<option value="Good" {{$admin_input == 'Good' ? 'selected' : ''}}>Good</option>
							<option value="Average" {{$admin_input == 'Average' ? 'selected' : ''}}>Average </option>
							<option value="Poor" {{$admin_input == 'Poor' ? 'selected' : ''}}>Poor</option>
						</select>
						@endif
                    @endif
			  </td>
				<td> 
					@if ( $record->status == 1 )
						Approved
					@elseif ( $record->status == 2 )
						Pending
					@else
						New
					@endif
				</td>
              <td>
              &nbsp;<input type="checkbox" name="sample" {{$record->sample && $record->status != 2 ? 'checked' : ''}}  data-id="{{ $record->OnDate }}{{$record->onHour}}" class="selectall"/>
                <a data-toggle="collapse" href="#collapse_{{ $record->OnDate }}{{$record->onHour}}"><img style="height:15px;" src="/images/forward.png"></a>
              </td>
            </tr>
            <tr style="width:100%;" id="collapse_{{ $record->OnDate }}{{$record->onHour}}" class="panel-collapse collapse">
            <td colspan="6" style="padding:0px;">
              <table style="table-layout:fixed;" class="table table-bordered">
              @foreach ($record->activities as $a)
                <tr>
                <td style="width:18%">{{ $a->starts_at}}</td>
                  <td style="width:15%">{{ number_format($a->tracked / 60,2,".",",") }}@if($a->is_manual) (Manual time) @endif</td>
                  <td style="width:15%">{{ number_format($a->totalApproved / 60,2,".",",") }}</td>
                  <td style="width:25%">{{ $a->taskSubject}}</td>
                  <td style="width:20%"></td>
                  <td style="width:7%">
                    <input type="checkbox" class="{{ $record->OnDate }}{{$record->onHour}}" value="{{$a->id}}" name="activities[]" {{$a->status && $record->status != 2 ? 'checked' : ''}}>
                  </td>
                </tr>
              @endforeach
              </table>
            </td>
            </tr>
          @endforeach
      </table>
    </div>
    <input type="hidden" id="hidden-forword-to" name="forworded_person">
    @if($isAdmin)
    <!-- <div class="form-group">
        <label for="forword_to">Forword to user</label>
        <select name="forword_to_user" id="" data-person="user" class="form-control select-forword-to">
          <option value="">Select</option>
          @foreach($users as $user)
          <option value="{{$user->id}}">{{$user->name}}</option>
          @endforeach
        </select>
    </div> -->
    @if(count($teamLeaders) > 0)
      <div class="form-group">
          <label for="forword_to">Forword to team leader</label>
          <select name="forword_to_team_leader" id="" data-person="team_lead" class="form-control select-forword-to">
            <option value="">Select</option>
            @foreach($teamLeaders as $ld)
            <option value="{{$ld->id}}">{{$ld->name}}</option>
            @endforeach
          </select>
      </div>
      @endif
    @endif
    @if($isTeamLeader)
      <div class="form-group">
          <label for="forword_to">Forword to admin</label>
          <select name="forword_to_admin" id="" data-person="admin" class="form-control select-forword-to">
            <option value="">Select</option>
            @foreach($admins as $admin)
            <option value="{{$admin->id}}">{{$admin->name}}</option>
            @endforeach
          </select>
      </div>
    @endif
    @if($taskOwner)
      <div class="form-group">
          <label for="forword_to">Forword to admin</label>
          <select name="forword_to_admin" id="" data-person="admin" class="form-control select-forword-to">
            <option value="">Select</option>
            @foreach($admins as $admin)
            <option value="{{$admin->id}}">{{$admin->name}}</option>
            @endforeach
          </select>
      </div>
      @if(count($teamLeaders) > 0)
      <div class="form-group">
          <label for="forword_to">Forword to team leader</label>
          <select name="forword_to_team_leader" id="" data-person="team_lead" class="form-control select-forword-to">
            <option value="">Select</option>
            @foreach($teamLeaders as $ld)
            <option value="{{$ld->id}}">{{$ld->name}}</option>
            @endforeach
          </select>
      </div>
      @endif
    @endif
   
    @if($hubActivitySummery)
    <div class="form-group">
        <label for="">Previous remarks</label>
        <textarea class="form-control" cols="30" rows="5" name="previous_remarks" placeholder="Rejection note...">@if($hubActivitySummery && isset($hubActivitySummery->rejection_note)){{$hubActivitySummery->rejection_note}}@endif</textarea>
    </div>
    @endif
    <div class="form-group">
        <label for="">New remarks</label>
        <textarea class="form-control" name="rejection_note" id="rejection_note" cols="30" rows="5" placeholder="Rejection note..."></textarea>
    </div>
     <div class="gone_est_notes form-group"></div>
    </div>
    <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    @if($isAdmin)
        <button type="submit" class="btn btn-secondary final-submit-record" data-status="1">Approve</button>
        <button type="submit" class="btn btn-secondary final-submit-record" data-status="2">Pending</button>
        @if(count($teamLeaders) > 0)
            <button type="submit" class="btn btn-danger submit-record">Forward</button>
        @endif
    @else
        <button type="submit" class="btn btn-danger submit-record">Forward</button> 
        <button type="button" class="btn btn-secondary submit-notes">Submit notes</button> 
    @endif
    </div>
</form>

<script type="text/javascript">
    $('#date_of_payment').datetimepicker({
      format: 'YYYY-MM-DD'
    });

    $("#checkAll").click(function(){
        $('input:checkbox').not(this).prop('checked', this.checked);
    });

    var array = [];
    $(".gone_estimated").each(function() {
        var id = $(this).data('id');
        if(jQuery.inArray(id, array) != -1) {
        } else {
            array.push(id);
            var $input = $("  <label> Note for "+$(this).data('id')+" </label><div class='form-group'><input name='notes_field["+id+"]' data-id='"+id+"' class='form-control notes-input' placeholder='Write note..' type='text' required></div>");
            $('.gone_est_notes').append($input);
        } 
    });

    /*if ($('.not_approve').length > 0) {
          $('.submit-record').remove();
          $('.submit-notes').toggleClass('hide');
    }*/
    
    $(document).on("click",".submit-notes",function() {

        var vali = false;
        $('.notes-input').each(function() {
            if($(this).val() == ''){
                toastr['error']('invalid notes', 'error');
                vali = true; 
            }
        });

        if( vali == true ){
            return false;
        }

        var taskid = $(this).data("id");
        var form = $(this).closest("form");
        var data = form.serialize();
        
        $.ajax({
              type: 'GET',
              url: '/hubstaff-activities/activities/save-notes',
              data: data
          }).done(response => {
            $('#records-modal').modal('hide');
              console.log(response);
          }).fail(function (response) {
              
          });
    });

    $(document).on("click",".show-task-history",function() {
        var taskid = $(this).data("id");
        $.ajax({
              type: 'GET',
              url: '/hubstaff-activities/activities/task-history',
              data: {
                  task_id: taskid
              }
          }).done(response => {
              console.log(response);
          }).fail(function (response) {
              
          });
    });

</script>
