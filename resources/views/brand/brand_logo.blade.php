@extends('layouts.app')

@section('styles')
<style>
.btn {
    padding: 6px 6px;
}
.small-image{max-width: 100%;max-height: 100px;}
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
.scroll_div{
    max-height:390px;
    overflow: auto;
}
.fa-trash{
    cursor: pointer;
}
</style>
@endsection

@section('content')
<?php
$query = http_build_query(Request::except('page'));
$query = url()->current() . (($query == '') ? $query . '?page=' : '?' . $query . '&page=');
?>
<div class="ajax-loader" style="display: none;">
    <div class="inner_loader">
    <img src="{{ asset('/images/loading2.gif') }}">
    </div>
</div>


<div class="row">
    <div class="col-lg-12 margin-tb">
        <h2 class="page-heading">Brand Logos </h2>

        <form method="get" action="{{route('brand.logo_data')}}">
            <div class="row mb-3">
                <div class="col-md-3">
                    @php
                        if(isset($_REQUEST['brand_name']))
                            $brand_name = $_REQUEST['brand_name'];
                        else
                            $brand_name = '';
                    @endphp

                    <input name="brand_name" type="text" class="form-control" value="{{$brand_name}}"  placeholder="Brand Name" id="brand_name">
                </div>
                
                <div class="col-md-1">
                    <button type="submit" class="btn btn-image" ><img src="/images/filter.png"></button>
                
                    <a href="{{route('brand.logo_data')}}" type="button" class="btn btn-image" id=""><img src="/images/resend2.png"></a>    
                </div>
            </div>
        </form>
        

        <div class="pull-left">
            <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#upload_data_modal">
                Add Brand Logos
            </button>
        </div>
        
    </div>
</div>


<div class="infinite-scroll">
{!! $brand_data->links() !!}
    <div class="table-responsive mt-3">
        <table class="table table-bordered">
            <tr>
                <th width="10%">ID</th>
                <th width="40%">Name</th>
                <!-- <th width="40%">Logo</th> -->
                <th width="10%">Action</th>
            </tr>
            @foreach($brand_data as $key => $value)
            <tr class="tr_{{$value->brands_id}}">
                <td class="index">{{ $key+1}}</th>
                <td class="brand_name">{{ $value->brands_name }}</td>
                @if($value->brand_logos_image != null)
                    <!-- <td class="brand_image">
                    <img src="/brand_logo/{{ $value->brand_logos_image }}"  style="height: 150px; width: 150px;display: block;margin-left: auto;margin-right: auto;" />
                    </td> -->
                @else
                    <!-- <td class="brand_image"></td> -->
                @endif
                <td>
                    <button title="Open Images" type="button" class="btn preview-attached-img-btn btn-image no-pd" data-id="{{$value->brands_id}}" data-name="{{$value->brands_name}}" >
                        <img src="/images/forward.png" style="cursor: default;">
                    </button>
                    <i class="fa fa-trash delete_logo" aria-hidden="true" data-id="{{$value->brands_id}}" ></i>
                </td>
            </tr>

            <tr class="expand-{{$value->brands_id}} hidden">
                
                <td colspan="4" id="attach-image-list-{{$value->brands_id}}" >
                    
                </td>
            </tr>
            @endforeach
        </table>
    </div>
</div>

 <!--Upload Data Modal -->
 <div class="modal fade" id="upload_data_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Upload Brand Logos</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="order_product_id" class="order_product_id" value="" />
                <input type="hidden" name="order_id" class="order_id" value="" />
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Upload :</strong>
                        <input type="file" enctype="multipart/form-data" name="file[]" class="form-control upload_file_data" name="image" multiple/>
                        
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary upload_file_btn">Save</button>
        </div>
        </div>
    </div>
    </div>

