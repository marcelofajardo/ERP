@extends('layouts.app')

@section("styles")
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css">
    <style>
  .checkbox_select{
    display: none;
  }
  .align {
    padding: 0px 10px 10px 10px !important;
  }
  #activate{
    display: none;
  }
</style>
@endsection

@section('content')
    <div class="row">
        <div class="col">
            <h2 class="page-heading">Quick Sell</h2>
        </div>
    </div>

 

<form action="{{ route('quicksell.pending') }}" method="GET" id="searchForm" class="form-inline align-items-start">
  
  <input type="hidden" name="selected_products" id="selected_products" value="">
  <div class="form-group mr-3 mb-3">
    <input name="term" type="text" class="form-control" id="product-search"
           value="{{ isset($term) ? $term : '' }}"
           placeholder="sku,brand,category,status,stage">
    </div>
    <div class="form-group mr-3">
      @php
       $category_parent = \App\Category::where('parent_id', 0)->orderby('title','asc')->get();
       $category_child = \App\Category::where('parent_id', '!=', 0)->orderby('title','asc')->get();
       @endphp
      <select class="form-control select-multiple2" name="category[]" multiple data-placeholder="Category...">
        <optgroup label="Category">
         @foreach($category_parent as $c)
                            <option value="{{ $c->id }}">{{ $c->title }}</option>
                            @if($c->childs)
                              @foreach($c->childs as $categ)
                              <option value="{{ $categ->id }}">---{{ $categ->title }}</option>
                              @endforeach
                            @endif
                        @endforeach
                        @foreach($category_child as $c)
                            <option value="{{ $c->id }}">{{ $c->title }}</option>
                            @if($c->childs)
                              @foreach($c->childs as $categ)
                              <option value="{{ $categ->id }}">---{{ $categ->title }}</option>
                              @endforeach
                            @endif
                        @endforeach
        </optgroup>
      </select>
    </div>

  <div class="form-group mr-3">
    @php $brands = \App\Brand::getAll(); @endphp
    <select class="form-control select-multiple2" name="brand[]" multiple data-placeholder="Brands...">
      <optgroup label="Brands">
        @foreach ($brands as $key => $name)
          <option value="{{ $key }}">{{ $name }}</option>
        @endforeach
      </optgroup>
    </select>
  </div>

  <div class="form-group mr-3">
    {{-- <strong>Color</strong> --}}
    @php $colors = new \App\Colors(); @endphp
    <select class="form-control select-multiple2" name="color[]" multiple data-placeholder="Colors...">
      <optgroup label="Colors">
        @foreach ($colors->all() as $key => $col)
          <option value="{{ $key }}" {{ isset($color) && $color == $key ? 'selected' : '' }}>{{ $col }}</option>
        @endforeach
      </optgroup>
    </select>
  </div>

  <div class="form-group mr-3">
    <select class="form-control select-multiple2" name="supplier[]" multiple data-placeholder="Supplier...">
      <optgroup label="Suppliers">
        @foreach ($suppliers as $key => $supp)
          <option value="{{ $supp->supplier }}" {{ isset($supplier) && $supplier == $supp->id ? 'selected' : '' }}>{{ $supp->supplier }}</option>
        @endforeach
      </optgroup>
    </select>
  </div>

  @if (Auth::user()->hasRole('Admin'))
    <div class="form-group mr-3">
      <select class="form-control select-multiple2" name="location[]" multiple data-placeholder="Location...">
        <optgroup label="Locations">
          @foreach ($locations as $name)
            <option value="{{ $name }}" {{ isset($location) && $location == $name ? 'selected' : '' }}>{{ $name }}</option>
          @endforeach
        </optgroup>
      </select>
    </div>
  @endif

  <div class="form-group mr-3">
    <input name="size" type="text" class="form-control"
           value="{{ isset($size) ? $size : '' }}"
           placeholder="Size">
  </div>

  <div class="form-group mr-3">
    @php $groups = \App\QuickSellGroup::all(); @endphp
    <select class="form-control select-multiple2" name="group[]" multiple data-placeholder="Groups...">
      <optgroup label="Groups">
        @foreach ($groups as $group)
          <option value="{{ $group->id }}">@if($group->name != null) {{ $group->name }} @else {{ $group->group }} @endif</option>
        @endforeach
      </optgroup>
    </select>
  </div>

  <div class="form-group mr-3">
    {!! Form::select('per_page',[
    "20" => "20 Images Per Page",
    "30" => "30 Images Per Page",
    "50" => "50 Images Per Page",
    "100" => "100 Images Per Page",
    ], request()->get("per_page",null), ['placeholder' => '-- Select Images Per Page --','class' => 'form-control']) !!}
  </div>
  <div class="form-group mr-3">
    <strong class="mr-3">Price</strong>
    <input type="text" name="price" data-provide="slider" data-slider-min="0" data-slider-max="400000" data-slider-step="1000" data-slider-value="[{{ isset($price) ? $price[0] : '0' }},{{ isset($price) ? $price[1] : '400000' }}]"/>
  </div>
  <button type="submit" class="btn btn-image"><img src="/images/filter.png"/></button>
  <button type="button" class="btn btn-image"><a href="/quickSell/pending"><img src="/images/icons-refresh.png"/></a></button>
  {{-- </div>
</div> --}}
</form>
<br>
<div>
  
  <a href="{{ url('/quickSell/pending') }}"><button type="button" class="btn btn-secondary">Product Active</button></a>
  <button type="button" class="btn btn-secondary" id="selet-all-multiple">Attach all</button>
  <button type="button" class="btn btn-secondary" id="activate">Activate Products</button>
