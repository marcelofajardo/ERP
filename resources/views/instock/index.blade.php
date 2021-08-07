@extends('layouts.app')

@section('favicon' , 'instock.png')

@section('title', 'Instock Info')

@section("styles")
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
@endsection
<style type="text/css">
  .dis-none {
    display: none;
  }
</style>
@section('content')
  <div class="row">
    <div class="col-lg-12 margin-tb">
      <div class="">
        <h2 class="page-heading">In stock Products</h2>


        <!--Product Search Input -->
        <form action="{{ route('productinventory.instock') }}" method="GET" id="searchForm" class="form-inline align-items-start">
      <div class="d-flex" style="flex-direction: column;width:100%">
          <div class="d-flex" >

          <input type="hidden" name="type" value="{{ $type }}">
          <input type="hidden" name="date" value="{{ $date }}">
          <div class="form-group mr-3 mb-3" style="flex:1">
            <input name="term" type="text" class="form-control" id="product-search" value="{{ isset($term) ? $term : '' }}" placeholder="sku,brand,category,status" style="width: 100%">
          </div>
          <div class="form-group mr-3 mb-3 "  style="flex:1">
            {!! $category_selection !!}
          </div>

          <div class="form-group mr-3 mb-3"  style="flex:1">
             <?php echo Form::select("stock_status",[ "" => "--Select--"] + \App\Product::STOCK_STATUS,request("stock_status"),["class" => "form-control"]); ?>
          </div>
          <div class="form-group mr-3 mb-3"  style="flex:1">
            <select class="form-control globalSelect2" data-placeholder="Select brands" data-ajax="{{ route('select2.brands',['sort'=>true]) }}"
                name="brand[]" multiple>

                @if ($selected_brand)        
                    @foreach($selected_brand as $brand)
                        <option value="{{ $brand->id }}" selected>{{ $brand->name }}</option>
                    @endforeach
                @endif

            </select>
        </div>
          <div class="form-group mr-3 mb-3"  style="flex:1">
              <input placeholder="Shoe Size" type="text" name="shoe_size" value="{{request()->get('shoe_size')}}" class="form-control-sm form-control" style="width: 100%">
          </div>

          <div class="form-group mr-3 mb-3"  style="flex:1">
            @php $colors = new \App\Colors();
            @endphp
            <select data-placeholder="Select color"  class="form-control select-multiple2" name="color[]" multiple>
              <optgroup label="Colors">
                @foreach ($colors->all() as $id => $name)
                  {{-- <option value="{{ $id }}" {{ isset($color) && $color == $id ? 'selected' : '' }}>{{ $name }}</option> --}}
                  <option value="{{ $id }}" {{ isset($color) && in_array($name,$color) ? 'selected' : '' }}>{{ $name }}</option>
                  
                @endforeach
              </optgroup>
            </select>
          </div>

          @if (Auth::user()->hasRole('Admin'))

            <div class="form-group mr-3 mb-3"  style="flex:1">
              <select data-placeholder="Select location" class="form-control select-multiple2" name="location[]" multiple>
                <optgroup label="Locations">
                  @foreach ($locations as $name)
                    {{-- <option value="{{ $name }}" {{ isset($location) && $location == $name ? 'selected' : '' }}>{{ $name }}</option> --}}
                    <option value="{{ $name }}" {{  isset($location) && in_array($name,$location) ? 'selected' : '' }}>{{ $name }}</option>
                  @endforeach
                </optgroup>
              </select>
            </div>
            @endif
    </div>
    <div class="d-flex justify-content-between">
      <div class="">
        @if (Auth::user()->hasRole('Admin'))
            <div class="form-group mr-3">
              <input type="checkbox" name="no_locations" id="no_locations" {{ isset($no_locations) ? 'checked' : '' }}>
              <label for="no_locations">With no Locations</label>
            </div>
        @endif

          <div class="form-group">
            <input type="checkbox" id="in_pdf" name="in_pdf"> <label for="in_pdf">Export PDF</label>
          </div>

          <button type="submit" class="btn btn-image"><img src="/images/filter.png" /></button>
        </div>
        <div class="">
          <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#productModal">Upload Products</button>
          <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#updateBulkProductModal" id="bulk-update-btn">Update Products</button>
          <button type="button" class="btn btn-secondary" id="js-send-product-btn">Send Products</button>
        </div>

    </div>
  </div>
        </form>


      </div>
    </div>
  </div>



  @include('instock.partials.product-modal')
  @include('instock.partials.bulk-upload-modal')

  @include('partials.flash_messages')

