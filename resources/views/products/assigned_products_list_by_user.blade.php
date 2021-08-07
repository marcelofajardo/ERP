@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h2 class="page-heading">Products Assigned To : {{ $user->name }}</h2>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <table class="table table-striped table-bordered">
                <tr>
                    <td>SKU</td>
                    <td>Product Name</td>
                    <td>Assigned On</td>
                </tr>
                @foreach($userProducts as $userProduct)
                    <tr>
                        <td>
                            <a href="{{ action('ProductController@show', $userProduct->product->id) }}">{{ $userProduct->product->sku }}</a>
                            <br>
                            <a href="{{ action('ProductController@show', $userProduct->product->id) }}">{{ $userProduct->product->id }}</a>
                        </td>
                        <td>{{ $userProduct->product->name }}</td>
                        <td>{{ $userProduct->created_at ?? 'N/A' }}</td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            @if(Session::has('success'))
                toastr['success']("{{ Session::get('success') }}", 'Success');
            @endif
        });
    </script>
@endsection