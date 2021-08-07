@extends('layouts.app')

@section('content')
<style type="text/css">
    #loading-image {
            position: fixed;
            top: 50%;
            left: 50%;
            margin: -50px 0px 0px -50px;
            z-index: 60;
        }
</style>

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <h2 class="page-heading">Last 20 Customers</h2>
            <div class="pull-left">
               
            </div>
            <div class="pull-right">
            </div>
        </div>
    </div>

    

    <div class="table-responsive mt-3">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>Name</th>
            <th>Link</th>
          </tr>
        </thead>

        <tbody>


          @foreach ($customers as $customer)

            <tr>
            <td>{{ $customer->name }}</td>
                <td><a href="/attachImages/{{ $customer->id }}/1" class="btn btn-secondary btn-sm">Click</button></a>
            </tr>
			@endforeach
         
        </tbody>
      </table>
    </div>

@endsection

