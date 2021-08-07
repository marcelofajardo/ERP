@extends('layouts.app')

@section('title', 'Supplier Inventory History')

@section('large_content')
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
                            
                            <div class="col-md-2">
                                <input name="search" type="text" class="form-control" value="{{$request->search??''}}"  placeholder="Search" id="search">
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
                        <th>Product Id</th>
                        <th>Product Name</th>
                        <th>Brand Name</th>
                        @for($i = 0;$i < 7; $i++)

                        <th>{{\Carbon\Carbon::now()->subDays($i)->toDateString()}}</th>
                          @endfor
                      
                      
                    </tr>
                </thead>
                <tbody>
                    @foreach ($allHistory as $key=> $row ) 
                    <tr>
                      
                      <td>{{$row->supplier_name}}</td>
                     <td>{{$row->product_id}}</td>
                      <td>{{$row->product_name}}</td>
                      <td>{{$row->brand_name}}</td>


                       @for($i=0;$i < 7;$i++)



                       @php

                       $in_stock=' - ';

                       foreach($row->dates as $value)
                       {
                          if($value->date===\Carbon\Carbon::now()->subDays($i)->toDateString())
                          {
                             $in_stock=$value->in_stock;
                          }
                       }

                       @endphp

                        <td>{{$in_stock}}</td>
                          @endfor
                     
                    </tr>

                    @endforeach
                    <tr>
                         <td colspan="10">
        {{ $inventory->appends(request()->except("page"))->links() }}
    </td>
                    </tr>
                </tbody>
            </table>
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



</script>

@endsection