</div>


    @include('partials.flash_messages')

    <div class="row mt-6" style="margin: 10px;">
        <form action="{{ route('quicksell.activate') }}" method="POST" id="activate_products">
            @csrf
            <input type="hidden" name="checkbox_value" id="value">

        </form>
        @foreach ($products as $index => $product)
          <div class="col-md-3 col-xs-6 text-center">
            <input type="checkbox" class="checkbox_select" name="quick" value="{{ $product->id }}"/>

                {{-- <a href="{{ route('leads.show', $lead['id']) }}"> --}}
                <img src="{{ $product->getMedia(config('constants.media_tags'))->first()
              ? $product->getMedia(config('constants.media_tags'))->first()->getUrl()
              : '' }}" class="img-responsive grid-image" alt="" />
                
                <div id="set{{ $product->id }}">
                  <p>Supplier : {{ $product->supplier }}</p>
                  <p>Price : {{ $product->price }}</p>
                  <p>Size : {{ $product->size }}</p>
                  <p>Brand : {{ $product->brand ? $brands[$product->brand] : '' }}</p>
                  <p>Category : {{ $product->category ? $categories[$product->category] : '' }}</p>
                   @if($product->groups)
      
                  <p>Group :@foreach($product->groups as $group)
                    @php 
                  $grp = \App\QuickSellGroup::where('group',$group->quicksell_group_id)->first();
                  @endphp
                  @if($grp != null && $grp->name != null) {{ $grp->name }} , @else {{ $group->quicksell_group_id }}, @endif @endforeach @endif </p>
                </div>

                <a href class="btn btn-image edit-modal-button" data-toggle="modal" data-target="#editModal" data-product="{{ $product }}"><img src="/images/edit.png" /></a>
                {!! Form::open(['method' => 'POST','route' => ['products.archive', $product->id],'style'=>'display:inline']) !!}
                <button type="submit" class="btn btn-image"><img src="/images/archive.png" /></button>
                {!! Form::close() !!}

                @if(auth()->user()->isAdmin())
                    {!! Form::open(['method' => 'DELETE','route' => ['products.destroy', $product->id],'style'=>'display:inline']) !!}
                    <button type="submit" class="btn btn-image"><img src="/images/delete.png" /></button>
                    {!! Form::close() !!}
                @endif
                {{-- </a> --}}
            </div>
        @endforeach
        
    </div>

    {!! $products->links() !!}

    @include('quicksell.partials.modal-product')
    @include('quicksell.partials.modal-create-group')