<?php
  $query = http_build_query( Request::except('page' ) );
  $query = url()->current() . ( ( $query == '' ) ? $query . '?page=' : '?' . $query . '&page=' );
?>


  <div class="productGrid" id="productGrid">
    <div class="row">
      <div class="col-2">
        <div class="form-group">
          Goto :
          <select onchange="location.href = this.value;" class="form-control">
            @for($i = 1 ; $i <= $products->lastPage() ; $i++ )
              <option value="{{ $query.$i }}" {{ ($i == $products->currentPage() ? 'selected' : '') }}>{{ $i }}</option>
              @endfor
          </select>
        </div>
      </div>
    </div>
    <div class="infinite-scroll">
        @include('instock.product-items')
    </div>
  </div>

  <div id="instruction-model" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Create Dispatch / Location Change</h4>
        </div>
        <form id="store-instruction-stock" action="<?php echo route("productinventory.instruction") ?>" method="post">
          <?php echo csrf_field(); ?>
          <div class="modal-body">
                                      
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary create-instruction-receipt">Save</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </form>  
      </div>
    </div>
  </div>

  <div id="instruction-dispatch-model" class="modal fade" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Create Dispatch</h4>
        </div>
        <form id="store-dispatch-stock" action="<?php echo route("productinventory.dispatch.store") ?>" enctype="multipart/form-data" method="post">
          <?php echo csrf_field(); ?>
          <div class="modal-body">    
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary create-dispatch-store">Save</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </form>  
      </div>
    </div>
  </div>

    <div id="crt-attach-images-model" class="modal fade" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Attach Images to Message</h4>
          </div>
          <form id="crt-attach-images-frm">
           <?php echo csrf_field(); ?>
            <div class="modal-body">    
               <div class="form-group">
                <label for="customer_id">Customer:</label>
                <?php echo Form::select("customer_id", [], null,["class"=> "form-control customer-search-box", "style"=>"width:100%;"]);  ?>
              </div>
            </div>
            <div class="modal-body">    
               <div class="form-group">
                <label for="customer_id">Message:</label>
                <textarea name="message" class="form-control"></textarea>
              </div>
            </div>
            <input type="hidden" name="images" id="images" value="">
            <input type="hidden" name="image" value="">
            <input type="hidden" name="screenshot_path" value="">
            <input type="hidden" name="status" value="2">
            <div class="modal-footer">
              <button type="submit" class="btn btn-secondary btn-send-attached-img">Send</button>
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
          </form>  
        </div>
    </div>
  </div>

  <div id="instruction-model-dynamic" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title"></h4>
        </div>
          <div class="modal-body">
                                      
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
      </div>
    </div>
  </div>

  <form action="{{ route('stock.privateViewing.store') }}" method="POST" id="selectProductForm">
    @csrf
    <input type="hidden" name="date" value="{{ $date }}">
    <input type="hidden" name="customer_id" value="{{ $customer_id }}">
    <input type="hidden" name="products" id="selected_products_private_viewing" value="">
  </form>

  <div id="show-more-content" class="modal fade" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
            <div class="modal-body">
             </div> 
      </div>
    </div>
  </div>

@endsection

