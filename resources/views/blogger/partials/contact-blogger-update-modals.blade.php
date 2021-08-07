<div id="contactBloggerUpdateModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <form action="#" method="POST">
                <input type="hidden" name="_method" value="put">
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title">Contact Blogger</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <!-- name -->
                        <div class="col-md-12 col-lg-12 @if($errors->has('name')) has-danger @elseif(count($errors->all())>0) has-success @endif">
                            <div class="form-group">
                                {!! Form::label('name', __('Name'), ['class' => 'form-control-label']) !!}
                                {!! Form::text('name', null, ['class'=>'form-control '.($errors->has('name')?'form-control-danger':(count($errors->all())>0?'form-control-success':'')),'required']) !!}
                                @if($errors->has('name'))
                                    <div class="form-control-feedback">{{$errors->first('name')}}</div>
                                @endif
                            </div>
                        </div>
                        <!-- email -->
                            <div class="col-md-12 col-lg-12 @if($errors->has('email')) has-danger @elseif(count($errors->all())>0) has-success @endif">
                            <div class="form-group">
                                {!! Form::label('email', __('Email'), ['class' => 'form-control-label']) !!}
                                {!! Form::email('email', null, ['class'=>'form-control '.($errors->has('email')?'form-control-danger':(count($errors->all())>0?'form-control-success':'')),'required']) !!}
                                @if($errors->has('email'))
                                    <div class="form-control-feedback">{{$errors->first('email')}}</div>
                                @endif
                            </div>
                        </div>
                        <!-- instagram_handle -->
                        <div class="col-md-12 col-lg-12 @if($errors->has('instagram_handle')) has-danger @elseif(count($errors->all())>0) has-success @endif">
                            <div class="form-group">
                                {!! Form::label('instagram_handle', __('Instagram handle'), ['class' => 'form-control-label']) !!}
                                {!! Form::text('instagram_handle', null, ['class'=>'form-control '.($errors->has('instagram_handle')?'form-control-danger':(count($errors->all())>0?'form-control-success':'')),'required']) !!}
                                @if($errors->has('instagram_handle'))
                                    <div class="form-control-feedback">{{$errors->first('instagram_handle')}}</div>
                                @endif
                            </div>
                        </div>
                        <!-- quote -->
                        <div class="col-md-12 col-lg-12 @if($errors->has('quote')) has-danger @elseif(count($errors->all())>0) has-success @endif">
                            <div class="form-group">
                                {!! Form::label('quote', __('Quote'), ['class' => 'form-control-label']) !!}
                                {!! Form::text('quote', null, ['class'=>'form-control '.($errors->has('quote')?'form-control-danger':(count($errors->all())>0?' form-control-success':''))]) !!}
                                @if($errors->has('quote'))
                                    <div class="form-control-feedback">{{$errors->first('quote')}}</div>
                                @endif
                            </div>
                        </div>
                        <!-- status -->
                        <div class="col-md-12 col-lg-12 @if($errors->has('status')) has-danger @elseif(count($errors->all())>0) has-success @endif">
                            <div class="form-group">
                                 {!! Form::label('status', __('Status'), ['class' => 'form-control-label']) !!}
                                {!! Form::select('status', ['pending'=>'Pending','negotiating'=>'Negotiating','approved'=>'Approved','rejected'=>'Rejected'], null, ['class'=>'form-control  '.($errors->has('status')?'form-control-danger':(count($errors->all())>0?'form-control-success':''))]) !!}
                                    @if($errors->has('status'))
                            <div class="form-control-feedback">{{$errors->first('status')}}</div>
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