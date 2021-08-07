@extends('layouts.app')
@section('title', "Drafted Products")
@section('content')
<style type="text/css">
    .cls_commu_his {
        width: 100% !important;
    }

    .cls_filter_inputbox {
        display: block;
        margin-left: 10px;
    }

    .btn-image img {
        width: 17px !important
    }
</style>
<div class="row">
    <div class="col-lg-12 margin-tb">
        <h2 class="page-heading">Drafted Products ({{ $products->total() }})</h2>
        <div class="pull-left cls_filter_box">
            <form class="form-inline" action="?" method="GET">
                <div class="form-group ml-3 " style="margin-left: 10px;">
                    <label for="keyword">Keyword</label>
                    <input placeholder="Search by keyword" type="text" name="keyword" value="{{request()->get('keyword')}}" class="form-control-sm form-control cls_commu_his">
                </div>
                <div class="form-group ml-3 cls_filter_inputbox" style="margin-left: 10px;">
                    <label for="supplier_id">Supplier</label>
                    <?php echo Form::select("supplier_id", \App\Helpers::selectSupplierList(), request('supplier_id'), ["class" => "form-control-sm  form-control select2", 'style' => 'width:200px']); ?>
                </div>
                <div class="form-group ml-3 cls_filter_inputbox" style="margin-left: 10px;">
                    <label for="category">Category</label>
                    <?php echo \App\Helpers::selectCategoryList(request('category')); ?>
                </div>
                <div class="form-group ml-3 cls_filter_inputbox" style="margin-left: 10px;">
                    <label for="brand_id">Brand</label>
                    <?php echo Form::select("brand_id", \App\Helpers::selectBrandList(), request('brand_id'), ["class" => "form-control-sm form-control select2", 'style' => 'width:200px']); ?>
                </div>
                <div class="form-group ml-3 cls_filter_inputbox" style="margin-left: 10px;">
                    <label for="brand_id">Status</label>
                    <?php echo Form::select("statuds_id", \App\Helpers::selectStatusList(), request('status_id'), ["class" => "form-control-sm form-control select2", 'style' => 'width:150px']); ?>
                </div>
                <button type="submit" style="margin-top: 20px;padding: 5px;" class="btn btn-image"><img src="/images/filter.png" /></button>
                <button type="button" onclick="return confirm('Are you sure you want to delete ?')" style="margin-top: 20px;padding: 5px;" class="btn btn-image btn-delete-multiple"><img src="/images/delete.png" /></button>
                
                <a href="javascript:;" style="margin-top: 20px;padding: 5px;" data-toggle="modal" data-target="#createQuickSellModal" class="btn btn-sm btn-primary">Quick Sell</a>
            </form>
        </div>
    </div>
</div>
<div class="table-responsive mt-3">
    <table class="table table-bordered table-small-row">
        <tr>
            <th> </th>
            <th>Product id</th>
            <th>Name</th>
            <th>Brand</th>
            <th>Category</th>
            <th>Composition</th>
            <th>Dimension</th>
            <th>Size</th>
            <th>Color</th>
            <th>Status</th>
            <th>All images</th>
            <th>Supplier</th>
            <th>Link</th>
            <th>Created At</th>
            <th>Action</th>
        </tr>
        @foreach ($products as $product)
        <tr>
            <td>
                <input type="checkbox" class="product-delete" id="" name="product_id_delete" data-id="{{$product->id}}" value="">
            </td>
            <td><a target="_blank" href="/products/{{ $product->id }}">{{ $product->id }}</a></td>
            <td>{{ $product->product_name }}</td>
            <td>{{ $product->brand_name }}</td>
            <td>{{ $product->category_name }}</td>
            <td>{{ $product->composition }}</td>
            <td>{{ $product->lmeasurement }} X {{ $product->hmeasurement }} X {{ $product->dmeasurement }}</td>
            <td>{{ $product->size }}</td>
            <td>{{ $product->color }}</td>
            <td>{{ $product->getStatusName() }}</td>
            <td>
                @php $extraImages = [] @endphp
                @if ($images = $product->getMedia(config('constants.attach_image_tag')))
                @foreach ($images as $i => $image)
                @if($i == 0)
                <img class="more-list-btn" src="{{ $image->getUrl() }}" class="img-responsive" width="20px">
                @php $extraImages[] = $image->getUrl() @endphp
                @else
                @php $extraImages[] = $image->getUrl() @endphp
                @endif
                @endforeach
                @endif
                @if(!empty($extraImages))
                <div class="more-list-btn-row" data-list="{{ json_encode($extraImages) }}"></div>
                @endif
            </td>
            <td>{{ $product->supplier }}</td>
            <td>@if($product->supplier_link) <a href="{{ $product->supplier_link }}" target="__blank">Go To Website</a> @endif</td>
            <td>{{ date("Y-m-d",strtotime($product->created_at)) }}</td>
            <td>
                <a href class="btn btn-image edit-modal-button" data-toggle="modal" data-product="{{ $product->id }}"><img width="3px" src="/images/edit.png" /></a>
                <form action="{{ route('products.destroy',$product->id) }}" method="POST" style="display:inline">
                    @csrf
                    @method('DELETE')
                    @if(auth()->user()->checkPermission('products-delete'))
                    <button onclick="return confirm('Are you sure you want to delete ?')" type="submit" class="btn btn-image"><img width="3px" src="/images/delete.png" /></button>
                    @endif
                </form>
            </td>
        </tr>
        @endforeach
    </table>
