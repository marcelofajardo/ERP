@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css">
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.7/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
@endsection
@section('content')

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Sku Format</h2>
            </div>
            <div class="pull-right">
                <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#skuCreateModal">+</a>
            </div>
        </div>
    </div>
   <!--  <div class="row input-daterange">
        <div class="col-md-4">
            <input type="text" name="from_date" id="from_date" class="form-control" placeholder="From Date" readonly />
        </div>
        <div class="col-md-4">
            <input type="text" name="to_date" id="to_date" class="form-control" placeholder="To Date" readonly />
        </div>
        <div class="col-md-4">
            <button type="button" name="filter" id="filter" class="btn btn-primary">Filter</button>
            <button type="button" name="refresh" id="refresh" class="btn btn-default">Refresh</button>
        </div>
    </div> -->

    <table class="table table-bordered" id="sku-table">
        <thead>
        <tr>
            <th>Category</th>
            <th>Brand</th>
            <th>SKU Example</th>
            <th>SKU Format</th>
            <th>Actions</th>
        </tr>
        </thead>
    </table>
@include('sku-format.edit')
<div id="sku-format-history" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Sku History <span id="sku-format-history-text"> </span></h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered table-striped">
                            <tr>
                                <th width="10%">ID</th>
                                <th width="30%">Old Value</th>
                                <th width="30%">New Value</th>
                                <th width="20%">Submitted By</th>
                                <th width="10%">Created By</th>
                            </tr>
                            <tbody class="sku-format-history-records">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endsection

    @section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.min.js"></script>
    <script src="//cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <div id="skuCreateModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <form action="{{ url('sku-format') }}" method="POST">
                    @csrf

                    <div class="modal-header">
                        <h4 class="modal-title">Create SKU Format</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <strong>SKU Example:</strong>
                            <input type="text" name="sku_examples" class="form-control" value="{{ old('sku_examples') }}">

                            @if ($errors->has('sku_examples'))
                                <div class="alert alert-danger">{{$errors->first('sku_examples')}}</div>
                            @endif
                        </div>
                        <div class="form-group">
                            <strong>Sku Format:</strong>
                            <input type="text" name="sku_format" class="form-control" value="{{ old('sku_format') }}">

                            @if ($errors->has('sku_format'))
                                <div class="alert alert-danger">{{$errors->first('sku_format')}}</div>
                            @endif
                        </div>
                        <div class="form-group users">
                            <select class="form-control" name="category_id">
                                @foreach($categories as $category)
                                    <option class="form-control" value="{{ $category->id }}">{{ $category->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group users">
                            <select class="form-control" name="brand_id">
                                @foreach($brands as $brand)
                                    <option class="form-control" value="{{ $brand->id }}">{{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-secondary">Save</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
    <script>
        $(document).ready(function(){
            $('.input-daterange').datepicker({
                todayBtn:'linked',
                format:'yyyy-mm-dd',
                autoclose:true
            });

            load_data();

            function load_data(from_date = '', to_date = '',id = '')
            {
                $('#sku-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url:'{{ route("skuFormat.datatable") }}',
                        data:{from_date:from_date, to_date:to_date , id:id}
                    },
                    columns: [
                        {
                            data:'category',
                            name:'category'
                        },
                        {
                            data:'brand',
                            name:'brand'
                        },
                        {
                            data:'sku_examples',
                            name:'sku_examples'
                        },
                        {
                            data:'sku_format',
                            name:'sku_format'
                        },
                        {
                            data:'actions',
                            name:'actions'
                        }
                    ]
                });
            }

            $('#filter').click(function(){
                var from_date = $('#from_date').val();
                var to_date = $('#to_date').val();
                if(from_date != '' &&  to_date != '')
                {
                    $('#sku-table').DataTable().destroy();
                    load_data(from_date, to_date);
                }
                else
                {
                    alert('Both Date is required');
                }
            });

            $('#refresh').click(function(){
                $('#from_date').val('');
                $('#to_date').val('');
                $('#sku-table').DataTable().destroy();
                load_data();
            });

            $('#sku-table').on('draw.dt', function () {

            });

        });

        function editSKU(id){
            $("#skuEditModal"+id).modal();
        }

        function showHistory(id){
            $.ajax({
                type: "get",
                url: "sku-format/history",
                data: {id: id}
            }).done(function (response) {
                var m = $("#sku-format-history");
                if(response.code == 200) {
                    var html = "";
                    $.each(response.data,function(k, v) {
                        html += "<tr>"; 
                            html += "<td>"+v.id+"</td>"; 
                            html += "<td>"+v.old_sku_format+"</td>"; 
                            html += "<td>"+v.sku_format+"</td>"; 
                            html += "<td>"+v.user_name+"</td>"; 
                            html += "<td>"+v.created_at+"</td>"; 
                        html += "</tr>";
                    });
                    m.find(".sku-format-history-records").html(html);
                    m.modal("show");
                }
            }).fail(function (response) {
               alert('failed');
            });
            //$("#skuEditModal"+id).modal();
        }

        function updateEdit(id){
             
             var id = id;
             var category_id = $('#category'+id).val();
             var brand_id = $('#brand'+id).val();
             var sku_examples = $('#sku_example'+id).val();
             var sku_format = $('#sku_format'+id).val();
            
            $.ajax({
                type: "POST",
                url: "{{ route('sku.update') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                     id: id,
                     category_id: category_id,
                     brand_id: brand_id,
                     sku_examples: sku_examples,
                     sku_format: sku_format,
                },
                
            }).done(function (response) {
               
               alert('SKU Updated')
             
            }).fail(function (response) {
               alert('failed');
            });
            
        }

    
     $(document).ready(function() {
       $(".select-multiple").multiselect();
       $(".select-multiple2").select2();
         });

    </script>
@endsection
