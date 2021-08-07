<script type="text/x-jsrender" id="template-result-block">
	<div class="row page-template-{{:page}}">
		<table class="table table-bordered">
		    <thead>
		      <tr>
		      	<th><input type="checkbox" class="select-all-records"></th>
		      	<th>Id</th>
		        <th>Customer Name</th>
		        <th>Number From</th>
		        <th>Number To</th>
		        <th>Group</th>
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
			        <td>{{:prop.phone}}</td>
			        <td>{{:prop.group_id}}</td>
			        <td>{{:prop.message}}</td>
			        <td>{{:prop.created_at}}</td>
			        <td>
			        	{{props prop.mediaList}}
			        		<img width="75px" heigh="75px" src="{{>prop}}">
			        	{{/props}}
			        </td>
			        <td>
			        	<button type="button" data-id="{{>prop.id}}" class="btn btn-delete-template">
			        		<img width="15px" src="/images/delete.png">
			        	</button>
			        	<button type="button" class="btn btn-xs btn-image load-communication-modal" data-object="customer" data-limit="10" data-id="{{:prop.customer_id}}" data-is_admin="1" data-is_hod_crm="" data-load-type="text_with_incoming_img" data-attached="1" data-all="1" title="Load messages">
			        		<img src="/images/chat.png" alt="" style="cursor: nwse-resize; width: 0px;">
			        	</button>
		        		<button type="button" class="btn btn-image do_not_disturb" data-customer-id="{{>prop.customer_id}}">
				        	{{if prop.do_not_disturb == 1}}
				        		<img src="/images/do-not-disturb.png" style="cursor: nwse-resize;">
				        	{{else}}
					       		<img src="/images/do-disturb.png" style="cursor: nwse-resize;">
				        	{{/if}}
			        	</button>
			        </td>
			      </tr>
			    {{/props}}  
		    </tbody>
		</table>
		{{:pagination}}
	</div>
</script>
<script type="text/x-jsrender" id="template-send-message-report">
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
