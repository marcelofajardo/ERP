
		<div class="modal-content">
		   <div class="modal-header">
		      <h5 class="modal-title">Make payment</h5>
		      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		      <span aria-hidden="true">&times;</span>
		      </button>
		   </div>
		   <div class="modal-body">
           <div class="col-xs-12 col-sm-12 col-md-12 text-center">
           <p class="btn btn-primary open-payment-method pull-right">Add Payment method</p><br>
           </div>
           
           <div class="col-xs-12 col-sm-12 col-md-12">
            <div id="permission-from" class="dropdown-wrapper hidden">
                <div class="payment-dropdown-header">
                    <div class="form-group">
                    <strong>Name</strong>
                    <input type="text" id="payment-method-input" name="name" class="form-control" required>
                    </div>
                    <button type="button" class="btn btn-sm btn-primary add-payment-method pull-right">Submit</button><br><br>
                </div>
            </div>
            </div>
            {!! Form::model($user, ['method' => 'post','route' => ['user-management.savePayments', $user->id]]) !!}
            <div class="col-xs-12 col-sm-12 col-md-12">
                {{ Form::label('currency', 'Currency') }}
                {{ Form::text('currency',null, array('class' => 'form-control')) }}
            </div>
           <div class="col-xs-12 col-sm-12 col-md-12">
                    {{ Form::label('note', 'Note') }}
                    {{ Form::text('note',null, array('class' => 'form-control')) }}
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                    {{ Form::label('payment_method', 'Payment Method') }}
                    <div style="position: relative;">
                        <select id="payment_method" name="payment_method_id" class="form-control payment" onclick="console.log('hello');">
                            @foreach($paymentMethods as $paymentMethod)
                            <option value="{{ $paymentMethod->id }}"> {{ $paymentMethod->name }} </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                <strong>Payment pending</strong>
                <br>
                @foreach($pendingTerms as $term)
                    <input type="checkbox" name="amounts[]" value="{{$term['totalAmountTobePaid']}}">Total amount : {{$term['totalAmountTobePaid']}} {{$term['payment_currency']}} <br>
                @endforeach
                </div>
                <br>
                <br>
                <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <button type="submit" class="btn btn-secondary">Submit</button>
        {!! Form::close() !!} 

            </div>
         </div>
		   <div class="modal-footer">
		      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		      
		   </div>
		</div>