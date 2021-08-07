@include("product-inventory.partials.grid")
<div class="row">
  {!! $products->appends(Request::except('page'))->links() !!}
</div>