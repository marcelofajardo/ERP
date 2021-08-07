@extends('layouts.app')

@section('title', 'Suppliers List')

@section('styles')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">

@endsection

@section('content')
    <div class="container">
        <h3 align="center">Supplier Brand Count</h3>
        <br />
        <div class="table-responsive">
            <br />
            <div align="right">
                <button type="button" name="add" id="add" class="btn btn-info">Add</button>
            </div>
            <br />
            <div id="alert_message"></div>
            <table id="count_data" class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th width="30%">Supplier</th>
                    <th width="30%">Category</th>
                    <th width="30%">Brand</th>
                    <th width="30%">Count</th>
                    <th width="30%">URL</th>
                    <th width="30%"></th>

                </tr>
                </thead>
            </table>
        </div>
    </div>


@endsection


@section('scripts')
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" language="javascript" >
        $(document).ready(function(){

            fetch_data();

            function fetch_data()
            {
                var dataTable = $('#count_data').DataTable({
                    "processing" : true,
                    "serverSide" : true,
                    "searching": false,
                    "order" : [],
                    "ajax" : {
                        url:"{{ route('supplier.brand.count.get') }}",
                        type:"POST",
                        data:{'_token': '{{ csrf_token() }}'},
                    }
                });
            }

            function update_data(id, column_name, value)
            {

                $.ajax({
                    url:"{{ route('supplier.brand.count.update') }}",
                    method:"POST",
                    data:{'_token': '{{ csrf_token() }}',id:id, column_name:column_name, value:value},
                    success:function(data)
                    {
                        $('#alert_message').html('<div class="alert alert-success">'+data+'</div>');
                        $('#count_data').DataTable().destroy();
                        fetch_data();
                    }
                });
                setInterval(function(){
                    $('#alert_message').html('');
                }, 5000);
            }

            $(document).on('blur', '.update', function(){
                var id = $(this).data("id");
                var column_name = $(this).data("column");
                var value = $(this).val();
                update_data(id, column_name, value);
            });

            $('#add').click(function(){
                var html = '<tr>';
                html += "<td><select class='form-control' id='supplier_id'>@foreach($supplier as $suppliers) <option value='{{ $suppliers->id }}' class='form-control'>{{ $suppliers->supplier }}</option>@endforeach</select></td>" +"";
                html += " <td><select class='form-control' id='category_id'>@foreach($category_parent as $categories) <option value=\"{{ $categories->id }}\" class=\"form-control\">{{ $categories->title }} </option> @if($categories->childs) @foreach($categories->childs as $cat) <option value='{{ $cat->id }}' class='form-control'>-&nbsp;{{ $cat->title }}</option> @endforeach
                                @endif @endforeach @foreach($category_child as $categories) <option value='{{ $categories->id }}' class='form-control'>{{ $categories->title }}</option> @if($categories->childs) @foreach($categories->childs as $cat)  <option value='{{ $cat->id }}' class='form-control'>-&nbsp;{{ $cat->title }}</option> @endforeach  @endif @endforeach </select></td>\n" +
                    "";
                html += " <td><select class='form-control' id='brand_id'>@foreach($brand as $brands) <option value='{{ $brands->id }}' class='form-control'>{{ $brands->name }}</option>@endforeach </select></td>\n" +
                    "";
                html += "<td><input type='number' class='form-control' id='count'></td>";
                html += "<td><input type='text' class='form-control' id='url'></td>";
                html += '<td><button type="button" name="insert" id="insert" class="btn btn-success btn-xs">Insert</button></td>';
                html += '</tr>';
                $('#count_data tbody').prepend(html);
            });

            $(document).on('click', '#insert', function(){
                var supplier_id = $('#supplier_id').val();
                var count = $('#count').val();
                var brand_id = $('#brand_id').val();
                var category_id = $('#category_id').val();
                var url = $('#url').val();
                console.log(brand_id);

                if(brand_id != '' && supplier_id != '' && count != '' && category_id != '')
                {
                    $.ajax({
                        url:"{{ route('supplier.brand.count.save') }}",
                        method:"POST",
                        data:{'_token': '{{ csrf_token() }}', brand_id:brand_id, supplier_id:supplier_id, count:count , category_id:category_id , url:url},
                        success:function(data)
                        {
                            $('#alert_message').html('<div class="alert alert-success">'+data+'</div>');
                            $('#count_data').DataTable().destroy();
                            fetch_data();
                        }
                    });
                    setInterval(function(){
                        $('#alert_message').html('');
                    }, 5000);
                }
                else
                {
                    alert("Both Fields is required");
                }
            });

            $(document).on('click', '.delete', function(){
                var id = $(this).attr("id");
                if(confirm("Are you sure you want to remove this?"))
                {
                    $.ajax({
                        url:"{{ route('supplier.brand.count.delete') }}",
                        method:"POST",
                        data:{'_token': '{{ csrf_token() }}',id:id},
                        success:function(data){
                            $('#alert_message').html('<div class="alert alert-success">'+data+'</div>');
                            $('#count_data').DataTable().destroy();
                            fetch_data();
                        }
                    });
                    setInterval(function(){
                        $('#alert_message').html('');
                    }, 5000);
                }
            });
        });
    </script>
@endsection