</div>

{!! $products->appends(Request::except('page'))->links() !!}

<div class="modal" role="dialog" id="show-more-images">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="show-more-images" role="document">
                <div class="row show-list-here">

                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal" role="dialog" id="editModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="edit-drafted" role="document">

            </div>
        </div>
    </div>

</div>

<div class="modal" role="dialog" id="createQuickSellModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
          <h4 class="modal-title">Create Group</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="edit-drafted" role="document">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-6 ml-3 mt-3">
                        <div class="form-group">
                            <strong>Name:</strong>
                            <?php echo Form::text("groupName", '', ["class" => "form-control-sm form-control", 'id' => 'groupName']); ?>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6 ml-3 mt-3">
                        <div class="form-group">
                        <button type="button" style="margin-top: 20px;padding: 5px;" id="btn-quick-sell" class="btn btn-sm btn-primary">Quick Sell</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(".select2").select2({});
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).on("click", ".btn-delete-multiple", function() {
        var products = [];
        $.each($("input[name='product_id_delete']:checked"), function() {
            products.push($(this).data("id"));
        });
        $.ajax({
            url: "/drafted-products/delete",
            type: 'post',
            data: {
                products: products
            },
            success: function(response) {
                alert("Deleted successfully!");
                location.reload();
            },
            error: function() {
                alert('Oops, Something went wrong!!');
            }
        });
    });

    $(document).on("click", "#btn-quick-sell", function() {
        var products = [];
        $.each($("input[name='product_id_delete']:checked"), function() {
            products.push($(this).data("id"));
        });
        var groupName=$.trim($("#groupName").val());
        if(groupName.length==0){
            alert("Please enter group name");
            return false;
        }
        $.ajax({
            url: "/drafted-products/addtoquicksell",
            type: 'post',
            data: {
                products: products,
                groupName:groupName
            },
            success: function(response) {
                alert("Products added into quick sell successfully!");
                $("#groupName").val(' ');
                location.reload();
            },
            error: function() {
                alert('Oops, Something went wrong!!');
            }
        });
    });


    $(document).on("click", ".more-list-btn", function() {
        var row = $(this).closest("td").find(".more-list-btn-row");
        var html = "";
        $.each(row.data("list"), function(k, r) {
            html += '<img src="' + r + '" alt="..." class="img-thumbnail col-md-4">';
        })
        $(".show-list-here").html(html);
        $("#show-more-images").modal("show");
    });

    $(document).on("click", ".edit-modal-button", function(e) {
        var id = $(this).data("product");
        $.ajax({
            url: "/drafted-products/edit",
            type: 'get',
            data: {
                id: id
            },
            success: function(response) {
                $("#editModal").find(".edit-drafted").html(response);
                $("#editModal").modal('show');
            },
            error: function() {
                alert('Oops, Something went wrong!!');
            }
        });
    });

    $(document).on("submit", "#formDraftedProduct", function(e) {
        e.preventDefault();
        var form = $("#formDraftedProduct");
        let formData = {
            id: $(this).data("id"),
            name: form.find('input[name="name"]').val(),
            brand_id: form.find('select[name="brand_id"] option:selected').val(),
            category: form.find('select[name=category] option:selected').val(),
            short_description: form.find('input[name=short_description]').val(),
            price: form.find('input[name=price]').val(),
            status_id: form.find('select[name=status_id] option:selected').val(),
            quick_product: form.find('select[name=quick_product] option:selected').val(),
            supplier_link: form.find('input[name=supplier_link]').val(),
            composition: form.find('input[name=composition]').val(),
            size: form.find('input[name=size]').val(),
            lmeasurement: form.find('input[name=lmeasurement]').val(),
            hmeasurement: form.find('input[name=hmeasurement]').val(),
            dmeasurement: form.find('input[name=dmeasurement]').val(),
            color: form.find('input[name=color]').val()
        }
        $.ajax({
            url: "/drafted-products/edit",
            type: 'post',
            datatype: 'json',
            data: formData,
            success: function(response) {
                $("#editModal").modal('hide');
                alert(response.message);
                location.reload();
            },
            error: function() {
                alert('Oops, Something went wrong!!');
            }
        });
    });
</script>

@endsection