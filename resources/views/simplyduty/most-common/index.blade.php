@extends('layouts.app')

@section('title', 'Most Common')


@section('content')

<div class="row">
        <div class="col-lg-12 margin-tb">
            <h2 class="page-heading">Most Common Combinations (<span id="count">{{ $products->total() }}</span>)</h2>
            <div class="pull-right">
                <button type="button" class="btn btn-secondary" onclick="submitGroup()">Create Group</button>
                <button type="button" class="btn btn-image" onclick="resetSearch()"><img src="/images/resend2.png"/></button>
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
                <th style="width:10%"></th>
                <th style="width:30%">Category</th>
                <th style="width:30%">Count</th>
                <th style="width:40%">Combination</th>

            </tr>
            <tr>
            <th style="width:10%"><input type="checkbox" id="ckbCheckAll">Select All</th>    
            <th>{!! $category_selection !!}</th>
            <th></th>    
            <th><input type="text" id="combination" class="search form-control"></th>
          </tr>
            </thead>
             {!! $products->appends(Request::except('page'))->links() !!}
            <tbody>
            @include('simplyduty.most-common.partials.data')
            </tbody>
        </table>
    </div>
    {!! $products->appends(Request::except('page'))->links() !!}
@endsection
@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>

    $(".select-multiple").multiselect();
    $(".select-multiple2").select2();

 $(document).ready(function() {
        src = "{{ route('hscode.mostcommon.index') }}";
        $(".search,.category_class").on('blur', function () {
            category = $('#category_value').val();
            if(category == 1){
                category = '';
            }
            combination = $('#combination').val();
            $.ajax({
                url: src,
                dataType: "json",
                data: {
                    category : category,
                    combination : combination,
                },
                beforeSend: function() {
                       $("#loading-image").show();
                },
            
            }).done(function (data) {
                 $("#loading-image").hide();
                 $('#count').text(data.total)
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
       
       });
       });  

       function resetSearch() {
           src = "{{ route('hscode.mostcommon.index') }}";
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
                    $('#category_value').val('');
                    $('#combination').val('');
                $('#count').text(data.total)
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

       function createGroup() {
        $('#groupModal').modal('show');
        }

        $(document).ready(function () {
        $("#ckbCheckAll").click(function () {
            $(".checkBoxClass").prop('checked', $(this).prop('checked'));
            });
        });

        function submitGroup(){
        existing_group = $('#existing_group').val();
        name = $('#name').val();
        composition = $('#composition').val();
        var compositions = [];
            $.each($("input[name='composition']:checked"), function(){
                compositions.push($(this).val());
            });
        if(compositions.length == 0){
            alert('Please Select Combinations');
        }else{
            $.each($("input[name='composition']:checked"), function(){
                name  = $(this).attr('data-name');
                category =  $(this).attr('data-category');
                src = "{{ route('hscode.save.group') }}";
                $.ajax({
                url: src,
                type: "POST",
                dataType: "json",
                data: {
                    name : name,
                    category : category, 
                    "_token": "{{ csrf_token() }}",
                },
                beforeSend: function () {
                    $("#loading-image").show();
                },
                success: function(data) {
                    if(data.error){
                        alert(data.error);
                        $("#loading-image").hide();
                    }else{
                        resetSearch();
                    }
                },
                error: function(xhr) { // if error occured
                    alert("Error occured.please try again");
                },

                });

            });
                

               
        }
    }

    $( ".category_class" ).change(function() {
            category = $(this).val();
            combination = $('#combination').val();
            $.ajax({
                url: "{{ route('hscode.mostcommon.index') }}",
                dataType: "json",
                data: {
                    category : category,
                    combination : combination,
                },
                beforeSend: function() {
                       $("#loading-image").show();
                },
            
            }).done(function (data) {
                 $("#loading-image").hide();
                 $('#count').text(data.total);
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
    });
        </script>
@endsection
