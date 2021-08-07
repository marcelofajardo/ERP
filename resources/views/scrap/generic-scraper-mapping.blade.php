@extends('layouts.app')

@section('title', 'Mapping Supplier Scraper')

@section("styles")
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>
    <style type="text/css">
        #loading-image {
            position: fixed;
            top: 50%;
            left: 50%;
            margin: -50px 0px 0px -50px;
        }
    </style>
@endsection

@section('content')
    <div id="myDiv">
        <img id="loading-image" src="/images/pre-loader.gif" style="display:none;"/>
    </div>
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <h2 class="page-heading">Mapping Supplier Scraper</h2>
            <div class="pull-right">
            </div>

        </div>
    </div>

@include('partials.flash_messages')
   <div class="mt-3 col-md-12">
    <table class="table table-bordered table-striped" id="log-table">
            <thead>
            <tr>
                <th></th>
                <th>Selector</th>
                <th>Function</th>
                <th>Parameter</th>
                <th>Remove</th>
                
            </tr>
            </thead>
            <tbody id="column">
                @foreach($mappings as $mapping)
                <tr>
                <td>
                    <select class="form-control select-value">
                    <option value="sku" @if($mapping->field_name == 'sku') selected @endif>Sku</option>
                    <option value="title" @if($mapping->field_name == 'title') selected @endif>Title</option>
                    <option value="supplier" @if($mapping->field_name == 'supplier') selected @endif>Supplier</option>
                    <option value="url" @if($mapping->field_name == 'url') selected @endif>Url</option>
                    <option value="category" @if($mapping->field_name == 'category') selected @endif>Category</option>
                    <option value="material_used" @if($mapping->field_name == 'material_used') selected @endif>Material_used</option>
                    <option value="description" @if($mapping->field_name == 'description') selected @endif>Description</option>
                    <option value="dimension" @if($mapping->field_name == 'dimension') selected @endif>Dimension</option>
                    <option value="price" @if($mapping->field_name == 'price') selected @endif>Price</option>
                    <option value="discounted_price" @if($mapping->field_name == 'discounted_price') selected @endif>Discounted price</option>
                    <option value="images" @if($mapping->field_name == 'images') selected @endif>Images</option>
                    <option value="sizes" @if($mapping->field_name == 'sizes') selected @endif>Sizes</option>
                    <option value="brand" @if($mapping->field_name == 'brand') selected @endif>Brand</option>
                    <option value="sizes" @if($mapping->field_name == 'sizes') selected @endif>Sizes</option>
                    <option value="color" @if($mapping->field_name == 'color') selected @endif>Color</option>
                    <option value="country" @if($mapping->field_name == 'country') selected @endif>Country</option>
                    <option value="is_sale" @if($mapping->field_name == 'is_sale') selected @endif>Is sale</option>
                    <option value="size_system" @if($mapping->field_name == 'size_system') selected @endif>Size system</option>
                    <option value="currency" @if($mapping->field_name == 'currency') selected @endif>Currency</option>
                    <option value="b2b_price" @if($mapping->field_name == 'b2b_price') selected @endif>B2b price</option>
                </select>
                </td>
                <td><input type="text" class="selector form-control" value="{{ $mapping->selector }}"></td>
                <td><input type="text" class="function form-control" value="{{ $mapping->function }}"></td>
                <td><input type="text" class="parameter form-control" value="{{ $mapping->parameter }}"></td>
                <td><button class="btn btn-secondary" onclick="removeEntry({{ $mapping->id }})">Remove</button></td>
                </tr>
                @endforeach
                
                    
                    
                    
            </tbody>
        </table>
        <button class="btn btn-secondary" onclick="addNewColumn()">Add New</button>
        <button class="btn btn-secondary" onclick="saveColumn()">Save</button>
        <button class="btn btn-link"><a href="/scrap/generic-scraper">Back</a></button>
    </div>
@endsection

