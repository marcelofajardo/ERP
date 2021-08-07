@extends('layouts.app')

@section("styles")
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
@endsection

@section('content')
    <div class="row">
      <div class="col-lg-12 margin-tb">
        <h2 class="page-heading">Inventory List</h2>
      </div>
    </div>


    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            {{ $message }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="table-responsive">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>Brands</th>
            <th>Data</th>
          </tr>
        </thead>

        <tbody>
          @foreach ($inventory_data as $brand_name => $suppliers)
            <tr>
              <td>{{ $brand_name }}</td>
              <td>
                @foreach ($suppliers as $supplier_name => $categories)
                  <table class="table">
                    <th>{{ $supplier_name != '' ? $supplier_name : 'Without Supplier' }}</th>
                    <tr>
                      <td>
                        <table class="table">
                          @foreach ($categories as $main_id => $main)
                            <th>{{ $categories_array[$main_id] }}</th>
                          @endforeach
                          <tr>
                            @foreach ($categories as $main_id => $main)
                              <td>
                                <table class="table">
                                  @foreach ($main as $next_id => $count)
                                    <th>{{ $categories_array[$next_id] }}</th>

                                  @endforeach
                                  <tr>
                                  @foreach ($main as $next_id => $count)
                                      <td>{{ $count }}</td>

                                  @endforeach
                                </tr>

                                </table>
                              </td>
                            @endforeach
                          </tr>
                        </table>
                      </td>
                    </tr>
                  </table>
                @endforeach
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

@endsection

@section('scripts')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
  <script>

  </script>
@endsection
