@extends('layouts.app')

@section('favicon' , 'password-manager.png')

@section('title', 'SimplyDuty Category')


@section('content')

<div class="row">
        <div class="col-lg-12 margin-tb">
            <h2 class="page-heading">SimplyDuty Calculation</h2>
            <div class="pull-right">
                {{-- <button type="button" class="btn btn-secondary" data-toggle="modal"
                                        data-target="#createCalculationModal">New Calculation
                                </button>
                                
                <button type="button" class="btn btn-image" onclick="resetSearch()"><img src="/images/resend2.png"/></button> --}}
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
    
    <div class="table-responsive mt-3">
        <table class="table table-bordered" id="category-table">
            <thead>
            <tr>
                <th style="width:10%">Hsode</th>
                <th style="width:10%">Destination Currency</th>
                <th style="width:10%">Origin Currency</th>
                <th style="width:10%">Value</th>
                <th style="width:10%">Duty</th>
                <th style="width:10%">DutyRate</th>
                <th style="width:10%">DutyType</th>
                <th style="width:10%">Shipping</th>
                <th style="width:10%">Insurance</th>
                <th style="width:10%">Total</th>
                <th style="width:10%">ExchangeRate</th>
                <th style="width:10%">DutyMinimis</th>
                <th style="width:10%">VatMinimis</th>
                <th style="width:10%">VatRate</th>
                <th style="width:10%">VAT</th>
                <th>Created At</th>
                <th>Updated At</th>
            </tr>
            {{-- <tr>
            <th><input type="text" id="code" class="search form-control"></th>    
            <th><input type="text" id="description" class="search form-control"></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
          </tr> --}}
            </thead>
             {!! $calculations->appends(Request::except('page'))->links() !!}
            <tbody>
            @include('simplyduty.calculation.partials.data')
            </tbody>
        </table>
    </div>
    {!! $calculations->appends(Request::except('page'))->links() !!}

     @include('simplyduty.calculation.partials.create')
@endsection
@section('scripts')

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
 $(document).ready(function() {
        $(function() {
            $('.selectpicker').selectpicker();
        });

        src = "{{ route('simplyduty.category.index') }}";
        $(".search").autocomplete({
        source: function(request, response) {
            code = $('#code').val();
            description = $('#description').val();
            $.ajax({
                url: src,
                dataType: "json",
                data: {
                    code : code,
                    description : description,
                },
                beforeSend: function() {
                       $("#loading-image").show();
                },
            
            }).done(function (data) {
                 $("#loading-image").hide();
                console.log(data);
                $("#category-table tbody").empty().html(data.tbody);
                if (data.links.length > 10) {
                    $('ul.pagination').replaceWith(data.links);
                } else {
                    $('ul.pagination').replaceWith('<ul class="pagination"></ul>');
                }
                
            }).fail(function (jqXHR, ajaxOptions, thrownError) {
                alert('No response from server');
            });
        },
        minLength: 1,
       
        });
       });  

       function getCategoryData() {
           src = "{{ route('simplyduty.category.update') }}"
           $.ajax({
                url: src,
                dataType: "json",
                data: {
                    
                },
                beforeSend: function() {
                       
                },
            
            }).done(function (data) {
                alert('Category Updated');
            }).fail(function (jqXHR, ajaxOptions, thrownError) {
                alert('No response from server');
            });
           
       }

       function resetSearch() {
           src = "{{ route('simplyduty.category.index') }}";
            reset = '';
            $.ajax({
                url: src,
                dataType: "json",
                data: {
                    reset : reset,
                },
                beforeSend: function() {
                        $("#loading-image").show();
                },
            
            }).done(function (data) {
                    $("#loading-image").hide();
                console.log(data);
                $("#category-table tbody").empty().html(data.tbody);
                if (data.links.length > 10) {
                    $('ul.pagination').replaceWith(data.links);
                } else {
                    $('ul.pagination').replaceWith('<ul class="pagination"></ul>');
                }
                
            }).fail(function (jqXHR, ajaxOptions, thrownError) {
                alert('No response from server');
            });
           
       }
        </script>
@endsection
