<script type="text/x-jsrender" id="template-add-time">
    <form  method="post">
    <?php echo csrf_field(); ?>
		<div class="modal-content">
		   <div class="modal-header">
		      <h5 class="modal-title">{{if data.id}} Edd Avaibility {{else}}Add Avaibility{{/if}}</h5>
		      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		      <span aria-hidden="true">&times;</span>
		      </button>
		   </div>
		   <div class="modal-body">
           
           <input type="text" id="time_user_id" name="user_id" class="form-control" 
           value="{{if data.user_id}}{{:data.user_id}}{{/if}}">

           <div class="form-group">
                  <strong>Day</strong>
                  <select class="form-control" name="day">

                    <option value='monday' {{if data.weekday == 'monday'}} selected {{/if}}>Monday</option>

                    <option value='tuesday' {{if data.weekday == 'tuesday'}} selected {{/if}}>Tuesday</option>

                    <option value='wednesday' {{if data.weekday == 'wednesday'}} selected {{/if}}>Wednesday</option>

                    <option value='thursday' {{if data.weekday == 'thursday'}} selected {{/if}}>Thursday</option>

                    <option value='friday' {{if data.weekday == 'friday'}} selected {{/if}}>Friday</option>

                    <option value='saturday' {{if data.weekday == 'saturday'}} selected {{/if}}>Saturday</option>

                 </select>
			</div>
         <div class="form-group">
            <strong>Available Day (eg. 6) <small> From Week </small> </strong>
            <input type="number" step=0.1 class="form-control" name="availableDay" 
            value={{if data.day}}{{:data.day }}{{/if}}>
			</div>
         <div class="form-group">
            <strong>Available Hour (eg. 2) <small> From Day </small> </strong>
            <input type="number" step=0.1 class="form-control" name="availableHour" 
            value={{if data.minute}}{{:data.minute}}{{/if}}>
			</div>
            <div class="form-group">
                  <strong>Available From (eg. 10) <small>24 Hours format</small> </strong>
                  <input type="number" step=0.1 class="form-control" name="from" value={{if data.from}}{{:data.from}}{{/if}}>
			</div>
            <div class="form-group">
                  <strong>Available To (eg. 18) <small>24 Hours format</small></strong>
                  <input type="number" step=0.1 class="form-control" name="to" value={{if data.to}}{{:data.to}}{{/if}}>
			</div>
            <div class="form-group">
                  <strong>Status</strong>
                  <select class="form-control" name="status">
                    <option value="1" {{if data.status == 1}} selected {{/if}}>Available</option>
                    <option value="0" {{if data.status == 0}} selected {{/if}}>Not Available</option>
                 </select>
			</div>
            <div class="form-group">
                  <strong>Note</strong>
                  <textarea class="form-control" name="note" id="" rows="3">{{if data.note}}{{:data.note}}{{/if}}</textarea>
			</div>
		   <div class="modal-footer">
		      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		      <button type="button"  class="btn btn-secondary submit-time" data-dismiss="modal">Submit</button>
		   </div>
		</div>
	</form> 
</script>