@section('scripts')
<script type="text/javascript">
    function addNewColumn(){
        sku = '<option value="sku">Sku</option>';
        title = '<option value="title">Title</option>';
        supplier = '<option value="supplier">Supplier</option>';
        category = '<option value="category">Category</option >';
        url = '<option value="url">URL</option >';
        material_used = '<option value="material_used">Material Used</option >';
        description = '<option value="description">Description</option >';
        price = '<option value="price">Price</option >';
        discounted_price = '<option value="discounted_price">Discountedprice</option >';
        images = '<option value="images">Images</option>';
        sizes = '<option value="sizes">Sizes</option>';
        brand = '<option value="brand">Brand</option>';
        color = '<option value="color">Color</option>';
        country = '<option value="country">Country</option >';
        is_sale = '<option value="is_sale">Is Sale</option >';
        size_system = '<option value="size_system">Sizesystem</option >';
        currency = '<option value="currency">Currency</option >';
        b2b_price = '<option value="b2b_price">B2bprice</option >';
        composition = '<option value="composition">Composition</option >';
        dimension = '<option value="dimension">Dimension</option >';

        $(".select-value").each(function( index ) {
            if($(this).children("option:selected").val() == 'sku'){
                sku = '';
            } 
            if($(this).children("option:selected").val() == 'title'){
                title = '';
            }
            if($(this).children("option:selected").val() == 'brand'){
                brand = '';
            }
            if($(this).children("option:selected").val() == 'url'){
                url = '';
            }
            if($(this).children("option:selected").val() == 'category'){
                category = '';
            }
            if($(this).children("option:selected").val() == 'material_used'){
                material_used = '';
            }
            if($(this).children("option:selected").val() == 'description'){
                description = '';
            } 
            if($(this).children("option:selected").val() == 'price'){
                price = '';
            } 
            if($(this).children("option:selected").val() == 'discounted_price'){
                discounted_price = '';
            }
            if($(this).children("option:selected").val() == 'images'){
                images = '';
            }
            if($(this).children("option:selected").val() == 'sizes'){
                sizes = '';
            }
            if($(this).children("option:selected").val() == 'color'){
                color = '';
            }
            if($(this).children("option:selected").val() == 'country'){
                country = '';
            }
            if($(this).children("option:selected").val() == 'is_sale'){
                is_sale = '';
            }
            if($(this).children("option:selected").val() == 'size_system'){
                size_system = '';
            }
            if($(this).children("option:selected").val() == 'currency'){
                currency = '';
            }
            if($(this).children("option:selected").val() == 'b2b_price'){
                b2b_price = '';
            }
            if($(this).children("option:selected").val() == 'composition'){
                composition = '';
            }if($(this).children("option:selected").val() == 'dimension'){
                dimension = '';
            }
        });

        html = '<tr><td><select class="form-control select-value">'+sku+''+url+''+title+''+category+''+material_used+''+description+''+price+''+discounted_price+''+images+''+sizes+''+brand+''+color+''+country+''+is_sale+''+size_system+''+currency+''+b2b_price+''+composition+''+dimension+'</select></td><td><input type="text" class="selector form-control"></td><td><input type="text" class="function form-control"></td><td><input type="text" class="parameter form-control"></td></tr>';
        if(sku == '' && url == '' && category == '' && material_used == '' && description == '' && price == '' && discounted_price == '' && images == '' && sizes == '' && brand == '' && color == '' && country == '' && is_sale == '' && size_system == '' && currency == '' && b2b_price == '' && dimension == ''){
            alert('All Fields Selected');
        }else{
            $('#column').append(html);    
        }
        

    }

    function saveColumn(){
        selector = []
        $(".selector").each(function(index) {
            value = $(this).val();
            selector.push(value);
        });
        functions = []
        $(".function").each(function(index) {
            value = $(this).val();
            functions.push(value);
        });
        parameter = []
        $(".parameter").each(function(index) {
            value = $(this).val();
            parameter.push(value);
        });
        select = []
        $(".select-value").each(function(index) {
            value = $(this).val();
            select.push(value);
        });
        id = "{{ $id }}";
        $.ajax({
            url: "{{ route('generic.mapping.save') }}",
            type: 'POST',
            dataType: 'json',
            data: {
                "_token": "{{ csrf_token() }}",
                selector: selector,
                functions : functions,
                parameter : parameter,
                select : select,
                id : id,
        },
        })
        .done(function() {
            location.reload();
        })
        .fail(function() {
            alert('Something went wrong');
        })
        
        
    }

    function removeEntry(id){
        var result = confirm("Want to delete?");
        if (result) {
            $.ajax({
            url: "{{ route('generic.mapping.delete') }}",
            type: 'POST',
            dataType: 'json',
            data: {
                "_token": "{{ csrf_token() }}",
                id : id,
            },
            })
            .done(function() {
                location.reload();
            })
            .fail(function() {
                alert('Something went wrong');
            })
        }
    }


</script>

@endsection


