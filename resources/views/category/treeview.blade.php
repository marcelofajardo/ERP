    @extends('layouts.app')

    @section('content')
        <style>
            .btn-secondary{
                color: #757575;
                border: 1px solid #ddd;
                background-color: #fff;
            }
        </style>
        {{-- <button id="show-sub1">click</button> --}}

        {{-- <div class="container"> --}}
{{--            <div class="row my-4">--}}
{{--                <div class="col-lg-12 margin-tb">--}}
{{--                    <div class="">--}}
{{--                        <h2 class="page-heading text-center"> Category </h2>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
        <h2 class="page-heading flex" style="padding: 8px 5px 8px 10px;border-bottom: 1px solid #ddd;line-height: 32px;">Category
            <div class="margin-tb" style="flex-grow: 1;">
                <div class="pull-right ">


                    <div class="d-flex justify-content-between  mx-3">

                        <a href="{{ route('category.map-category') }}" class="btn btn-secondary my-0 mr-3">Edit References</a>
                        <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#category-popup">
                            Add category
                        </button>
                    </div>
                </div>
            </div>
        </h2>
        <form action="{{ route('category',['filter'=>true]) }}" class="d-block filter_category_form mx-4" id="filter_category_form">
           <div class="form-group mb-3">
               <input style="border: 1px solid #ddd;height:30px;border-radius: 4px; padding: 0 5px;" type="text" placeholder="Enter name" name="filter" id="filter_all_category" value="{{ $selected_value }}">
               <button class="btn"><img src="/images/filter.png" style="width:16px"></button>
           </div>
        </form>




        {{-- <!-- Add category modal --> --}}
        <div class="modal fade" id="category-popup" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4>Add New Category</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">


                        {{-- {!! Form::open(['route' => 'a'dd.category'']) !!} --}}
                    <form  method="POST" action="{{ route('add.category') }}">
                        @csrf
                        <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
                            {{-- {!! Form::label('Title:') !!}
                            {!! Form::text('title', old('title'), ['class' => 'form-control', 'placeholder' => 'Enter Title']) !!}
                            <span class="text-danger">{{ $errors->first('title') }}</span> --}}
                            <label for="title_category">New Title</label>
                            <input type="text" class="form-control" id="title_category" name="title" placeholder="Enter title" required >


                        </div>

                        <div class="form-group {{ $errors->has('magento_id') ? 'has-error' : '' }}">
                            {{-- {!! Form::label('Magento Id:') !!}
                            {!! Form::text('magento_id', old('magento_id'), ['class' => 'form-control', 'placeholder' => 'Enter Magento Id']) !!}
                            <span class="text-danger">{{ $errors->first('magento_id') }}</span> --}}

                            <label for="category_magento_id">Magento Id:</label>
                            <input type="text" class="form-control" id="category_magento_id" name="magento_id"
                                placeholder="Enter Magento Id" required>
                        </div>


                        <div class="form-group {{ $errors->has('show_all_id') ? 'has-error' : '' }}">
                            {{-- {!! Form::label('Show all Id:') !!}
                            {!! Form::text('show_all_id', old('show_all_id'), ['class' => 'form-control', 'placeholder' => 'Enter Show All Id']) !!}
                            <span class="text-danger">{{ $errors->first('show_all_id') }}</span> --}}

                            <label for="category_show_all_id">Show All Id:</label>
                            <input type="text" class="form-control" id="category_show_all_id" name="show_all_id"
                                placeholder="Enter Show All Id" required>

                        </div>

                        <div class="form-group">
                            <label for="cat_category_segment_id">Select Category Segment:</label>

                            {{-- {!! Form::label('Category Segment:') !!}
                            {!! Form::select('category_segment_id', $category_segments, old('category_segment_id'), ['class' => 'form-control', 'placeholder' => 'Select Category Segment']) !!} --}}
                            <select class="form-control" name="category_segment_id" id="category_segment_id">
                                <option>Select Category Segment</option>
                                @foreach ($category_segments as $k=> $catSeg)
                                
                                <option value="{{ $k }}">{{ $catSeg }}</option>
                                @endforeach
                            </select>

                        </div>

                        <div class="form-group {{ $errors->has('parent_id') ? 'has-error' : '' }}">
                            {!! Form::label('Category:') !!}
                            {{-- {!! Form::select('parent_id',$allCategories, old('parent_id'), ['class'=>'form-control', 'placeholder'=>'Select Category']) !!} --}}
                            <?php echo $allCategoriesDropdown; ?>
                            <span class="text-danger">{{ $errors->first('parent_id') }}</span>
                        </div>
                        <div class="d-flex justify-content-between form-group {{ $errors->has('parent_id') ? 'has-error' : '' }}">
                            <div>
                                <input type="checkbox" id="need_to_check_measurement" name="need_to_check_measurement" value="1">
                                <label for="need_to_check_measurement"> Need to Check Measurement</label>
                            </div>
                            <div>
                                <input type="checkbox" id="need_to_check_size" name="need_to_check_size" value="1">
                                <label for="need_to_check_size"> Need to Check Size</label>
                            </div>
                        </div>
                        

                        <div class="form-group d-flex justify-content-between">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button class="btn btn-secondary">Create</button>
                        </div>
                    </form>
                    </div>
                 
                </div>
            </div>
        </div>

        {{-- Edit modal --}}
        <div class="modal fade" id="editCategoryModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h4 class="modal-title" id="exampleModalLongTitle">Edit category</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                    <form class="edit-category-form" id="edit-category-form" name="edit-category-form" method="POST" action="" >
                        @csrf
                        <div class="form-group">
                            <label for="edit_title_category">New Title</label>
                            <input type="text" class="form-control" id="edit_title_category" name="title" placeholder="Enter title"  required>
                        </div>

                        <div class="form-group">
                            <label for="edit_category_magento_id">Magento Id:</label>
                            <input type="number" class="form-control" id="edit_category_magento_id" name="magento_id"
                            placeholder="Enter Magento Id" required>
                        </div>

                        <div class="form-group">
                            <label for="edit_category_show_all_id">Show All Id:</label>
                            <input type="text" class="form-control" id="edit_category_show_all_id" name="show_all_id"
                                placeholder="Enter Show All Id">
                        </div>

                        <div class="form-group">
                            <label for="cat_category_segment_id">Select Category Segment</label>
                            {{-- <input type="text" class="form-control" id="cat_category_segment_id" name="category_segment_id"
                                placeholder="Enter Show All Id"> --}}
                     {{-- {{ dd($category_segments) }}    --}}
                            <select class="form-control" class="form-control" name="category_segment_id" id="edit_category_segment_id">
                                <option>Select Category Segment</option>
                                @foreach ($category_segments as $k=> $catSeg)
                                
                                <option value="{{ $k }}">{{ $catSeg }}</option>
                                @endforeach
                            </select>
                        
                        </div>

                        <div class="form-group {{ $errors->has('parent_id') ? 'has-error' : '' }}">
                            {!! Form::label('Category:') !!}
                            {{-- {!! Form::select('parent_id',$allCategories, old('parent_id'), ['class'=>'form-control', 'placeholder'=>'Select Category']) !!} --}}
                            <?php echo $allCategoriesDropdown; ?>
                            <span class="text-danger">{{ $errors->first('parent_id') }}</span>
                        </div>
                        <div class="d-flex justify-content-between form-group {{ $errors->has('parent_id') ? 'has-error' : '' }}">
                            <div>
                                <input type="checkbox" id="edit_need_to_check_measurement" name="need_to_check_measurement" >
                                <label for="edit_need_to_check_measurement"> Need to Check Measurement</label>
                            </div>
                            <div>
                                <input type="checkbox" id="edit_need_to_check_size" name="need_to_check_size" >
                                <label for="edit_need_to_check_size"> Need to Check Size</label>
                            </div>
                        </div>  
                        <div class="form-group d-flex justify-content-between">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-secondary edit-category-submit-btn" >Edit</button>
                        </div>

                    </form>
                </div>
               
              </div>
            </div>
          </div>
          <div class="col-md-12 margin-tb">
              @if ($message = Session::get('error-remove'))
              <div class="alert alert-danger alert-block py-1">
                  <button type="button" class="close" data-dismiss="alert">×</button>
                  <strong>{{ $message }}</strong>
              </div>
          @endif
          @if ($message = Session::get('success-remove'))
              <div class="alert alert-success alert-block py-1">
                  <button type="button" class="close" data-dismiss="alert">×</button>
                  <strong>{{ $message }}</strong>
              </div>
          @endif
            <div class="table-responsive">
                <table class="table table-bordered" style="table-layout:fixed;">
                <thead>
                <tr>
                    <th  style="width: 10%">Id</th>
                    <th style="width: 50%" >Name</th>
                    <!-- <th style="width: 10%" width="40%">Logo</th> -->
                    <th style="width: 20%" >Created At</th>
                    <th style="width: 20%" >Action</th>
                </tr>
                </thead>

                @foreach ($categories as $key => $cat)
                    <tr class="parent-cat">
                        <td class="index">{{ $key + 1 }}</td>
                        <td class="brand_name" data-id="{{ $cat->title }}">{{ $cat->title }}
                            ({{ count($cat->childs) }})</td>
                        <td class="created_at">{{ $cat->created_at }}</td>

                        <td>
                            <button type="button" class="btn btn-xs no-pd show-sub-category" data-id="{{ $cat->id }}"
                                data-name="{{ $cat->id }}">
                                <img src="/images/forward.png" style="cursor: pointer;" width="16px">
                            </button>
                            <button type="submit" class="btn btn-xs category_edit_popup" data-id="{{ $cat->id }}">
                                <img src="/images/edit.png" style="cursor: pointer; width: 16px;">
                            </button>
                            <form style="display: inline-block" action="{{ route('category.remove') }}" method="POST" class="category_deleted">
                                @csrf
                                <input type="text" name="edit_cat" value={{ $cat->id }} hidden>
                                <button type="submit" class="btn btn-xs" data-id="{{ $cat->id }}">
                                <img src="/images/delete.png" style="cursor: pointer; width: 16px;">
                                    </button>
                            </form>

                        </td>   
                    </tr>
                    <tr class="add-childs">
                    </tr>
                    {{-- <tr class="expand-{{$cat->brands_id}} hidden">
                    {{-- <tr class="expand-{{$cat->brands_id}} hidden">
                    
                    <td colspan="4" id="attach-image-list-{{$cat->brands_id}}" >
                        
                    </td>
                </tr> --}}
                @endforeach
            </table>
            </div>
        </div>

        <script type="text/javascript">

            $(document).on('click', '.show-sub-category', function(e) {
                var subCat = $(this).data('name');
                $this = $(this)

                $this.hasClass('show_child') ? $this.removeClass('show_child') : $this.addClass('show_child')

                if ($this.hasClass('show_child')) {

                    $.ajax({
                        url: "{{ route('category.child-category') }}",
                        method: 'GET',
                        dataType: "json",
                        data: {
                            _token: "{{ csrf_token() }}",
                            'subCat': subCat,
                        },
                        success: function(response) {
                            console.log(response, 'aaaaaaaaaaaaaa')

                            if (response.length) {


                                let html =
                                    '<td colspan="4" class="px-3" style="color: #757575;"><h5 style="color: #000;" class="pl-2 mt-0">Child category</h5><table style="width:100%; ">';

                                response.forEach((element, key) => {
                                    html += `
        
                                        <tr class="parent-cat" colspan="4">
                                    
                                            <td class="index" style="width: 10%"> ${key+1} </td>
                                            <td class="brand_name  style="width: 50%" data-id="${element.id}">${element.title} (${element.child_level_sencond.length})</td>
                                            <td class="created_at" style="width: 20%">${element.created_at}</td>
                                        
                                            <td  style="width: 20%">
                                                <button type="button" class="btn btn-xs no-pd show-sub-category"
                                                            data-id="${ element.id }" data-name="${ element.id }">
                                                            <img src="/images/forward.png" style="cursor: pointer" width="16px">
                                                </button>
                                                <button type="button" class="btn btn-xs category_edit_popup" data-id="${element.id}"
                                                >
                                                    <img src="/images/edit.png" style="cursor: pointer; width: 16px;">
                                                </button>
                                                <form style="display: inline-block" action="{{ route('category.remove') }}" method="POST" class="category_deleted">
                                                    @csrf
                                                    <input type="text" name="edit_cat" value="${element.id}" hidden>
                                                    <button type="submit" class="btn btn-xs" data-id="${element.id}">
                                                    <img src="/images/delete.png" style="cursor: pointer; width: 16px;">
                                                        </button>
                                                </form>
                                            </td>
                                        </tr>
                                        <tr class="add-childs"> 
                                                    
                                                </tr>   
`
                                });
                                html += '</table></td>'
                                $this.closest('.parent-cat').next('.add-childs:first').html(html)
                            } else {
                                let html =
                                    ' <td colspan="4"> <h5 class="text-center mt-0"> No child category available for this<h5> </td>'
                                $this.closest('.parent-cat').next('.add-childs:first').html(html)

                            }

                        },
                        error: function(response) {
                        }
                    });
                } else {
                    $this.closest('.parent-cat').next('.add-childs:first').empty()
                }

            });


            let edited_data_id = null
            $(document).on('click', '.category_edit_popup', function(e) {
                e.preventDefault()
                const dataId = $(this).data('id')

                $.ajax({
                    url: "{{ route('category.child-edit-category') }}",
                    method: 'GET',
                    dataType: "json",
                    data: {
                        _token: "{{ csrf_token() }}",
                        'dataId': dataId,
                    },
                    success: function(response) {
                    console.log(response,'respose')
                    edited_data_id = response.id
                    $('#edit_title_category').val(response.title)
                    $('#edit_category_magento_id').val(response.magento_id)
                    $('#edit_category_show_all_id').val(response.show_all_id);
                    $('#edit_category_segment_id').val(response.category_segment_id?.id ?? null);
                    $('#edit_need_to_check_measurement').attr('checked', response.need_to_check_measurement ? true :false);
                    $('#edit_need_to_check_size').attr('checked', response.need_to_check_size? true: false);
                    $('#editCategoryModal').modal('show')
                    },
                    error: function(response) {
                    }
                });

            })

     

            $(document).on('submit', '#edit-category-form', function(e) {

                e.preventDefault()
                const dataId = edited_data_id ?? null
                console.log(dataId)
                const form_data=  $('#edit-category-form').serialize();
                console.log(dataId,'dataid')
               
                $.ajax({
                    url: '/category/'+ dataId + '/edit-category',
                    method: 'POST',
                    dataType: "json",
                    data:$('#edit-category-form').serialize() ,
                    success: function(response) {
                        // location.reload()
                        toastr["success"]('Category update successfully');
                        $('#editCategoryModal').modal('hide')

                    },
                    error: function(response) {
                        toastr["error"]("Oops,something went wrong");

                    }
                });

            })

            $(document).on('submit', '.category_deleted', function(e) {
                e.preventDefault()
             
                const form_data=  $(this).serialize();
                // console.log(form_data,'dataid')
               
                $.ajax({
                    url: 'category/remove',
                    method: 'POST',
                    dataType: "json",
                    data:$(this).serialize() ,
                    success: function(response) {
                        console.log(response)
                        
                        // location.reload()
                        if(response['error-remove']){
                            toastr["error"](response['error-remove']);

                        }
                        if(response['success-remove'])
                        toastr["success"](response['success-remove']);
                        // $('#editCategoryModal').modal('hide')
                    },
                    error: function(response) {
                            toastr["error"]("Oops,something went wrong");
                    }
                });

            })

            // $(document).on('submit','#filter_category_form',function(e){
            //     e.preventDefault()
            //     console.log('its wokting')
            // })

        
        </script>
    @endsection
