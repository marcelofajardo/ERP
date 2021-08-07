@extends('layouts.app')

@section('title', 'Supplier Inventory History')

@section('large_content')
    <div id="myDiv">
        <img id="loading-image" src="/images/pre-loader.gif" style="display:none;"/>
    </div>
    <div class="row">
        <div class="col-md-12">
            <h2 class="page-heading">Supplier Inventory History ({{$total_rows}})</h2>
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



<form method="get" action="{{route('supplier.product.history')}}">

     <div class="form-group">
                        <div class="row">
                            
                            
                            

                            <div class="col-md-3">
                               <select class="form-control select-multiple" id="supplier-select" tabindex="-1" aria-hidden="true" name="supplier" onchange="//showStores(this)">
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

                            
                            <div class="col-md-1 d-flex justify-content-between">
                               <button type="submit" class="btn btn-image" ><img src="/images/filter.png"></button><button type="button" onclick="resetForm(this)" class="btn btn-image" id=""><img src="/images/resend2.png"></button>  
                            </div>
                          <!--   <div class="col-md-1">
                                  
                            </div> -->
                        </div>

                    </div>

</form>


    <div class="row">
        <div class="col-md-12">
          
            <table id="table" class="table table-striped table-bordered">
                <thead>
                
                      
         

                    <tr>
                       
                        <th>Supplier Name</th> 
                      
                        <th>Products</th>

                        <th> Brands </th>
                        <?php foreach($columnData as $e) { ?>
                            <th> <?php echo $e; ?> </th>
                        <?php } ?> 
                        <th>Summary</th>
                          
                      
                      
                    </tr>
                </thead>
                <tbody>
                    @foreach ($allHistory as $key=> $row ) 
                    <tr>
                       <td>{{$row['supplier_name']}}</td>
                       <td>{{$row['products']}}</td>
                       <td><a href="javascript:;" data-supplier-id="{{ $row['supplier_id'] }}" class="brand-result-page">{{$row['brands']}}</a></td>
                       <?php foreach($columnData as $e) { ?>
                           <td> <?php echo isset($row['dates'][$e]) ? $row['dates'][$e] : 0; ?> </td>
                       <?php } ?> 
                       <td class="showSummary"><a target="_blank" href="{{route('supplier.product.summary',$row['supplier_id'])}}">Details</td>
                    </tr>

                    @endforeach
                    <tr>
                         <td colspan="11">
        {{ $inventory->appends(request()->except("page"))->links() }}
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
 

    function resetForm(selector)
        {
            
           $(selector).closest('form').find('input,select').val('');

           $(selector).closest('form').submit();
        }


     $(document).on("click",".brand-result-page",function() {
          var $this = $(this);
          $.ajax({
              url:'/product/history/by/supplier-brand',
              data:{
                supplier_id : $this.data("supplier-id")
              },
              beforeSend: function () {
                  $("#loading-image").show();
              },
              success:function(result){
                $("#loading-image").hide();
                var brandModel = $("#brand-history-model");
                    brandModel.find(".modal-body").html(result);
                    brandModel.modal("show");
              },
              error:function(exx){
                $("#loading-image").hide();
              }
          });
     });

</script>

@endsection



