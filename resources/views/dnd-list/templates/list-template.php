<script type="text/x-jsrender" id="template-result-block">
	<div class="table-responsive mt-3">
		<table class="table table-bordered">
		    <thead>
		      <tr>
		      	<th><input type="checkbox" class="choose-all">&nbsp;ID</th>
				<th>Name</th>
				<th>Phone</th>
				<th>Whatsapp number</th>
		      </tr>
		    </thead>
		    <tbody>
		    	{{props data}}
			      <tr>
			      	<td><input type="checkbox" class="select-customer" name="customer_id[]" value="{{:prop.id}}">&nbsp;{{:prop.id}}</td>
			      	<td>{{:prop.name}}</td>
			      	<td>{{:prop.phone}}</td>
			      	<td>{{:prop.whatsapp_number}}</td>
			      </tr>
			    {{/props}}
		    </tbody>
		</table>
		{{:pagination}}
	</div>
</script>