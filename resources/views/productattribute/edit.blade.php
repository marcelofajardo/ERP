@extends('layouts.app')

@section('styles')
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css">
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Edit Attribute</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-secondary" href="{{ route('productattribute.index') }}"> Back</a>
            </div>
        </div>
    </div>

    @if (  $isApproved == -1 )
        <div class="alert alert-danger alert-block mt-2">
            <button type="button" class="close" data-d ismiss="alert">×</button>
            <p><strong>Product has been rejected</strong></p>
            <p><strong>Reason : </strong> {{ $rejected_note }}</p>
        </div>
    @endif

    <form action="{{ route('productattribute.update',$id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-8">
                <div class="form-group">
                    <strong>Details not found:</strong>
                    <input type="checkbox" class="" name="dnf" value="Details not found"
                            {{ old('dnf') == 'Details not found' ? 'checked'
                                                         : ($dnf == 'Details not found' ? 'checked' : '') }}/>
                    @if ($errors->has('dnf'))
                        <div class="alert alert-danger">{{$errors->first('dnf')}}</div>
                    @endif
                </div>

                <div class="form-group">
                    <strong>Name:</strong>
                    <input type="text" class="form-control" name="name" placeholder="Name" value="{{old('name') ? old('name') : $name}}"/>
                    @if ($errors->has('name'))
                        <div class="alert alert-danger">{{$errors->first('name')}}</div>
                    @endif
                </div>

                <div class="form-group">
                    <strong>Short Description:</strong>

                    <input type="text" class="form-control" name="short_description" placeholder="Short Description"
                           value="{{ old('short_description') ? old('short_description') : $short_description }}"/>

                    @if ($errors->has('short_description'))
                        <div class="alert alert-danger">{{$errors->first('short_description')}}</div>
                    @endif
                </div>

                <div class="form-group">
                    <strong>Mesaurement{{--/Size--}}</strong>
                    <div style="padding: 10px 0;">
                        <label for="measurement_type"> Measurement :</label>
                        <input id="measurement_type" type="checkbox" name="measurement_size_type"
                               value="measurement" {{ old('measurement_size_type') == 'measurement' ? 'checked'
                                                        : ($measurement_size_type == 'measurement' ? 'checked' : '') }} />

                        {{--<label for="size_type"> Size :</label>
                        <input id="size_type" type="radio" name="measurement_size_type"
                               value="size" {{ old('measurement_size_type') == 'size' ? 'checked'
                                                        : ($measurement_size_type == 'size' ? 'checked' : '') }} />--}}
                    </div>

                    <div id="measurement_row" class="row" style="display:none;">
                        <div class="col-4">
                            <input type="text" class="form-control" name="lmeasurement" placeholder="L" value="{{ old('lmeasurement') ? old('lmeasurement') : $lmeasurement }}"/>
                        </div>
                        <div class="col-4">
                            <input type="text" class="form-control" name="hmeasurement" placeholder="H" value="{{ old('hmeasurement') ? old('hmeasurement') : $hmeasurement }}"/>
                        </div>
                        <div class="col-4">
                            <input type="text" class="form-control" name="dmeasurement" placeholder="D" value="{{ old('dmeasurement') ? old('dmeasurement') : $dmeasurement }}"/>
                        </div>
                    </div>
                    @if ($errors->any())
                        <div style="padding-top: 10px;">
                            @if ($errors->has('measurement_size_type'))
                                <div class="alert alert-danger">{{$errors->first('measurement_size_type')}}</div>
                            @endif

                            @if ($errors->has('lmeasurement'))
                                <div class="alert alert-danger">{{$errors->first('lmeasurement')}}</div>
                            @endif
                            @if ($errors->has('hmeasurement'))
                                <div class="alert alert-danger">{{$errors->first('hmeasurement')}}</div>
                            @endif
                            @if ($errors->has('dmeasurement'))
                                <div class="alert alert-danger">{{$errors->first('dmeasurement')}}</div>
                            @endif
                            @if ($errors->has('size_value'))
                                <div class="alert alert-danger">{{$errors->first('size_value')}}</div>
                            @endif
                        </div>
                    @endif
                </div>

                <div class="form-group">
                    <strong>Category</strong>
                    <?php echo $category ?>
                </div>

                <div class="form-group">
                  <strong>Size:</strong>
                  {{-- <input type="text" class="form-control" name="size" placeholder="Size" value="{{old('size') ? old('size') : $size }}"/> --}}
                  <select class="form-control select-multiple" name="size[]" id="size-selection" multiple>
                    <option value="">Select Category</option>
                  </select>

                  <input type="text" name="other_size" class="form-control mt-3 hidden" id="size-manual-input" placeholder="Manual Size" value="{{ !empty($size) ? $size[0] : '' }}">

                  @if ($errors->has('size'))
                      <div class="alert alert-danger">{{$errors->first('size')}}</div>
                  @endif
                </div>

                <div class="form-group">
                    <strong> Composition :</strong>
                    <input type="text" class="form-control" name="composition" placeholder="Composition" value="{{ old('composition') ? old('composition') : $composition }}"/>
                    @if ($errors->has('composition'))
                        <div class="alert alert-danger">{{$errors->first('composition')}}</div>
                    @endif
                </div>

                <div class="form-group">
                    <strong> SKU :</strong>
                    <input type="text" class="form-control" name="sku" placeholder="SKU" value="{{ old('sku') ? old('sku') : $sku }}"/>
                    @if ($errors->has('sku'))
                        <div class="alert alert-danger">{{$errors->first('sku')}}</div>
                    @endif
                </div>

                <div class="form-group">
                    <strong> SKU+Color:</strong>
                    {{ $sku.$color }}
                </div>

                <div class="form-group">
                    <strong> Made In :</strong>
                    <input type="text" class="form-control" name="made_in" placeholder="Made In" value="{{ old('made_in') ? old('made_in') : $made_in }}"/>
                    @if ($errors->has('made_in'))
                        <div class="alert alert-danger">{{$errors->first('made_in')}}</div>
                    @endif
                </div>

                <div class="form-group">
                    <strong> Brand :</strong>

	                <?php
	                $brands = \App\Brand::getAll();
	                echo Form::select('brand',$brands, ( old('brand') ? old('brand') : $brand ), ['placeholder' => 'Select a brand','class' => 'form-control']);?>
                    {{--<input type="text" class="form-control" name="brand" placeholder="Brand" value="{{ old('brand') ? old('brand') : $brand }}"/>--}}
                    @if ($errors->has('brand'))
                        <div class="alert alert-danger">{{$errors->first('brand')}}</div>
                    @endif
                </div>

                <div class="form-group">
                    <input type="radio" name="color_selection" value="color_manual"> &nbsp; <strong> Color (manual) :</strong>
                    <?php
                    $colors = new \App\Colors();
                    echo Form::select( 'color_manual', $colors->all(), ( old( 'color' ) ? old( 'color' ) : $color ), [ 'placeholder' => 'Select a color', 'class' => 'form-control' ] );?>
                    {{--<input type="text" class="form-control" name="color" placeholder="Color" value="{{ old('color') ? old('color') : $color }}"/>--}}
                    @if ($errors->has('color'))
                        <div class="alert alert-danger">{{$errors->first('color')}}</div>
                    @endif
                </div>

                <div class="form-group">
                    <input type="radio" name="color_selection" value="color_ai"> &nbsp; <strong> Color (AI) :</strong>
                    <?php
                    $colors = new \App\Colors();
                    echo Form::select('color_ai',$colors->all(), '', ['placeholder' => 'Select a color','class' => 'form-control']);?>
                    {{--<input type="text" class="form-control" name="color" placeholder="Color" value="{{ old('color') ? old('color') : $color }}"/>--}}
                    @if ($errors->has('color'))
                        <div class="alert alert-danger">{{$errors->first('color')}}</div>
                    @endif
                </div>

                <div class="form-group">
                    <strong> Price (in Euro):</strong>
                    <input type="number" class="form-control" name="price" placeholder="Price (in Euro)" value="{{ old('price') ? old('price') : $price }}"/>
                    @if ($errors->has('price'))
                        <div class="alert alert-danger">{{$errors->first('price')}}</div>
                    @endif
                </div>
                <div class="form-group">
                    <strong> Price (in INR):</strong>
                    <input type="number" disabled class="form-control" placeholder="Price (in INR)" value="{{ $price_inr }}"/>
                </div>

                <div class="form-group">
                    <strong> Special Price:</strong>
                    <input type="number" disabled class="form-control" placeholder="Price (in Euro)" value="{{ isset($price_special) ? $price_special : 0 }}"/>
                </div>

                <div class="form-group">
                    <strong> Special Offer Price (Broadcast):</strong>
                    <input type="number" name="price_special_offer" class="form-control" placeholder="Special Offer Price" value="{{ $price_special_offer }}"/>
                </div>

                <div class="form-group">
                    <strong> Product Link :</strong>
                    <input type="text" class="form-control" name="product_link" placeholder="Product Link" value="{{ old('product_link') ? old('product_link') : $product_link }}"/>
                    @if ($errors->has('product_link'))
                        <div class="alert alert-danger">{{$errors->first('product_link')}}</div>
                    @endif
                </div>

              <div class="form-group">
                <strong>Supplier</strong>

                {{-- @php $supplier_list = (new \App\ReadOnly\SupplierList)->all(); @endphp
                <select class="form-control" name="supplier">
                  <option value="">Select Supplier</option>
                  @foreach ($supplier_list as $index => $value)
                    <option value="{{ $index }}" {{ $index == $supplier ? 'selected' : '' }}>{{ $value }}</option>
                  @endforeach
                </select> --}}

                <select class="form-control" name="supplier[]" multiple>
                  <option value="">Select Supplier</option>
                  @foreach ($suppliers as $index => $supplier)
                    <option value="{{ $supplier->id }}" {{ $product_suppliers->contains($supplier->id) ? 'selected' : '' }}>{{ $supplier->supplier }}</option>
                  @endforeach
                </select>
              </div>

                <div class="form-group">
                    <strong> Supplier Link :</strong>
                    <input type="text" class="form-control" name="supplier_link" placeholder="Supplier Link" value="{{ old('supplier_link') ? old('supplier_link') : $supplier_link }}"/>
                    @if ($errors->has('supplier_link'))
                        <div class="alert alert-danger">{{$errors->first('supplier_link')}}</div>
                    @endif
                </div>

                <div class="form-group">
                    <strong> Description Link :</strong>
                    <input type="text" class="form-control" name="description_link" placeholder="Description Link" value="{{ old('description_link') ? old('description_link') : $description_link }}"/>
                    @if ($errors->has('description_link'))
                        <div class="alert alert-danger">{{$errors->first('description_link')}}</div>
                    @endif
                </div>

                @if (Auth::user()->hasRole('Admin'))
                  <div class="form-group">
                      <strong>Location :</strong>
                      <select class="form-control" name="location">
                        <option value="">Select a Location</option>
                        @foreach ($locations as $name)
                          <option value="{{ $name }}" {{ (old('location') ?? $location) == $name ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                      </select>

                      @if ($errors->has('location'))
                          <div class="alert alert-danger">{{$errors->first('location')}}</div>
                      @endif
                          @csrf
                          <div>
                              <table class="table table-bordered">
                                  <tr>
                                      <th>Qty</th>
                                      <th>Size</th>
                                      <th>Del</th>
                                  </tr>
                                  @foreach($prod_size_qty as $q)
                                      <tr>
                                          <td>{{ $q->quantity }}</td>
                                          <td>{{ $q->size }}</td>
                                          <td>{{ ($q->supplier) ? $q->supplier->supplier : "Name" }}</td>
                                          <td>
                                              <a href="{{ action('ProductAttributeController@delSizeQty', $q->id) }}">Delete</a>
                                          </td>
                                      </tr>
                                  @endforeach
                              </table>
                          </div>
                          <div id="sizeQtHolder">
                              <div>
                                  <input style="width: 100px;" type="text" name="qty[]" placeholder="Qty">
                                  <input style="width: 100px;" type="text" name="sizex[]" placeholder="Size">
                                  <span class="btn btn-xs btn-secondary add-sizeQtHolder">Add</span>
                              </div>
                          </div>
                  </div>
                @endif

            @if ($errors->has( 'image' ))
                <div class="alert alert-danger">{{$errors->first('image')}}</div>
            @endif

            <?php $i = 0 ?>

            @for(  ; $i < sizeof($images) ; $i++ )

                <strong>Image {{ $i+1 }}:</strong>
                <div class="old-image{{$i}}" style="
                @if ($errors->has('image.'.$i))
                        display: none;
                @endif
                        ">
                    <p>
                        <img src="{{$images[$i]->getUrl()}}" class="img-responsive" style="max-width: 200px;"  alt="">
                        <input type="text" hidden name="oldImage{{$i}}" value="0">
                    </p>
                    <button class="btn btn-image removeOldImage" data-id="{{$i}}" media-id="{{ $images[$i]->id }}"><img src="/images/delete.png" /></button>
                </div>
                <div class="form-group new-image{{ $i }}" style="
                @if ( !$errors->has('image.'.$i))
                        display: none;
                @endif
                        ">
                    <strong>Upload Image:</strong>
                    <input  type="file" enctype="multipart/form-data" class="form-control" name="image[]" />
                    @if ($errors->has( 'image.'.$i ))
                        <div class="alert alert-danger">{{$errors->first('image.'.$i )}}</div>
                    @endif
                </div>

            @endfor

            @for( ;  $i < 5 ; $i++  )
                    <strong>Image {{ $i+1  }}:</strong>

                    <div class="form-group new-image">
                        <strong>Upload Image:</strong>
                        <input  type="file" enctype="multipart/form-data" class="form-control" name="image[]" />
                        @if ($errors->has('image.'.$i))
                            <div class="alert alert-danger">{{$errors->first( 'image.'.($i) )}}</div>
                        @endif
                    </div>
            @endfor

                <input type="text" hidden name="stage" value="2">
                <button type="submit" class="btn btn-secondary">+</button>
            </div>

            @if (!empty($reference))
              <div class="col-xs-12 col-md-4">
                <div class="card">
                  <div class="card-body">
                    <h5 class="card-title">Reference</h5>

                    @foreach($reference as $key=>$property)
                        <li><strong>{{ ucfirst($key) }}</strong>: <strong class="text-info">
                                @if (is_array($property))
                                    @foreach($property as $item)
                                        @if ($loop->last)
                                            {{ $item }}
                                        @else
                                            {{ $item . ', ' }}
                                        @endif
                                    @endforeach
                                @else
                                    {{ ucfirst($property)  }}
                                @endif
                            </strong></li>
                    @endforeach
                      <li><a target="_new" href="{{ $scraped ? $scraped->url : '' }}">Link</a></li>
                  </div>
                </div>
              </div>
            @endif

        </div>
    </form>


@endsection

@section('scripts')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.min.js"></script>
  <script type="text/javascript">
    // $(document).ready(function() {
    //    $(".select-multiple").multiselect();
    // });

    $(document).on('click', '.add-sizeQtHolder', function () {
        $("#sizeQtHolder").append(
            '<div>\n' +
            '                                  <input style="width: 100px;" type="text" name="qty[]" placeholder="Qty">\n' +
            '                                  <input style="width: 100px" type="text" name="sizex[]" placeholder="Size">\n' +
            '                              </div>'
        );
    });

    var category_tree = {!! json_encode($category_tree) !!};
    var categories_array = {!! json_encode($categories_array) !!};
    var old_category = {{ $old_category }};
    var selected_sizes = {!! json_encode($size) !!};

    console.log(selected_sizes);

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

    updateSizes(old_category);

    selected_sizes.forEach(function(index) {
      $('#size-selection option[value="' + index + '"]').attr('selected', 'selected');
    });

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
  </script>
@endsection
