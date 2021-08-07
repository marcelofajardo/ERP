@extends('layouts.app')
@section('favicon' , 'task.png')

@section('title', 'All Contents | Content Management')

@section('content')
<style type="text/css">
	.preview-category input.form-control {
	  width: auto;
	}
	.no-mr {
		margin:0px;
	}
	.pd-3 {
		padding:0px;
	}
    .form-group {
        width:100% !important;
    }
    .select2-container {
        width:100% !important;
    }
</style>

<div class="row">
	<div class="col-lg-12 margin-tb">
        <h2 class="page-heading">All Contents | Content Management <span class="count-text"></span></h2>
    </div>
    <br>
	@include("partials.flash_messages")
    <div class="col-lg-12 margin-tb">
    	<div class="row">
		    <div class="col col-md-12">
		    	<div class="h" style="margin-bottom:10px;">
		    		<form action="{{route('content-management.contents')}}" class="form-inline message-search-handler" method="GET">
					  <div class="row" style="width:100%;">
				  		<!-- <div class="col-md-2">
				  			<div class="form-group">
							    <?php echo Form::text("keyword",request("keyword"),["class"=> "form-control","placeholder" => "Enter keyword"]) ?>
						  	</div>
						 </div> -->
                      
                         <div class="col-md-2">
                              <div class="form-group">
                            <select id="search_by_publisher" class="form-control input-sm select2" name="publisher_id">
                                <option value="">Select a Publisher</option>
                                @foreach ($users as $id => $user)
                                    <option value="{{ $id }}" {{ $id == $selected_publisher ? 'selected' : '' }}>{{ $user }}</option>
                                @endforeach
                            </select>
                            </div>		
				  		</div>
                          <div class="col-md-2">
                              <div class="form-group">
                            <select id="search_by_creator" class="form-control input-sm select2" name="creator_id">
                                <option value="">Select a Creator</option>
                                @foreach ($users as $id => $user)
                                    <option value="{{ $id }}" {{ $id == $selected_creator ? 'selected' : '' }}>{{ $user }}</option>
                                @endforeach
                            </select>
                            </div>		
				  		</div>
                          <div class="col-md-2">
                              <div class="form-group">
                            <select id="store_website_id" class="form-control input-sm select2" name="store_website_id">
                                <option value="">Select a Store</option>
                                @foreach ($store_websites as $id => $website)
                                    <option value="{{ $id }}" {{ $id == $selected_website ? 'selected' : '' }}>{{ $website }}</option>
                                @endforeach
                            </select>
                            </div>		
				  		</div>
                          <div class="col-md-2">
                              <div class="form-group">
                            <select id="store_social_content_category_id" class="form-control input-sm select2" name="store_social_content_category_id">
                                <option value="">Select a Category</option>
                                @foreach ($categories as $id => $category)
                                    <option value="{{ $id }}" {{ $id == $selected_category ? 'selected' : '' }}>{{ $category }}</option>
                                @endforeach
                            </select>
                            </div>		
				  		</div>
                          <div class="col-md-4">
                         <div class="form-group">
						  		<button style="display: inline-block;width: 10%" class="btn btn-sm btn-image btn-search-action">
						  			<img src="/images/search.png" style="cursor: default;">
						  		</button>
                                  <button style="display: inline-block;float:right" class="btn btn-sm btn-secondary ">
						  			<a href="/content-management" style="color:white;">Back</a>
						  		</button>
						  	</div>
                         </div>
					  </div>	
					</form>	
		    	</div>
		    </div>
	    </div>	
		<div>
        <table class="table table-bordered table-striped" style="table-layout:fixed;">
            <tr>
                <th style="width:5%;">Sl no</th>
                <th style="width:20%;">Site name</th>
                <th style="width:20%;">Category</th>
                <th style="width:20%;">Creator</th>
                <th style="width:20%;">Publisher</th>
                <th style="width:15%;">Content</th>
            </tr>
            @foreach($records as $key => $record)
            <tr>
                <td>{{++$key}} </td>
                <td>{{$record['siteName']}} </td>
                <td>{{$record['category']}} </td>
                <td>
				{{$record['creator']}}
				</td>
                <td>
				{{$record['publisher']}}
				</td>
                <td>
                @if($record['extension'] == 'png' || $record['extension'] == 'jpeg' || $record['extension'] == 'jpg' || $record['extension'] == 'gif')
                <img style="width:100px;height:auto;" src="{{$record['url']}}" alt="">
                @else 
                <button class="btn btn-secondary btn-xs"><a href="{{$record['url']}}" target="_blank" class="d-inline-block" style="color:white;">
                          View Doc
                      </a></button>
                @endif
                </td>
            </tr>
            @endforeach
        
            </table>
    </div>
	</div>
</div>
<div id="loading-image" style="position: fixed;left: 0px;top: 0px;width: 100%;height: 100%;z-index: 9999;background: url('/images/pre-loader.gif') 
          50% 50% no-repeat;display:none;">
</div>

<script type="text/javascript">
	       $('#search_by_publisher').select2({
                width: "100%"
        });

        $('#search_by_creator').select2({
                width: "100%"
        });
        $('#store_website_id').select2({
                width: "100%"
        });
        $('#store_social_content_category_id').select2({
                width: "100%"
        });
        
</script>

@endsection

