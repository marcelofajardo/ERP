@extends('layouts.app')
@section('content')
<style type="text/css">
	.imagePreview {
	    width: 100%;
	    height: 180px;
	    background-position: center center;
	  background:url(http://cliquecities.com/assets/no-image-e3699ae23f866f6cbdf8ba2443ee5c4e.jpg);
	  background-color:#fff;
	    background-size: cover;
	  background-repeat:no-repeat;
	    display: inline-block;
	  box-shadow:0px -3px 6px 2px rgba(0,0,0,0.2);
	}
	.btn-primary
	{
	  display:block;
	  border-radius:0px;
	  box-shadow:0px 4px 6px 2px rgba(0,0,0,0.2);
	  margin-top:-5px;
	}
	.imgUp
	{
	  margin-bottom:15px;
	}
	.del
	{
	  position:absolute;
	  top:0px;
	  right:15px;
	  width:30px;
	  height:30px;
	  text-align:center;
	  line-height:30px;
	  background-color:rgba(255,255,255,0.6);
	  cursor:pointer;
	}
	.imgAdd
	{
	  width:30px;
	  height:30px;
	  border-radius:50%;
	  background-color:#4bd7ef;
	  color:#fff;
	  box-shadow:0px 0px 2px 1px rgba(0,0,0,0.2);
	  text-align:center;
	  line-height:30px;
	  margin-top:0px;
	  cursor:pointer;
	  font-size:15px;
	}
	.error {
		color: #FF0000;
	}

    #loading-image {
        position: fixed;
        top: 50%;
        left: 50%;
        margin: -50px 0px 0px -50px;
        z-index: 60;
    }
</style>
<div id="myDiv">
        <img id="loading-image" src="/images/pre-loader.gif" style="display:none;"/>
    </div>
<div class="row" id="product-template-page">
	<div class="col-lg-12 margin-tb">
        <h2 class="page-heading">Templates</h2>
       <!--  <div class="pull-right">
            <button type="button" class="btn btn-secondary create-product-template-btn">+ Add Template</button>
        </div> -->
        <div class="pull-right">
            <a type="button" class="btn btn-secondary" href="{{route('fetch.bearbanner.templates')}}" style="margin-right: 7px !important;">Refresh Templates</a>
        </div>
        <div class="pull-right">
            <button type="button" class="btn btn-secondary" onclick="generateProducts()" style="margin-right: 7px !important;">Generate Product</button>
        </div>
    </div>
    <br>
    <div class="col-lg-12 margin-tb">
		<div class="col-md-12" id="page-view-result">

		</div>
	</div>
</div>
<div id="display-area"></div>
 @include("template.partials.list-template") 




@include("template.partials.create-form-template")
@include("template.partials.edit-form-template")
@include("partials.modals.large-image-modal")
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jsrender/1.0.5/jsrender.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript" src="js/common-helper.js"></script>
<script type="text/javascript" src="js/template.js"></script>


<script type="text/javascript">
	template.init({
		bodyView : $("#product-template-page"),
		baseUrl : "<?php echo url("/"); ?>"
	});

	

	function bigImg(img){

        $('#image_crop').attr('src',img);
        $('#largeImageModal').modal('show');
    }

    function normalImg(){
        $('#largeImageModal').modal('hide');
    }
	
	function editTemplate(id,name,image,numberImage,checkbox,uid){
		$('#id').val(id);
    	$('#name').val(name);
    	var templateRow={};
    	$('.special').html('');
    	$.each(templatesData.data,(function(k,v)
    	{
    		if(v.uid==uid)
    		{
    			templateRow=v;
    		}
    	})
    	)

    	if(templateRow.modifications.length)
    	{
    		$.each(templateRow.modifications,function(k,v)
    		{ 
    			


                  

                    //console.log(v);

    			if(v.tag !=='image_url')
    			{
    				$('.special').append('<div class="form-group row"> <label for="'+v.tag+'" class="col-sm-3 col-form-label">'+v.tag+'</label><div class="col-sm-6"> <input type="text" name="modifications_array['+v.row_index+']['+v.tag+']" class="form-control" id="'+v.tag+v.row_index+'" value="'+v.value+'"></div></div>');
    			}

    			else
    			{
                    $('.special').append('<div class="form-group row"><label for="'+v.tag+'" class="col-sm-3 col-form-label">'+v.tag+'</label><div class="col-sm-3 imgUp"><div class="imagePreview imageBearBanner" id="" style="background-image:url("'+v.value+ '")"></div> <label class="btn btn-primary"> Upload<input type="file" name="files['+v.row_index+']['+v.tag+']" class="uploadFile img" value="Upload Photo" style="width: 0px;height: 0px;overflow: hidden;" id="uploadFile"> </label></div><input type="hidden" name="modifications_array['+v.row_index+'][image_url]" value=""</div>');
    			}

            

    			
    		$('.special').append('<hr>');


    		})
    	}

    	$('#imagePreview').css('background-image', 'url("' + image + '")');
		if(checkbox == 1){
    		$('#auto').prop("checked", true);
    	}else{
    		$('#auto').prop("checked", false);
    	}
    	$('#number').val(numberImage);
		$('#product-template-edit-modal').modal('show');
    }

    function generateProducts(){
    	$.ajax({
                url: '/templates/generate-template-category-branch',
                dataType: "json",
                type: "GET",
                beforeSend: function () {
                    $("#loading-image").show();
                },
            }).done(function (data) {
            	$("#loading-image").hide();
                alert('Product Template Created');
            }).fail(function (jqXHR, ajaxOptions, thrownError) {
                alert('Please Check');
            });
    }

    var templatesData=JSON.parse('{!!$templates->toJson()!!}');

    console.log(templatesData);
</script>

@endsection