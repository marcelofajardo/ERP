<div id="lawyerFormModal" class="modal fade" role="dialog">
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
                    <div class="form-group">
                        {!! Form::label('name','Name',['class'=>'form-control-label']) !!}
                        {!! Form::text('name',null,['class'=>'form-control','placeholder'=>'','required']) !!}
                        @if ($errors->has('name'))
                            <div class="alert alert-danger">{{$errors->first('name')}}</div>
                        @endif
                    </div>
                    <div class="form-group">
                        {!! Form::label('speciality_id','Speciality',['class'=>'form-control-label']) !!}
                        {!! Form::select('speciality_id',$specialities,null,['class'=>'form-control','placeholder'=>'Select Speciality of a Lawyer','required']) !!}
                        @if ($errors->has('speciality_id'))
                            <div class="alert alert-danger">{{$errors->first('speciality_id')}}</div>
                        @endif
                    </div>
                    <div class="form-group">
                        {!! Form::label('rating','Rating',['class'=>'form-control-label']) !!}
                        {!! Form::select('rating',[1=>1,2,3,4,5,6,7,8,9,10],null,['class'=>'form-control','placeholder'=>'Rate this Lawyer']) !!}
                        @if ($errors->has('rating'))
                            <div class="alert alert-danger">{{$errors->first('rating')}}</div>
                        @endif
                    </div>
                    <div class="form-group">
                        {!! Form::label('address','Address',['class'=>'form-control-label']) !!}
                        {!! Form::text('address',null,['class'=>'form-control','placeholder'=>'']) !!}
                        @if ($errors->has('address'))
                            <div class="alert alert-danger">{{$errors->first('address')}}</div>
                        @endif
                    </div>
                    <div class="form-group">
                        {!! Form::label('phone','Phone',['class'=>'form-control-label']) !!}
                        {!! Form::text('phone',null,['class'=>'form-control','placeholder'=>'']) !!}
                        @if ($errors->has('phone'))
                            <div class="alert alert-danger">{{$errors->first('phone')}}</div>
                        @endif
                    </div>
                    <div class="form-group">
                        {!! Form::label('email','Email',['class'=>'form-control-label']) !!}
                        {!! Form::input('email','email',null,['class'=>'form-control','placeholder'=>'']) !!}
                        @if ($errors->has('email'))
                            <div class="alert alert-danger">{{$errors->first('email')}}</div>
                        @endif
                    </div>
                    <div class="form-group">
                        {!! Form::label('referenced_by','Referenced By',['class'=>'form-control-label']) !!}
                        {!! Form::text('referenced_by',null,['class'=>'form-control','placeholder'=>'']) !!}
                        @if ($errors->has('referenced_by'))
                            <div class="alert alert-danger">{{$errors->first('referenced_by')}}</div>
                        @endif
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