<script type="text/x-jsrender" id="template-taskavaibility">
    <form>
		<div class="modal-content">
		   <div class="modal-header">
		      <h5 class="modal-title">Task Hours</h5>
		      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		      <span aria-hidden="true">&times;</span>
		      </button>
		   </div>
		   <div class="modal-body">
                <div class="task_hours_section">
                    <p><strong>Pending Task Hours:</strong> <span>{{:data.total_pending_hours}} Hours</span></><br>
					<p><strong>Today Available Hours:</strong>  <span>{{:data.today_avaibility_hour}} Hours</span></p>
                    <p><strong>Total Available Hours:</strong>  <span>{{:data.total_avaibility_hour}} Hours</span></p>
                </div>
            </div>
		   <div class="modal-footer">
		      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		   </div>
        </div>
   </form>
</script>