@extends('layouts.app')
@section('favicon' , 'task.png')
@section('title', 'List | Email Templates')

@section('content')
<link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
<div class="row">
	<div class="col-lg-12 margin-tb">
	    <h2 class="page-heading">List | Email Templates</h2>
	</div>
	<div class="col-lg-12" style="margin-bottom: 20px;">
		<a href="{{ route('') }}">
			<button class="btn btn-secondary">
				Create
			</button>
		</a>
	</div>
</div>
<div class="row">
	<div class="col-lg-12 margin-tb">
		<table class="table table-bordered data-table">
	        <thead>
	            <tr>
	                <th class="n-s">No</th>
	                <th class="n-s">key</th>
	                <th class="n-s">subject</th>
	                <th class="n-s">template</th>
	                <th>created_at</th>
	                <th width="100px">Action</th>
	            </tr>
	        </thead>
	        <tbody>
	        </tbody>
	    </table>	    
	</div>
</div>
@endsection
@section('scripts')
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript">
  	$(function () {
  		$('.data-table thead th').each( function () {
  			if($(this).hasClass("n-s")){
		        var title = $(this).text();
		        $(this).html( '<input type="text" placeholder="Search '+title+'" />' );
  			}
	    });
    	var table = $('.data-table').DataTable({
        	processing: true,
        	serverSide: true,
        	ajax: "{{ route('email-templates.index') }}",
        	columns: [
            	{data: 'DT_RowIndex', name: 'DT_RowIndex'},
            	{data: 'key', name: 'key'},
            	{data: 'subject', name: 'subject'},
            	{data: 'template', name: 'template'},
            	{data: 'created_at', name: 'created_at'},
            	{data: 'action', name: 'action', orderable: false, searchable: false},
        	]
    	});

    	// Apply the search
	    table.columns().every( function () {
	        var that = this;
	        $( 'input', this.header() ).on( 'keyup change clear', function () {
	            if ( that.search() !== this.value ) {
	                that
	                    .search( this.value )
	                    .draw();
	            }
	        } );
	    } );
  	});
</script>
@endsection

