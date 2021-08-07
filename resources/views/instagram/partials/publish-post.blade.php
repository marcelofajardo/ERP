<div id="add-vendor-info-modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" style="width: 1000px;">
		<div class="modal-content">
			<div id="myDiv">
			   	<img id="loading-image" src="/images/pre-loader.gif" style="display:none;"/>
			</div>

            <div class="modal-header">
                <h2>Publish Post</h2>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
			
            <div class="modal-body">
				<form role="form" method="post" action="{{ route('post.store') }}" name="post-create" autocomplete="off" style="margin-top: 30px;">
					@csrf

					<div class="row">
						<div class="col-md-4 col-lg-4 col-xs-12">
		                    <div class="card media-manager">
		                        <div class="card-header pl-1">
		                            <h3 class="card-title">@lang('Media')</h3>

		                            <a href="{{route('attachImages','instagram-post')}}" class="btn btn-secondary btn-sm mr-3 attachInstagramMedia" title="attach media from all content"><i class="fa fa-paperclip"></i></a>
									<!-- <button type="button" class="btn btn-secondary btn-sm mr-3 attachInstagramMedia" title="attach media from all content"><i class="fa fa-paperclip"></i></button> -->
		                            <small class="ml-3 text-gray">{{ $used_space }} / {{ $storage_limit }}</small>
		                            <div class="card-options">
		                                <button type="button" class="btn btn-secondary btn-sm btn-delete mr-3" disabled>
		                                    <i class="fa fa-trash"></i>
		                                </button>
		                                <span class="btn btn-primary btn-sm btn-upload">
		                                    <i class="fa fa-upload"></i> <span class="d-none d-md-inline">@lang('Upload')</span>
		                                    <input type="file" name="files[]" data-url="{{ route('media.upload') }}" multiple />
		                                </span>
		                            </div>
		                        </div>
		                        <div class="p-3">
		                            <div class="dimmer active">
		                                <div class="loader"></div>
		                                <div class="dimmer-content">

		                                	
		                                    <div class="d-flex flex-wrap align-content-start media-files-container">
		                                    </div>
		                                </div>
		                            </div>
		                        </div>
		                    </div>
		                </div>
						<div class="col-md-4 col-lg-4 col-xs-12">
							<small style="color: red;">Please Maintain aspect ratios between 0.800 and 1.910 for Photos</small>
							<div class="card">
								<div class="card-header pl-3">
									<h3 class="card-title">@lang('New post')</h3>
									<div class="card-options">
										<select name="account" class="form-control form-control-sm target-account">
											@foreach($accounts as $account)
											<option value="{{ $account->id }}">{{ $account->last_name }}</option>
											@endforeach
										</select>
									</div>
								</div>
								<div class="p-3">

									<div class="form-group">
										<div class="selectgroup w-100">
											<label class="selectgroup-item">
												<input type="radio" name="type" value="post" class="selectgroup-input post-type" checked="">
												<span class="selectgroup-button">@lang('Post')</span>
											</label>
											<label class="selectgroup-item">
												<input type="radio" name="type" value="album" class="selectgroup-input post-type">
												<span class="selectgroup-button">@lang('Album')</span>
											</label>
											<label class="selectgroup-item">
												<input type="radio" name="type" value="story" class="selectgroup-input post-type">
												<span class="selectgroup-button">@lang('Story')</span>
											</label>
										</div>
									</div>
									<br class="hidden-sm hidden-xs">
									<br class="hidden-sm hidden-xs">
									<br class="hidden-sm hidden-xs">
									<br class="hidden-sm hidden-xs">
									<br class="hidden-sm hidden-xs">
									<div class="form-group">
										<label class="form-label">@lang('Location')</label>
										<select name="location" class="form-control location-lookup"></select>
									</div>

									<div class="form-group">
										<label class="form-label">Hashtags</label>
										<input type="text" name="hashtags" id="search_hashtag" class="form-control location-lookup"></select>
										<div id="auto"></div>
									</div>

									<div class="form-group">
										<label class="form-label">@lang('Caption')</label>
										<textarea rows="3" name="caption" id="caption_ig" class="form-control caption-text" placeholder="@lang('Compose a post caption')" data-emojiable="true"></textarea>
									</div>

									<div class="form-group">
										<label class="custom-control custom-checkbox">
											<input type="checkbox" class="custom-control-input is-scheduled" name="scheduled" value="1">
											<span class="custom-control-label">@lang('Schedule')</span>
										</label>
									</div>

									<div class="form-group">
										<div class="input-icon">
											<span class="input-icon-addon"><i class="fe fe-calendar"></i></span>
											<input type="text" name="scheduled_at" class="form-control dm-date-time-picker scheduled-at" placeholder="@lang('Schedule at')" disabled="">
										</div>
									</div>

								</div>
								<div class="card-footer p-3">
									<button type="submit" class="btn btn-primary btn-block btn-schedule d-none">
										<i class="fe fe-clock"></i> @lang('Schedule post')
									</button>
									<button type="submit" class="btn btn-success btn-block btn-publish mt-0">
										<i class="fe fe-check"></i> @lang('Publish now')
									</button>
								</div>
							</div>
						</div>


						<div class="col-md-4 col-lg-4 col-xs-12">
		                    <div class="card preview-story d-none"></div>
		                    <div class="card preview-timeline">
		                        <div class="pt-5 pb-2 text-center">
		                            <img src="{{ asset('/images/ig-logo.png') }}" alt="Instagram">
		                        </div>
		                        <div class="p-3 d-flex align-items-center px-2">
		                            <div class="avatar avatar-md mr-3"></div>
		                            <div>
		                                <div class="preview-username active"></div>
		                                <small class="d-block text-muted preview-location active"></small>
		                            </div>
		                        </div>
		                        <div class="image-preview">
		                            <div id="carousel" class="carousel slide">
		                                <ol class="carousel-indicators"></ol>
		                                <div class="carousel-inner"></div>
		                            </div>
		                        </div>
		                        <div class="card-body">
		                            <div class="preview-caption active">
		                                <span></span>
		                                <span></span>
		                            </div>
		                        </div>
		                    </div>
		                </div>
					</div>
				</form>
			</div>
        </div>
	</div>
</div>
   