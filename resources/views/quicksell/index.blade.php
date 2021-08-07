@extends('layouts.app')

@section("styles")
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css">
  <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.11/css/bootstrap-select.min.css" />
<style>
  .checkbox_select{
    display: none;
  }
  .align {
    padding: 0px 10px 10px 10px !important;
  }
  .groups-css {
    display : inline-flex;
  }
  .group-checkbox {
    padding-left : 5px;
  }
  #Div2 {
  display: none;
  }
</style>
@endsection

@section('content')
<div class="row">
  <div class="col">
    <h2 class="page-heading">Quick Sell @if($totalProduct) ({{ $totalProduct }}) @endif</h2>
  </div>
</div>

  {{-- @include('quicksell.partials.modal-image') --}}



<form action="{{ route('quicksell.search') }}" method="GET" id="searchForm" class="form-inline align-items-start">
  <!-- @csrf -->
  {{-- <div class="form-group">
      <div class="row"> --}}
  <input type="hidden" name="selected_products" id="selected_products" value="">
  <div class="form-group mr-3 mb-3">
    <input name="term" type="text" class="form-control" id="product-search"
           value="{{ isset($term) ? $term : '' }}"
           placeholder="sku,brand,category,status,stage">
    </div>
    <div class="form-group mr-3">
      @php
        if(empty($category_dropdown)) { 
          $category_dropdown = (new \App\Category)->attr([
          'name' => 'category[]', 
          'class' => 'form-control select-multiple2', 
          'multiple' => 'multiple',
          'data-placeholder' => 'Select Category'
          ])->selected()->renderAsDropdown();
        }
       @endphp
       {!! $category_dropdown !!}
    </div>

  <div class="form-group mr-3">
    @php $brands = \App\Brand::getAll(); @endphp
    {{ Form::select('brand[]',$brands,request('brand'),["class" => "form-control select-multiple2" ,"multiple" => true, "data-placeholder" => "Select Brands"]) }}
  </div>

  <div class="form-group mr-3">
    @php $colors = (new \App\Colors())->all(); @endphp
    {{ Form::select('color[]',$colors,request('color'),["class" => "form-control select-multiple2" ,"multiple" => true, "data-placeholder" => "Select Colors",'style' => "min-width:250px;"]) }}
  </div>

  <div class="form-group mr-3">
    {{ Form::select('supplier[]',$suppliers->pluck('supplier','id'),request('supplier'),["class" => "form-control select-multiple2" ,"multiple" => true , "data-placeholder" => "Select supplier"]) }}
  </div>

  @if (Auth::user()->hasRole('Admin'))
    <div class="form-group mr-3">
      {{ Form::select('location[]',$locations,request('location'),["class" => "form-control select-multiple2" ,"multiple" => true , "data-placeholder" => "Select location"]) }}
    </div>
  @endif

  <div class="form-group mr-3">
    <input name="size" type="text" class="form-control"
           value="{{ isset($size) ? $size : '' }}"
           placeholder="Size">
  </div>

  <div class="form-group mr-3">
    @php $groups = \App\QuickSellGroup::get()->pluck('name','id'); @endphp
    {{ Form::select('group[]',$groups,request('group'),["class" => "form-control select-multiple2" ,"multiple" => true , "data-placeholder" => "Select Group",'style' => "min-width:250px;"]) }}
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
  <button type="button" class="btn btn-image"><a href="/quickSell"><img src="/images/icons-refresh.png"/></a></button>
  {{-- </div>
</div> --}}
</form>
<br>
<div>
  {{-- <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#imageModal">Upload</button> --}}
  <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#productModal">Upload</button>
  <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#productGroup">Create Group</button>
  <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#productGroupExist">Add Existing Group</button>
  <button type="button" class="btn btn-secondary" id="multiple">Send Multiple Images</button>
  <a href="{{ url('/quickSell/pending') }}"><button type="button" class="btn btn-secondary">Product Pending</button></a>
  <button type="button" class="btn btn-secondary" id="selet-all-multiple">Attach all</button>
  <button type="button" class="btn btn-secondary" id="send-msg-to-customer" >Customer Send Message </button>
</div>

@include('partials.flash_messages')
<?php 
    $query = http_build_query(Request::except('page'));
    $query = url()->current() . (($query == '') ? $query . '?page=' : '?' . $query . '&page=');
?>

<div class="form-group position-fixed hidden-xs hidden-sm" style="top: 50px; left: 20px;z-index: 99">
    Goto :
    <select onchange="location.href = this.value;" class="form-control" id="page-goto">
        @for($i = 1 ; $i <= $products->lastPage() ; $i++ )
            <option data-value="{{$i}}" value="{{ $query.$i }}" {{ ($i == $products->currentPage() ? 'selected' : '') }}>{{ $i }}</option>
        @endfor
    </select>
</div>
<div class="infinite-scroll">
  <div class="row mt-6 " style="margin: 10px;">
    @foreach ($products as $index => $product)
    <div class="col-md-3 col-xs-6 text-left">
      <input type="checkbox" class="checkbox_select" name="quick" value="{{ $product->id }}"/>
      {{-- <a href="{{ route('leads.show', $lead['id']) }}"> --}}
      <img src="{{ $product->getMedia(config('constants.media_tags'))->first()
                ? $product->getMedia(config('constants.media_tags'))->first()->getUrl()
                : '' }}" class="img-responsive grid-image" alt="" />
      <div class="align" id="set{{ $product->id }}">       
      <p>Supplier : {{ $product->supplier }}</p>
      <p>Price : {{ $product->price }}</p>
      @if($product->size != null) <p>Size :  {{ $product->size }} </p>@endif
      <p>Brand : {{ $product->brand ? $brands[$product->brand] : '' }}</p>
      <p>Category : {{ $product->category ? $categories[$product->category] : '' }}</p>
      @if($product->groups)
      
      <p class="groups-css">Group :@if($product->groups->count() == 0) <input type="checkbox" name="blank" class="group-checkbox checkbox" data-id="{{ $product->id }}"> @else @foreach($product->groups as $group)
        @php 
      $grp = \App\QuickSellGroup::where('group',$group->quicksell_group_id)->first();
      @endphp 

      @if($grp != null && $grp->name != null) {{ $grp->name }} , @else {{ $group->quicksell_group_id }}, @endif @endforeach @endif </p>

      @endif
      </div>   
      <button type="button" class="btn btn-image sendWhatsapp" data-id="{{ $product->id }}"><img src="/images/send.png" /></button>

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
  {!! $products->appends(request()->except("page"))->links() !!}
