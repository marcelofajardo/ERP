@extends('layouts.app')

@section('title', 'Purchase Products Order Excel File')

@section('styles')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
<style>
.ajax-loader{
    position: fixed;
    left: 0;
    right: 0;
    top: 0;
    bottom: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.2);
    z-index: 1060;
}
.inner_loader {
	top: 30%;
    position: absolute;
    left: 40%;
    width: 100%;
    height: 100%;
}
.pd-5 {
  padding:5px !important;
}
.pd-3 {
  padding:3px !important;
}
.status-select-cls .multiselect {
  width:100%;
}
.btn-ht {
  height:30px;
}
.status-select-cls .btn-group {
  width:100%;
  padding: 0;
}
.table.table-bordered.order-table a{
color:black!important;
}
.fa-info-circle{
    padding-left:10px;
    cursor: pointer;
}
table tr td {
  word-wrap: break-word;
}
.fa-list-ul{
    cursor: pointer;
}

.fa-upload{
    cursor: pointer;
}
.fa-refresh{
    cursor: pointer;
    color:#000;
}
.sheet_tab{
    display: none;
}
.uni_search{
    display: none !important;
}
.change_version{
    margin-top:20px;
}
</style>
@endsection

@section('large_content')
	<div class="ajax-loader" style="display: none;">
		<div class="inner_loader">
		<img src="{{ asset('/images/loading2.gif') }}">
		</div>
	</div>

    <div class="row">
        <div class="col-12" style="padding:0px;">
            <h2 class="page-heading">Excel File</h2>
        </div>
         <div class="col-10" style="padding-left:0px;">
            <div >
               
            </div>
        </div>
    </div>	

    @if($file_found == 'not_found')
        <strong>File Not Found</strong>
    @else
    <div>

        <form action="" method="GET" class="form-inline align-items-start">
        @if(!empty($version_excel_data))
            <div class="form-group mr-3 mb-3">
                    
                
                <strong>Excel File Varsion:</strong>
                <select class="form-control" name="version_excel" id="version_excel" required>
                    @foreach ($version_excel_data as $key => $val)
                        @if($version == $val->file_version)
                            <option value="{{$val->file_version}}" selected="true"> Version {{$val->file_version}}</option>
                        @else
                            <option value="{{$val->file_version}}"> Version {{$val->file_version}}</option>
                        @endif
                    @endforeach
                </select>
                
                
            </div>
            
            <input type="button" name="change_version" class="change_version btn btn-secondary" value="Change Version"/>
        @endif
        </form>

        <div id="exTab2" class="container mt-3 tab sheet_tab" >
            <ul class="nav nav-tabs">
                @foreach ($excel_array as $key => $item)
                    @if($key == 0)
                    <li class="tablinks" onclick="openTab(event, 'sheet_{{$key+1}}' )" id="defaultOpen">
                    @else
                    <li class="tablinks" onclick="openTab(event, 'sheet_{{$key+1}}' )">
                    @endif
                        <a href="#sheet_{{$key+1}}" data-toggle="tab">Sheet {{$key+1}}</a>
                    </li>
                @endforeach
            </u>
        </div>

        <div id="myDiv">
            <img id="loading-image" src="/images/pre-loader.gif" style="display:none;"/>
        </div>


        @foreach ($excel_array as $key => $item)

            <div class="tabcontent" id="sheet_{{$key+1}}">

                <form name="form_sheet_{{$key+1}}" id="form_sheet_{{$key+1}}" action="" method="GET" class="form-inline align-items-start">
                    @foreach ($item as $kk => $vv)
                        
                        @foreach ($vv as $k => $v)
                            
                        @if($kk == 0)   
                            <div class="form-group mr-3 mb-3">
                                <input name="{{$k}}" type="text" class="form-control global" id="{{$k}}" value="" placeholder="{{$v}}">
                            </div>
                        @endif
                        
                        @endforeach
                        
                    @endforeach
                    <input name="uni_search" type="text" class="form-control global uni_search" id="000" value="" placeholder="Universal Search">
                    <input name="form_id" type="hidden" class="form-control global" id="form_id" value="form_{{$key+1}}">
                    <button type="button" class="btn btn-image search_bt" id="search_btn" ><img src="/images/filter.png" /></button>
                    <button type="button" class="btn btn-image" onclick="location.reload();"><img src="/images/resend2.png" /></button>
                </form>

                <input type="hidden" name="file_name" class="file_name" value="{{$name}}" />
                <input type="hidden" name="log_excel_imports_id" class="log_excel_imports_id" value="{{$id}}" />

                <form name="excel_sheet" method="post" id="form_{{$key+1}}">
                    
                    <div class="tab-pane mt-3 " >
                        <div class="table-responsive mt-3">
                            <table class="table table-striped">
                                <thead>
                                
                                        @foreach ($item as $kk => $vv)
                                        <tr id="sheet_{{$key}}_{{$kk}}">

                                            @if($kk == 0)
                                                <th>Delete</th>
                                            @else
                                                <td><input type="checkbox" name="checkbox" value="{{$kk+1}}"></td>
                                            @endif


                                            @foreach ($vv as $k => $v)
                                                
                                                @if($kk == 0)
                                                    <th scope="col">{{$v}}
                                                    <input type="hidden" name="head_{{$k}}" value="{{$v}}" />
                                                    </th>
                                                @else
                                                    <td class="expand-row" ><textarea style="display:none;" class="form-control" name="{{$k}}" rows="3">{{$v}}</textarea>

                                                       
                                                            <div class="td-mini-container brand-supplier-mini-{{ $k }}">
                                                                {{ strlen($v) > 20 ? substr($v, 0, 20).'...' : $v }}
                                                            </div>
                                                            <div class="td-full-container hidden brand-supplier-full-{{ $k }}">
                                                                {{ $v }}
                                                            </div>
                                                        
                                                    </td >
                                                @endif

                                            @endforeach
                                        </tr>   
                                        @endforeach

                                </thead>
                                    <tr class="add_row_btn_div">
                                        <td>
                                        <input type="button" name="add_row" data-sheet_id="{{$key}}" class="add_row btn btn-secondary" value="+" title="Add New Row"/>
                                        </td>
                                    </tr>
                            </table>
                        </div>
                    </div>

                    <div class="btn_allign">
                        <!-- <button type="button" class="btn btn-default" >Close</button> -->
                        <!-- <button type="submit" class="btn btn-secondary">Update</button> -->
                        <input type="button" name="update_sheet" class="update_sheet btn btn-secondary" value="Update" />
                    </div>
                </form>

            </div>

        @endforeach


    </div>
    @endif
    

    <div id="loading-image" style="position: fixed;left: 0px;top: 0px;width: 100%;height: 100%;z-index: 9999;background: url('/images/pre-loader.gif') 50% 50% no-repeat;display:none;">
   </div>




