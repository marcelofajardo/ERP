@extends('layouts.app')

@section('favicon' , 'manualimageupload.png')
@section('title', 'Manual Image Upload - ERP Sololuxury')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="">
                <h2 class="page-heading">Manual Image Upload</h2>
                 
                <form  method="GET" class="form-inline align-items-start">
                    <div class="form-group mr-3 mb-3">
                        <input name="term" type="text" class="form-control" id="product-search"
                               value="{{request()->get('term')}}"
                               placeholder="sku,brand,category,status,stage">
                    </div>
                    <div class="form-group mr-3 mb-3">
                      {!! $category_selection !!}
                    </div>

                    <div class="form-group mr-3">
                      @php $brands = \App\Brand::getAll(); @endphp
                      <select class="form-control select-multiple2" name="brand">
                            <option>Select brand..</option>
                            @foreach ($brands as $key => $name)
                                <option value="{{ $key }}" {{ request()->get('brand') == $key ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                      </select>
                    </div>

                    <div class="form-group mr-3">
                      @php $colors = new \App\Colors(); @endphp
                      <select class="form-control select-multiple2" name="color">
                            <option>Select color..</option>
                            @foreach ($colors->all() as $key => $col)
                                <option value="{{ $key }}" {{ request()->get('color') == $key ? 'selected' : '' }}>{{ $col }}</option>
                            @endforeach
                      </select>
                    </div>

                    <div class="form-group mr-3">
                      <select class="form-control select-multiple2" name="supplier">
                        <option>Select supplier..</option>
                        @php 
                            $suppliers = \Illuminate\Support\Facades\DB::select('
                                    SELECT id, supplier
                                    FROM suppliers

                                    INNER JOIN (
                                        SELECT supplier_id FROM product_suppliers GROUP BY supplier_id
                                        ) as product_suppliers
                                    ON suppliers.id = product_suppliers.supplier_id
                            '); 
                        @endphp
                        @foreach ($suppliers as $key => $item)
                            <option value="{{ $item->id }}" {{  request()->get('supplier') ? 'selected' : '' }}>{{ $item->supplier }}</option>
                        @endforeach
                      </select>
                    </div>

                    <div class="form-group mr-3">
                      <input name="size" type="text" class="form-control"
                             value="{{ request()->get('size') }}"
                             placeholder="Size"
                             style="width: 80px;">
                    </div>

                    
                    <button type="submit" class="btn btn-image"><img src="/images/filter.png" /></button>
                </form>
            </div>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    <div class="productGrid" id="productGrid">
        @foreach ($products as $product)
            <div class="col-md-3 col-xs-6 text-center mb-5">
                  <a href="javascript:;" class="js-open-frm" data-id="{{$product->id}}">
                      <img src="{{ $product->getMedia(config('constants.media_tags'))->first() ? $product->getMedia(config('constants.media_tags'))->first()->getUrl() : '' }}" class="img-responsive grid-image" alt="" />
                      <p>Sku : {{ strlen($product->sku) > 18 ? substr($product->sku, 0, 15) . '...' : $product->sku }}</p>
                      <p>Id : {{ $product->id }}</p>
                      <p>Size : {{ strlen($product->size) > 17 ? substr($product->size, 0, 14) . '...' : $product->size }}</p>
                      <p>Price : {{ $product->price_special }}</p>
                      <p>Status : {{ $stage->getNameById( $product->stage )}}</p>

                      <p>Supplier : {{ $product->supplier }}</p>
                      @php
                        $supplier_list = '';
                      @endphp
                      @foreach ($product->suppliers as $key => $supplier)
                        @php
                          if ($key == 0) {
                            $supplier_list .= "$supplier->supplier";
                          } else {
                            $supplier_list .= ", $supplier->supplier";
                          }
                        @endphp
                      @endforeach
                      <p>Suppliers : {{ $supplier_list }}</p>
                  </a>
            </div>
        @endforeach
    </div>
    {!! $products->links() !!}

<div id="open_edit_frm" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="row">
        <div class="col-lg-1"></div>
        <div class="col-lg-10 modal_content">
        </div>
        <div class="col-lg-1"></div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
    <script type="text/javascript">
         $(document).ready(function() {
           $(".select-multiple2").select2();
        });
         $('.js-open-frm').click(function() {
            var id = $(this).data('id');
            $.ajax(
                {
                    url: '/productselection/'+id+'/edit?open_from=1&status_id=3',
                    type: "get",
                }).done(function (html) {
                  $("#open_edit_frm").find('.modal_content').html(html);
                  $("#open_edit_frm").find('.js-back').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');
                  $("#open_edit_frm").modal('show');
            }).fail(function (jqXHR, ajaxOptions, thrownError) {
                alert('No response from server');
            }); 
         })
         
    </script>
@endsection