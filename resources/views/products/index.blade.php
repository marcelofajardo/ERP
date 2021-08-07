@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
      <h2 class="page-heading">Products</h2>
      <div class="pull-left">
        <form action="/products{{ (isset($archived) && $archived == 'true') ? '?archived=false' : '?archived=true' }}" method="GET">
          <div class="form-group">
            <div class="row">
              <div class="col-md-8 pr-0">
                <input name="term" type="text" id="product-search" class="form-control" value="{{ isset($term) ? $term : '' }}" placeholder="Search">
              </div>
              <div class="col-md-4 pl-0">
                <button type="submit" class="btn btn-image"><img src="/images/search.png" /></button>
                @if (isset($archived) && $archived == 'true')
                  <a href="/products?archived=false" class="btn-link">Active</a>
                @else
                  <a href="/products?archived=true" class="btn-link">Archived</a>
                @endif
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="pull-right">
        @if(auth()->user()->checkPermission('products-create'))
          <a class="btn btn-secondary" href="{{ route('products.create') }}">+</a>
        @endif
      </div>
    </div>
</div>


@if ($message = Session::get('success'))
<div class="alert alert-success">
  <p>{{ $message }}</p>
</div>
@endif

<div class="table-responsive">
  <table class="table table-bordered">
    <tr>
      <th>No</th>
      <th>Image</th>
      <th>Sku</th>
      <th>Name</th>
      <th>Published On</th>
      <th width="280px">Action</th>
    </tr>
    @foreach ($products as $product)
    <tr>
      <td>{{ $product->id }}</td>
      <td>
        @if ($images = $product->getMedia(config('constants.media_tags')))
          @foreach ($images as $image)
            <img src="{{ $image->getUrl() }}" class="img-responsive" width="50px">
          @endforeach
        @endif
      </td>
      <td>{{ $product->sku }}</td>
      <td>{{ $product->name }}</td>
      <td>
         <?php echo Form::select("is_published[$product->id][]",$websiteList,$product->publishedOn(),[
          "class" => "form-control select2-mul update-product-store",
          "multiple" => true,
          "data-id" => $product->id
        ]); ?>
      </td>
      <td>
        <a class="btn btn-image" href="{{ route('products.show',$product->id) }}"><img src="/images/view.png" /></a>
        <a href class="btn btn-image edit-modal-button" data-toggle="modal" data-target="#editModal" data-product="{{ $product }}"><img src="/images/edit.png" /></a>

        @if (isset($archived) && $archived == 'true')
          {!! Form::open(['method' => 'POST','route' => ['products.restore', $product->id],'style'=>'display:inline']) !!}
          <button type="submit" class="btn btn-xs btn-secondary">Restore</button>
          {!! Form::close() !!}
        @else
          {!! Form::open(['method' => 'POST','route' => ['products.archive', $product->id],'style'=>'display:inline']) !!}
          <button type="submit" class="btn btn-image"><img src="/images/archive.png" /></button>
          {!! Form::close() !!}
        @endif

        <form action="{{ route('products.destroy',$product->id) }}" method="POST" style="display:inline">
          @csrf
          @method('DELETE')
          @if(auth()->user()->checkPermission('products-delete'))
          <button type="submit" class="btn btn-image"><img src="/images/delete.png" /></button>
          @endif
        </form>
      </td>
    </tr>
    @endforeach
  </table>
</div>

{!! $products->appends(Request::except('page'))->links() !!}

<script type="text/javascript">
  $(".select2-mul").select2({tags:true});

  $(document).on("change",".update-product-store",function() {
    var $this = $(this);
     $.ajax({
          url: "/products/published",
          type: 'POST',
          data: {
              id: $this.data("id"),
              _token: "{{csrf_token()}}",
              website: $this.val(),
          },
          success: function (response) {
            toastr['success']('Product updated for store!!', 'success');
          },
          error: function () {
              alert('Oops, Something went wrong!!');
          }
      });
  });

</script>

@endsection
