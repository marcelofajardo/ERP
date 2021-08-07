@extends('layouts.app')

@section('styles')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.5/css/bootstrap-select.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
@endsection

@section('content')

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <h2 class="page-heading">Budget</h2>
            <div class="pull-left">
              <form class="form-inline" action="{{ route('budget.index') }}" method="GET">
                <div class="form-group">
                  <div class='input-group date' id='date-filter'>
                    <input type='text' class="form-control" name="date" value="{{ $date }}" required />

                    <span class="input-group-addon">
                      <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                  </div>
                </div>
                <button type="submit" class="btn btn-secondary ml-3">Submit</button>
              </form>
            </div>
            <div class="pull-right">
              <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#budgetCreateModal">+</a>
            </div>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
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

    <div id="exTab2" class="container mt-3">
      <ul class="nav nav-tabs">
        <li class="active">
          <a href="#fixed_tab" data-toggle="tab">Fixed</a>
        </li>
        <li>
          <a href="#variable_tab" data-toggle="tab">Variable</a>
        </li>
      </u>
    </div>

    <div class="tab-content">
      <div class="tab-pane active mt-3" id="fixed_tab">
        <div class="table-responsive mt-3">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>Date</th>
                <th>Description</th>
                <th>Amount</th>
                <th>Category</th>
                <th>Actions</th>
              </tr>
            </thead>

            <tbody>
              @foreach ($fixed_budgets as $budget)
                <tr>
                  <td>{{ \Carbon\Carbon::parse($budget->date)->format('d-m') }}</td>
                  <td>{{ $budget->description }}</td>
                  <td>{{ $budget->amount }}</td>
                  <td>
                    {{ $budget->category->name }} -
                    {{ $budget->subcategory->name }}
                  </td>
                  <td>
                    {!! Form::open(['method' => 'DELETE','route' => ['budget.destroy', $budget->id],'style'=>'display:inline']) !!}
                      <button type="submit" class="btn btn-image"><img src="/images/delete.png" /></button>
                    {!! Form::close() !!}
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        {!! $fixed_budgets->appends(Request::except('page'))->links() !!}
      </div>

      <div class="tab-pane mt-3" id="variable_tab">
        <div class="table-responsive mt-3">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>Date</th>
                <th>Description</th>
                <th>Amount</th>
                <th>Category</th>
                <th>Actions</th>
              </tr>
            </thead>

            <tbody>
              @foreach ($variable_budgets as $budget)
                <tr>
                  <td>{{ \Carbon\Carbon::parse($budget->date)->format('d-m') }}</td>
                  <td>{{ $budget->description }}</td>
                  <td>{{ $budget->amount }}</td>
                  <td>
                    {{ $budget->category->name }} -
                    {{ $budget->subcategory->name }}
                  </td>
                  <td>
                    {!! Form::open(['method' => 'DELETE','route' => ['budget.destroy', $budget->id],'style'=>'display:inline']) !!}
                      <button type="submit" class="btn btn-image"><img src="/images/delete.png" /></button>
                    {!! Form::close() !!}
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        {!! $variable_budgets->appends(Request::except('variable-page'))->links() !!}
      </div>
    </div>

    <hr>

    <h3>Add Categories</h3>

    <div class="row">
      <div class="col-sm-12 col-md-6">
        <form class="form-inline" action="{{ route('budget.category.store') }}" method="POST">
          @csrf

          <div class="form-group">
            <input type="text" class="form-control" name="category" value="{{ old('category') }}" placeholder="Category Name" required>

            @if ($errors->has('category'))
              <div class="alert alert-danger">{{$errors->first('category')}}</div>
            @endif
          </div>

          <button type="submit" class="btn btn-secondary ml-3">Create</button>
        </form>
      </div>

      <div class="col-sm-12 col-md-6">
        <form class="form-inline" action="{{ route('budget.subcategory.store') }}" method="POST">
          @csrf

          <div class="form-group">
            <select class="form-control" name="parent_id" required>
              <option value="">Select a Category</option>
              @foreach ($categories as $category)
                <option value="{{ $category->id }}" {{ $category->id == old('parent_id') ? 'selected' : '' }}>{{ $category->name }}</option>
              @endforeach
            </select>

            @if ($errors->has('parent_id'))
              <div class="alert alert-danger">{{$errors->first('parent_id')}}</div>
            @endif
          </div>

          <div class="form-group ml-3">
            <input type="text" class="form-control" name="subcategory" value="{{ old('subcategory') }}" placeholder="Sub Category Name" required>

            @if ($errors->has('subcategory'))
              <div class="alert alert-danger">{{$errors->first('subcategory')}}</div>
            @endif
          </div>

          <button type="submit" class="btn btn-secondary ml-3">Create</button>
        </form>
      </div>
    </div>

    <div id="budgetCreateModal" class="modal fade" role="dialog">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <form action="{{ route('budget.store') }}" method="POST">
            @csrf

            <div class="modal-header">
              <h4 class="modal-title">Create a Budget</h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <strong>Description:</strong>
                <textarea name="description" class="form-control" rows="8" cols="80">{{ old('description') }}</textarea>

                @if ($errors->has('description'))
                  <div class="alert alert-danger">{{$errors->first('description')}}</div>
                @endif
              </div>

              <div class="form-group">
                <strong>Date:</strong>
                <div class='input-group date' id='date-datetime'>
                  <input type='text' class="form-control" name="date" value="{{ date('Y-m-d') }}" required />

                  <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                  </span>
                </div>

                @if ($errors->has('date'))
                  <div class="alert alert-danger">{{$errors->first('date')}}</div>
                @endif
              </div>

              <div class="form-group">
                <strong>Amount:</strong>
                <input type="number" name="amount" class="form-control" value="{{ old('amount') }}" required>

                @if ($errors->has('amount'))
                  <div class="alert alert-danger">{{$errors->first('amount')}}</div>
                @endif
              </div>

              <div class="form-group">
                <strong>Type:</strong>
                <select class="form-control" name="type" required>
                  <option value="fixed" {{ 'fixed' == old('type') ? 'selected' : '' }}>Fixed</option>
                  <option value="variable" {{ 'variable' == old('type') ? 'selected' : '' }}>Variable</option>
                </select>

                @if ($errors->has('type'))
                  <div class="alert alert-danger">{{$errors->first('type')}}</div>
                @endif
              </div>

              <div class="form-group">
                <strong>Category:</strong>
                <select class="form-control" name="budget_category_id" id="budget_category" required>
                  <option value="">Select a Category</option>
                  @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ $category->id == old('category') ? 'selected' : '' }}>{{ $category->name }}</option>
                  @endforeach
                </select>

                @if ($errors->has('budget_category_id'))
                  <div class="alert alert-danger">{{$errors->first('budget_category_id')}}</div>
                @endif
              </div>

              <div class="form-group">
                <strong>Sub Category:</strong>
                <select class="form-control" name="budget_subcategory_id" id="budget_subcategory" required>
                  <option value="">Select a Sub Category</option>
                </select>

                @if ($errors->has('budget_subcategory_id'))
                  <div class="alert alert-danger">{{$errors->first('budget_subcategory_id')}}</div>
                @endif
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-secondary">Add</button>
            </div>
          </form>
        </div>

      </div>
    </div>

@endsection

@section('scripts')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.5/js/bootstrap-select.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>

  <script type="text/javascript">
    $(document).ready(function() {
      $('#date-datetime, #date-filter').datetimepicker({
        format: 'YYYY-MM-DD'
      });
    });

    $('#budget_category').on('change', function() {
      var category_id = $(this).val();
      var subcategories = {!! json_encode($subcategories) !!};

      $('#budget_subcategory').empty();

      $('#budget_subcategory').append($('<option>', {
        value: '',
        text: 'Select a Sub Category'
      }));

      Object.keys(subcategories).forEach(function(index, category) {
        if (subcategories[index].parent_id == category_id) {
          $('#budget_subcategory').append($('<option>', {
            value: subcategories[index].id,
            text: subcategories[index].name
          }));
        }
      });
    });
  </script>
@endsection
