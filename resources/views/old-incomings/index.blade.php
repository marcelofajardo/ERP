@extends('layouts.app')

@section('title', 'Old Incomings')

@section('content')
@include('partials.flash_messages')
<div class="row">
    <div class="col-lg-12 margin-tb">
		<h2 class="page-heading">Old Incomings</h2>
		<div class="pull-left">
			<form action="{{ route('filteredOldIncomings') }}" method="GET" class="form-inline align-items-start">
				<div class="form-group mr-3">
					{!! Form::text('sr_no', !empty($_GET['sr_no']) ? $_GET['sr_no'] : '', array('class' => 'form-control', 'placeholder' => 'Serial Number')) !!}
				</div>
				<div class="form-group mr-3">
					{!! Form::select('status', $status, !empty($_GET['status']) ? $_GET['status'] : '', ['class' => 'form-control', 'placeholder'=> 'Status']) !!}
				</div>
				<button type="submit" class="btn btn-image"><img src="/images/filter.png" /></button>
			</form>
		</div>
		<div class="pull-right">
			<button data-toggle="modal" data-target="#createOldInComingModal" class="btn btn-image set-reminder">
				<img src="{{ asset('images/add.png') }}" alt=""  style="width: 18px;">
			</button>
		</div>
	</div>
</div>
   

<div class="table-responsive mt-3">
    <table class="table table-bordered">
        <thead>
          <tr>
            <th scope="col">Sr. No.</th>
            <th scope="col">Name</th>
            <th scope="col" width="30%">Description</th>
            <th scope="col">Amount</th>
            <th scope="col" width="15%">Commitment</th>
            <th scope="col" width="30%">Communication</th>
            <th scope="col">Status</th>
          </tr>
        </thead>
        <tbody>
			@if (!empty($old_incomings))
				@foreach ($old_incomings as $incoming)
					<tr>
					<th scope="row">{{$incoming->serial_no}}</th>
					<td><a href="{{route('editOldIncomings', ['serial_no' => $incoming->serial_no])}}">{{$incoming->name}}</a></td>
                    <td>@php echo htmlspecialchars_decode(stripslashes(str_limit($incoming->description, 50, '<a href="javascript:void(0)">...</a>'))); @endphp
                        @if (strlen(strip_tags($incoming->description)) > 50)
                         <div>
                            <div class="panel-group">
                              <div class="panel panel-default">
                                <div class="panel-heading">
                                  <h4 class="panel-title">
                                    <a data-toggle="collapse" href="#collapse_desc{{$key}}" class="collapsed" aria-expanded="false">Read More</a>
                                  </h4>
                                </div>
                                <div id="collapse_desc{{$key}}" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                                  <div class="panel-body">
                                    <div class="messageList" id="message_list_310">
                                        {{$incoming->description}}     
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        @endif
                    </td>
					<td>{{$incoming->amount}}</td>
					<td>@php echo htmlspecialchars_decode(stripslashes(str_limit($incoming->commitment, 50, '<a href="javascript:void(0)">...</a>'))); @endphp
                        @if (strlen(strip_tags($incoming->commitment)) > 50)
                         <div>
                            <div class="panel-group">
                              <div class="panel panel-default">
                                <div class="panel-heading">
                                  <h4 class="panel-title">
                                    <a data-toggle="collapse" href="#collapse_commit{{$key}}" class="collapsed" aria-expanded="false">Read More</a>
                                  </h4>
                                </div>
                                <div id="collapse_commit{{$key}}" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                                  <div class="panel-body">
                                    <div class="messageList" id="message_list_310">
                                        {{$incoming->commitment}}     
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        @endif
                    </td>
					<td>{{$incoming->communication}}</td>
					<td>{{$incoming->status}}</td>
					</tr>
				@endforeach
			@endif
        </tbody>
      </table>
</div>
<div class="text-center">
    <div class="text-center">
        {!! $old_incomings->links() !!}
    </div>
</div>
<div id="createOldInComingModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
  
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Create</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
  
        <form action="{{ route('storeOldIncomings') }}" method="POST">
          @csrf
          <div class="modal-body">
            <div class="form-group">
                {!! Form::text('name', null, ['class' => 'form-control'.($errors->has('name') ? ' is-invalid' : ''), 'placeholder' => 'Name']) !!}
                @if ($errors->has('name'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('name') }}</strong>
                    </span>
                @endif
            </div>
            <div class="form-group">
				{!! Form::textarea('description', null, ['class' => 'form-control'.($errors->has('description') ? ' is-invalid' : ''), 'placeholder' => 'Description']) !!}
				@if ($errors->has('description'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('description') }}</strong>
                    </span>
                @endif
            </div>
            <div class="form-group">
				{!! Form::number('amount', null,['min' => '0','class' => 'form-control'.($errors->has('amount') ? ' is-invalid' : ''), 'placeholder' => 'Amount']) !!}
				@if ($errors->has('amount'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('amount') }}</strong>
                    </span>
                @endif
            </div>
            <div class="form-group">
				{!! Form::textarea('commitment', null, ['class' => 'form-control'.($errors->has('commitment') ? ' is-invalid' : ''), 'placeholder' => 'Commitment']) !!}
				@if ($errors->has('commitment'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('commitment') }}</strong>
                    </span>
                @endif
            </div>
            <div class="form-group">
				{!! Form::textarea('communication', null, ['class' => 'form-control'.($errors->has('communication') ? ' is-invalid' : ''), 'placeholder' => 'Communication']) !!}
				@if ($errors->has('communication'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('communication') }}</strong>
                    </span>
                @endif
            </div>
            <div class="form-group">
				{!! Form::select('status', $status, null, ['class' => 'form-control'.($errors->has('status') ? ' is-invalid' : ''), 'placeholder' => 'Select Status']) !!}
				@if ($errors->has('status'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('status') }}</strong>
                    </span>
                @endif
            </div>
            <div class="form-group">
				{!! Form::email('email', null, ['class' => 'form-control'.($errors->has('email') ? ' is-invalid' : ''), 'placeholder' => 'Email']) !!}
				@if ($errors->has('email'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>
            <div class="form-group">
				{!! Form::text('number', null, ['class' => 'form-control'.($errors->has('number') ? ' is-invalid' : ''), 'placeholder' => 'Number']) !!}
				@if ($errors->has('number'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('number') }}</strong>
                    </span>
                @endif
            </div>
            <div class="form-group">
				{!! Form::text('address', null, ['class' => 'form-control'.($errors->has('address') ? ' is-invalid' : ''), 'placeholder' => 'Address']) !!}
				@if ($errors->has('address'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('address') }}</strong>
                    </span>
                @endif
            </div>
          </div>
  
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-secondary">Create</button>
          </div>
        </form>
      </div>
    </div>
</div>
  @endsection