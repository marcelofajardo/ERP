@extends('layouts.app')

@section('styles')
<style>
.btn {
    padding: 6px 6px;
}
.small-image{max-width: 100%;max-height: 100px;}
.align-right{
    text-align:right;
}
.brand-header-row .select2-container--default{
    width: auto !important;
}
    .flex{
        display: flex;
    }
.brand-header-row .select2-container .select2-selection--single{
    height: 34px !important;
}
.brand-header-row .btn-secondary{
        color: #757575;
        border: 1px solid #ccc;
        background: #fff;
        padding: 6px 10px;
    }
.brand-list .select2-container--default .select2-selection--multiple .select2-selection__rendered{
    overflow: auto;
}
.brand-list .form-group{
    margin-bottom: 0 !important;
}
</style>
@endsection

@section('content')
<?php
$query = http_build_query(Request::except('page'));
$query = url()->current() . (($query == '') ? $query . '?page=' : '?' . $query . '&page=');
?>
<div class="form-group position-fixed hidden-xs hidden-sm" style="top: 50px; left: 20px;">
    Goto :
    <select onchange="location.href = this.value;" class="form-control" id="page-goto">
        @for($i = 1 ; $i <= $brands->lastPage() ; $i++ )
            <option value="{{ $query.$i }}" {{ ($i == $brands->currentPage() ? 'selected' : '') }}>{{ $i }}</option>
            @endfor
    </select>
</div>
<div class="row brand-header-row">
<div class="col-md-12 pl-0">
    <h2 class="page-heading">Brand List (<span>{{ $brands->total() }}</span>) </h2>
</div>
    <div class="col-8 flex pl-0">
        <div class="form-inline">
            <div class="form-group">
                <input type="number" id="product_price" step="0.01" class="form-control" placeholder="Product price">
            </div>

            <div class="form-group ml-3">
                <select class="form-control select-multiple" id="brand" data-placeholder="Brands...">
                    @foreach ($brands as $brand)
                        <option value="{{ $brand->id }}" data-brand="{{ $brand }}">{{ $brand->name }}</option>
                    @endforeach
                </select>
            </div>

            <button type="button" id="calculatePriceButton" class="btn btn-secondary ml-3">Calculate</button>
        </div>
        <div class="form-inline ml-5 pl-5">
            <form>
                <div class="form-group">
                    <input type="text" value="{{ request('keyword') }}" name="keyword" id="search_text" class="form-control" placeholder="Enter keyword for search">
                </div>
                <button type="submit" class="btn btn-secondary ml-3">Search</button>
            </form>
        </div>

        <div id="result-container">

        </div>
    </div>
{{--    <div class="col-4 mt-1">--}}
{{--        <div class="form-inline">--}}
{{--            <form>--}}
{{--                <div class="form-group">--}}
{{--                    <input type="text" value="{{ request('keyword') }}" name="keyword" id="search_text" class="form-control" placeholder="Enter keyword for search">--}}
{{--                </div>--}}
{{--                <button type="submit" class="btn btn-secondary ml-3">Search</button>--}}
{{--            </form>--}}
{{--        </div>--}}
{{--    </div>--}}
    <div class="col-lg-4 margin-tb align-right">

        <div class="pull-left">
        </div>
        <div>
            <a class="btn btn-secondary" data-toggle="collapse" href="#inProgressFilterCount" href="javascript:;">Number of brands per site</a>
            <a class="btn btn-secondary" href="{{ route('brand.create') }}">+</a>
            <a class="btn btn-secondary fetch-new" href="#">Fetch New Brands</a>
        </div>
    </div>
</div>

@if ($message = Session::get('success'))
<div class="alert alert-success">
    <p>{{ $message }}</p>
</div>
@endif

<div class="row">

<br>
<?php 
    $bList = \App\Brand::pluck('name','id')->toArray();