@endsection
@section('scripts')

<script type="text/javascript">
$('.add_row').on('click', function(e) {

    var sheet_id = $(this).attr("data-sheet_id");

    var search_form_id = $(this).parents('form').attr('id');

    var listing_form_id = $('#'+search_form_id +' th').length;

    var total_column = listing_form_id - 1;

    var tr_length = $('#'+search_form_id +' tr').length;

    var total_tr = $('#'+search_form_id +' tr').length - 1;

    var row_data  = '<tr id="sheet_'+sheet_id+'_'+total_tr+'">';

        row_data += '<td><input type="checkbox" name="checkbox" value="'+tr_length+'"></td>';

        for (i = 0; i < total_column; i++) {
            var name = i;
            row_data += '<td><textarea class="form-control" name="'+name+'" rows="3"></textarea>';	
            row_data += '</td>';
        }

        row_data += '</tr>';

    $('#'+search_form_id +' thead').append(row_data);

});

$('.update_sheet').on('click', function(e) {

    var listing_form_id = $(this).parents('form').attr('id');
    var form_data = $("#"+listing_form_id).serializeArray();
    var file_name = $(".file_name").val();
    var log_excel_imports_id = $(".log_excel_imports_id").val();


    $.ajax({
            url: "{{ route('purchase-product.update_excel_sheet') }}",
            type: 'POST',
            data:{
                form_data : JSON.stringify(form_data),	
                file_name : file_name,
                log_excel_imports_id : log_excel_imports_id,
                _token: "{{csrf_token()}}"
            },
            beforeSend: function () {
                $("#loading-image").show();
            },
        }).done( function(response) {
            $("#loading-image").hide();
        
            if(response.code == 200)
            {
                toastr['success'](response.message);
            }
            
            // var origin   = window.location.origin; 
            // window.location.href = origin+'/excel-importer'; 

                
        }).fail(function(errObj) {
            $("#loading-image").hide();
        });
});

$('.change_version').on('click', function(e) {

    // console.log($("#version_excel option:selected" ).val());
    var select_dropdown = $('#version_excel').val();
    var log_excel_imports_id = $(".log_excel_imports_id").val();

    var origin   = window.location.origin; 

    window.location.href = origin+'/purchase-product/openfile/'+log_excel_imports_id+'/'+select_dropdown;
});

$('.search_bt').on('click', function(e) {
            // console.log("++++++++++111++++++++++");
            // console.log($(this).parents('form').attr('id'));

            var search_form_id = $(this).parents('form').attr('id');

            var listing_form_id = $('#'+search_form_id +' #form_id').val();
          
            $.each($('#'+search_form_id+' input'), function( k, v ) {
                console.log( "Key: " + k + ", Id: " + v.id + ", value data : " + v.value );

                if(v.value != '')
                {
                    $.each($('#'+listing_form_id+" textarea[name="+v.id+"]"), function( kk, vv ) {

                        var tr_id = $(vv).parents('tr').attr('id');
                        console.log(tr_id);
                        var str = vv.value;

                        var match = str.includes(v.value);
                        if(match == true)
                        {
                            console.log("match true");
                        }else{
                            $("#"+tr_id).css("display", "none");
                        }
                    });
                }
            });

        });



        $(document).on('click', '.expand-row', function() {
            var selection = window.getSelection();
            if (selection.toString().length === 0) {
            
                $(this).find('.td-mini-container').toggleClass('hidden');
                $(this).find('.td-full-container').toggleClass('hidden');
            }
        });
</script>
@endsection