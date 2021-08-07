@extends('layouts.app')

@section('title', 'Direct Database Query Page')

@section('styles')
    <style>
        #collapse {
            overflow-y: scroll;
            height: 600px;
        }
        #collapse1 {
            overflow-y: scroll;
            height: 600px;
        }

        li {
            list-style-type: none;
        }
        .padding-left-zero {
            padding-left: 0px;
        }
        .border{
            border: 1px solid grey;
            border-radius: 4px;
        }
        .update_column{
            padding: 0;
            border-right: 1px solid #dee2e6;
        }
        .update_column h3{
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 10px;
            font-size: 16px;
            font-weight: bold;
            margin-top: 12px;
        }
        .where_column{
            padding: 0;
            border-right: 1px solid #dee2e6;
        }
        .left_bar, .right_bar{
            padding: 0 15px;
        }
        .left_bar .col-md-6, .right_bar .col-md-4 {
            width: 50%;
            border-bottom: 1px solid #ddd;
        }
        .left_bar .col-md-6 .form-group, .right_bar .col-md-4 .form-group{
            margin: 6px 0;
        }
        .left_bar .col-md-6.text-left, .right_bar .col-md-4.text-left{
            border-right: 1px solid #ddd;
            align-items: center;
            display: flex;
        }
        .left_bar .col-md-6 strong{
            border-left: 1px solid #ddd;
            height: -webkit-fill-available;
            display: flex;
            align-items: center;
            padding-left: 15px;
        }

        .right-cont{
            border-right: none !important;
        }

    </style>
@endsection
@section('content')


    <h2 class="page-heading flex" style="padding: 8px 5px 8px 10px;border-bottom: 1px solid #ddd;line-height: 32px;">Direct Database Query Page
    </h2>


    <div class="row" style="margin: 0 1px">

    <div class="col-xs-2 pl-3 pr-0">
        <div class="form-group">
            <select name="table" class="form-control table_class" id="table_id">
                <option value>Select Table</option>
                @foreach($table_array as $tab)
                <option value="{{$tab}}" >{{$tab}}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-xs-2 pl-3 text-left save_class d-none">
        <button type="submit" class="btn btn-secondary save_change_btn mr-2">Update</button>
        <button type="submit" class="btn btn-secondary delete_btn">Delete</button>
    </div>
    </div>
    <div class="container_" style="margin: 0 28px">
    <form class="db_query">
        <input type="hidden" name="table_name" class="table_name" value="">
        <div class="row border d-none" >
            <div class="col-md-6 update_column">
                <h3 class="text-center d-none mb-0">Update Columns</h3>
                <div class="row left_bar"> 
                </div>
            </div>
            <div class="col-md-6 update_column right-cont   ">
                <h3 class="text-center d-none mb-0">Where Query</h3>
                <div class="row right_bar">   
                </div>
            </div>
        </div>
    </form>    
    </div>


@endsection

@section('scripts')

<script>  
$(document).ready(function(){
    $('.column-operator').select2();
});

$('.table_class').change(function(){
    let table_name = this.value;
    if(table_name != ''){
        $.ajax({
            url: '{{route('admin.databse.menu.direct.dbquery.columns')}}',
            data: table_name,
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
            success: function(response){
                $('.container_ .row .left_bar').html('');
                $('.container_ .row .right_bar').html('');

                let cols = response.data;
                $.each(cols, function(index, value){
                    let input_type = value.Type;
                    let html = `
                                <div class="col-xs-6 col-sm-6 col-md-6 text-left">
                                    <input name="columns[${value.Field}]" type="checkbox" value="${value.Field}">
                                    <strong class="ml-3">${value.Field}</strong>
                                </div> 
                                <div class="col-xs-6 col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <input placeholder="${value.Field}" class="form-control" name="update_${value.Field}" type="${input_type}">
                                    </div>
                                </div>
                                `;

                    if(value.Field !== 'id') {
                        $('.container_ .row .left_bar').append(html);
                    }

                    html = `
                                <div class="col-xs-4 col-sm-4 col-md-4 text-left">
                                    <strong>${value.Field}</strong>
                                </div>
                                <div class="col-xs-4 col-sm-4 col-md-4 text-left">
                                    <select class="column-operator" id="ColumnOperator[]" name="criteriaColumnOperators['${value.Field}']">
                                    <option value="">Select Operator</option><option value="=">=</option><option value=">">&gt;</option><option value=">=">&gt;=</option><option value="<">&lt;</option><option value="<=">&lt;=</option><option value="!=">!=</option><option value="LIKE">LIKE</option><option value="LIKE %...%">LIKE %...%</option><option value="NOT LIKE">NOT LIKE</option><option value="IN (...)">IN (...)</option><option value="NOT IN (...)">NOT IN (...)</option><option value="BETWEEN">BETWEEN</option><option value="NOT BETWEEN">NOT BETWEEN</option><option value="IS NULL">IS NULL</option><option value="IS NOT NULL">IS NOT NULL</option>
                                    </select>
                                </div>
                                <div class="col-xs-4 col-sm-4 col-md-4">
                                    <div class="form-group">
                                        <input placeholder="${value.Field}" class="form-control" name="where_${value.Field}" type="text">
                                    </div>
                                </div>
                                `;

                    $('.container_ .row .right_bar').append(html);
                });
                $('.column-operator').select2();
            }
        });
        $('.save_class').removeClass('d-none');
        $('.container_ h3').removeClass('d-none');
        $('.container_ .row').removeClass('d-none');
        $('.table_name').val(table_name);
    }else{
        $('.save_class').addClass('d-none');
        $('.container_ h3').addClass('d-none');
        $('.container_ .row').addClass('d-none');
        $('.table_name').val('table_name');
    }
   
});

$('.save_change_btn').click(function(){
    let is_checkbox_empty = 1;
    $("input:checkbox:checked").each(function(){
        is_checkbox_empty = 0;
    });

    if(is_checkbox_empty){
        toastr["error"]('Please check at least one field !');
        return false;
    }

    let form_data = $('.db_query').serialize();
    $.ajax({
        url: '{{route('admin.databse.menu.direct.dbquery.confirm')}}',
        data: form_data,
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
        success: function(response){
            if(confirm('Do You really want to run the following query? \n' + response.sql)){
                $.ajax({
                    url: '{{route('admin.databse.menu.direct.dbquery.update')}}',
                    data: {
                        sql: response.sql
                    },
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                    success: function(response){
                        if(response.error == ''){
                            toastr["success"]('Database updated successfully !');
                        }else{
                            toastr["error"](response.error.errorInfo[2]);
                        }
                    }
                });
                
            }
        }
    });
});



$('.delete_btn').click(function(){
    let is_input_empty = 1;
    $(".right_bar input").each(function(){
        if($(this).val() != '') is_input_empty = 0;
    });
    // if(is_input_empty){
    //     if(!confirm('You are about to DESTROY a complete table! Do you really want to drop table ?')){
    //         return false;
    //     };
    // }

    let form_data = $('.db_query').serialize();
    $.ajax({
        url: '{{route('admin.databse.menu.direct.dbquery.delete.confirm')}}',
        data: form_data,
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
        success: function(response){
            if(confirm('Do You really want to run the following query? \n' + response.sql)){
                $.ajax({
                    url: '{{route('admin.databse.menu.direct.dbquery.delete')}}',
                    data: {
                        sql: response.sql
                    },
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                    success: function(response){
                        if(response.error == ''){
                            toastr["success"]('Database updated successfully !');
                        }else{
                            toastr["error"](response.error.errorInfo[2]);
                        }
                    }
                });
                
            }
        }
    });
});

</script>

@endsection