</div>


@include('quicksell.partials.modal-product')
@include('quicksell.partials.modal-create-group')
@include('quicksell.partials.modal-add-existing-group')
@include('quicksell.partials.modal-whats-app')
@include('quicksell.partials.modal-multiple-whats-app')
@include('quicksell.partials.modal-add-group-details')
@include('quicksell.partials.modal-send-customer-message')

@endsection

@section('scripts')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.11/js/bootstrap-select.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jscroll/2.3.7/jquery.jscroll.min.js"></script>
  <script type="text/javascript">
    $('.infinite-scroll').jscroll({
        debug: true,
        autoTrigger: true,
        loadingHtml: '<img class="center-block" src="/images/loading.gif" alt="Loading..." />',
        padding: 0,
        nextSelector: '.pagination li.active + li a',
        contentSelector: '.infinite-scroll',
        callback: function () {
          $('ul.pagination:visible:first').remove();
          var next_page = $('.pagination li.active + li a');
            var page_number = next_page.attr('href').split('page=');
            var current_page = page_number[1] - 1;
          $('#page-goto option[data-value="' + current_page + '"]').attr('selected', 'selected');
          
        }
    });

    $(document).on("click","#attached-all-quick",function(){
        if($(this).html() == "Attached-ALL") {
          $(this).html('Uncheck Attached-ALL');
          $("input[name='quick']").attr('checked','checked');
        }else{
          $(this).html('Attached-ALL');
          $("input[name='quick']").removeAttr('checked');
        }
    });

    $(document).on('click', '.edit-modal-button', function() {
      var product = $(this).data('product');
      var url = '/quickSell/' + product.id + '/edit';

      $('#updateForm').attr('action', url);
      $('#supplier_select').val(product.supplier);
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
      $('.sendWhatsapp').on('click', function(e) {
        e.preventDefault(e);
        id = $(this).attr("data-id");
        $('#quicksell_id').val(id);
        $("#whatsappModal").modal();
      });
    });

    $(document).ready(
            function(){
              $("#multiple").click(function () {
                $(".checkbox_select").toggle();
                $(this).text("Please Select Checkbox");
                $(this).click(function () {
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

            $("#send-msg-to-customer").click(function () {
                  $('#sendCustomerMessage').modal('show');
                  val = $('input[name="quick"]:checked');
                  $(".selected_checkbox_customer").text(val.length);
                  var list = [];
                  $('input[name="quick"]:checked').each(function() {
                    list.push(this.value);
                  });
                  $(".products_customer").val(list);
                });

             var customerSearch = function () {
                  $(".customer_multi_select").select2({
                      tags: true,
                      width : '100%',
                      ajax: {
                          url: '/erp-leads/customer-search',
                          dataType: 'json',
                          delay: 750,
                          data: function (params) {
                              return {
                                  q: params.term, // search term
                              };
                          },
                          processResults: function (data, params) {

                              params.page = params.page || 1;

                              return {
                                  results: data,
                                  pagination: {
                                      more: (params.page * 30) < data.total_count
                                  }
                              };
                          },
                      },
                      placeholder: 'Search for Customer by id, Name, No',
                      escapeMarkup: function (markup) {
                          return markup;
                      },
                      minimumInputLength: 2,
                      templateResult: formatCustomer,
                      templateSelection: (customer) => customer.text || customer.name,

                  });
              };

              function formatCustomer(customer) {
                  if (customer.loading) {
                      return customer.name;
                  }

                  if (customer.name) {
                      return "<p> <b>Id:</b> " + customer.id + (customer.name ? " <b>Name:</b> " + customer.name : "") + (customer.phone ? " <b>Phone:</b> " + customer.phone : "") + "</p>";
                  }
              }

              customerSearch();


      $(document).ready(function() {
          $(".select-multiple").multiselect({
            placeholder: function(){
                $(this).data('placeholder');
            }
          });
          $(".select-multiple2").select2({
            placeholder: function(){
                $(this).data('placeholder');
            }
          });
       });
      

    $(function() {
      $('.selectpicker').selectpicker();
    });

   $(document).ready(function() {
      $(".checkbox").change(function() {
        if(this.checked) {
            id = $(this).attr("data-id");
            $('#product_group_id').val(id);
            $("#productGroupDetails").modal();
        }
      });
    });

    $(document).ready(
            function(){
              $("#selet-all-multiple").click(function () {
                $(".checkbox_select").toggle();
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

        $("#send-msg-to-customer").click(function () {

        });
        $(document).ready(function(){
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
                },
                
            }).done(function (response) {
                console.log(response);
                setid = 'set'+id;
                
                 $("#"+setid).html("<p>Supplier : "+response.data[0]+ "</p><p>Price :"+response.data[1]+"</p><p>Brand : " +response.data[2]+ "</p> <p>Category : "+response.data[3]+"</p> <p>Group : "+response.data[4]+"</p>");
                }).fail(function (response) {
               alert('failed');
            });
     
            

           
          });
        }); 

        function switchVisible() {
            if (document.getElementById('Div1')) {

                if (document.getElementById('Div1').style.display == 'none') {
                    document.getElementById('Div1').style.display = 'block';
                    document.getElementById('Div2').style.display = 'none';
                }
                else {
                    document.getElementById('Div1').style.display = 'none';
                    document.getElementById('Div2').style.display = 'block';
                }
            }
          }
</script>
@endsection
