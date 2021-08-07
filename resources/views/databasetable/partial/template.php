<script type="text/x-jsrender" id="template-list-state-process-list">
	<table class="table table-bordered">
	    <thead>
	      <tr>
	      	<th>Id</th>
	        <th>User</th>
	        <th>Host</th>
	        <th>db</th>
	        <th>Command</th>
	        <th>Time</th>
	        <th>State</th>
	        <th>Info</th>
	        <th>Progress</th>
	        <th>Action</th>
	      </tr>
	    </thead>
	    <tbody>
    		{{props records}}
		      <tr>
		      	<td>{{:prop.Id}}</td>
		      	<td>{{:prop.User}}</td>
		        <td>{{:prop.Host}}</td>
		        <td>{{:prop.db}}</td>
		        <td>{{:prop.Command}}</td>
		        <td>{{:prop.Time}}</td>
		        <td>{{:prop.State}}</td>
		        <td>{{:prop.Info}}</td>
		        <td>{{:prop.Progress}}</td>
		        <td><a class="kill-process" href="javascript:;" data-id="{{:prop.Id}}">KILL</a></td>
		      </tr>
	       {{/props}}
	    </tbody>
	</table>
</script>