@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <h2 class="page-heading">Update Old Incomings</h2>
        </div>
    </div>
    <form action="{{ route('updateOldIncomings', ['id' => $old_incoming->serial_no]) }}" method="POST">
        @csrf
        <div class="modal-body">
            <div class="form-group">
                {!! Form::text('name', $old_incoming->name, ['class' => 'form-control'.($errors->has('name') ? ' is-invalid' : ''), 'placeholder' => 'Name']) !!}
                @if ($errors->has('name'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('name') }}</strong>
                    </span>
                @endif
            </div>
            <div class="form-group">
                {!! Form::textarea('description', $old_incoming->description, ['class' => 'form-control'.($errors->has('description') ? ' is-invalid' : ''), 'placeholder' => 'Description']) !!}
                @if ($errors->has('description'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('description') }}</strong>
                    </span>
                @endif
            </div>
            <div class="form-group">
                {!! Form::number('amount', $old_incoming->amount,['min' => '0','class' => 'form-control'.($errors->has('amount') ? ' is-invalid' : ''), 'placeholder' => 'Amount']) !!}
                @if ($errors->has('amount'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('amount') }}</strong>
                    </span>
                @endif
            </div>
            <div class="form-group">
                {!! Form::textarea('commitment', $old_incoming->commitment, ['class' => 'form-control'.($errors->has('commitment') ? ' is-invalid' : ''), 'placeholder' => 'Commitment']) !!}
                @if ($errors->has('commitment'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('commitment') }}</strong>
                    </span>
                @endif
            </div>
            <div class="form-group">
                {!! Form::textarea('communication', $old_incoming->communication, ['class' => 'form-control'.($errors->has('communication') ? ' is-invalid' : ''), 'placeholder' => 'Communication']) !!}
                @if ($errors->has('communication'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('communication') }}</strong>
                    </span>
                @endif
            </div>
            <div class="form-group">
                {!! Form::select('status', $status, $old_incoming->status, ['class' => 'form-control'.($errors->has('status') ? ' is-invalid' : ''), 'placeholder' => 'Select Status']) !!}
                @if ($errors->has('status'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('status') }}</strong>
                    </span>
                @endif
            </div>
            <div class="form-group">
                {!! Form::email('email', $old_incoming->email, ['class' => 'form-control'.($errors->has('email') ? ' is-invalid' : ''), 'placeholder' => 'Email']) !!}
                @if ($errors->has('email'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>
            <div class="form-group">
                {!! Form::text('number', $old_incoming->number, ['class' => 'form-control'.($errors->has('number') ? ' is-invalid' : ''), 'placeholder' => 'Number']) !!}
                @if ($errors->has('number'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('number') }}</strong>
                    </span>
                @endif
            </div>
            <div class="form-group">
                {!! Form::text('address', $old_incoming->address, ['class' => 'form-control'.($errors->has('address') ? ' is-invalid' : ''), 'placeholder' => 'Address']) !!}
                @if ($errors->has('address'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('address') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-secondary">Update</button>
        </div>
    </form>
@endsection
    