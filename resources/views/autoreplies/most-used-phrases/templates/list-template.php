<script type="text/x-jsrender" id="template-result-block">
	<div class="row">
		<table class="table table-bordered">
		    <thead>
		      <tr>
		      	<th width="2%">Id</th>
		        <th width="38%">Pharse</th>
		        <th width="30%">Delete At</th>
		        <th width="30%">Delete By</th>
		      </tr>
		    </thead>
		    <tbody>
		    	{{props data}}
			      <tr>
			      	<td>{{:prop.id}}</td>
			      	<td>{{:prop.phrase}}</td>
			        <td>{{:prop.deleted_at}}</td>
			        <td>{{:prop.user_name}}</td>
			      </tr>
			    {{/props}}  
		    </tbody>
		</table>
		{{:pagination}}
	</div>
</script>
