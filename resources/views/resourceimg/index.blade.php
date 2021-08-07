@extends('layouts.app')

@section("styles")
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
@endsection
@section('content')
<link href="{{ asset('css/treeview.css') }}" rel="stylesheet">
	<div class="container">
		<div class="row">
        <div class="col-lg-12 margin-tb">
            <h2 class="page-heading">List Resources Center</h2>
            <div class="pull-left">
                    <br>
               <!--  <form action="{{ route('document.index') }}" method="GET">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-4">
                                <input name="term" type="text" class="form-control"
                                       value="{{ isset($term) ? $term : '' }}"
                                       placeholder="user,department,filename">
                            </div>
                            <div class="col-md-3">
                                <select class="form-control select-multiple2" name="category[]" data-placeholder="Select Category.." multiple>
                                    <option>Select Category</option>
                                    
                                </select>
                            </div>

                            <div class="col-md-3">
                                <div class='input-group date' id='filter-date'>
                                    <input type='text' class="form-control" name="date" value="{{ isset($date) ? $date : '' }}" placeholder="Date" />

                                    <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                  </span>
                                </div>
                            </div>

                            <div class="col-md-1">
                            <button type="submit" class="btn btn-image"><img src="/images/filter.png" /></button>
                            </div>

                        </div>
                    </div>
                </form> -->
            </div>
            <div class="pull-right">
                <a href="{{ url('/resourceimg/pending/1') }}"><button type="button" class="btn btn-secondary">Pending</button></a>
                <button type="button" class="btn btn-secondary" title="Add Category" data-toggle="modal" data-target="#addcategory">Add Category</button>
                <button type="button" class="btn btn-secondary" title="Edit Category" data-toggle="modal" data-target="#editcategory">Edit Category</button>
               	<button type="button" class="btn btn-image" title="Add Resource" data-toggle="modal" data-target="#addresource">
		        	   		<i class="fa fa-plus"></i>
		        	   	</button>

            </div>
        </div>
    

		
		 <div class="col-lg-12 margin-tb">
                @if ($message = Session::get('success'))
                    <div class="alert alert-success alert-block">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong>{{ $message }}</strong>
                    </div>
                @endif
                @if ($message = Session::get('danger'))
                    <div class="alert alert-danger alert-block">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong>{{ $message }}</strong>
                    </div>
                @endif
		      
		        <div class="table-responsive col-md-12" style="margin-top : 30px;">
		          <table class="table table-striped table-bordered" style="border: 1px solid #ddd;">
		            <thead>
		              <tr>
  		              	<th style="width: 2%;">#</th>
  		              	<th style="width: 10%;">Category</th>
  		              	<th style="width: 10%;">Sub Category</th>
  		              	<th style="width: 10%;">Url</th>
  		              	<th style="width: 10%;">Images</th>
  		              	<th style="width: 15%;">Created at</th>
  		              	<th style="width: 10%;">Created by</th>
  		              </tr>
		            </thead>
		            <tbody>
		            	 @if(count($allresources) > 0)
				            @foreach($allresources as $key => $resources)
				                <tr>
				                	<td>{{($key+1)}}</td>
					                <td>@if(isset($resources->category->title)) {{ $resources->category->title }} @endif</td>
					                <td>@if(isset($resources->sub_category->title)) {{ $resources->sub_category->title }} @endif</td>
					                <td><a href="{{ $resources['url'] }}" title="View Url" target="_blank">Click Here</a></td>
					                <td><a href="{{ action('ResourceImgController@imagesResource', $resources['id']) }}" title="View Images">View</a></td>
		    		                <td>{{date("l, d/m/Y",strtotime($resources['updated_at']))}}</td>
		    		                <td>{{ucwords($resources['created_by'])}}</td>
		    		            </tr>
				            @endforeach
				         @else
				        	<tr>
				        		<td class="text-center" colspan="8">No Record found.</td>
				        	</tr>
				        @endif
		            </tbody>
		          </table>
		        </div>
		        {{ $allresources->render() }}
		      </div>
		    </div>
		  </div>
		
	

	@include('resourceimg.partials.modal-create-resource-center')
	@include('resourceimg.partials.modal-create-edit-category')

			
@endsection
@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.5/js/bootstrap-select.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
    <script>
       $(function() {
       $('.selectpicker').selectpicker();
        });

        $('#filter-date').datetimepicker({
            format: 'YYYY-MM-DD'
        });

        $(document).ready(function() {
            $('#category_id').multiselect({
                nonSelectedText:'Select Category',
                buttonWidth:'300px',
                includeSelectAllOption: true,
                enableFiltering: true,
                enableCaseInsensitiveFiltering: true,

                onChange:function(option, checked){

                    $('#sub_cat_id').html('');
                    $('#sub_cat_id').multiselect('rebuild');

                    var selected = this.$select.val();
                    if(selected.length > 0)
                    {
                        $.ajax({
                            url:"{{ url('/api/values-as-per-category') }}",
                            method:"POST",
                            data:{selected:selected,'_token':'{{ csrf_token() }}'},
                            success:function(data)
                            {

                                $('#sub_cat_id').html(data);
                                $('#sub_cat_id').multiselect('rebuild');

                            }
                        })
                    }
                }
            });
            $('#sub_cat_id').multiselect({
                nonSelectedText:'Please Sub Category',
                buttonWidth:'300px',
                includeSelectAllOption: true,
                enableFiltering: true,
                enableCaseInsensitiveFiltering: true,
            });
        });

    </script>
	<script type="text/javascript">
		function PasteImage(){var e=document.getElementById("my_canvas").toDataURL();$("#cpy_img").val(e),$("#save_img").fadeIn(200),$(".msg").empty(),$(".msg").css("color","green"),$(".msg").text("Image Loaded Successfully."),$(".can_id").attr("placeholder","Image Loaded Successfully, Paste another to change."),$("#src_img").attr("src",e)}var CLIPBOARD=new CLIPBOARD_CLASS("my_canvas",!0);function CLIPBOARD_CLASS(e,t){var a=this,n=document.getElementById(e),i=document.getElementById(e).getContext("2d");document.addEventListener("paste",function(e){"can_id"==e.target.id&&(console.log(e),a.paste_auto(e))},!1),this.paste_auto=function(e){if(e.clipboardData){var t=e.clipboardData.items;if(!t)return;for(var a=!1,n=0;n<t.length;n++)if($("#cpy_img").val(""),-1!==t[n].type.indexOf("image")){var i=t[n].getAsFile(),c=(window.URL||window.webkitURL).createObjectURL(i);this.paste_createImage(c),a=!0}1==a?(e.preventDefault(),$(".msg").text("Image Loading, Please Wait."),$(".msg").css("color","red"),setTimeout(PasteImage,5e3)):(e.preventDefault(),$(".can_id").attr("placeholder","Please paste only image."))}},this.paste_createImage=function(e){var a=new Image;a.onload=function(){1==t?(n.width=a.width,n.height=a.height):i.clearRect(0,0,n.width,n.height),i.drawImage(a,0,0)},a.src=e}}
	</script>
	<script src="{{asset('js/treeview.js')}}"></script>
@endsection