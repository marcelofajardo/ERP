@extends('layouts.app')

@section('large_content')
    <style type="text/css">
        .btn-secondary {
            margin-top : 2px;
        }
        .category-mov-btn
        {
            min-height : 60px;
        }
    </style>
    <div class="row">
        <div class="col-md-12">
            <h2 class="page-heading">Categories Map (References)</h2>
        </div>
        <div class="col-md-12">
            <form method="post" id="update-reference-tb" action="{{ action('CategoryController@saveReferences') }}">
                @csrf
                <table class="table table-bordered table-striped sortable-tables">

                    <tr>
                        <th>Category Name</th>
                        <th>Unknown References</th>
                        <th>Status After Autocrop</th>
                    </tr>
                    <tr>
                        <td colspan="3">Unknown references are shown in 'Unknown Category'. If you want a reference not to appear in here, enter it in 'Ignore Category Reference'. Please note that products with ignored or unknown categories will be linked to those categories.</td>
                    </tr>

                    @foreach($fillerCategories as $category)
                        <tr>
                            <td>
                                {{ $category->title }} <button style="padding-right:0px;" type="button" class="btn btn-xs show-history-btn" title="Show History" data-id="{{ $category->id }}"><i class="fa fa-info-circle"></i></button>
                            </td>
                            <td>
                                <div data-cat-id="{{ $category->id }}" class="col-md-8 category-mov-btn">
                                    @php $options = explode(',', $category->references) @endphp
                                    @if(count($options)>0)
                                        @foreach($options as $option)
                                            @if(strlen($option) > 1)
                                                <span class="btn btn-secondary">{{$option}} <i data-name="{{$option}}" class="fa fa-eye call-used-product"></i></span>
                                            @endif
                                        @endforeach
                                    @else
                                         &nbsp;    
                                    @endif
                                </div>
                                <!-- <select data-cat-id="{{ $category->id }}" name="category[{{ $category->id }}][]" cols="30" rows="2" class="form-control" multiple>
                                    @php $options = explode(',', $category->references) @endphp
                                    @if(count($options)>0)
                                        @foreach($options as $option)
                                            @if(strlen($option) > 1)
                                                <option selected value="{{$option}}">{{$option}}</option>
                                            @endif
                                        @endforeach
                                    @endif
                                </select> -->
                            </td>
                            <td>
                                <div data-cat-id="{{ $category->id }}" class=" category-next-btn">
                                   <?php echo Form::select("status_after_autocrop",$allStatus,$category->status_after_autocrop,["class" => "select2 form-control status_after_autocrop","style"=>"width:200px;"]); ?>
                                </div>
                            </td>
                        </tr>
                    @endforeach

                    <tr>
                        <th>Category Name</th>
                        <th>References</th>
                        <th>References</th>
                    </tr>

                    @foreach($categories as $category)
                        <tr>
                            <td>
                                {{ $category->title }} <button style="padding-right:0px;" type="button" class="btn btn-xs show-history-btn" title="Show History" data-id="{{ $category->id }}"><i class="fa fa-info-circle"></i></button>
                            </td>
                            <td>
                                <div data-cat-id="{{ $category->id }}" class="col-md-8 category-mov-btn">
                                    @php $options = explode(',', $category->references) @endphp
                                    @if(count($options)>0)
                                        @foreach($options as $option)
                                            @if(strlen($option) > 1)
                                                <span class="btn btn-secondary">{{$option}} <i data-name="{{$option}}" class="fa fa-eye call-used-product"></i></span>
                                            @endif
                                        @endforeach
                                    @else
                                         &nbsp;    
                                    @endif
                                </div>    

                                <!-- <select data-cat-id="{{ $category->id }}" name="category[{{ $category->id }}][]" cols="30" rows="2" class="form-control" multiple>
                                    @php $options = explode(',', $category->references) @endphp
                                    @if(count($options)>0)
                                        @foreach($options as $option)
                                            @if(strlen($option) > 1)
                                                <option selected value="{{$option}}">{{$option}}</option>
                                            @endif
                                        @endforeach
                                    @endif
                                </select> -->
                            </td>
                            <td>
                                <div data-cat-id="{{ $category->id }}" class=" category-next-btn">
                                   <?php echo Form::select("status_after_autocrop",$allStatus,$category->status_after_autocrop,["class" => "select2 form-control status_after_autocrop","style"=>"width:200px;"]); ?>
                                </div>
                            </td>
                        </tr>
                        <!-- get sub categories -->
                        @php
                            $subcategories = \App\Category::where( 'id', '>', 1 )->where('parent_id', $category->id)->get();
                        @endphp
                        @if ( $subcategories != null )
                            @foreach($subcategories as $subcategory)
                                <tr>
                                    <td>
                                        {{ $category->title }} &gt; {{ $subcategory->title }} <button style="padding-right:0px;" type="button" class="btn btn-xs show-history-btn" title="Show History" data-id="{{ $subcategory->id }}"><i class="fa fa-info-circle"></i></button>
                                    </td>
                                    <td>
                                        <div data-cat-id="{{ $subcategory->id }}" class="col-md-8 category-mov-btn">
                                            @php $options = explode(',', $subcategory->references) @endphp
                                            @if(count($options)>0)
                                                @foreach($options as $option)
                                                    @if(strlen($option) > 1)
                                                        <span class="btn btn-secondary">{{$option}} <i data-name="{{$option}}" class="fa fa-eye call-used-product"></i></span>
                                                    @endif
                                                @endforeach
                                            @else
                                                 &nbsp;    
                                            @endif
                                        </div>
                                        <!-- <select data-cat-id="{{ $category->id }}" name="category[{{ $subcategory->id }}][]" cols="30" rows="2" class="form-control" multiple>
                                            @php $options = explode(',', $subcategory->references) @endphp
                                            @if(count($options)>0)
                                                @foreach($options as $option)
                                                    @if(strlen($option) > 1)
                                                        <option selected value="{{$option}}">{{$option}}</option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </select> -->
                                    </td>
                                    <td>
                                        <div data-cat-id="{{ $subcategory->id }}" class=" category-next-btn">
                                           <?php echo Form::select("status_after_autocrop",$allStatus,$subcategory->status_after_autocrop,["class" => "select2 form-control status_after_autocrop","style"=>"width:200px;"]); ?>
                                        </div>
                                    </td>
                                </tr>
                                <!-- get sub categories -->
                                @php
                                    $sscategories = \App\Category::where( 'id', '>', 1 )->where('parent_id', $subcategory->id)->get();
                                @endphp
                                @if ( $sscategories != null )
                                    @foreach($sscategories as $sscategory)
                                        <tr>
                                            <td>
                                                {{ $category->title }} &gt; {{ $subcategory->title }} &gt; {{ $sscategory->title }} <button style="padding-right:0px;" type="button" class="btn btn-xs show-history-btn" title="Show History" data-id="{{ $sscategory->id }}"><i class="fa fa-info-circle"></i></button>
                                            </td>
                                            <td>
                                                <div data-cat-id="{{ $sscategory->id }}" class="col-md-8 category-mov-btn">
                                                    @php $options = explode(',', $sscategory->references) @endphp
                                                    @if(count($options)>0)
                                                        @foreach($options as $option)
                                                            @if(strlen($option) > 1)
                                                                <span class="btn btn-secondary">{{$option}} <i data-name="{{$option}}" class="fa fa-eye call-used-product"></i></span>
                                                            @endif
                                                        @endforeach
                                                    @else
                                                         &nbsp;    
                                                    @endif
                                                </div>
                                                <!-- <select data-cat-id="{{ $category->id }}" name="category[{{ $sscategory->id }}][]" cols="30" rows="2" class="form-control" multiple>
                                                    @php $options = explode(',', $sscategory->references) @endphp
                                                    @if(count($options)>0)
                                                        @foreach($options as $option)
                                                            @if(strlen($option) > 1)
                                                                <option selected value="{{$option}}">{{$option}}</option>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </select> -->
                                            </td>
                                            <td>
                                                <div data-cat-id="{{ $sscategory->id }}" class=" category-next-btn">
                                                   <?php echo Form::select("status_after_autocrop",$allStatus,$subcategory->status_after_autocrop,["class" => "select2 form-control status_after_autocrop","style"=>"width:200px;"]); ?>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            @endforeach
                        @endif
                    @endforeach
                </table>
            </form>
        </div>
    </div>
