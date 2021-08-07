<script type="text/x-jsrender" id="template-result-block">
	<div class="row page-template-{{:page}}">
		<table class="table table-bordered">
		    <thead>
		      <tr>
		      	<th><input type="checkbox" class="select-all-records"></th>
		      	<th>Id</th>
		        <th>Customer Name</th>
		        <th>Number From</th>
		        <th>Lead Group</th>
		        <th>Message</th>
		        <th>Created At</th>
		        <th>Attached</th>
		        <th>Action</th>
		      </tr>
		    </thead>
		    <tbody>
		    	{{props data}}
			      <tr>
			      	<td><input class="select-id-input" type="checkbox" name="ids[]" value="{{:prop.id}}"></td>
			      	<td>{{:prop.id}}</td>
			      	<td>{{:prop.customer_name}}</td>
			        <td>{{:prop.whatsapp_number}}</td>
			        <td>{{:prop.lead_id}}</td>
			        <td class="chat_short_message"><span>{{:prop.short_message}}</span></td>
			        <td class="chat_long_message" style="display: none;"><span >{{:prop.long_message}}</span></td>
			        <td>{{:prop.created_at}}</td>
			        <td>
			        	{{props prop.media_url}}
			        		<img width="38px" heigh="75px" src="{{>prop}}">
			        	{{/props}}
			        </td>
			        <td><button type="button" data-id="{{>prop.id}}" data-chat_ids="{{:prop.chat_id}}" class="btn btn-delete-template-111 delete-lead-chat-messages"><img width="15px" src="/images/delete.png"></button></td>
			      </tr>
			    {{/props}}  
		    </tbody>
		</table>
		{{:pagination}}
	</div>
</script>
<script type="text/x-jsrender" id="template-send-lead-report">
	<table class="table table-bordered">
	    <thead>
	      <tr>
	      	<th>Date</th>
	        <th>Group ID</th>
	        <th>Total sent</th>
	      </tr>
	    </thead>
	    <tbody>
    		{{props data}}
		      <tr>
		      	<td>{{:prop.created_at}}</td>
		      	<td>{{:prop.group_id}}</td>
		        <td>{{:prop.total_sent}}</td>
		      </tr>
	       {{/props}}
	       <tr colspan="2">
	       	   <td></td>
	       	   <td><b>Total</b></td>
	       	   <td>{{:total}}</td>
		   </tr>
	    </tbody>
	</table>
</script>
