<script type="text/x-jsrender" id="template-userdetails">
    <form>
		<div class="modal-content">
		   <div class="modal-header">
		      <h5 class="modal-title">User Details</h5>
		      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		      <span aria-hidden="true">&times;</span>
		      </button>
		   </div>
		   <div class="modal-body">
                <div class="task_hours_section">
                <p><strong>User Id:</strong> <span>{{:data.id}} </span></><br>
                    <p><strong>Name:</strong> <span>{{:data.name}} </span></><br>
					<p><strong>Email:</strong>  <span>{{:data.email}} </span></p>
                    <p><strong>Phone:</strong>  <span>{{:data.phone}} </span></p>
                    <p><strong>Whatsapp Number:</strong><span>{{:data.whatsapp_number}} </span></p>
                    <p><strong>Status: </strong><span>{{if data.is_active == 1}} Active {{/if}} {{if data.is_active == 0}} In active {{/if}}</span></p>
                    <p><strong>Hourly Rate :</strong><span>{{if data.hourly_rate}} {{:data.hourly_rate}} {{/if}} {{if !data.hourly_rate}} 0 {{/if}} </span></p>
                    
                </div>
            </div>
		   <div class="modal-footer">
		      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		   </div>
        </div>
   </form>
</script>