@endsection
@section('scripts')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jscroll/2.3.7/jquery.jscroll.min.js"></script>
<script type="text/javascript">
    $('ul.pagination').hide();
    $(function() {
        $('.infinite-scroll').jscroll({
            autoTrigger: true,
            loadingHtml: '<img class="center-block" src="/images/loading.gif" alt="Loading..." />',
            padding: 2500,
            nextSelector: '.pagination li.active + li a',
            contentSelector: 'div.infinite-scroll',
            callback: function() {
                $('ul.pagination').first().remove();
                $(".select-multiple").select2();
            }
        });
    });


    $(document).on('click', '.preview-attached-img-btn', function (e) {     
        e.preventDefault();
        var logo_id = $(this).data('id');
        var brand_name = $(this).data('name');
        var origin   = window.location.origin; 
        // $('#attach-image-list-'+logo_id).html('ffff');

        $.ajax({
            url: "{{ route('brand.get_all_images') }}",
            method: 'GET',
            dataType: "json",
            data: {
                _token: "{{ csrf_token() }}",
                'logo_id': logo_id,
                'brand_name':brand_name,
            },
            success: function(response) {
                var html_content = '';
                html_content += '<div class="col-md-12">';
                html_content += '<button class="btn btn-secondary btn-xs pull-right btn_save_brand_image" data-id="'+logo_id+'">Save</button>';
                html_content += '</div>';
                html_content += '<div class="col-md-12 scroll_div">';
                    $.each( response.brand_logo_image, function( key, value ) {
                        html_content += '<div class="col-md-2">';
                        html_content += '<div class="col-md-12 text-center" style="padding:5px; margin-bottom:2px !important;" >';
                        html_content += '<div style="border: 1px solid #bfc0bf;padding:0px 5px;">';
                        html_content += '<div data-interval="false" id="carousel" class="carousel slide" data-ride="carousel" >';
                        html_content += '<div class="carousel-inner maincarousel">';
                        html_content += '<div class="item" style="display: block;">';
                        html_content += '<img src="'+origin+'/brand_logo/'+value.brand_logo_image_name+'"  style="height: 150px; width: 150px;display: block;margin-left: auto;margin-right: auto;" />';
                        html_content += '</div>';
                        html_content += '</div>';
                        html_content += '</div>';
                        html_content += '<div class="row pl-4 pr-4" style="padding: 0px; margin-bottom: 8px;">';
                        html_content += '<div class="custom-control custom-checkbox">';
                        if(logo_id == value.brand_with_logos_brand_id && value.brand_logos_id == value.brand_with_logos_brand_logo_image_id)
                            html_content += '<input type="radio" name="brand_logo_radio_'+logo_id+'" checked class="brand_logo_radio_'+logo_id+'" value="'+value.brand_logos_id+'" />';
                        else
                            html_content += '<input type="radio" name="brand_logo_radio_'+logo_id+'" class="brand_logo_radio_'+logo_id+'" value="'+value.brand_logos_id+'" />';
                        // html_content += '<label class="custom-control-label" for="defaultUnchecked_'+value.id+'" ></label>';
                        html_content += '</div>';
                        html_content += '</div>';
                        html_content += '</div>';
                        html_content += '</div>';

                        html_content += '</div>';
                    });
                    html_content += '</div>';
                $('#attach-image-list-'+logo_id).html(html_content);
            },
            error: function(response){
                toastr['error'](response.responseJSON.message, 'error');
            } 
        });

        
        var expand = $('.expand-'+logo_id);
        $(expand).toggleClass('hidden');

    });


    $(document).on("click",".upload_file_btn",function(e) {

    var fd = new FormData();
    var files = $('.upload_file_data')[0].files;
    var fileArray = []

    if(files.length > 0 ){

        const acceptedImageTypes = ['image/gif', 'image/jpeg', 'image/png'];
        $.each(files,function(i,e){
            
            if ($.inArray(e.type, acceptedImageTypes) < 0) {
                toastr['error']('File Must be Image');
                return false;
            }
            fd.append('file[]',e);
        })
        fd.append('_token',"{{ csrf_token() }}");

        
        $.ajax({
            url: '{{route("brand.uploadlogo")}}',
            type: 'post',
            data: fd,
            // async: true,
            contentType: false,
            processData: false,
            beforeSend: function () {
                $('.ajax-loader').show();
            },
            success: function(response){
                $('.ajax-loader').hide();
                console.log(response)
                toastr['success'](response.msg, 'Success');
                $('#upload_data_modal').modal('hide');
            },
            error: function () {
                $('.ajax-loader').hide();
                toastr['error']('Data not Uploaded successfully!');
            }
        });

    }else{
        alert("Please select a file.");
    }
    });

    $(document).on("click",".btn_save_brand_image",function(e) {
        var logo_id = $(this).data('id');
        
        var logo_image_id = $('input[name="brand_logo_radio_'+logo_id+'"]:checked').val();
        if (logo_image_id == undefined) {
            toastr['error']('Please Select Logo');
            return false;
        }

        $.ajax({
            url: "{{ route('brand.set_logo_with_brand') }}",
            method: 'POST',
            dataType: "json",
            data: {
                _token: "{{ csrf_token() }}",
                logo_id: logo_id,
                logo_image_id:logo_image_id,
            },
            success: function(response) {
                
                var html_content = '';
                if(response.code == 200) {
                    toastr['success'](response.message, 'Success');

                    html_content = "<img src='/brand_logo/"+response.brand_logo_image+"'  style='height: 150px; width: 150px;display: block;margin-left: auto;margin-right: auto;' />";

                    $('.tr_'+logo_id+' .brand_image').html(html_content);

                    // setTimeout(function(){ location.reload(); }, 2000);
                }
            },
            error: function(response){
                toastr['error'](response.responseJSON.message, 'error');
            } 
        });
    });
    
    $(document).on("click",".delete_logo",function(e) {
        var brand_id = $(this).data('id');

        if (confirm('Are you sure you want to remove this logo?')) {
            $.ajax({
                url: "{{ route('brand.remove_logo') }}",
                type: "POST",
                dataType: "json",
                data: {
                    _token: "{{ csrf_token() }}",
                    brand_id: brand_id,
                },
                success: function (response) {
                    if(response.code == 200) {
                    toastr['success'](response.message, 'Success');

                    var html_content = '';
                    $('.tr_'+brand_id+' .brand_image').html(html_content);

                    //setTimeout(function(){ location.reload(); }, 2000);
                }
                }
            });
        }
    });
</script>
@endsection