@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.min.js"></script>
    <script type="text/javascript">
        $(".select-multiple").multiselect();

        $(document).on('click', '.edit-modal-button', function() {
            var product = $(this).data('product');
            var url = '/quickSell/' + product.id + '/edit';
            $('#updateForm').attr('action', url);
            $('#supplier_select').val(product.supplier);
            $('#productId').val(product.id);
            $('#price_field').val(product.price);
            $('#price_special_field').val(product.price_special);
            $('#size_field').val(product.size);
            $('#brand_field').val(product.brand);
            @if (Auth::user()->hasRole('Admin'))
            $('#location_field').val(product.location);
            @endif
            $('#category_selection').val(product.category);
            $('#product_id').val(product.id);
        });

        var category_tree = {!! json_encode($category_tree) !!};
        var categories_array = {!! json_encode($categories_array) !!};

        var id_list = {
            41: ['34', '34.5', '35', '35.5', '36', '36.5', '37', '37.5', '38', '38.5', '39', '39.5', '40', '40.5', '41', '41.5', '42', '42.5', '43', '43.5', '44'], // Women Shoes
            5: ['34', '34.5', '35', '35.5', '36', '36.5', '37', '37.5', '38', '38.5', '39', '39.5', '40', '40.5', '41', '41.5', '42', '42.5', '43', '43.5', '44'], // Men Shoes
            40: ['36-36S', '38-38S', '40-40S', '42-42S', '44-44S', '46-46S', '48-48S', '50-50S'], // Women Clothing
            12: ['36-36S', '38-38S', '40-40S', '42-42S', '44-44S', '46-46S', '48-48S', '50-50S'], // Men Clothing
            63: ['XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXL'], // Women T-Shirt
            31: ['XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXL'], // Men T-Shirt
            120: ['24-24S', '25-25S', '26-26S', '27-27S', '28-28S', '29-29S', '30-30S', '31-31S', '32-32S'], // Women Sweat Pants
            123: ['24-24S', '25-25S', '26-26S', '27-27S', '28-28S', '29-29S', '30-30S', '31-31S', '32-32S'], // Women Pants
            128: ['24-24S', '25-25S', '26-26S', '27-27S', '28-28S', '29-29S', '30-30S', '31-31S', '32-32S'], // Women Denim
            130: ['24-24S', '25-25S', '26-26S', '27-27S', '28-28S', '29-29S', '30-30S', '31-31S', '32-32S'], // Men Denim
            131: ['24-24S', '25-25S', '26-26S', '27-27S', '28-28S', '29-29S', '30-30S', '31-31S', '32-32S'], // Men Sweat Pants
            42: ['60', '65', '70', '75', '80', '85', '90', '95', '100', '105', '110', '115', '120'], // Women Belts
            14: ['60', '65', '70', '75', '80', '85', '90', '95', '100', '105', '110', '115', '120'], // Men Belts
        };

        $('#product-category').on('change', function() {
            updateSizes($(this).val());
        });

        function updateSizes(category_value) {
            var found_id = 0;
            var found_final = false;
            var found_everything = false;
            var category_id = category_value;

            $('#size-selection').empty();

            $('#size-selection').append($('<option>', {
                value: '',
                text: 'Select Category'
            }));
            console.log('PARENT ID', categories_array[category_id]);
            if (categories_array[category_id] != 0) {

                Object.keys(id_list).forEach(function(id) {
                    if (id == category_id) {
                        $('#size-selection').empty();

                        $('#size-selection').append($('<option>', {
                            value: '',
                            text: 'Select Category'
                        }));

                        id_list[id].forEach(function(value) {
                            $('#size-selection').append($('<option>', {
                                value: value,
                                text: value
                            }));
                        });

                        found_everything = true;
                        $('#size-manual-input').addClass('hidden');
                    }
                });

                if (!found_everything) {
                    Object.keys(category_tree).forEach(function(key) {
                        Object.keys(category_tree[key]).forEach(function(index) {
                            if (index == categories_array[category_id]) {
                                found_id = index;

                                return;
                            }
                        });
                    });

                    console.log('FOUND ID', found_id);

                    if (found_id != 0) {
                        Object.keys(id_list).forEach(function(id) {
                            if (id == found_id) {
                                $('#size-selection').empty();

                                $('#size-selection').append($('<option>', {
                                    value: '',
                                    text: 'Select Category'
                                }));

                                id_list[id].forEach(function(value) {
                                    $('#size-selection').append($('<option>', {
                                        value: value,
                                        text: value
                                    }));
                                });

                                $('#size-manual-input').addClass('hidden');
                                found_final = true;
                            }
                        });
                    }
                }

                if (!found_final) {
                    $('#size-manual-input').removeClass('hidden');
                }
            }
        }

         $(document).ready(function() {
       $(".select-multiple").multiselect();
       $(".select-multiple2").select2();
    });

           $(document).ready(
            function(){
              $("#selet-all-multiple").click(function () {
                $(".checkbox_select").toggle();
                $("#activate").toggle();
                
                var checkBoxes = $(".checkbox_select");
                checkBoxes.prop("checked", !checkBoxes.prop("checked"));
                $("#multiple").text("Please Select Checkbox");
                $("#multiple").click(function () {
                  $('#multipleWhatsappModal').modal('show');
                  val = $('input[name="quick"]:checked');
                  $("#selected_checkbox").text(val.length);
                  var list = [];
                  $('input[name="quick"]:checked').each(function() {
                    list.push(this.value);
                  });
                  $("#products").val(list);
                });
              });

            });  

        

