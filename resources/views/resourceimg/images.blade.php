@extends('layouts.app')
@section('content')
<style type="text/css">
	.myImg{border-radius:5px;cursor:pointer;transition:.3s}.myImg:hover{opacity:.7}.modal{display:none;position:fixed;z-index:1;padding-top:100px;left:0;top:0;width:100%;height:100%;overflow:auto;background-color:#000;background-color:rgba(0,0,0,.9)}.modal-content{margin:auto;display:block;width:100%;max-width:720px}#caption{margin:auto;display:block;width:80%;max-width:700px;text-align:center;color:#ccc;padding:10px 0;height:150px}#caption,.modal-content{-webkit-animation-name:zoom;-webkit-animation-duration:.6s;animation-name:zoom;animation-duration:.6s}@-webkit-keyframes zoom{from{-webkit-transform:scale(0)}to{-webkit-transform:scale(1)}}@keyframes zoom{from{transform:scale(0)}to{transform:scale(1)}}.close{position:absolute;top:15px;right:35px;color:#f1f1f1;font-size:40px;font-weight:700;transition:.3s}.close:focus,.close:hover{color:#bbb;text-decoration:none;cursor:pointer}@media only screen and (max-width:700px){.modal-content{width:100%}}.del_btn{position:absolute!important;background:rgba(255,255,255,.5)!important;bottom:0!important;border-radius:0!important;right:15px!important;padding:10px 12px 8px 12px}.myh4{text-align:center;background:rgba(0,0,0,.4);padding:10px 0;margin:0;text-transform:uppercase;color:#f5f5f5;font-weight:600}
</style>
	<div class="container">
		<div class="row">
		  <div class="col-md-12">
		    <div class="panel panel-default">
		      <div class="panel-heading">
		      	Resources Center Images
		      </div>
		      <div class="panel-body">
		        <h4>
		        	<b>Category ::</b> {{@$title}}
		        	
		        	@if(isset($allresources->sub_category->title))
		        	<br>
		        	<br>
		        	<b>Sub Category :: </b>  {{ $allresources->sub_category->title }} 
		        	@endif
		        	{!! Form::open(['route'=>'delete.resource']) !!}
        			    <input type="hidden" name="id" value="{{$allresources['id']}}">
        			    <button type="submit" name="button_type" value="delete" class="pull-right btn btn-image"><img src="/images/delete.png" /></button>
        			    @if($allresources['is_pending'] == 0)
        			    	 <a href="{{ action('ResourceImgController@index')}}" class="pull-right btn btn-image"><i class="fa fa-reply"></i>
        			    @else
        			     	<a href="{{ url('resourceimg/pending/1')}}" class="pull-right btn btn-image"><i class="fa fa-reply"></i>
        			    @endif
        			    Back</a>
        			{!! Form::close() !!}
		        </h4>
		        <p><b>Resource Url ::</b> <a href="{{@$url}}">{{@$url}}</a></p>
		        <p><b>Description ::</b> {{@$description}}</p>
		        <hr>
		        <div class="row">
		        	@isset($allresources['image1'])
    				<div class="col-md-6">
		        		<img onclick="OpenModel(this.id)" 
		        			 id="myImg1" class="myImg" src="{{URL::to('/category_images/'.$allresources['image1'])}}" 
		        						alt="{{URL::to('/category_images/'.$allresources['image1'])}}" 
		        						style="width: 100% !important;height: 250px !important;">
		        	</div>
		        	@endisset
		        	@isset($allresources['image2'])
		        	<div class="col-md-6">
		        		<img onclick="OpenModel(this.id)" 
		        			 id="myImg2" class="myImg" src="{{URL::to('/category_images/'.$allresources['image2'])}}" 
		        						alt="{{URL::to('/category_images/'.$allresources['image2'])}}" 
		        						style="width: 100% !important;height: 250px !important;">
		        	</div>
		        	@endisset
		        	@isset($allresources['images'])
		        	@if($allresources['images'] != null)
		        	@foreach(json_decode($allresources['images']) as $image)
		        	<div class="col-md-6" style="margin-top: 15px">
		        		<img onclick="OpenModel(this.id)" 
		        			 id="myImg2" class="myImg" src="{{URL::to('/category_images/'.$image)}}" 
		        						alt="{{URL::to('/category_images/'.$image)}}" 
		        						style="width: 100% !important;height: 250px !important;">
		        	</div>

		        	@endforeach
		        	@endif
		        	@endisset
		        </div>
		      </div>
		    </div>
		  </div>
		</div>
	</div>
@endsection
<div id="myModal" class="modal" onclick="CloseModel()">
  <span onclick="CloseModel()" class="close">&times;</span>
  <img class="modal-content" id="img01">
  <div id="caption"></div>
</div>
<script type="text/javascript">
	var modal=document.getElementById("myModal"),img=document.getElementsByClassName("myImg"),modalImg=document.getElementById("img01"),captionText=document.getElementById("caption");function OpenModel(e){console.log(e),modal.style.display="block",modalImg.src=$("#"+e).attr("src"),
	captionText.innerHTML="Source :: <a target='_blank' href='"+$("#"+e).attr("alt")+"'>"+$("#"+e).attr("alt")+"</a>"}
	var span=document.getElementsByClassName("close")[0];function CloseModel(){modal.style.display="none"}document.onkeydown=function(e){("key"in(e=e||window.event)?"Escape"===e.key||"Esc"===e.key:27===e.keyCode)&&(modal.style.display="none")};
</script>