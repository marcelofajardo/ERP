<div id="createBloggerProductModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <form action="{{ route('blogger-product.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title"></h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <!-- blogger_id -->
                        <div class="col-md-12 col-lg-12 @if($errors->has('blogger_id')) has-danger @elseif(count($errors->all())>0) has-success @endif">
                            <div class="form-group">
                                 {!! Form::label('blogger_id', __('Blogger'), ['class' => 'form-control-label']) !!}
                                {!! Form::select('blogger_id', $select_bloggers, null, ['class'=>'form-control  '.($errors->has('blogger_id')?'form-control-danger':(count($errors->all())>0?'form-control-success':''))]) !!}
                                    @if($errors->has('blogger_id'))
                            <div class="form-control-feedback">{{$errors->first('blogger_id')}}</div>
                                        @endif
                            </div>
                        </div>
                        <!-- brand_id -->
                        <div class="col-md-12 col-lg-12 @if($errors->has('brand_id')) has-danger @elseif(count($errors->all())>0) has-success @endif">
                            <div class="form-group">
                                 {!! Form::label('brand_id', __('Brand'), ['class' => 'form-control-label']) !!}
                                {!! Form::select('brand_id', $select_brands, null, ['class'=>'form-control  '.($errors->has('brand_id')?'form-control-danger':(count($errors->all())>0?'form-control-success':''))]) !!}
                                    @if($errors->has('brand_id'))
                            <div class="form-control-feedback">{{$errors->first('brand_id')}}</div>
                                        @endif
                            </div>
                        </div>
                        <!-- shoot_date -->
                        <div class="col-md-12 col-lg-12 @if($errors->has('shoot_date')) has-danger @elseif(count($errors->all())>0) has-success @endif">
                            <div class="form-group">
                                {!! Form::label('shoot_date', __('Shoot date'), ['class' => 'form-control-label']) !!}
                                {!! Form::date('shoot_date', null, ['class'=>'form-control '.($errors->has('shoot_date')?'form-control-danger':(count($errors->all())>0?' form-control-success':''))]) !!}
                                @if($errors->has('shoot_date'))
                                    <div class="form-control-feedback">{{$errors->first('shoot_date')}}</div>
                                @endif
                            </div>
                        </div>
                        <!-- first_post -->
                        <div class="col-md-12 col-lg-12 @if($errors->has('first_post')) has-danger @elseif(count($errors->all())>0) has-success @endif">
                            <div class="form-group">
                                {!! Form::label('first_post', __('1st Post'), ['class' => 'form-control-label']) !!}
                                {!! Form::date('first_post', null, ['class'=>'form-control '.($errors->has('first_post')?'form-control-danger':(count($errors->all())>0?' form-control-success':''))]) !!}
                                @if($errors->has('first_post'))
                                    <div class="form-control-feedback">{{$errors->first('first_post')}}</div>
                                @endif
                            </div>
                        </div>
                        <!-- second_post -->
                        <div class="col-md-12 col-lg-12 @if($errors->has('second_post')) has-danger @elseif(count($errors->all())>0) has-success @endif">
                            <div class="form-group">
                                {!! Form::label('second_post', __('2nd Post'), ['class' => 'form-control-label']) !!}
                                {!! Form::date('second_post', null, ['class'=>'form-control '.($errors->has('second_post')?'form-control-danger':(count($errors->all())>0?' form-control-success':''))]) !!}
                                @if($errors->has('second_post'))
                                    <div class="form-control-feedback">{{$errors->first('second_post')}}</div>
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
                        <!-- initial_quote -->
                        <div class="col-md-12 col-lg-12 @if($errors->has('initial_quote')) has-danger @elseif(count($errors->all())>0) has-success @endif">
                            <div class="form-group">
                                {!! Form::label('initial_quote', __('Initial Quote'), ['class' => 'form-control-label']) !!}
                                {!! Form::text('initial_quote', null, ['class'=>'form-control '.($errors->has('initial_quote')?'form-control-danger':(count($errors->all())>0?' form-control-success':''))]) !!}
                                @if($errors->has('initial_quote'))
                                    <div class="form-control-feedback">{{$errors->first('initial_quote')}}</div>
                                @endif
                            </div>
                        </div>
                        <!-- final_quote -->
                        <div class="col-md-12 col-lg-12 @if($errors->has('final_quote')) has-danger @elseif(count($errors->all())>0) has-success @endif">
                            <div class="form-group">
                                {!! Form::label('final_quote', __('Final Quote'), ['class' => 'form-control-label']) !!}
                                {!! Form::text('final_quote', null, ['class'=>'form-control '.($errors->has('final_quote')?'form-control-danger':(count($errors->all())>0?' form-control-success':''))]) !!}
                                @if($errors->has('final_quote'))
                                    <div class="form-control-feedback">{{$errors->first('final_quote')}}</div>
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

<div id="updateBloggerProductModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <form action="#" method="POST">
                @csrf
                <input type="hidden" name="_method" value="put">
                <div class="modal-header">
                    <h4 class="modal-title"></h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <!-- blogger_id -->
                        <div class="col-md-12 col-lg-12 @if($errors->has('blogger_id')) has-danger @elseif(count($errors->all())>0) has-success @endif">
                            <div class="form-group">
                                 {!! Form::label('blogger_id', __('Blogger'), ['class' => 'form-control-label']) !!}
                                {!! Form::select('blogger_id', $select_bloggers, null, ['class'=>'form-control  '.($errors->has('blogger_id')?'form-control-danger':(count($errors->all())>0?'form-control-success':''))]) !!}
                                    @if($errors->has('blogger_id'))
                            <div class="form-control-feedback">{{$errors->first('blogger_id')}}</div>
                                        @endif
                            </div>
                        </div>
                        <!-- brand_id -->
                        <div class="col-md-12 col-lg-12 @if($errors->has('brand_id')) has-danger @elseif(count($errors->all())>0) has-success @endif">
                            <div class="form-group">
                                 {!! Form::label('brand_id', __('Brand'), ['class' => 'form-control-label']) !!}
                                {!! Form::select('brand_id', $select_brands, null, ['class'=>'form-control  '.($errors->has('brand_id')?'form-control-danger':(count($errors->all())>0?'form-control-success':''))]) !!}
                                    @if($errors->has('brand_id'))
                            <div class="form-control-feedback">{{$errors->first('brand_id')}}</div>
                                        @endif
                            </div>
                        </div>
                        <!-- shoot_date -->
                        <div class="col-md-12 col-lg-12 @if($errors->has('shoot_date')) has-danger @elseif(count($errors->all())>0) has-success @endif">
                            <div class="form-group">
                                {!! Form::label('shoot_date', __('Shoot date'), ['class' => 'form-control-label']) !!}
                                {!! Form::date('shoot_date', null, ['class'=>'form-control '.($errors->has('shoot_date')?'form-control-danger':(count($errors->all())>0?' form-control-success':''))]) !!}
                                @if($errors->has('shoot_date'))
                                    <div class="form-control-feedback">{{$errors->first('shoot_date')}}</div>
                                @endif
                            </div>
                        </div>
                        <!-- first_post -->
                        <div class="col-md-12 col-lg-12 @if($errors->has('first_post')) has-danger @elseif(count($errors->all())>0) has-success @endif">
                            <div class="form-group">
                                {!! Form::label('first_post', __('1st Post'), ['class' => 'form-control-label']) !!}
                                {!! Form::date('first_post', null, ['class'=>'form-control '.($errors->has('first_post')?'form-control-danger':(count($errors->all())>0?' form-control-success':''))]) !!}
                                @if($errors->has('first_post'))
                                    <div class="form-control-feedback">{{$errors->first('first_post')}}</div>
                                @endif
                            </div>
                        </div>
                        <!-- first_post_likes -->
                        <div class="col-md-12 col-lg-12 @if($errors->has('first_post_likes')) has-danger @elseif(count($errors->all())>0) has-success @endif">
                            <div class="form-group">
                                {!! Form::label('first_post_likes', __('First post likes'), ['class' => 'form-control-label']) !!}
                                {!! Form::text('first_post_likes', null, ['class'=>'form-control '.($errors->has('first_post_likes')?'form-control-danger':(count($errors->all())>0?' form-control-success':''))]) !!}
                                @if($errors->has('first_post_likes'))
                                    <div class="form-control-feedback">{{$errors->first('first_post_likes')}}</div>
                                @endif
                            </div>
                        </div>
                        <!-- first_post_engagement -->
                        <div class="col-md-12 col-lg-12 @if($errors->has('first_post_engagement')) has-danger @elseif(count($errors->all())>0) has-success @endif">
                            <div class="form-group">
                                {!! Form::label('first_post_engagement', __('First post engagement'), ['class' => 'form-control-label']) !!}
                                {!! Form::text('first_post_engagement', null, ['class'=>'form-control '.($errors->has('first_post_engagement')?'form-control-danger':(count($errors->all())>0?' form-control-success':''))]) !!}
                                @if($errors->has('first_post_engagement'))
                                    <div class="form-control-feedback">{{$errors->first('first_post_engagement')}}</div>
                                @endif
                            </div>
                        </div>
                        <!-- first_post_response -->
                        <div class="col-md-12 col-lg-12 @if($errors->has('first_post_response')) has-danger @elseif(count($errors->all())>0) has-success @endif">
                            <div class="form-group">
                                {!! Form::label('first_post_response', __('First post response'), ['class' => 'form-control-label']) !!}
                                {!! Form::text('first_post_response', null, ['class'=>'form-control '.($errors->has('first_post_response')?'form-control-danger':(count($errors->all())>0?' form-control-success':''))]) !!}
                                @if($errors->has('first_post_response'))
                                    <div class="form-control-feedback">{{$errors->first('first_post_response')}}</div>
                                @endif
                            </div>
                        </div>
                        <!-- first_post_sales -->
                        <div class="col-md-12 col-lg-12 @if($errors->has('first_post_sales')) has-danger @elseif(count($errors->all())>0) has-success @endif">
                            <div class="form-group">
                                {!! Form::label('first_post_sales', __('First post sales'), ['class' => 'form-control-label']) !!}
                                {!! Form::text('first_post_sales', null, ['class'=>'form-control '.($errors->has('first_post_sales')?'form-control-danger':(count($errors->all())>0?' form-control-success':''))]) !!}
                                @if($errors->has('first_post_sales'))
                                    <div class="form-control-feedback">{{$errors->first('first_post_sales')}}</div>
                                @endif
                            </div>
                        </div>
                        <!-- second_post -->
                        <div class="col-md-12 col-lg-12 @if($errors->has('second_post')) has-danger @elseif(count($errors->all())>0) has-success @endif">
                            <div class="form-group">
                                {!! Form::label('second_post', __('2nd Post'), ['class' => 'form-control-label']) !!}
                                {!! Form::date('second_post', null, ['class'=>'form-control '.($errors->has('second_post')?'form-control-danger':(count($errors->all())>0?' form-control-success':''))]) !!}
                                @if($errors->has('second_post'))
                                    <div class="form-control-feedback">{{$errors->first('second_post')}}</div>
                                @endif
                            </div>
                        </div>
                        <!-- second_post_likes -->
                        <div class="col-md-12 col-lg-12 @if($errors->has('second_post_likes')) has-danger @elseif(count($errors->all())>0) has-success @endif">
                            <div class="form-group">
                                {!! Form::label('second_post_likes', __('Second post likes'), ['class' => 'form-control-label']) !!}
                                {!! Form::text('second_post_likes', null, ['class'=>'form-control '.($errors->has('second_post_likes')?'form-control-danger':(count($errors->all())>0?' form-control-success':''))]) !!}
                                @if($errors->has('second_post_likes'))
                                    <div class="form-control-feedback">{{$errors->first('second_post_likes')}}</div>
                                @endif
                            </div>
                        </div>
                        <!-- second_post_engagement -->
                        <div class="col-md-12 col-lg-12 @if($errors->has('second_post_engagement')) has-danger @elseif(count($errors->all())>0) has-success @endif">
                            <div class="form-group">
                                {!! Form::label('second_post_engagement', __('Second post engagement'), ['class' => 'form-control-label']) !!}
                                {!! Form::text('second_post_engagement', null, ['class'=>'form-control '.($errors->has('second_post_engagement')?'form-control-danger':(count($errors->all())>0?' form-control-success':''))]) !!}
                                @if($errors->has('second_post_engagement'))
                                    <div class="form-control-feedback">{{$errors->first('second_post_engagement')}}</div>
                                @endif
                            </div>
                        </div>
                        <!-- second_post_response -->
                        <div class="col-md-12 col-lg-12 @if($errors->has('second_post_response')) has-danger @elseif(count($errors->all())>0) has-success @endif">
                            <div class="form-group">
                                {!! Form::label('second_post_response', __('Second post response'), ['class' => 'form-control-label']) !!}
                                {!! Form::text('second_post_response', null, ['class'=>'form-control '.($errors->has('second_post_response')?'form-control-danger':(count($errors->all())>0?' form-control-success':''))]) !!}
                                @if($errors->has('second_post_response'))
                                    <div class="form-control-feedback">{{$errors->first('second_post_response')}}</div>
                                @endif
                            </div>
                        </div>
                        <!-- second_post_sales -->
                        <div class="col-md-12 col-lg-12 @if($errors->has('second_post_sales')) has-danger @elseif(count($errors->all())>0) has-success @endif">
                            <div class="form-group">
                                {!! Form::label('second_post_sales', __('Second post sales'), ['class' => 'form-control-label']) !!}
                                {!! Form::text('second_post_sales', null, ['class'=>'form-control '.($errors->has('second_post_sales')?'form-control-danger':(count($errors->all())>0?' form-control-success':''))]) !!}
                                @if($errors->has('second_post_sales'))
                                    <div class="form-control-feedback">{{$errors->first('first_post_sales')}}</div>
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
                        <!-- initial_quote -->
                        <div class="col-md-12 col-lg-12 @if($errors->has('initial_quote')) has-danger @elseif(count($errors->all())>0) has-success @endif">
                            <div class="form-group">
                                {!! Form::label('initial_quote', __('Initial Quote'), ['class' => 'form-control-label']) !!}
                                {!! Form::text('initial_quote', null, ['class'=>'form-control '.($errors->has('initial_quote')?'form-control-danger':(count($errors->all())>0?' form-control-success':''))]) !!}
                                @if($errors->has('initial_quote'))
                                    <div class="form-control-feedback">{{$errors->first('initial_quote')}}</div>
                                @endif
                            </div>
                        </div>
                        <!-- final_quote -->
                        <div class="col-md-12 col-lg-12 @if($errors->has('final_quote')) has-danger @elseif(count($errors->all())>0) has-success @endif">
                            <div class="form-group">
                                {!! Form::label('final_quote', __('Final Quote'), ['class' => 'form-control-label']) !!}
                                {!! Form::text('final_quote', null, ['class'=>'form-control '.($errors->has('final_quote')?'form-control-danger':(count($errors->all())>0?' form-control-success':''))]) !!}
                                @if($errors->has('final_quote'))
                                    <div class="form-control-feedback">{{$errors->first('final_quote')}}</div>
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