@extends('layouts.app')

@section('favicon' , 'scraperurl.png')

@section('title', 'Scraped URL Info')

@section("styles")
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<style type="text/css">
    #loading-image {
        position: fixed;
        top: 50%;
        left: 50%;
        margin: -50px 0px 0px -50px;
    }
</style>
@endsection

@section('large_content')
<div id="myDiv">
    <img id="loading-image" src="/images/pre-loader.gif" style="display:none;" />
</div>
<!-- <div class="row">
        <div class="col-lg-12 margin-tb">
            <h2 class="page-heading">Scraped URLs</h2>
             <div class="pull-right">
                <button type="button" class="btn btn-image" onclick="refreshPage()"><img src="/images/resend2.png" /></button>
            </div>

        </div>
    </div> -->
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="page-heading-title">Scraped URLs</div>
        <div class="pull-left cls_filter_box">
            <form class="form-inline" method="post" style='padding-left:25px;'>
                <div class="form-group mr-3 mb-3">
                    @php
                    $websites = \App\ScrapedProducts::select('id','website')->groupBy('website')->get();
                    @endphp
                    <select class="form-control select-multiple2" data-placeholder="Select websites.." multiple id="website">
                        <optgroup label="Websites">
                            @foreach ($websites as $website)
                            <option value="{{ $website->website }}">{{ $website->website }}</option>
                            @endforeach
                        </optgroup>
                    </select>
                </div>
                <div class="form-group mr-3 mb-3">
                    <input type="text" class="search form-control" id="url" placeholder='URL' size="22">
                </div>
                <div class="form-group mr-3 mb-3">
                    <input type="text" class="search form-control" id="sku" placeholder='SKU' size="17">
                </div>
                <div class="form-group mr-3 mb-3">
                    @php $brands = \App\Brand::getAll(); @endphp
                    <select class="form-control select-multiple2" name="brand[]" id="brand" data-placeholder="Select brand.." multiple>
                        <optgroup label="Brands">
                            @foreach ($brands as $key => $name)
                            <option value="{{ $key }}" {{ isset($brand) && $brand == $key ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </optgroup>
                    </select>
                </div>
                <div class="form-group mr-3 mb-3">
                    <input type="text" class="search form-control" id="title" placeholder='Title' size="17">
                </div>
                <div class="form-group mr-3 mb-3">
                    <input type="text" class="search form-control" id="currency" placeholder='Currency' size="17">
                </div>
                <div class="form-group mr-3 mb-3">
                    <input type="text" class="search form-control" id="price" placeholder='Price' size="20">
                </div>
                <div class="form-group mr-3 mb-3">
                    <input type="text" class="search form-control" id="color" placeholder='Color' size="22">
                </div>
                <div class="form-group mr-3 mb-3">
                    <input type="text" class="search form-control" id="psize" placeholder='Sizes' size="17">
                </div>
                <div class="form-group mr-3 mb-3">
                    <input type="text" class="search form-control" id="dimension" placeholder='Dimensions' size="17">
                </div>
                <div class="form-group mr-3 mb-3">
                    <input type="text" class="search form-control" id="category" placeholder='Category' size="17">
                </div>
                <div class="form-group mr-3 mb-3">
                    <input type="text" class="search form-control" id="product_id" placeholder='PID eg.123,124,125' size="17">
                </div>

                <div class="form-group mr-3 mb-3">
                    <div class='input-group' id='created-date'>
                        <input type='text' class="form-control " name="phone_date" value="" placeholder="Date" id="created_date" size="12" />
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                </div>
                <div class="form-group mr-3 mb-3">
                    <select class="search form-control" id="prod_img_filter" name="prod_img_filter">
                        <option value="">FIlter Images</option>
                        <option value="0">With Images</option>
                        <option value="1">Without Images</option>
                    </select>
                </div>
                <div class="form-group mr-3 mb-3">
                    <select class="search form-control" id="prod_error_filter" name="prod_error_filter">
                        <option value="">Filter Errors</option>
                        <option value="0">With Errors</option>
                        <option value="1">Without Errors</option>
                    </select>
                </div>
                <div class="form-group">
                    <button style="display: inline-block;width: 10%" class="btn btn-sm btn-image" id="btn-scraped-search-action">
                        <img src="/images/search.png" style="cursor: default;">
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@include('partials.flash_messages')
<!-- <div class="mt-3 col-md-12">
        <form action="/scrap/scraped-urls" method="get">
            <div class="row">
                <div class="col-md-5">
                    
                     <select class="form-control select-multiple2" data-placeholder="Select columns.." multiple id="columns" name="columns[]">
                        <optgroup label="columns">
                            <option value="">Select Any</option>
                            <option value="color" @if($response != null) @if(in_array('color',$response['columns']))  selected  @endif @endif>Color</option>
                            <option value="category"  @if($response != null) @if(in_array('category',$response['columns']))  selected  @endif @endif>Category</option>
                            <option value="description"  @if($response != null) @if(in_array('description',$response['columns']))  selected  @endif @endif>Description</option>
                            <option value="size_system"  @if($response != null) @if(in_array('size_system',$response['columns']))  selected  @endif @endif>Size system</option>
                            <option value="is_sale"  @if($response != null) @if(in_array('is_sale',$response['columns']))  selected  @endif @endif>Is Sale</option>
                            <option value="gender"  @if($response != null) @if(in_array('gender',$response['columns']))  selected  @endif @endif>Gender</option>
                            <option value="composition"  @if($response != null) @if(in_array('composition',$response['columns']))  selected  @endif @endif>Composition</option>
                            <option value="size"  @if($response != null) @if(in_array('size',$response['columns']))  selected  @endif @endif>Size</option>
                            <option value="lmeasurement"  @if($response != null) @if(in_array('lmeasurement',$response['columns']))  selected  @endif @endif>Lmeasurement</option>
                            <option value="hmeasurement"  @if($response != null) @if(in_array('hmeasurement',$response['columns']))  selected  @endif @endif>Hmeasurement</option>
                            <option value="dmeasurement"  @if($response != null) @if(in_array('dmeasurement',$response['columns']))  selected  @endif @endif>Dmeasurement</option>
                            <option value="measurement_size_type"  @if($response != null) @if(in_array('measurement_size_type',$response['columns']))  selected  @endif @endif>Measurement size type</option>
                        </optgroup>
                      </select>
                </div>
                <div class="col-md-3">
                    <div class="input-group input-daterange">
                        <input type="text" name="daterange" class="form-control" value="2012-04-05">
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-image"><img src="/images/filter.png" /></button>
                </div>
            </div>    
        </form>
     </div> -->
<div class="mt-3 col-md-12">
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <table class="table table-bordered table-striped" id="log-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Total</th>
                        <th>Total Unique</th>
                        </th>
                </thead>
                <tbody>
                    <?php if (!$summeryRecords->isEmpty()) { ?>
                        <?php foreach ($summeryRecords as $rec) { ?>
                            <tr>
                                <td><?php echo $rec->date; ?></td>
                                <td><?php echo $rec->total_record; ?></td>
                                <td><?php echo $rec->total_u_record; ?></td>
                            </tr>
                        <?php } ?>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="mt-3 col-md-12">
    <table class="table table-bordered table-striped" id="log-table">
        <thead>
            <tr>
                <th width="5%">PId</th>
                <th width="5%">Website</th>
                <th width="10%">Url</th>
                <th width="10%">Sku</th>
                <th width="10%">Brand</th>
                <th width="10%">Title</th>
                <th width="10%">Currency</th>
                <th width="5%">Price</th>
                <th width="5%">Image</th>
                <th width="5%">Created_at</th>
                <!-- <th width="10%"><button class="btn btn-link" onclick="sortByDateUpdated()" id="header-updated" value="0">Updated_at</button></th> -->
                @if($response != null)
                @if(in_array('color',$response['columns']))
                <th width="5%">Color</th>
                @endif
                @if(in_array('category',$response['columns']))
                <th width="5%">Category</th>
                @endif
                @if(in_array('description',$response['columns']))
                <th>Description</th>
                @endif
                @if(in_array('size_system',$response['columns']))
                <th>Size system</th>
                @endif
                @if(in_array('is_sale',$response['columns']))
                <th>Is Sale</th>
                @endif
                @if(in_array('gender',$response['columns']))
                <th>Gender</th>
                @endif
                @if(in_array('composition',$response['columns']))
                <th>Composition</th>
                @endif
                @if(in_array('size',$response['columns']))
                <th width="5%">Sizes</th>
                @endif
                @if(in_array('dimension',$response['columns']))
                <th width="5%">Dimensions</th>
                @endif
                @if(in_array('lmeasurement',$response['columns']))
                <th>Lmeasurement</th>
                @endif
                @if(in_array('hmeasurement',$response['columns']))
                <th>Hmeasurement</th>
                @endif
                @if(in_array('dmeasurement',$response['columns']))
                <th>Dmeasurement</th>
                @endif
                @if(in_array('measurement_size_type',$response['columns']))
                <th>Measurement size type</th>
                @endif

                @endif
                <th width="5%">Errors</th>
                <th>Action</th>
            </tr>
            <!-- <tr>
                <th width="30%">
                    @php 
                    $websites = \App\ScrapedProducts::select('id','website')->groupBy('website')->get();
                    @endphp
                    <select class="form-control select-multiple2" data-placeholder="Select websites.." multiple id="website">
                                <optgroup label="Websites">
                                  @foreach ($websites as $website)
                                    <option value="{{ $website->website }}">{{ $website->website }}</option>
                                  @endforeach
                                </optgroup>
                              </select>
                </th>
                <th width="10%"><input type="text" class="search form-control" id="url"></th>
                <th width="10%"><input type="text" class="search form-control" id="sku"></th>
                <th width="40%"> @php $brands = \App\Brand::getAll(); @endphp
                              <select class="form-control select-multiple2" name="brand[]" id="brand" data-placeholder="Select brand.." multiple >
                                <optgroup label="Brands">
                                  @foreach ($brands as $key => $name)
                                    <option value="{{ $name }}" {{ isset($brand) && $brand == $key ? 'selected' : '' }}>{{ $name }}</option>
                                  @endforeach
                                </optgroup>
                              </select></th>
                <th width="15%"><input type="text" class="search form-control" id="title"></th>
                <th width="10%"><input type="text" class="search form-control" id="currency"></th>
                <th width="10%"><input type="text" class="search form-control" id="price"></th>
                <th width="10%"> <div class='input-group' id='created-date'>
                        <input type='text' class="form-control " name="phone_date" value="" placeholder="Date" id="created_date" />
                            <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div></th>
                <th> <div class='input-group' id='updated-date'>
                        <input type='text' class="form-control " name="phone_date" value="" placeholder="Date" id="updated_date" />
                            <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div></th>
                        
                @if($response != null)
                    
                    @if(in_array('color',$response['columns']))
                    <th></th>
                     @endif
                    
                    @if(in_array('category',$response['columns']))
                     <th></th>
                    @endif

                    @if(in_array('description',$response['columns']))
                     <th></th>
                    @endif

                    @if(in_array('size_system',$response['columns']))
                     <th></th>
                    @endif

                    @if(in_array('is_sale',$response['columns']))
                     <th></th>
                    @endif

                    @if(in_array('gender',$response['columns']))
                     <th></th>
                    @endif
                    
                    @if(in_array('composition',$response['columns']))
                    <th></th>
                    @endif

                    @if(in_array('size',$response['columns']))
                    <th></th>
                    @endif

                    @if(in_array('lmeasurement',$response['columns']))
                     <th></th>
                    @endif

                    @if(in_array('hmeasurement',$response['columns']))
                     <th></th>
                    @endif

                    @if(in_array('dmeasurement',$response['columns']))
                     <th></th>
                    @endif

                    @if(in_array('measurement_size_type',$response['columns']))
                     <th></th>
                    @endif

                    @endif
            </tr>
            </thead> -->

        <tbody id="content_data">
            @include('scrap.partials.scraped_url_data')
        </tbody>

        {!! $logs->render() !!}

    </table>
    {!! $logs->links() !!}
</div>


@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        $(".select-multiple").multiselect();
        $(".select-multiple2").select2();
        // $('#brand').on('change', function (e) {
        //     website = $('#website').val();
        //     brand = $('#brand').val();
        //     url = $('#url').val();
        //     sku = $('#sku').val();
        //     title = $('#title').val();
        //     currency = $('#currency').val();
        //     price = $('#price').val();
        //     columns = $('#columns').val();
        //     src = "/scrap/scraped-urls";
        //    $.ajax({
        //         url: src,
        //         dataType: "json",
        //         data: {
        //             website : website,
        //             url : url,
        //             sku : sku,
        //             title : title,
        //             currency : currency,
        //             price : price,
        //             brand: brand,
        //             columns : columns,
        //         },
        //         beforeSend: function() {
        //                $("#loading-image").show();
        //         },

        //     }).done(function (data) {
        //          $("#loading-image").hide();
        //         console.log(data);
        //         $("#log-table tbody").empty().html(data.tbody);
        //         if (data.links.length > 10) {
        //             $('ul.pagination').replaceWith(data.links);
        //         } else {
        //             $('ul.pagination').replaceWith('<ul class="pagination"></ul>');
        //         }

        //     }).fail(function (jqXHR, ajaxOptions, thrownError) {
        //         alert('No response from server');
        //     });
        // });

        // $('#website').on('change', function (e) {
        //     website = $('#website').val();
        //     brand = $('#brand').val();
        //     url = $('#url').val();
        //     sku = $('#sku').val();
        //     title = $('#title').val();
        //     currency = $('#currency').val();
        //     price = $('#price').val();
        //     columns = $('#columns').val();
        //    src = "/scrap/scraped-urls"; 
        //    $.ajax({
        //         url: src,
        //         dataType: "json",
        //         data: {
        //             website : website,
        //             url : url,
        //             sku : sku,
        //             title : title,
        //             currency : currency,
        //             price : price,
        //             brand: brand,
        //             columns : columns,
        //         },
        //         beforeSend: function() {
        //                $("#loading-image").show();
        //         },

        //     }).done(function (data) {
        //          $("#loading-image").hide();
        //         console.log(data);
        //         $("#log-table tbody").empty().html(data.tbody);
        //         if (data.links.length > 10) {
        //             $('ul.pagination').replaceWith(data.links);
        //         } else {
        //             $('ul.pagination').replaceWith('<ul class="pagination"></ul>');
        //         }

        //     }).fail(function (jqXHR, ajaxOptions, thrownError) {
        //         alert('No response from server');
        //     });
        // });
    });



    function myFunction(input) {
        /* Get the text field */
        var copyText = document.getElementById(input);

        /* Select the text field */
        copyText.select();
        copyText.setSelectionRange(0, 99999); /*For mobile devices*/

        /* Copy the text inside the text field */
        document.execCommand("copy");

        /* Alert the copied text */
        alert("Copied the text: " + copyText.value);
    }



    $(function() {

        var start = moment().subtract(0, 'days');
        var end = moment();

        function cb(start, end) {
            $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            $('#custom').val(start.format('YYYY/MM/DD') + ' - ' + end.format('YYYY/MM/DD'));
        }

        $('#reportrange').daterangepicker({
            startDate: start,
            endDate: end,
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        }, cb)
        cb(start, end);
    });

    //Ajax Request For Search
    $(document).ready(function() {


        //Expand Row
        $(document).on('click', '.expand-row', function() {
            var selection = window.getSelection();
            if (selection.toString().length === 0) {
                $(this).find('.td-mini-container').toggleClass('hidden');
                $(this).find('.td-full-container').toggleClass('hidden');
            }
        });

        //Filter by date
        count = 0;
        $('#created-date').datetimepicker({
            format: 'YYYY/MM/DD'
        }).on('dp.change',
            function(e) {
                if (count > 0) {
                    var formatedValue = e.date.format(e.date._f);
                    created = $('#created_date').val();
                    updated = $('#updated_date').val();
                    website = $('#website').val();
                    url = $('#url').val();
                    sku = $('#sku').val();
                    title = $('#title').val();
                    currency = $('#currency').val();
                    price = $('#price').val();
                    brand = $('#brand').val();
                    columns = $('#columns').val();

                    src = "/scrap/scraped-urls";
                    // $.ajax({
                    //     url: src,
                    //     dataType: "json",
                    //     data: {
                    //         created : created,
                    //         updated : updated,
                    //         website : website,
                    //         url : url,
                    //         sku : sku,
                    //         title : title , 
                    //         currency : currency , 
                    //         price : price , 
                    //         brand : brand,
                    //         columns : columns,

                    //     },
                    //     beforeSend: function () {
                    //         $("#loading-image").show();
                    //     },

                    // }).done(function (data) {
                    //     $("#loading-image").hide();
                    // $("#content_data").empty().html(data.tbody);
                    // if (data.links.length > 10) {
                    //     $('ul.pagination').replaceWith(data.links);
                    // } else {
                    //     $('ul.pagination').replaceWith('<ul class="pagination"></ul>');
                    // }


                    // }).fail(function (jqXHR, ajaxOptions, thrownError) {
                    //     alert('No response from server');
                    // });  

                }
                count++;
            });


        count = 0;
        $('#updated-date').datetimepicker({
            format: 'YYYY/MM/DD'
        }).on('dp.change',
            function(e) {
                if (count > 0) {
                    var formatedValue = e.date.format(e.date._f);
                    created = $('#created_date').val();
                    updated = $('#updated_date').val();
                    website = $('#website').val();
                    url = $('#url').val();
                    sku = $('#sku').val();
                    title = $('#title').val();
                    currency = $('#currency').val();
                    price = $('#price').val();
                    brand = $('#brand').val();
                    columns = $('#columns').val();

                    src = "/scrap/scraped-urls";
                    $.ajax({
                        url: src,
                        dataType: "json",
                        data: {
                            created: created,
                            updated: updated,
                            website: website,
                            url: url,
                            sku: sku,
                            title: title,
                            currency: currency,
                            price: price,
                            brand: brand,
                            columns: columns,

                        },
                        beforeSend: function() {
                            $("#loading-image").show();
                        },

                    }).done(function(data) {
                        $("#loading-image").hide();
                        $("#content_data").empty().html(data.tbody);
                        if (data.links.length > 10) {
                            $('ul.pagination').replaceWith(data.links);
                        } else {
                            $('ul.pagination').replaceWith('<ul class="pagination"></ul>');
                        }


                    }).fail(function(jqXHR, ajaxOptions, thrownError) {
                        alert('No response from server');
                    });

                }
                count++;
            });


        //Search    
        src = "/scrap/scraped-urls";
        $(".search").autocomplete({
            source: function(request, response) {
                url = $('#url').val();
                sku = $('#sku').val();
                title = $('#title').val();
                currency = $('#currency').val();
                price = $('#price').val();
                columns = $('#columns').val();

                //    $.ajax({
                //         url: src,
                //         dataType: "json",
                //         data: {
                //             website : website,
                //             url : url,
                //             sku : sku,
                //             title : title,
                //             currency : currency,
                //             price : price,
                //             columns : columns,

                //         },
                //         beforeSend: function() {
                //                $("#loading-image").show();
                //         },

                //     }).done(function (data) {
                //          $("#loading-image").hide();
                //         console.log(data);
                //         $("#log-table tbody").empty().html(data.tbody);
                //         if (data.links.length > 10) {
                //             $('ul.pagination').replaceWith(data.links);
                //         } else {
                //             $('ul.pagination').replaceWith('<ul class="pagination"></ul>');
                //         }

                //     }).fail(function (jqXHR, ajaxOptions, thrownError) {
                //         alert('No response from server');
                //     });
            },
            minLength: 1,

        });
    });

    function refreshPage() {
        blank = ''
        $.ajax({
            url: src,
            dataType: "json",
            data: {
                blank: blank
            },
            beforeSend: function() {
                $("#loading-image").show();
            },

        }).done(function(data) {
            $("#loading-image").hide();
            console.log(data);
            $("#log-table tbody").empty().html(data.tbody);
            if (data.links.length > 10) {
                $('ul.pagination').replaceWith(data.links);
            } else {
                $('ul.pagination').replaceWith('<ul class="pagination"></ul>');
            }

        }).fail(function(jqXHR, ajaxOptions, thrownError) {
            alert('No response from server');
        });
    }



    function sortByDateCreated() {
        orderCreated = $('#header-created').val();
        website = $('#website').val();
        url = $('#url').val();
        sku = $('#sku').val();
        title = $('#title').val();
        currency = $('#currency').val();
        price = $('#price').val();
        brand = $('#brand').val();
        columns = $('#columns').val();

        src = "/scrap/scraped-urls";
        $.ajax({
            url: src,
            dataType: "json",
            data: {
                website: website,
                url: url,
                sku: sku,
                title: title,
                currency: currency,
                price: price,
                brand: brand,
                orderCreated: orderCreated,
                columns: columns,

            },
            beforeSend: function() {
                if (orderCreated == 0) {
                    $('#header-created').val('1');
                } else {
                    $('#header-created').val('0');
                }
                $("#loading-image").show();
            },

        }).done(function(data) {
            $("#loading-image").hide();
            $("#content_data").empty().html(data.tbody);
            if (data.links.length > 10) {
                $('ul.pagination').replaceWith(data.links);
            } else {
                $('ul.pagination').replaceWith('<ul class="pagination"></ul>');
            }


        }).fail(function(jqXHR, ajaxOptions, thrownError) {
            alert('No response from server');
        });

    }


    function sortByDateUpdated() {
        orderUpdated = $('#header-updated').val();
        website = $('#website').val();
        url = $('#url').val();
        sku = $('#sku').val();
        title = $('#title').val();
        currency = $('#currency').val();
        price = $('#price').val();
        brand = $('#brand').val();
        columns = $('#columns').val();

        src = "/scrap/scraped-urls";
        $.ajax({
            url: src,
            dataType: "json",
            data: {
                website: website,
                url: url,
                sku: sku,
                title: title,
                currency: currency,
                price: price,
                brand: brand,
                orderUpdated: orderUpdated,
                columns: columns,
            },
            beforeSend: function() {
                if (orderUpdated == 0) {
                    $('#header-updated').val('1');
                } else {
                    $('#header-updated').val('0');
                }
                $("#loading-image").show();
            },

        }).done(function(data) {
            $("#loading-image").hide();
            $("#content_data").empty().html(data.tbody);
            if (data.links.length > 10) {
                $('ul.pagination').replaceWith(data.links);
            } else {
                $('ul.pagination').replaceWith('<ul class="pagination"></ul>');
            }


        }).fail(function(jqXHR, ajaxOptions, thrownError) {
            alert('No response from server');
        });

    }
    $('.input-daterange input').each(function() {
        $(this).daterangepicker({
            locale: {
                format: "YYYY-MM-DD"
            }
        });
    });

    $('#btn-scraped-search-action').on('click', function() {
        created = $('#created_date').val();
        orderCreated = $('#header-created').val();
        website = $('#website').val();
        url = $('#url').val();
        sku = $('#sku').val();
        title = $('#title').val();
        currency = $('#currency').val();
        price = $('#price').val();
        brand = $('#brand').val();
        columns = $('#columns').val();
        color = $('#color').val();
        category = $('#category').val();
        psize = $('#psize').val();
        dimension = $('#dimension').val();
        product_id = $('#product_id').val();
        prod_img_filter = $('#prod_img_filter').val();
        prod_error_filter = $('#prod_error_filter').val();
        src = "/scrap/scraped-urls";
        $.ajax({
            url: src,
            dataType: "json",
            data: {
                created: created,
                website: website,
                url: url,
                sku: sku,
                title: title,
                currency: currency,
                price: price,
                brand: brand,
                orderCreated: orderCreated,
                columns: columns,
                color: color,
                category: category,
                psize: psize,
                dimension: dimension,
                product_id: product_id,
                prod_img_filter:prod_img_filter,
                prod_error_filter:prod_error_filter,
            },
            beforeSend: function() {
                if (orderCreated == 0) {
                    $('#header-created').val('1');
                } else {
                    $('#header-created').val('0');
                }
                $("#loading-image").show();
            },
        }).done(function(data) {
            $("#loading-image").hide();
            $("#content_data").empty().html(data.tbody);
            if (data.links.length > 10) {
                $('ul.pagination').replaceWith(data.links);
            } else {
                $('ul.pagination').replaceWith('<ul class="pagination"></ul>');
            }
        }).fail(function(jqXHR, ajaxOptions, thrownError) {
            $("#loading-image").hide();
            alert('No response from server');
        });
        return false;
    });
</script>
@endsection