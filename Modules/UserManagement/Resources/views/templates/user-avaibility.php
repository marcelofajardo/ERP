<script type="text/x-jsrender" id="template-avaibility">
		<div class="modal-content">
		   <div class="modal-header">
		      <h5 class="modal-title">User Avaibility</h5>
		      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		      <span aria-hidden="true">&times;</span>
		      </button>
		   </div>
		   <div class="modal-body">
           <table class="table table-bordered table-striped" style="table-layout:fixed;">
                <tr>
                    <th>Date and Day</th>
                    <th>Status</th>
                    <th>Time</th>
                    <th>Note</th>
                    <th>Action</th>
                </tr>
                {{props avaibility}}
                <tr>
                <form  method="post">
                    <?php echo csrf_field(); ?>
                    <td>{{:prop.date}}
                        <br>
                        {{:prop.day}}
                        </td>
                    <td>
                        <select class="form-control status-{{:prop.id}}" name="status">
                        <option value="1" {{if prop.status == 1}} selected {{/if}}>Available</option>
                        <option value="0" {{if prop.status == 0}} selected {{/if}}>Not Available</option>
                        </select>
                    </td>
                    <td>{{:prop.from}}-{{:prop.to}}</td>
                    <td>
                    <textarea class="form-control note-{{:prop.id}}" name="note" id="" rows="3">{{:prop.note}}</textarea>
                    </td>
                    <td>
		                <button type="button" data-id="{{:prop.id}}" class="btn btn-xs btn-secondary update-avaibility">Submit</button>
                    </td>
                </tr>
                {{/props}}
                </form>
			</table>
			</div>
		   <div class="modal-footer">
		      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		   </div>
		</div>
</script>