<label for="invoice_no">Invoice Number</label>
<div class="form-group">
  <input type="text" class="form-control" name="invoice_no" id="invoice_no" value="" required autofocus>
</div>
<label for="customer_name">Select Customer Name</label>
<div class="form-group">
  <select name="customer" type="text" class="form-control" placeholder="Search by customer name" id="customer-name-search" data-allow-clear="true">
    <?php
    if (request()->get('customer')) {
      echo '<option value="'.request()->get('customer').'" selected>'.request()->get('customer').'</option>';
    }
    ?>
  </select>
</div>
<label for="customer_name">Select Product</label>  <button class="btn btn-secondary btn-xs ml-2 add-new-product" >Add new product</button>
<div class="form-group">
  <select name="product" type="text" class="form-control" placeholder="Search by product name and short description" id="product-name-search" data-allow-clear="true">
    <?php
    if (request()->get('product')) {
      echo '<option value="'.request()->get('product').'" selected>'.request()->get('product').'</option>';
    }
    ?>
  </select>
</div>
<div class="form-group add-new-product-form d-none">
  <div class="row">
      <div class="col-md-3">
        <input type="text" name="sku" class="form-control" placeholder="SKU">
      </div>
      <div class="col-md-3">
        <input type="number" name="price" class="form-control" placeholder="Price">
      </div>
      <div class="col-md-3">
        <input type="text" name="size" class="form-control" placeholder="Size">
      </div>
      <div class="col-md-3">
        <input type="text" name="name" class="form-control" placeholder="name">
      </div>
  </div>
  <div class="row">
      <div class="col-md-12">
          <button class="btn btn-secondary btn-xs pull-right mt-1 save-new-product"> Save </button>
      </div>
  </div>
</div>
<div class="product_list_class">
  <p style="float:left"><strong>Total Products:</strong><strong id="product_count90"></strong></p>
  <div class="table-responsive">
    <table class="table" id="product_table">
      <thead>
        <th>
          Product Name
        </th>
        <th>
          Product Price
        </th>
        <th>
          Product Qty
        </th>
      </thead>
      <tbody id="product_table_body">

      </tbody>
    </table>
  </div>
</div>
<button type="button" name="submit" class="btn btn-primary btn-sm" id="save_cart_btn">Save</button>
<script type="text/javascript">
//variables
var slected_products_ids =[]
var products_list =[]
var products_for_display=[]

$(".add-new-product").click(function(){
    $(".add-new-product-form").toggleClass("d-none");
});

$('.save-new-product').on('click',function(){
  
  var price = $('input[name="price"]').val();
  var size = $('input[name="size"]').val();
  var name = $('input[name="name"]').val();
  var sku = $('input[name="sku"]').val();
  console.log(price, size, name, sku);
  if( price == '' || size == '' || name == '' || sku == '' ){
    alert('Please fill up details');
    return false;
  };

  $.ajax({
     headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      url: "/order/create-product",
      type: "post",
      data:{ sku:sku, price:price, size:size, name:name }
    }).done(function(response) {
      alert(response.message);
    }).fail(function(errObj) {
      console.log(errObj)
    });
})

//Product Search
$('#product-name-search').select2({
  tags: true,
  width : '100%',
  ajax: {
    url: '/customers/product-search',
    dataType: 'json',
    delay: 500,
    data: function (params) {
      return {
        q: params.term, // search term
      };
    },
    processResults: function (data, params) {
      //clear product list previous data
      products_list = [];
      //assign new data to product list
      products_list.push(data)
      for (var i in data) {
        data[i].id = data[i].id ? data[i].id : data[i].name;
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
  placeholder: 'Search by product name and short description',
  escapeMarkup: function (markup) {
    return markup;
  },
  minimumInputLength: 1,
  templateResult: function (product) {
    if (product.loading) {
      return product.text;
    }
    if (product.name) {
      return "<p><b>Product Name:</b> " + product.name +"</p>";
    }
  },
  templateSelection: (product) =>product.name
});

//on product select 2 select
$('#product-name-search').on('select2:select', function (e) {
  e.preventDefault();
  var $this = $(this);
  var product_id = parseInt($this.val())
  slected_products_ids.push(product_id)
  products_list.forEach((item, i) => {
    item.filter((p, z) => {
      if(p.id === product_id){
        products_for_display.push(p)
        $('#product_table_body').append("<tr><td>"+p.name+"</td><td>"+p.price+"</td><td><input class='qty_class_vals' type='text' value='' name='qty'/></td></tr>")
      }
    });
  });
  $('#product_count90').text(products_for_display.length)
})
//Customer Search
$('#customer-name-search').select2({
  tags: true,
  width : '100%',
  ajax: {
    url: '/customers/customer-search',
    dataType: 'json',
    delay: 500,
    data: function (params) {
      return {
        q: params.term, // search term
      };
    },
    processResults: function (data, params) {

      for (var i in data) {
        data[i].id = data[i].id ? data[i].id : data[i].name;
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
  placeholder: 'Search by customer name',
  escapeMarkup: function (markup) {
    return markup;
  },
  minimumInputLength: 1,
  templateResult: function (customer) {
    if (customer.loading) {
      return customer.text;
    }
    if (customer.name) {
      return "<p><b>Customer Name:</b> " + customer.name +"</p>";
    }
  },
  templateSelection: (customer) =>customer.name,

});
$('#save_cart_btn').on('click',function(){
  var qty = qty_collector()
  var invoice_no = $('#invoice_no').val()
  var customer_id = $('#customer-name-search').val()
  $.ajax({
     headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      url: "/order",
      type: "post",
      data:{invoice_no:invoice_no,qty:JSON.stringify(qty),customer_id:customer_id,slected_products_ids:JSON.stringify(slected_products_ids)}
    }).done(function(response) {
      if(response.code == 200){
        toastr['success']('Success');
      }else{
        toastr['error']( 'Something went wrong' );
      }
    }).fail(function(xhr, status, error) {
        var err = eval("(" + xhr.responseText + ")");
        toastr['error']( err.message );
        
    });
})
function qty_collector(){
  var qty = []
  $('.qty_class_vals').each(function(i, obj) {
    qty.push(parseInt(obj.value))
  });
  return qty;
}
$('#product_table tbody').on('click', 'tr', function () {
  var ref = $(this);
  $(this).find('td:eq(2)').children('.qty_class_vals').keyup(function(){
    $(this).attr("value",$(this).val())
  })
})

</script>