@endsection
<div id="loading-image" style="position: fixed;left: 0px;top: 0px;width: 100%;height: 100%;z-index: 9999;background: url('/images/pre-loader.gif') 
          50% 50% no-repeat;display:none;">
</div>
<div class="common-modal modal show-listing-exe-records" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
    </div>  
</div>
<div id="categoryUpdate" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <form class="cat-update-form" method="post">
                <?php echo csrf_field(); ?>
                <div class="modal-header">
                    <h4 class="modal-title">Change Category</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        {{ Form::hidden("old_cat_id",null,["id" => "old-cat-id"]) }}
                        {{ Form::hidden("cat_name",null,["id" => "cat-name"]) }}
                        <?php echo $allCategoriesDropdown; ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button class="btn btn-secondary btn-category-update">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

@section('scripts')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/css/select2.min.css" rel="stylesheet"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/js/select2.min.js"></script>
    <script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <script>
        $("select").select2({
            tags: true
        });

        $(document).on("click",".call-used-product",function() {
            var $this = $(this);
            $.ajax({
                type: 'GET',
                url: '/category/references/used-products',
                beforeSend: function () {
                    $("#loading-image").show();
                },
                data: {
                    _token: "{{ csrf_token() }}",
                    q : $this.data("name")
                },
                dataType: "json"
            }).done(function (response) {
                $("#loading-image").hide();
                if (response.code == 200) {
                    if(response.html != "") {
                        $(".show-listing-exe-records").find('.modal-dialog').html(response.html);
                        $(".show-listing-exe-records").modal('show');
                    }else{
                        toastr['error']('Sorry no product founds', 'error');
                    }
                }
            }).fail(function (response) {
                $("#loading-image").hide();
                console.log("Sorry, something went wrong");
            });
        });

        $(".sortable-tables").find(".category-mov-btn").find("span").draggable({
            //containment : ".category-mov-btn",
            appendto : false
        });

        $(".sortable-tables").find(".category-mov-btn").droppable({
          drop: function( event, ui ) {
            //Get dragged Element (checked)
              draggedElement = $(ui.draggable);

              //Get dropZone where element is dropped (checked)
              dropZone = $(event.target);
              console.log(dropZone);

              //Move element from list, to dropZone (Change Parent, Checked)
              $(dropZone).append(draggedElement);

              //Get current position of draggable (relative to document)
              var offset = $(ui.helper).offset();
              xPos = offset.left;
              yPos = offset.top;

              //Move back element to dropped position
              $(draggedElement).css('top', 2).css('left', 2);

              var catId = [];
              window.catId = [];

              var iterate = $(".category-mov-btn").each(function(k,v){
                 var $this = $(v);
                 var categoryId = $this.data("cat-id");
                 var allTypes = [];
                    $.each($this.find("span"),function(k,v){
                        var $span = $(v);
                        allTypes.push($span.text());
                    });
                    var keyName = "cat_"+categoryId;
                    window.catId.push("cat_"+categoryId + "#" + allTypes.join(","));
              });

              $.when(iterate).then(  function() {
                $.ajax({
                        type: "POST",
                        headers: {
                            'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                        },
                        url: '/category/references',
                        data: {info : window.catId},
                    }).done(response => {
                        toastr['success']('Category Updated successfully', 'success');
                    });
              } );
          }
        });

        $(document).on("change",".status_after_autocrop",function() {
            $.ajax({
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                },
                url: '/category/update-field',
                data: {_f : "status_after_autocrop" , _v : $(this).val(), id: $(this).closest(".category-next-btn").data("cat-id")},
            }).done(response => {
                toastr['success']('Category Updated successfully', 'success');
            });
        });

        $(document).on("click",".category-mov-btn span",function(event){
            $(".btn-category-update").html("Update");
            var $target = $(event.target);
            if($target[0].tagName != "I") {
                var catModal = $("#categoryUpdate");
                    catModal.find("#cat-name").val($(this).text());
                    catModal.find("#old-cat-id").val($(this).closest(".category-mov-btn").data("cat-id"));
                    $("#categoryUpdate").modal("show");
            }
        });

        $(document).on("change",".new-category-update",function(e) {
            e.preventDefault();
            var $this = $(this);
            $.ajax({
                type: 'POST',
                url: '/category/references/affected-product',
                beforeSend: function () {
                    $("#loading-image").show();
                },
                data: $this.closest('form').serialize(),
                dataType: "json"
            }).done(function (response) {
                $("#loading-image").hide();
                if (response.code == 200) {
                    $(".btn-category-update").html("Update ("+response.total+")");
                }
            }).fail(function (response) {
                $("#loading-image").hide();
                toastr['error']('Sorry, something went wrong', 'error');
            });
        });

        $(document).on("click",".btn-category-update",function(e) {
            e.preventDefault();
            var $this = $(this);
            $.ajax({
                type: 'POST',
                url: '/category/references/update-category',
                beforeSend: function () {
                    $("#loading-image").show();
                },
                data: $this.closest('form').serialize(),
                dataType: "json"
            }).done(function (response) {
                $("#loading-image").hide();
                if (response.code == 200) {
                    if(response.html != "") {
                        toastr['success'](response.message, 'success');
                    }else{
                        toastr['error']('Sorry, something went wrong', 'error');
                    }
                    $(".show-listing-exe-records").modal('hide');
                }
            }).fail(function (response) {
                $("#loading-image").hide();
                toastr['error']('Sorry, something went wrong', 'error');
                $(".show-listing-exe-records").modal('hide');
            });
        });

        /*$(document).on("click",".btn-category-update",function(e) {
            e.preventDefault();
            var form = $(this).closest("form");
            $.ajax({
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function() {
                    $("#loading-image").show();
                },
                url: '/category/reference',
                data: form.serialize(),
            }).done(response => {
                toastr['success']('Category Updated successfully', 'success');
                location.reload();
            });
        });*/

        $(document).on("click",".show-history-btn",function(e) {
            e.preventDefault();
            var $this = $(this);
            $.ajax({
                type: 'GET',
                url: '/category/'+$this.data("id")+'/history',
                beforeSend: function () {
                    $("#loading-image").show();
                }
            }).done(function (response) {
                $("#loading-image").hide();
                $(".show-listing-exe-records").find('.modal-dialog').html(response);
                $(".show-listing-exe-records").modal('show');
            }).fail(function (response) {
                $("#loading-image").hide();
                toastr['error']('Sorry no record found', 'error');
            });
        }); 

    </script>
@endsection