@section('scripts')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jscroll/2.3.7/jquery.jscroll.min.js"></script>
  <script>

     var customerSearch = function() {
        $(".customer-search-box").select2({
          tags : true,
          ajax: {
              url: '/erp-leads/customer-search',
              dataType: 'json',
              delay: 750,
              data: function (params) {
                  return {
                      q: params.term, // search term
                  };
              },
              processResults: function (data,params) {

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
          escapeMarkup: function (markup) { return markup; },
          minimumInputLength: 2,
          templateResult: formatCustomer,
          templateSelection: (customer) => customer.text || customer.name,

      });
    };

    customerSearch();

    function formatCustomer (customer) {
        if (customer.loading) {
            return customer.name;
        }

        if(customer.name) {
            return "<p> <b>Id:</b> " +customer.id  + (customer.name ? " <b>Name:</b> "+customer.name : "" ) +  (customer.phone ? " <b>Phone:</b> "+customer.phone : "" ) + "</p>";
        }

    }


    $(document).on('click', '.quick_category_add', function () {
        var textBox = $(this).closest(".quick-category-sec").find(".quick_category");

        if (textBox.val() == "") {
            alert("Please Enter Category!!");
            return false;
        }

        $.ajax({
            type: "POST",
            url: "{{ route('add.reply.category') }}",
            data: {
                '_token': "{{ csrf_token() }}",
                'name': textBox.val()
            }
        }).done(function (response) {
            textBox.val('');
            $(".quickCategory").append('<option value="[]" data-id="' + response.data.id + '">' + response.data.name + '</option>');
        })
    });

    $(document).on('click', '.delete_category', function () {
        var quickCategory = $(this).closest(".quick-category-sec").find(".quickCategory");

        if (quickCategory.val() == "") {
            alert("Please Select Category!!");
            return false;
        }

        var quickCategoryId = quickCategory.children("option:selected").data('id');
        if (!confirm("Are sure you want to delete category?")) {
            return false;
        }
        $.ajax({
            type: "POST",
            url: "{{ route('destroy.reply.category') }}",
            data: {
                '_token': "{{ csrf_token() }}",
                'id': quickCategoryId
            }
        }).done(function (response) {
            location.reload();
        })
    });

    $(document).on('click', '.delete_quick_comment', function () {
        var quickComment = $(this).closest(".quick-category-sec").find(".quickComment");

        if (quickComment.val() == "") {
            alert("Please Select Quick Comment!!");
            return false;
        }

        var quickCommentId = quickComment.children("option:selected").data('id');
        if (!confirm("Are sure you want to delete comment?")) {
            return false;
        }
        $.ajax({
            type: "DELETE",
            url: "/reply/" + quickCommentId,
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            },
        }).done(function (response) {
            location.reload();
        })
    });

    $(document).on('click', '.quick_comment_add', function () {
        var textBox = $(this).closest(".quick-category-sec").find(".quick_comment");
        var quickCategory = $(this).closest(".quick-category-sec").find(".quickCategory");

        if (textBox.val() == "") {
            alert("Please Enter New Quick Comment!!");
            return false;
        }

        if (quickCategory.val() == "") {
            alert("Please Select Category!!");
            return false;
        }

        var quickCategoryId = quickCategory.children("option:selected").data('id');

        var formData = new FormData();

        formData.append("_token", "{{ csrf_token() }}");
        formData.append("reply", textBox.val());
        formData.append("category_id", quickCategoryId);
        formData.append("model", 'Product Dispatch');

        $.ajax({
            type: 'POST',
            url: "{{ route('reply.store') }}",
            data: formData,
            processData: false,
            contentType: false
        }).done(function (reply) {
            textBox.val('');
            $('.quickComment').append($('<option>', {
                value: reply,
                text: reply
            }));
        })
    });

    $(document).on('change', '.quickCategory', function () {
        if($(this).val() != "") {
            var replies = JSON.parse($(this).val());
            var thiss = $(this);
            $(this).closest(".quick-category-sec").find('.quickComment').empty();
            $(this).closest(".quick-category-sec").find('.quickComment').append($('<option>', {
                value: '',
                text: 'Quick Reply'
            }));

            replies.forEach(function (reply) {
                $(thiss).closest(".quick-category-sec").find('.quickComment').append($('<option>', {
                    value: reply.reply,
                    text: reply.reply,
                    'data-id': reply.id
                }));
            });
        }
    });

    $(document).on('change', '.quickComment', function () {
        $('.quick-message-field').val($(this).val());
    });

    $(document).on('change', '.location-change-product', function () {
        $this = $(this);
        $.ajax({
          url: "<?php echo route('productinventory.location.change'); ?>",
          data : {
            product_id : $this.data("product-id"),
            location : $(this).val()
          },
          method : "get",
          dataType : "json"
        }).done(function(data) {
          var producthistory = data.productHistory;
            $html = '<tr><td>'+producthistory.location_name+'</td><td></td><td></td><td></td><td>'+producthistory.date_time+'</td><td>'+data.userName+'</td></tr>';
            $(".location_"+$this.data("product-id")).html("Location : " +$this.val());
            $(document).find('.product-location-history').append($html);
            alert("Location has been updated successfully");
        }).fail(function() {
          
        });
    });

    

    $(document).on('click', '.crt-instruction', function(e) {
      e.preventDefault();

      var $this = $(this);
      var instructionModal = $("#instruction-model");

      $.ajax({
          url: "<?php echo route('productinventory.instruction.create'); ?>",
          data : {
            product_id : $this.data("product-id")
          },
          method : "get"
        }).done(function(data) {

           instructionModal.find(".modal-body").html(data);
           $('.date-time-picker').datetimepicker({
              format: 'YYYY-MM-DD HH:mm'
           });

           customerSearch();

           instructionModal.modal("show");
        }).fail(function() {
          
        });

      /*var model = $("#instruction-model");
          model.find(".instruction-pr-id").val($(this).data("product-id"));
          model.modal("show");*/
    });

    $(document).on('click', '.crt-product-dispatch', function(e) {
      e.preventDefault();

      var $this = $(this);
      var instructionModal = $("#instruction-dispatch-model");

      $.ajax({
          url: "<?php echo route('productinventory.dispatch.create'); ?>",
          data : {
            product_id : $this.data("product-id")
          },
          method : "get"
        }).done(function(data) {

           instructionModal.find(".modal-body").html(data);

           $('.date-time-picker').datetimepicker({
              format: 'YYYY-MM-DD HH:mm'
           });

           instructionModal.modal("show");
        }).fail(function() {
          
        });
    });

    $(document).on('click', '.crt-attach-images', function(e) {
      e.preventDefault();

      var $this = $(this);
      var instructionModal = $("#crt-attach-images-model");
      instructionModal.find("#images").val(JSON.stringify(($this.data('media-ids') + '').split(",")));
      instructionModal.modal("show");
    });

    $(document).on('submit', '#crt-attach-images-frm', function(e) {
        e.preventDefault();
        var $this = $(this);
        $.ajax({
          url: "<?php echo route('whatsapp.send', 'customer'); ?>",
          data : $("#crt-attach-images-frm").serialize(),
          method : "post",
          beforeSend : function(){
            $this.find(".btn-send-attached-img").html('Sending Request..');
          }
        }).done(function(data) {
           $("#crt-attach-images-model").modal("hide");
        }).fail(function() {
          
        });

        $this.find(".btn-send-attached-img").html('Send');
        return false;
    });
    
    $(document).on('click', '.create-dispatch-store', function(e) {
      e.preventDefault();

      var $this = $(this);
      var instructionModal = $("#instruction-dispatch-model");
      var instructionForm = $("#store-dispatch-stock");

      var formData = new FormData(instructionForm[0]);

      $.ajax({
          headers: {
             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          url: instructionForm.attr("action"),
          data : formData,
          method : "post",
          processData: false,
          contentType: false,
          beforeSend : function(){
            $this.html('Sending Request..');
          }
        }).done(function(data) {
           $this.html('Save')
            ;
           if(data.code == 0) {
             var errors = "";
             $.each(data.errors,function(kE,vE){
                $.each(vE,function(eK, Ev){
                  errors += Ev+"<br>";
                })
             });
             $("#instruction-dispatch-model").find(".alert-danger").remove();
             $("#instruction-dispatch-model").find(".modal-body").prepend('<div class="alert alert-danger" role="alert">'+errors+'</div>');
           }else if(data.code == 1) {
              $('.transist_status_'+(instructionForm.find('.instruction-pr-id').val())).text('Transist Status : Delivered');
              $('.location_'+(instructionForm.find('.instruction-pr-id').val())).text('Location :');
              instructionForm.find(".alert-danger").remove();
              $("#instruction-dispatch-model").find(".modal-body").prepend('<div class="alert alert-success" role="alert">Instruction created successfully</div>');
              setTimeout(function(){ 
                instructionForm.find(".alert-success").remove();
                $("#instruction-dispatch-model").modal("hide");
                location.reload();
              }, 3000);

           }

        }).fail(function() {
          
        });
    });

    $(document).on('change', '.instruction-type-select', function(e) {
       if($(this).val() == "dispatch") {
         $("#instruction-model").find(".dispatch-instruction").removeClass("dis-none");
       }else{
         $("#instruction-model").find(".dispatch-instruction").addClass("dis-none");
       }
    });

    $(document).on('click', '.create-instruction-receipt', function(e) {
      e.preventDefault
        ();
      var $this = $(this);
      var instructionForm = $("#instruction-model").find("form");
      $.ajax({
          url: "<?php echo route('productinventory.instruction'); ?>",
          method : "post",
          data : instructionForm.serialize(),
          beforeSend : function(){
            $this.html('Sending Request..');
          }
        }).done(function(data) {
           $this.html('Save');
           if(data.code == 0) {
             var errors = "";
             $.each(data.errors,function(kE,vE){
                $.each(vE,function(eK, Ev){
                  errors += Ev+"<br>";
                })
             });
             $("#instruction-model").find(".alert-danger").remove();
             $("#instruction-model").find(".modal-body").prepend('<div class="alert alert-danger" role="alert">'+errors+'</div>');
           }else if(data.code == 1) {
              instructionForm.find(".alert-danger").remove();
              $("#instruction-model").find(".modal-body").prepend('<div class="alert alert-success" role="alert">Instruction created successfully</div>');
              setTimeout(function(){ 
                instructionForm.find(".alert-success").remove();
                $("#instruction-model").modal("hide");
                location.reload();
              }, 3000);
           }
        }).fail(function() {
          
        });

    });

    $(document).on('click', '.crt-instruction-history', function(e) {
      e.preventDefault();
      var $this = $(this);
      var instructionModal = $("#instruction-model-dynamic");
      instructionModal.find(".modal-title").html("Product Location History");
      $.ajax({
          url: "<?php echo route('productinventory.location.history'); ?>",
          data : {
            product_id : $this.data("product-id")
          },
          method : "get"
        }).done(function(data) {

           instructionModal.find(".modal-body").html(data);
           instructionModal.modal("show");
        }).fail(function() {
          
        });

    });

    var product_array = [];
         $('ul.pagination').not(":last").remove();

         $(".select-multiple").multiselect();
         $(".select-multiple2").select2();
         $('ul.pagination').hide();
         $(function () {
              $('.infinite-scroll').jscroll({
                  autoTrigger: true,
                  loadingHtml: '<img class="center-block" src="/images/loading.gif" alt="Loading..." />',
                  padding: 2500,
                  nextSelector: '.pagination li.active + li a',
                  contentSelector: 'div.infinite-scroll',
                  callback: function () {
                     
                      $('ul.pagination').not(":last").remove();
                      $(".select-multiple2").select2();
                  }
              });
          });

    // $('#product-search').autocomplete({
    //   source: function(request, response) {
    //     var results = $.ui.autocomplete.filter(searchSuggestions, request.term);
    //
    //     response(results.slice(0, 10));
    //   }
    // });

    /*$(document).on('click', '.pagination a', function(e) {
      e.preventDefault();
      var url = $(this).attr('href');

      getProducts(url);
    });

    function getProducts(url) {
      $.ajax({
        url: url
      }).done(function(data) {
        $('#productGrid').html(data.html);
      }).fail(function() {
        alert('Error loading more products');
      });
    }*/


    $(document).on('click', '.select-product', function(e) {
      e.preventDefault();
      var product_id = $(this).data('id');

      if ($(this).data('attached') == 0) {
        $(this).data('attached', 1);
        product_array.push(product_id);
      } else {
        var index = product_array.indexOf(product_id);

        $(this).data('attached', 0);
        product_array.splice(index, 1);
      }

      console.log(product_array);

      $(this).toggleClass('btn-success');
      $(this).toggleClass('btn-secondary');
    });

    $(document).on('click', '#privateViewingButton', function() {
      if (product_array.length == 0) {
        alert('Please select some products');
      } else {
        $('#selected_products_private_viewing').val(JSON.stringify(product_array));
        $('#selectProductForm').submit();
      }
    });

    var select_products_edit_array = [];
    var select_products_image_array = [];

    $(document).on('click', '.select-product-edit', function() {
      var id = $(this).data('id');
      var mediaIds = ($(this).closest('a').find('.crt-attach-images').data('media-ids') + '').split(",");
      if ($(this).prop('checked')) {
        select_products_edit_array.push(id);
        for(var i in mediaIds) {
          if (select_products_image_array.indexOf(mediaIds[i]) == -1) {
            select_products_image_array.push(mediaIds[i]);
          }
        }
      } else {
        var index = select_products_edit_array.indexOf(id);

        select_products_edit_array.splice(index, 1);
        
        for(var i in mediaIds) {
          var index = select_products_image_array.indexOf(mediaIds[i])
          select_products_image_array.splice(index, 1);
        }
      }

      console.log(select_products_edit_array);
    });

    $('#bulk-update-btn').on('click', function(e) {
      if (select_products_edit_array.length == 0) {
        e.stopPropagation();

        alert('Please select atleast 1 product!');
      }
    });

    $('#js-send-product-btn').on('click', function(e) {
      if (select_products_image_array.length == 0) {
        e.stopPropagation();

        alert('Please select atleast 1 product!');
      } else {
        var instructionModal = $("#crt-attach-images-model");
            instructionModal.find("#images").val(JSON.stringify(select_products_image_array));
            instructionModal.modal("show");
      }
    });

    $('#bulkUpdateButton').on('click', function() {
      $('#selected_products').val(JSON.stringify(select_products_edit_array));

      $(this).closest('form').submit();
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

    $(document).on("dblclick",".text-editable",function(){
      
      var val = $(this).html();
      if(val == "N/A") {
          val = "";
      }
      var fieldName = $(this).data("field-name");
      var productId = $(this).data("product-id");
      
      $(this).replaceWith('<input type="text" name="'+fieldName+'" data-field-name="'+fieldName+'" data-product-id="'+productId+'" class="editable-input form-control" value=\"' + val + '\" />');
      $(this).trigger("focus");
    });

    $(document).on('blur', '.editable-input', function(){
    
      var $this = $(this);
      var val = $this.val();
      var fieldName = $this.data("field-name");
      var productId = $this.data("product-id");

      if(val == "N/A") {
         alert("Please Enter Correct Value");
         $this.replaceWith('<span class="text-editable" data-field-name="'+fieldName+'" data-product-id="'+productId+'">' + val + '</span>');
         return false;
      }

      $.ajax({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          type: "post",
          url: '<?php echo route("productinventory.instock.update-field"); ?>',
          data: {
              "id": productId,
              "field_name": fieldName,
              "field_value" : val
          },
          dataType: "json",
          success: function(response) {
              if (response.code != 200) {
                  toastr['error'](response.message);
              } else {
                  toastr['success']('Success!');
                  $this.replaceWith('<span class="text-editable" data-field-name="'+fieldName+'" data-product-id="'+productId+'">' + val + '</span>');
              }
          },
          error: function() {
              toastr['error']('Can not store value please review!');
          }
      });

    });
    $(document).on('change', '.update-product-stock-status', function () {
        $this = $(this);
        $.ajax({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          type: "post",
          url: '<?php echo route("productinventory.instock.update-field"); ?>',
          data: {
              "id": $this.data("product-id"),
              "field_name": "stock_status",
              "field_value" : $this.val()
          },
          dataType: "json",
          success: function(response) {
              if (response.code != 200) {
                  toastr['error'](response.message);
              } else {
                  toastr['success']('Success!');
              }
          },
          error: function() {
              toastr['error']('Can not store value please review!');
          }
      });
    });

    $(document).on("click",".show-more-content-btn",function (){
        var text  = $(this).data("text");
        $("#show-more-content").find(".modal-body").html(text);
        $("#show-more-content").modal("show");
    });

  </script>
@endsection