?>
<div class="infinite-scroll">
    {!! $brands->links() !!}
    <div class="table-responsive mt-3">
        <table class="table table-bordered brand-list">
            <tr>
                <th width="2%">ID</th>
                <th width="3%">Name</th>
                <th width="5%">Image</th>
                <th width="8%">Similar Brands</th>
                <th width="10%">Merge Brands</th>
                <th width="4%">Magento ID</th>
                <th width="4%">Euro to Inr</th>
                <th width="6%" style="word-break:break-all">Deduction%</th>
                <th width="15%">Segment</th>
                @foreach($category_segments as $category_segment)
                    <th width="3%">{{ $category_segment->name }}</th>
                @endforeach
                <th width="12%">Selling on</th>
                <th width="12%">Priority</th>
                <th width="10%">Action</th>
            </tr>
            @foreach ($brands as $key => $brand)
            <tr>
                <td>{{ $brand->id }}</td>
                <td>{{ $brand->name }}</td>
                <td>
                    @if($brand->brand_image)
                        <img src="{{ $brand->brand_image }}" class="small-image">
                    @endif
                </td>
                <td>
                    @php
                        $similar_brands = explode(',', $brand->references);
                        $similar_brands = array_filter($similar_brands, function($element) {
                            return trim($element) !== "";
                        });
                    @endphp
                    @foreach($similar_brands as $similar_brand)
                        <p><span>{!! $similar_brand !!}</span> <a href="#"><span data-id="{{ $brand->id }}" class="fa fa-close unmerge-brand"></span></a></p>
                    @endforeach
                </td>
                <td>
                    <div class="form-select">
                      
 {{-- <strong>Brand</strong> --}}
 <select class="form-control globalSelect2 merge-brand merge_brand_close_e" data-brand-id = {{ $brand->id }} data-ajax="{{ route('select2.brands',['sort'=>true])  }}"  
     name="merge_brand" data-placeholder="-- Select Brand --">
     <option value="">Select a Brand</option>
 </select>


                    </div>
                </td>
                <td class="remote-td">{{ $brand->magento_id}}</td>
                <td>{{ $brand->euro_to_inr }}</td>
                <td>{{ $brand->deduction_percentage }}</td>
                <td>
                    <div class="form-select">
                        <?php
                        echo Form::select(
                            "brand_segment",
                            ["" => "--Select segment"] + \App\Brand::BRAND_SEGMENT,
                            $brand->brand_segment,
                            ["class" => "form-control change-brand-segment globalSelect2 merge_brand_close_e", "data-brand-id" => $brand->id,'data-placeholder'=>'-- Select Brand --']
                        ); ?>
                    </div>
                </td>
                @foreach($category_segments as $category_segment)
                    <td>
                        @php
                            $category_segment_discount = \DB::table('category_segment_discounts')->where('brand_id', $brand->id)->where('category_segment_id', $category_segment->id)->first();
                        @endphp

                        @if($category_segment_discount)
                            <input type="text" class="form-control" value="{{ $category_segment_discount->amount }}" onchange="store_amount({{ $brand->id }}, {{ $category_segment->id }})"></th>
                        @else
                            <input type="text" class="form-control" value="" onchange="store_amount({{ $brand->id }}, {{ $category_segment->id }})"></th>
                        @endif
                       {{-- <input type="text" class="form-control" value="{{ $brand->pivot->amount }}" onchange="store_amount({{ $brand->id }}, {{ $category_segment->id }})"> --}} {{-- Purpose : Comment code -  DEVTASK-4410 --}}


                    </td>
                @endforeach 
                <td class="show_brand" data-id="{{$brand->id}}" style="max-width: 150px;cursor: pointer; ">
                    <span style="word-wrap: break-word;" >{{ !empty($brand->selling_on) && !empty(explode(",", $brand->selling_on)[0]) ? (strlen($storeWebsite[explode(",", $brand->selling_on)[0]]) > 10 ? substr($storeWebsite[explode(",", $brand->selling_on)[0]], 0, 10) .' ...' : $storeWebsite[explode(",", $brand->selling_on)[0]]) : '' }}</span>
                    @if(!empty(explode(",", $brand->selling_on)[0]))
                    <br>
                    @endif
                    <span style="word-wrap: break-word;" >{{ !empty($brand->selling_on) && !empty(explode(",", $brand->selling_on)[1]) ? (strlen($storeWebsite[explode(",", $brand->selling_on)[1]]) > 10 ? substr($storeWebsite[explode(",", $brand->selling_on)[1]], 0, 10) .' ...' : $storeWebsite[explode(",", $brand->selling_on)[1]]) : '' }}</span>
                    @if(!empty(explode(",", $brand->selling_on)[1]))
                    @endif  
                    @if(explode(",", $brand->selling_on)[0] == '')
                    <a href="javascript:;" data-message="do you have fendi bags" class="btn btn-xs btn-image add-chat-phrases" title="Add phrases"><img src="/images/add.png" alt="" style="cursor: nwse-resize; width: 0px;"></a>
                    @endif
                </td>
               <td>
                <div class="form-group">
                    @php 
                    $priority_array=[null=>'Priority',1=>'Critical',2=>'High',3=>'Medium',4=>'Low'];
                    @endphp

                      {!!Form::select('priority',$priority_array,$brand->priority??'',array('class'=>'form-control input-sm mb-3 priority','data-id'=>$brand->id))!!}
                      
                    </div>       
                </td>
                <td>
                    <a style="padding:1px;" class="btn btn-image" href="{{ route('brand.edit',$brand->id) }}"><img src="/images/edit.png" /></a>
                    {!! Form::open(['method' => 'DELETE','route' => ['brand.destroy',$brand->id],'style'=>'display:inline']) !!}
                    <button style="padding:  1px;" type="submit" class="btn btn-image"><img src="/images/delete.png" /></button>
                    {!! Form::close() !!}
                    <a style="padding: 1px;" class="btn btn-image btn-attach-website" href="javascript:;"><i class="fa fa-globe"></i></a>
                    <a style="padding:  1px;" class="btn btn-image btn-create-remote" data-id="{{ $brand->id }}" href="javascript:;"><i class="fa fa-check-circle-o"></i></a>
                    <a style="padding: 1px;" class="btn btn-image btn-activity" data-href="{{ route('brand.activities',$brand->id) }}" href="javascript:;"><i class="fa fa-info"></i></a>
                </td>
            </tr>
            <div class="modal fade brand_modal_{{$brand->id}}" role="dialog">
                <div class="modal-dialog">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Attached Brands</h4>
                            <button type="button" class="close close_modal" data-dismiss="modal" data-id="{{$brand->id}}">&times;</button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group"> 
                                                <select name="attach_brands" class="form-control input-attach-brands select-multiple select2 attach_brands" multiple style="width:100%" data-placeholder="&nbsp-- Select Website(s) --" data-brand-id="{{$brand->id}}">
                                                    <option value="">&nbsp-- Select Website(s) --</option>            
                                                    @foreach($storeWebsite as $key => $w)
                                                    <option {{!empty($brand->selling_on) && in_array($key, explode(",", $brand->selling_on)) ? 'selected' : ''}} value="{{$key}}">{{$w}}</option> 
                                                    @endforeach
                                                </select> 
                                            </div>
                                        </div>  
                                    </div>  
                                </div>  
                            </div> 
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </table>
    </div>
