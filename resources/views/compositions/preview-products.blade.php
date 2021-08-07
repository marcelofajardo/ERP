<div class="modal-content">
   <div class="modal-header">
      <h5 class="modal-title">List Of Scraped Products</h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
   </div>
   <div class="modal-body">
        <div class="row">
            <table class="table table-bordered">
                <thead>
                  <tr>
                    <th width="2%"></th>
                    <th width="2%">SKU</th>
                    <th width="2%">Website</th>
                    <th width="38%">Title</th>
                    <th width="30%">Created At</th>
                  </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                      <tr>
                        <td></td>
                        <td>{{ $product->sku }}</td>
                        <td>{{ $product->website }}</td>
                        <td>{{ $product->title }}</td>
                        <td>{{ $product->created_at }}</td>
                      </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>