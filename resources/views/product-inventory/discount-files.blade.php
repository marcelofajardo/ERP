@extends('layouts.app')

@section('title', 'Supplier Inventory History')

@section('large_content')
    <div id="myDiv">
        <img id="loading-image" src="/images/pre-loader.gif" style="display:none;"/>
    </div>
    <div class="row">
        <div class="col-md-12">
            <h2 class="page-heading">Supplier Discount Files </h2>
        </div>

        <div class="col-12">
          <div class="pull-left"></div>

          <div class="pull-right">
            <div class="form-group">
              &nbsp;
            </div>
          </div>
        </div>
    </div>

    @include('partials.flash_messages')

<form method="post" action="{{route('supplier.discount.files.post')}}" enctype="multipart/form-data" class="excel_form">
@csrf
     <div class="form-group">
        <div class="row"> 
            <div class="col-md-3">
                <select class="form-control select-multiple" id="supplier-select" tabindex="-1" aria-hidden="true" name="supplier" >
                    <option value="">Select Supplier</option>
                    @foreach($suppliers as $supplier)
                        @if(isset($request->supplier) && $supplier->id==$request->supplier)
                            <option value="{{$supplier->id}}" selected="selected">{{$supplier->supplier}}</option>
                        @else
                            <option value="{{$supplier->id}}">{{$supplier->supplier}}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <input class="form-control" type="file" id="file" name="excel" placeholder="Select File..." accept=".xlsx, .xls"/>
            </div>
            <div class="col-md-3 d-flex justify-content-between">
                <button type="submit" class="btn btn-secondary" >Import</button>
            </div> 

            <div id="loading-image_" style="position: fixed;left: 0px;top: 0px;width: 100%;height: 100%;z-index: 9999;background: url('/images/pre-loader.gif') 50% 50% no-repeat;display:none;">
            </div>
        </div>
    </div>
</form>

    <div class="row">
        <div class="col-md-12">
          
            <table id="table" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Supplier Name</th> 
                        <th>Brand</th>
                        <th>Gender </th>
                        <th>Category</th>
                        <th>Generice price</th>
                        <th>Exceptions</th>
                        <th>Condition from retail</th>
                        <th>Retail condition for exceptions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rows as $key=> $row ) 
                    <tr>
                       <td>{{$row->supplier->supplier}}</td>
                       <td>{{$row->brand->name}}</td> 
                       <td>{{$row->gender}}</td> 
                       <td>{{$row->category}}</td> 
                       <td>{{$row->generic_price ?? '-'}}</td> 
                       <td>{{$row->exceptions ?? '-'}}</td> 
                       <td>{{$row->condition_from_retail ?? '-'}}</td> 
                       <td>{{$row->condition_from_retail_exceptions ?? '-'}}</td> 
                    </tr>
                    @endforeach
                    <tr>
                         <td colspan="11">
                            {{$rows->links()}}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

 <div id="brand-history-model" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
      <div class="modal-content">
          <div class="modal-header">
              <h4 class="modal-title">Brand History</h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
            <div class="modal-body">
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
      </div>
  </div>
</div>


@endsection

@section('scripts')

<script type="text/javascript">
 
    
    $('.excel_form').submit(function(e){
        if($('#supplier-select').val() == ''){
            toastr['error']('Please select supplier');
            e.preventDefault();
        }else if($('#file').val() == ''){
            toastr['error']('Please upload file');
            e.preventDefault();
        }else{
            $("#loading-image_").css('display', 'block');
        }
    });
 

</script>

@endsection



