<div id="createBloggerModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <form action="#" method="POST">
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title"></h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <!-- name -->
                        <div class="col-md-12 col-lg-12 @if($errors->has('name')) has-danger @elseif(count($errors->all())>0) has-success @endif">
                            <div class="form-group">
                                {!! Form::label('name', __('Name'), ['class' => 'form-control-label']) !!}
                                {!! Form::text('name', null, ['class'=>'form-control '.($errors->has('name')?'form-control-danger':(count($errors->all())>0?' form-control-success':'')),'required']) !!}
                                @if($errors->has('name'))
                                    <div class="form-control-feedback">{{$errors->first('name')}}</div>
                                @endif
                            </div>
                        </div>
                        <!-- agency -->
                        <div class="col-md-12 col-lg-12 @if($errors->has('agency')) has-danger @elseif(count($errors->all())>0) has-success @endif">
                            <div class="form-group">
                                {!! Form::label('agency', __('Agency'), ['class' => 'form-control-label']) !!}
                                {!! Form::text('agency', null, ['class'=>'form-control '.($errors->has('agency')?'form-control-danger':(count($errors->all())>0?' form-control-success':''))]) !!}
                                @if($errors->has('agency'))
                                    <div class="form-control-feedback">{{$errors->first('agency')}}</div>
                                @endif
                            </div>
                        </div>
                        <!-- phone -->
                        <div class="col-md-12 col-lg-12 @if($errors->has('phone')) has-danger @elseif(count($errors->all())>0) has-success @endif">
                            <div class="form-group">
                                {!! Form::label('phone', __('Phone'), ['class' => 'form-control-label']) !!}
                                {!! Form::text('phone', null, ['class'=>'form-control '.($errors->has('phone')?'form-control-danger':(count($errors->all())>0?'form-control-success':''))]) !!}
                                @if($errors->has('phone'))
                                    <div class="form-control-feedback">{{$errors->first('phone')}}</div>
                                @endif
                            </div>
                        </div>
                        <!-- email -->
                        <div class="col-md-12 col-lg-12 @if($errors->has('email')) has-danger @elseif(count($errors->all())>0) has-success @endif">
                            <div class="form-group">
                                {!! Form::label('email', __('Email'), ['class' => 'form-control-label']) !!}
                                {!! Form::text('email', null, ['class'=>'form-control '.($errors->has('email')?'form-control-danger':(count($errors->all())>0?' form-control-success':''))]) !!}
                                @if($errors->has('email'))
                                    <div class="form-control-feedback">{{$errors->first('email')}}</div>
                                @endif
                            </div>
                        </div>
                        <!-- city -->
                        <div class="col-md-12 col-lg-12 @if($errors->has('city')) has-danger @elseif(count($errors->all())>0) has-success @endif">
                            <div class="form-group">
                                {!! Form::label('city', __('City'), ['class' => 'form-control-label']) !!}
                                {!! Form::text('city', null, ['class'=>'form-control '.($errors->has('city')?'form-control-danger':(count($errors->all())>0?' form-control-success':''))]) !!}
                                @if($errors->has('city'))
                                    <div class="form-control-feedback">{{$errors->first('city')}}</div>
                                @endif
                            </div>
                        </div>
                        <!-- country -->
                        <div class="col-md-12 col-lg-12 @if($errors->has('country')) has-danger @elseif(count($errors->all())>0) has-success @endif">
                            <div class="form-group">
                                {!! Form::label('country', __('Country'), ['class' => 'form-control-label']) !!}
                                {!! Form::text('country', null, ['class'=>'form-control '.($errors->has('country')?'form-control-danger':(count($errors->all())>0?' form-control-success':''))]) !!}
                                @if($errors->has('country'))
                                    <div class="form-control-feedback">{{$errors->first('country')}}</div>
                                @endif
                            </div>
                        </div>
                        <!-- instagram_handle -->
                        <div class="col-md-12 col-lg-12 @if($errors->has('instagram_handle')) has-danger @elseif(count($errors->all())>0) has-success @endif">
                            <div class="form-group">
                                {!! Form::label('instagram_handle', __('Instagram handle'), ['class' => 'form-control-label']) !!}
                                {!! Form::text('instagram_handle', null, ['class'=>'form-control '.($errors->has('instagram_handle')?'form-control-danger':(count($errors->all())>0?' form-control-success':''))]) !!}
                                @if($errors->has('instagram_handle'))
                                    <div class="form-control-feedback">{{$errors->first('instagram_handle')}}</div>
                                @endif
                            </div>
                        </div>
                        <!-- followers -->
                        <div class="col-md-12 col-lg-12 @if($errors->has('followers')) has-danger @elseif(count($errors->all())>0) has-success @endif">
                            <div class="form-group">
                                {!! Form::label('followers', __('Followers'), ['class' => 'form-control-label']) !!}
                                {!! Form::text('followers', null, ['class'=>'form-control '.($errors->has('followers')?'form-control-danger':(count($errors->all())>0?' form-control-success':''))]) !!}
                                @if($errors->has('followers'))
                                    <div class="form-control-feedback">{{$errors->first('followers')}}</div>
                                @endif
                            </div>
                        </div>
                        <!-- followings -->
                        <div class="col-md-12 col-lg-12 @if($errors->has('followings')) has-danger @elseif(count($errors->all())>0) has-success @endif">
                            <div class="form-group">
                                {!! Form::label('followings', __('Following'), ['class' => 'form-control-label']) !!}
                                {!! Form::text('followings', null, ['class'=>'form-control '.($errors->has('followings')?'form-control-danger':(count($errors->all())>0?' form-control-success':''))]) !!}
                                @if($errors->has('followings'))
                                    <div class="form-control-feedback">{{$errors->first('followings')}}</div>
                                @endif
                            </div>
                        </div>
                        <!-- avg_engagement -->
                        <div class="col-md-12 col-lg-12 @if($errors->has('avg_engagement')) has-danger @elseif(count($errors->all())>0) has-success @endif">
                            <div class="form-group">
                                {!! Form::label('avg_engagement', __('Avg engagement'), ['class' => 'form-control-label']) !!}
                                {!! Form::text('avg_engagement', null, ['class'=>'form-control '.($errors->has('avg_engagement')?'form-control-danger':(count($errors->all())>0?' form-control-success':''))]) !!}
                                @if($errors->has('avg_engagement'))
                                    <div class="form-control-feedback">{{$errors->first('avg_engagement')}}</div>
                                @endif
                            </div>
                        </div>
                        <!-- fake_followers -->
                        <div class="col-md-12 col-lg-12 @if($errors->has('fake_followers')) has-danger @elseif(count($errors->all())>0) has-success @endif">
                            <div class="form-group">
                                {!! Form::label('fake_followers', __('Fake followers'), ['class' => 'form-control-label']) !!}
                                {!! Form::text('fake_followers', null, ['class'=>'form-control '.($errors->has('fake_followers')?'form-control-danger':(count($errors->all())>0?' form-control-success':''))]) !!}
                                @if($errors->has('fake_followers'))
                                    <div class="form-control-feedback">{{$errors->first('fake_followers')}}</div>
                                @endif
                            </div>
                        </div>
                        <!-- industry -->
                        <div class="col-md-12 col-lg-12 @if($errors->has('industry')) has-danger @elseif(count($errors->all())>0) has-success @endif">
                            <div class="form-group">
                                {!! Form::label('industry', __('Industry'), ['class' => 'form-control-label']) !!}
                                {!! Form::text('industry', null, ['class'=>'form-control '.($errors->has('industry')?'form-control-danger':(count($errors->all())>0?' form-control-success':''))]) !!}
                                @if($errors->has('industry'))
                                    <div class="form-control-feedback">{{$errors->first('industry')}}</div>
                                @endif
                            </div>
                        </div>
                        <!-- brands -->
                        <div class="col-md-12 col-lg-12 @if($errors->has('brands')) has-danger @elseif(count($errors->all())>0) has-success @endif">
                            <div class="form-group">
                                 {!! Form::label('brands[]', __('Brands'), ['class' => 'form-control-label']) !!}
                                {!! Form::select('brands[]', $select_brands, null, ['class'=>'form-control  '.($errors->has('brands')?'form-control-danger':(count($errors->all())>0?'form-control-success':'')),'multiple','id'=>'brands']) !!}
                                    @if($errors->has('brands'))
                            <div class="form-control-feedback">{{$errors->first('brands')}}</div>
                                        @endif
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-secondary">Add</button>
                </div>
            </form>
        </div>

    </div>
</div>