$(document).ready(function(){
    $("#activate").click(function(){  
     var checkbox = [];
            $.each($("input[name='quick']:checked"), function(){
                checkbox.push($(this).val());
            });  
            
            $("#value").val(checkbox);
         $("#activate_products").submit();         

    });

    $("#updateEditForm").on("click", function(e){
         e.preventDefault();
         
         var group_id = $('#group_old').val();
         var supplier_id = $('#supplier_select').val();
         var price = $('#price_field').val();
         var special_price = $('#price_special_field').val();
         var size_field = $('#size_field').val();
         var brand_field = $('#brand_field').val();
         var location_field = $('#location_field').val();
         var category_selection = $('#category_selection').val();
         var group_name_updated = $('#group_name_updated').val();
         var id = $('#product_id').val();
         
        $.ajax({
            type: "POST",
            url: "{{ route('quicksell.update') }}",
            data: {
                _token: "{{ csrf_token() }}",
               // _method: "POST",
                 group_old: group_id,
                 supplier: supplier_id,
                 price: price,
                 price_special: special_price,
                 size: size_field,
                 brand: brand_field,
                  location: location_field,
                 category: category_selection,
                 group_new: group_name_updated,
                 id: id,
                 is_pending: 0,
            },
            
        }).done(function (response) {
            //console.log(response);
            $("#editModal").modal("hide");
            setid = 'set'+id;
            
             $("#"+setid).html("<p>Supplier : "+response.data[0]+ "</p><p>Size:"+response.data[5]+"</p><p>Price :"+response.data[1]+"</p><p>Brand : " +response.data[2]+ "</p> <p>Category : "+response.data[3]+"</p> <p>Group : "+response.data[4]+"</p>");
            }).fail(function (response) {
           alert('failed');
        });
      });
});
</script>
@endsection
