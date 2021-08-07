@extends('layouts.app')

@section("styles")
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
@endsection

@section('content')
    <div class="row">
      <div class="col-lg-12 margin-tb">
        <h2 class="page-heading">Assign product to user</h2>
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
            <th>Brand</th>
            <th>Category</th>
            <th>Total</th>
            <th>Assign to</th>
          </tr>
        </thead>

        <tbody>
          @foreach ($newProducts as $key => $product)
            <tr>
              <td>{{ $product->brandName }}</td>
              <td>{{ $product->categoryName }}</td>
              <td>{{ $product->total }}</td>
                <td>
                 @if(!$product->assigned_to)
                <form class="form-inline">
                    @csrf
                    <input type="hidden" class="brand_name" name="category" value="{{$product->category}}">
                    <input type="hidden" class="category_name" name="brand" value="{{$product->brand}}">
                  <div class="form-group ml-3">
                    <?php echo Form::select("assigned_to",["" => "-- Select User --"]+$users,null,["class" => "form-control select2 assigned_to"]); ?>
                  </div>

                  <button type="button" class="btn btn-image ml-3 assign-btn" data-id="{{$key}}"><img src="/images/add.png" /></button>
                </form>
                @else 
                <p>{{$product->assignTo}}</p>
                @endif
                <p class="tempName{{$key}}"></p>
                </td>
            </tr>
          @endforeach
        </tbody>
      </table>
      {{$newProducts->links()}}
    </div>

@endsection

@section('scripts')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
  <script>
 $(".select2").select2({tags:true});


 $(document).on('click', '.assign-btn', function () {
    var form = $(this).closest("form");
    var classname = '.tempName'+$(this).data('id');
console.log(classname);
    var url = '/products/assign-product';
            $.ajax({
                type: 'POST',
                url: url,
                dataType: 'json',
                data: form.serialize(),
            }).done(function (response) {
               toastr["success"](response.message);
               var user = response.user;
               form.css('display','none');
              //  console.log($(classname));
               $(classname).text(user);
            }).fail(function(error) {
                toastr["error"](error.responseJSON.message);
            });
        });
  </script>
@endsection