</div>

<div id="ActivitiesModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Brand Activities</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body"></div>
        </div>
    </div>
</div>
<div id="upload-barnds-modal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
          <h4 class="modal-title">Upload Brand Logos</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <form id="upload-barnd-logos">
          <div class="modal-body">
              @csrf
              <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Logos</label>
                                <input type="file" name="files[]" id="filecount" multiple="multiple">
                            </div>
                        </div>  
                    </div>  
                </div>  
              </div>  
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-default">Save</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
       </form>
    </div>
  </div>
</div>
@endsection
@section('scripts')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jscroll/2.3.7/jquery.jscroll.min.js"></script>
<script src="/js/jquery-ui.js"></script>
<script src="/js/jquery.jscroll.min.js"></script>
<script src="/js/bootstrap-multiselect.min.js"></script>
<script src="/js/bootstrap-filestyle.min.js"></script>
<script type="text/javascript">
    function store_amount(brand_id, category_segment_id) {
        var amount = $(this.event.target).val();
        $.ajax({
            url: '{{ route('brand.store_category_segment_discount') }}',
            method: 'POST',
            data: {
                _token: "{{ csrf_token() }}",
                'brand_id': brand_id,
                'category_segment_id': category_segment_id,
                'amount': amount
            }
        });
    }

    jQuery(document).ready(function(){
        jQuery(".btn-activity").on("click",function(e){
            e.preventDefault();
            _this = jQuery(this);
            $.ajax({
                url: jQuery(_this).data('href'),
                method: 'GET',
                success: function(response) {
                    jQuery("#ActivitiesModal .modal-body").html(response);
                    jQuery("#ActivitiesModal").modal("show");
                },
                error: function(response){
                    toastr['error'](response.responseJSON.message, 'error');
                }
            });
        })
    });

    $(document).on('click', '.unmerge-brand', function(e) {
        e.preventDefault();
        var $this = $(this);
        if(confirm("Do you want to unmerge this brand?")) {
            var brand_name = $(this).parents().eq(1).find('span').first().text();
            var from_brand_id = $(this).data('id'); 
            $.ajax({
                url: '{{ route('brand.unmerge-brand') }}',
                method: 'POST',
                dataType: "json",
                data: {
                    _token: "{{ csrf_token() }}",
                    'brand_name': brand_name,
                    'from_brand_id': from_brand_id
                },
                success: function(response) {
                    toastr['success']((typeof response.message != "undefined") ? response.message : "Brand unmerged successfully", "success");
                    $this.closest("p").remove();
                    //location.reload();
                },
                error: function(response){
                    toastr['error'](response.responseJSON.message, 'error');
                } 
            });
        }
    });
    $(".select-multiple").select2();
    $(".select-multiple4").select2({
        tags: true
    });
    
    $('#calculatePriceButton').on('click', function() {
        var price = $('#product_price').val();
        var brand = $('#brand :selected').data('brand');
        var price_inr = Math.round(Math.round(price * brand.euro_to_inr) / 1000) * 1000;
        var price_special = Math.round(Math.round(price_inr - (price_inr * brand.deduction_percentage) / 100) / 1000) * 1000;

        var result = '<strong>INR Price: </strong>' + price_inr + '<br><strong>Special Price: </strong>' + price_special;

        $('#result-container').html(result);
    });

    $('ul.pagination').hide();
    $(function() {
        $('.infinite-scroll').jscroll({
            autoTrigger: true,
            loadingHtml: '<img class="center-block" src="/images/loading.gif" alt="Loading..." />',
            padding: 2500,
            nextSelector: '.pagination li.active + li a',
            contentSelector: 'div.infinite-scroll',
            callback: function() {
                setTimeout(function(){
                    $('ul.pagination').first().remove();
                }, 2000);
                $(".select-multiple").select2();
                initialize_select2();
            }
        });
    });

    $(document).on("change", ".input-attach-brands", function(e) {
        e.preventDefault();
        var brand_id = $(this).data("brand-id"),
            website = $(this).val();
        $.ajax({
            type: 'POST',
            url: "/brand/attach-website",
            data: {
                _token: "{{ csrf_token() }}",
                website: website,
                brand_id: brand_id
            }
        }).done(function(response) {
            if (response.code == 200) {
                toastr['success']('Website Attached successfully', 'success');
            }
        }).fail(function(response) {
            console.log("Could not update successfully");
        });
    });

    $(document).on("change", ".input-similar-brands", function(e) {
        e.preventDefault();
        var brand_id = $(this).data("reference-id");
        var reference = $(this).val();
        $.ajax({
            type: 'POST',
            url: "/brand/update-reference",
            data: {
                _token: "{{ csrf_token() }}",
                reference: reference,
                brand_id: brand_id
            }
        }).done(function(response) {
            if (response.code == 200) {
                toastr['success']('Reference updated successfully', 'success');
            }
        }).fail(function(response) {
            console.log("Could not update successfully");
        });
    });

    $(document).on("change",".merge-brand",function(e){
// console.log($(this).val(),'this is chnge')
$('.select2-selection__clear').remove()
        if($(this).val()){
            
            var ready = confirm("Are you sure want to merge brand ?");
                if (ready) {
                    var brand_id = $(this).data("brand-id");
                    var reference = $(this).val();
                    $.ajax({
                        type: 'POST',
                        url: "/brand/merge-brand",
                        data: {
                            _token: "{{ csrf_token() }}",
                            from_brand: brand_id,
                            to_brand: reference
                        }
                    }).done(function(response) {
                        if (response.code == 200) {
                            toastr['success']('Brand merged successfully', 'success');
                            //location.reload();
                        }
                    }).fail(function(response) {
                        console.log("Could not update successfully");
                    });
                }else{
                    return false;
                }

        }
    });
    

    $(document).on("change", ".change-brand-segment", function(e) {
        e.preventDefault();
        var brand_id = $(this).data("brand-id"),
            segment = $(this).val();
        $.ajax({
            type: 'POST',
            url: "/brand/change-segment",
            data: {
                _token: "{{ csrf_token() }}",
                segment: segment,
                brand_id: brand_id
            }
        }).done(function(response) {
            if (response.code == 200) {
                toastr['success']('Brand segment change successfully', 'success');
            }
        }).fail(function(response) {
            console.log("Could not update successfully");
        });
    });



    $(document).on("click", ".btn-create-remote", function(e) {
        e.preventDefault();
        var $this = $(this);
        var ready = confirm("Are you sure want to create remote id ?");
        if (ready) {
            var brandId = $(this).data("id");
            $.ajax({
                type: 'GET',
                url: "/brand/" + brandId + "/create-remote-id",
            }).done(function(response) {
                if (response.code == 200) {
                    $this.closest("tr").find(".remote-td").html(response.data.magento_id);
                    toastr['success'](response.message, 'success');
                } else if (response.code == 500) {
                    toastr['error'](response.message, 'error');
                }
            }).fail(function(response) {
                console.log("Could not update successfully");
            });
        }
    });

    $(document).on('change', '.priority', function () {
        var $this = $(this);
        var brand_id = $this.data("id");
        var priority = $this.val();
        $.ajax({
            type: "PUT",
            url: "/brand/priority/"+brand_id,
            data: {
                _token: "{{ csrf_token() }}",
                supplier_id : brand_id,
                priority: priority
            }
        }).done(function (response) {
             toastr['success'](response.message, 'success');
        }).fail(function (response) {
           toastr['error'](response.message, 'error');
        });
    });
    $(document).on("submit","#upload-barnd-logos",function(e) {
        e.preventDefault();
        var form = $(this);
        var postData = new FormData(form[0]);
        $.ajax({
            method : "POST",
            url: "/brand/fetch-new/",
            data: postData,
            processData: false,
            contentType: false,
            dataType: "json",
            success: function (response) {
                if(response.code == 200) {
                    toastr["success"]("Logos updated!", "Message")
                    $("#upload-barnd-logos").modal("hide");
                }else{
                    toastr["error"](response.error, "Message");
                }
            }
        });
    });
    // $(document).on('click','.fetch-new', function(event){
    //     event.preventDefault();
    //     $.ajax({
    //         type: "GET",
    //         url: "/brand/fetch-new/",
    //         data: {
    //             _token: "{{ csrf_token() }}",
    //         }
    //     }).done(function (response) {
    //          toastr['success'](response.message, 'success');
    //     }).fail(function (response) {
    //        toastr['error'](response.message, 'error');
    //     });
    // });
    $(document).on('click','.fetch-new', function(event){
        event.preventDefault();
        $.ajax({
            type: "GET",
            url: "/brand/fetch-new/",
            data: {
                _token: "{{ csrf_token() }}",
            }
        }).done(function (response) {
             toastr['success'](response.message, 'success');
        }).fail(function (response) {
           toastr['error'](response.message, 'error');
        });
    });

    $(document).on('click','.show_brand', function(event){
        $(".brand_modal_"+$(this).attr('data-id')).modal("show"); 
    }); 

    $('.modal').on('shown.bs.modal', function (e) {
        $(".attach_brands").select2({
            placeholder: " abc xyz"
        });
    })
 
</script>
@endsection
