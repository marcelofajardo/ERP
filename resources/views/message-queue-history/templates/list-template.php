<script type="text/x-jsrender" id="template-result-block">
	<div class="table-responsive mt-3">
		<table class="table table-bordered">
		    <thead>
		      <tr>
		      	<th width="10%">ID</th>
		        <th width="10%">Number</th>
		        <th width="10%">Counter</th>
		        <th width="15%">Type</th>
		        <th width="15%">User_id</th>
		        <th width="10%">Time</th>
		      </tr>
		    </thead>
		    <tbody>
		    	{{props data}}
			      <tr>
			      	<td>{{:prop.id}}</td>
			      	<td>{{:prop.number}}</td>
			      	<td>{{:prop.counter}}</td>
			      	<td>{{:prop.type}}</td>
			      	<td>{{:prop.user_id}}</td>
			      	<td>{{:prop.time}}</td>
			      </tr>
			    {{/props}}  
		    </tbody>
		</table>
	</div>
	{{:pagination}}
</script>


<script type="text/x-jsrender" id="template-load-data">
	<div class="modal-content">
	   <div class="modal-header">
	      
	      <h5 class="modal-title"></h5>
	      
	      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	      <span aria-hidden="true">&times;</span>
	      </button>
	   </div>
	   <div class="modal-body">
			{{:data}}
		</div>
	</div>			
</script>