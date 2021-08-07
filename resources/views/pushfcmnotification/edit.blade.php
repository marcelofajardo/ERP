@extends('layouts.app')


@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Edit Notification</h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-secondary" href="{{ route('pushfcmnotification.list') }}"> Back</a>
        </div>
    </div>
</div>


@if (count($errors) > 0)
<div class="alert alert-danger">
    <strong>Whoops!</strong> There were some problems with your input.<br><br>
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif



{!! Form::open(array('route' => 'pushfcmnotification.update','method'=>'POST')) !!}
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Notification Title:</strong>
            {!! Form::text('title', ($Notification->title)?$Notification->title:old('title'), array('placeholder' => 'Name','class' => 'form-control')) !!}
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Notification Website:</strong>
            <select name="url" class="form-control">
                <option value="">Select Website</option>
                @foreach($StoreWebsite as $website)
                <option value="{{$website->website}}" <?php if($Notification->url == $website->website){ echo 'selected'; }elseif($website->website==old('url')){ echo 'selected'; }else{ echo ''; } ?> >{{$website->website}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Notification body:</strong>
            {!! Form::textarea('body', ($Notification->body)?$Notification->body:old('body'), array('placeholder' => 'body','class' => 'form-control')) !!}
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Sent At:</strong>
            {!! Form::text('sent_at', ($Notification->sent_at)?$Notification->sent_at:old('sent_at'), array('placeholder' => 'time to send notification at','class' => 'form-control', 'id' => 'sent_at_fcm_edit')) !!}
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12 text-center">
        <input type="hidden" name="id" value="{{$Notification->id}}">
        <button type="submit" class="btn btn-secondary">+</button>
    </div>
    
</div>
{!! Form::close() !!}


@endsection
@section('scripts')
<script type="text/javascript">
    $(function () {
        $('#sent_at_fcm_edit').datetimepicker({
            format: 'Y-MM-DD HH:mm',
            stepping: 5
        });
    });
</script>
@endsection
