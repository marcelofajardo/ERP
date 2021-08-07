@extends('layouts.app')

@section('title', 'Magento Product Api call')

@section("styles")
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
  <link href="https://cdn.datatables.net/1.10.9/css/jquery.dataTables.min.css" rel="stylesheet"/>
  <script src="https://cdn.datatables.net/1.10.9/js/jquery.dataTables.min.js"></script>
  <style type="text/css">

  #loading-image {
    position: fixed;
    top: 50%;
    left: 50%;
    margin: -50px 0px 0px -50px;
  }
  input {
    width: 130px;
  }
  thead tr th{
    width: 220px !important;
  }
  thead tr th:nth-child(5),thead tr th:nth-child(6),thead tr th:nth-child(7),thead tr th:nth-child(8),thead tr th:nth-child(9),thead tr th:nth-child(11),thead tr th:nth-child(12),thead tr th:nth-child(13),thead tr th:nth-child(14)
   ,thead tr th:nth-child(15),thead tr th:nth-child(16),thead tr th:nth-child(17),thead tr th:nth-child(18),thead tr th:nth-child(19),thead tr th:nth-child(20){
    width: 80px !important;
  }

  #magento_list_tbl_895_wrapper{
    padding : 10px;
  }
</style>
@endsection

@section('content')

  <div class="row">
    <div class="col-lg-12 margin-tb">
      <h2 class="page-heading">Magento Product API Call</h2>
    </div>
  </div>

  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-body p-0">
        <div class="table-responsive">
          <table id="magento_list_tbl_895" class="table table-bordered table-hover" style="table-layout:fixed;padding:10px">
            <thead>
              <th>Website</th>
              <th>Product SKU</th>
              <th>Product Name</th>
              <th>Category assigned</th>
              <th>Size Pushed</th>
              <th>Brand Pushed</th>
              <th>Size Chart Pushed</th>
              <th>Dimensions Pushed</th>
              <th>Composition Pushed</th>
              <th>Images Pushed</th>
              <th>English</th>
              <th>Arabic</ th>
              <th>German</th>
              <th>Spanish</th>
              <th>French</th>
              <th>Italian</th>
              <th>Japanese</th>
              <th>Korean</th>
              <th>Russian</th>
              <th>Chinese</th>
              <th>Status</th>
              </thead>
            </table>
            <div class="text-center">
            </div>
          </div>
        </div>
      </div>
    </div>
  @endsection
  @section('scripts')
    <script type="text/javascript">
    if (localStorage.getItem("luxury-product-data-asin") !== null) {
      var data = JSON.parse(localStorage.getItem('luxury-product-data-asin'));
      var example = $("#magento_list_tbl_895").DataTable({
        dom: 'flBrtip',
        stateSave: true,
        paging: true,
        processing: true,
        serverSide: true,
        bJQueryUI: true,
        ordering: false,
        lengthMenu: [[10, 25, 50,100,200, -1], [10, 25, 50,100,200, "All"]],
        ajax:{
          method: "POST",
          url: "/logging/magento-product-skus-ajax/",
          data: {
            "_token": "{{ csrf_token() }}",
            productSkus:JSON.stringify(data)
          }
        },  fixedColumns: true,
        language: {
          searchPlaceholder: "Search..."
        },columns:
        [
          {
            mRender: function (data, type, row)
            {
              return row.websites.join(', ')
            }
          },{
            mRender: function (data, type, row)
            {
              return row.sku
            }
          }, {
            mRender: function (data, type, row)
            {
              return row.product_name
            }
          },{
            mRender: function (data, type, row)
            {
              return row.category_names.join(', ')
            }
          },{
            mRender: function (data, type, row)
            {
              return row.size
            }
          },{
            mRender: function (data, type, row)
            {
              return row.brands ?  row.brands  :'Not Provided'
            }
          },{
            mRender: function (data, type, row)
            {
            return row.size_chart_url ? row.size_chart_url : "No"
            }
          },{
            mRender: function (data, type, row)
            {
              return row.dimensions ? row.dimensions : 'Not Provided'
            }
          },{
            mRender: function (data, type, row)
            {
              return row.composition ? row.composition : 'Not Provided'
            }
          },{
            mRender: function (data, type, row)
            {
              if(row.media_gallery_entries.length > 0 ){
                return row.media_gallery_entries[0].file
              }else{
                return 'Not Provided'
              }
            }
          },{
            mRender: function (data, type, row)
            {
              return row.english
            }
          },{
            mRender: function (data, type, row)
            {
              return row.arabic
            }
          },{
            mRender: function (data, type, row)
            {
              return row.german
            }
          },{
            mRender: function (data, type, row)
            {
              return row.spanish
            }
          },{
            mRender: function (data, type, row)
            {
              return row.french
            }
          },{
            mRender: function (data, type, row)
            {
              return row.italian
            }
          },{
            mRender: function (data, type, row)
            {
              return row.japanese
            }
          },{
            mRender: function (data, type, row)
            {
              return row.korean
            }
          },{
            mRender: function (data, type, row)
            {
              return row.russian
            }
          },{
            mRender: function (data, type, row)
            {
              return row.chinese
            }
          },{
            mRender: function (data, type, row)
            {
              return row.success ? "Success" : "Product not found in Website."
            }
          }
        ]
      });
    }
    </script>
    @if (Session::has('errors'))
      <script>
      toastr["error"]("{{ $errors->first() }}", "Message")
      </script>
    @endif
    @if (Session::has('success'))
      <script>
      toastr["success"]("{{Session::get('success')}}", "Message")
      </script>
    @endif
  @endsection
