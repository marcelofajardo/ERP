@extends('layouts.app')


@section('title', $title)

@section('content')
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>

<div class="row" id="common-page-layout">
	<div class="col-lg-12 margin-tb">
        <h2 class="page-heading">{{$title}} <span class="count-text"></span></h2>
        <div class="pull-right">
        <a class="btn btn-secondary" href="{{ route('hubstaff-acitivties.activities') }}">Back</a>
    </div>
    </div>
    <br>
    <div class="col-lg-12 margin-tb">
    	<div class="row">
		    <div class="col">
		    	<div class="h" style="margin-bottom:10px;">
		    		<form class="form-inline" action="{{route('hubstaff-acitivties.pending-payments')}}" method="get">
					  <div class="row">
			  			<div class="form-group">
						    <label for="keyword">User:</label>
                            <?php echo Form::select("user_id",["" => "-- Select User --"]+$users,$user_id,["class" => "form-control select2"]); ?>
					  	</div>
		               	<div class="form-group">
					  		<label for="button">&nbsp;</label>
					  		<button type="submit" style="display: inline-block;width: 10%" class="btn btn-sm btn-image">
					  			<img src="/images/search.png" style="cursor: default;">
					  		</button>
					  	</div>	
					  </div>	
					</form>	
		    	</div>
		    </div>
	    </div>	
		<div class="col-md-12 margin-tb">
        <div class="table-responsive">
        <table class="table table-bordered">
        <tr>
          <th>User</th>
          <th>Time tracked</th>
          <th>Amount</th>
          <th width="10%" colspan="2" class="text-center">Action</th>
        </tr>
          @foreach ($activityUsers as $user)
            <tr>
              <td>{{ $user->userName }}</td>
              <td>{{number_format($user->total_tracked / 60,2,".",",")}}</td>
              <td>{{$user->amount}} </td>
              <td>
                <form action="">
                    <input type="hidden" class="user_id" name="user_id" value="{{$user->system_user_id}}">
                    <input type="hidden" class="starts_at" name="starts_at" value="{{$user->starts_at}}">
                    <input type="hidden" class="total_amount" name="total_amount" value="{{$user->amount}}">

                    <a class="btn btn-secondary create-payment">+</a>
                </form>
              </td>
          @endforeach
      </table>
    </div>
		</div>
	</div>
</div>
<div id="loading-image" style="position: fixed;left: 0px;top: 0px;width: 100%;height: 100%;z-index: 9999;background: url('/images/pre-loader.gif') 
          50% 50% no-repeat;display:none;">
</div>



<div id="paymentModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content" id="payment-content">
                
    <form action="{{route('hubstaff-acitivties.payment-request.submit')}}" method="post">
    @csrf

    <div class="modal-header">
        <h4 class="modal-title"></h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
    </div>
    <div class="modal-body">
        <div class="form-group">
            <strong>Amount:</strong>
            <input type='text' id="pending_amount" class="form-control" name="amount" value=""/>
            <input type='hidden' id="user_id" class="form-control" name="user_id" value=""/>
            <input type='hidden' id="starts_at" class="form-control" name="starts_at" value=""/>

        </div>
        <div class="form-group">
        <strong>Note:</strong>
        <textarea name="note" rows="4" cols="50" class="form-control">{{ old('note') }}</textarea>

        @if ($errors->has('note'))
            <div class="alert alert-danger">{{$errors->first('note')}}</div>
        @endif
        </div>
    </div>
    <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    <button type="submit" class="btn btn-danger">Submit</button>
    </div>
</form>



            </div>

        </div>
    </div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.min.js"></script>

<script type="text/javascript">


        $(document).on('click', '.create-payment', function(e) {
            var user_id = $('.user_id').val();
            var starts_at = $('.starts_at').val();
            var amount = $('.total_amount').val();
            $('#pending_amount').val(amount);
            $('#user_id').val(user_id);
            $('#pending_amount').val(amount);
            $('#starts_at').val(starts_at);
            $('#paymentModal').modal('show');
            // e.preventDefault();
            // var thiss = $(this);
            // var type = 'GET';
            //     $.ajax({
            //     url: '/hubstaff-activities/activities/approved/payment/'+user_id,
            //     type: type,
            //     beforeSend: function() {
            //         $("#loading-image").show();
            //     }
            //     }).done( function(response) {
            //     $("#loading-image").hide();
            //     $('#paymentModal').modal('show');
            //     $('#payment-content').html(response);
            //     $('#pending_amount').val(amount);
            //     }).fail(function(errObj) {
            //     $("#loading-image").hide();
            //     });
            });

</script>
@endsection

