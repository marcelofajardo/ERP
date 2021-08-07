<div id="caseFormModal" class="modal fade" role="dialog">
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
                    <!-- case_number -->
                    <div class="@if($errors->has('case_number')) has-danger @elseif(count($errors->all())>0) has-success @endif">
                        <div class="form-group">
                            {!! Form::label('case_number', 'Case Number', ['class' => 'form-control-label']) !!}
                            {!! Form::text('case_number', null, ['class'=>'form-control '.($errors->has('case_number')?'form-control-danger':(count($errors->all())>0?'form-control-success':''))]) !!}
                            @if($errors->has('case_number'))
                                <div class="form-control-feedback">{{$errors->first('case_number')}}</div>
                            @endif
                        </div>
                    </div>
                    <!-- lawyer_id -->
                    <div class="@if($errors->has('lawyer_id')) has-danger @elseif(count($errors->all())>0) has-success @endif">
                        <div class="form-group">
                             {!! Form::label('lawyer_id', 'Lawyer', ['class' => 'form-control-label']) !!}
                            {!! Form::select('lawyer_id', $lawyers, null, ['class'=>'form-control  '.($errors->has('lawyer_id')?'form-control-danger':(count($errors->all())>0?'form-control-success':'')),'placeholder' => 'Choose Lawyer for this case']) !!}
                            @if($errors->has('lawyer_id'))
                                <div class="form-control-feedback">{{$errors->first('lawyer_id')}}</div>
                            @endif
                        </div>
                    </div>
                    <!-- for_against -->
                    <div class="@if($errors->has('for_against')) has-danger @elseif(count($errors->all())>0) has-success @endif">
                        <div class="form-group">
                            {!! Form::label('for_against', 'For/Against', ['class' => 'form-control-label']) !!}
                            {!! Form::text('for_against', null, ['class'=>'form-control '.($errors->has('for_against')?'form-control-danger':(count($errors->all())>0?'form-control-success':''))]) !!}
                            @if($errors->has('for_against'))
                                <div class="form-control-feedback">{{$errors->first('for_against')}}</div>
                            @endif
                        </div>
                    </div>
                    <!-- court_detail -->
                    <div class="@if($errors->has('court_detail')) has-danger @elseif(count($errors->all())>0) has-success @endif">
                        <div class="form-group">
                            {!! Form::label('court_detail', 'Court and other Case Details', ['class' => 'form-control-label']) !!}
                            {!! Form::textarea('court_detail', null, ['class'=>'form-control '.($errors->has('court_detail')?'form-control-danger':(count($errors->all())>0?'form-control-success':'')),'rows'=>4]) !!}
                            @if($errors->has('court_detail'))
                                <div class="form-control-feedback">{{$errors->first('court_detail')}}</div>
                            @endif
                        </div>
                    </div>
                    <!-- status -->
                    <div class="@if($errors->has('status')) has-danger @elseif(count($errors->all())>0) has-success @endif">
                        <div class="form-group">
                            {!! Form::label('status', 'Status', ['class' => 'form-control-label']) !!}
                            {!! Form::select('status', $statuses, null, ['class'=>'form-control  '.($errors->has('status')?'form-control-danger':(count($errors->all())>0?'form-control-success':'')),'placeholder' => 'Choose status']) !!}
                            @if($errors->has('status'))
                                <div class="form-control-feedback">{{$errors->first('status')}}</div>
                            @endif
                        </div>
                    </div>
                    <!-- resource -->
                    <div class="@if($errors->has('resource')) has-danger @elseif(count($errors->all())>0) has-success @endif">
                        <div class="form-group">
                            {!! Form::label('resource', 'Resource', ['class' => 'form-control-label']) !!}
                            {!! Form::text('resource', null, ['class'=>'form-control '.($errors->has('resource')?'form-control-danger':(count($errors->all())>0?'form-control-success':''))]) !!}
                            @if($errors->has('resource'))
                                <div class="form-control-feedback">{{$errors->first('resource')}}</div>
                            @endif
                        </div>
                    </div>
                    <!-- last_date -->
                    <div class="@if($errors->has('last_date')) has-danger @elseif(count($errors->all())>0) has-success @endif">
                        <div class="form-group">
                            {!! Form::label('last_date', 'Last Date', ['class' => 'form-control-label']) !!}
                            {!! Form::input('date','last_date', null, ['class'=>'form-control '.($errors->has('last_date')?'form-control-danger':(count($errors->all())>0?'form-control-success':''))]) !!}
                            @if($errors->has('last_date'))
                                <div class="form-control-feedback">{{$errors->first('last_date')}}</div>
                            @endif
                        </div>
                    </div>
                    <!-- next_date -->
                    <div class="@if($errors->has('next_date')) has-danger @elseif(count($errors->all())>0) has-success @endif">
                        <div class="form-group">
                            {!! Form::label('next_date', 'Next Date', ['class' => 'form-control-label']) !!}
                            {!! Form::input('date','next_date', null, ['class'=>'form-control '.($errors->has('next_date')?'form-control-danger':(count($errors->all())>0?'form-control-success':''))]) !!}
                            @if($errors->has('next_date'))
                                <div class="form-control-feedback">{{$errors->first('next_date')}}</div>
                            @endif
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