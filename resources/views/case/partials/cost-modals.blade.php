<div id="costsModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content" style="width: 800px;left: -25%;">
            <div class="modal-header">
                <h4 class="modal-title">Add Payment/Cost for Case</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <table class="table table-striped">
                    <tr>
                        <th>Date Billed</th>
                        <th>Amount</th>
                        <th>Date Paid</th>
                        <th>Amount Paid</th>
                        <th>Action</th>
                    </tr>
                    <tbody class="case_costs">

                    </tbody>
                </table>
            </div>
            <button id="show-form"><i class="fa fa-plus"></i></button>
            <div id="add_payment" style="display: none">
                <div class="modal-body">
                    <div class="row">
                        <!-- billed_date -->
                        <div class="col-md-6 col-lg-6 @if($errors->has('billed_date')) has-danger @elseif(count($errors->all())>0) has-success @endif">
                            <div class="form-group">
                                {!! Form::label('billed_date', 'Billed Date', ['class' => 'form-control-label']) !!}
                                {!! Form::date('billed_date', null, ['class'=>'form-control '.($errors->has('billed_date')?'form-control-danger':(count($errors->all())>0?'form-control-success':'')),'required']) !!}
                                @if($errors->has('billed_date'))
                                    <div class="form-control-feedback">{{$errors->first('billed_date')}}</div>
                                @endif
                            </div>
                        </div>
                        <!-- amount -->
                        <div class="col-md-6 col-lg-6 @if($errors->has('amount')) has-danger @elseif(count($errors->all())>0) has-success @endif">
                            <div class="form-group">
                                {!! Form::label('amount', 'Amount', ['class' => 'form-control-label']) !!}
                                {!! Form::input('number','amount', null, ['class'=>'form-control '.($errors->has('amount')?'form-control-danger':(count($errors->all())>0?'form-control-success':'')),'required']) !!}
                                @if($errors->has('amount'))
                                    <div class="form-control-feedback">{{$errors->first('amount')}}</div>
                                @endif
                            </div>
                        </div>
                        <!-- paid_date -->
                        <div class="col-md-6 col-lg-6 @if($errors->has('paid_date')) has-danger @elseif(count($errors->all())>0) has-success @endif">
                            <div class="form-group">
                                {!! Form::label('paid_date', 'Paid Date', ['class' => 'form-control-label']) !!}
                                {!! Form::date('paid_date', null, ['class'=>'form-control '.($errors->has('paid_date')?'form-control-danger':(count($errors->all())>0?'form-control-success':''))]) !!}
                                @if($errors->has('paid_date'))
                                    <div class="form-control-feedback">{{$errors->first('paid_date')}}</div>
                                @endif
                            </div>
                        </div>
                        <!-- amount_paid -->
                        <div class="col-md-6 col-lg-6 @if($errors->has('amount_paid')) has-danger @elseif(count($errors->all())>0) has-success @endif">
                            <div class="form-group">
                                {!! Form::label('amount_paid', 'Amount Paid', ['class' => 'form-control-label']) !!}
                                {!! Form::input('number','amount_paid', null, ['class'=>'form-control '.($errors->has('amount_paid')?'form-control-danger':(count($errors->all())>0?'form-control-success':''))]) !!}
                                @if($errors->has('amount_paid'))
                                    <div class="form-control-feedback">{{$errors->first('amount_paid')}}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-secondary add-cost-button">Add</button>
                </div>
            </div>
        </div>

    </div>
</div>