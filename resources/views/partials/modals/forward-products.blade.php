<div id="forwardProductsModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <form action="" id="forward-products-form" method="POST">
        @csrf

        <div class="modal-header">
          <h4 class="modal-title">Attach to a customer</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
        <input type="hidden" value="" name="forward_suggestedproductid" id="forward_suggestedproductid"/>
        <input type="hidden" name="products" id="product_lists" value="">
        <input type="hidden" name="type" id="forward_type" value="">
          <div class="form-group">
            <strong>Customer:</strong>
            <!-- <select class="form-control select2" name="customer_id" required>
              <option value="">Select Customer</option>
              @php 
              $customers = \App\Customer::pluck('name','id');
              @endphp
              @foreach ($customers as $key => $customer)
                <option value="{{ $key }}">{{ $customer }}</option>
              @endforeach
            </select> -->
            <select name="customer_id" type="text" class="form-control" placeholder="Search" id="customer-search1" data-allow-clear="true">
            </select>
            
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-secondary">Forward</button>
        </div>
      </form>
    </div>

  </div>
</div>


<script>
$('#customer-search1').select2({
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
                    for (var i in data) {
                        if(data[i].name) {
                            var combo = data[i].name+'/'+data[i].id;
                        }
                        else {
                            var combo = data[i].text;
                        }
                        data[i].id = combo;
                    }
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
            minimumInputLength: 1,
            templateResult: function (customer) {
                if (customer.loading) {
                    return customer.name;
                }
                if (customer.name) {
                    return "<p> " + (customer.name ? " <b>Name:</b> " + customer.name : "") + (customer.phone ? " <b>Phone:</b> " + customer.phone : "") + "</p>";
                }
            },
            templateSelection: (customer) => customer.text || customer.name,
        });
</script>
