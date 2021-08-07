<html>
<head>
    <style>

    </style>
</head>
<body>
<h2 style="text-align: center;">Customer Chats</h2>
<div style="width: 500px; margin: 10px auto">
    @foreach($messages as $message)
        <div style="border-bottom: 1px solid #cccccc">
            @if ($message->user_id > 0)
                <div style="padding: 5px;">
                    <strong>{{ $message->created_at }}</strong> {{ $message->message }}
                    @if($message->hasMedia(config('constants.media_tags')))
                        <br>
                        @foreach($message->getMedia(config('constants.media_tags')) as $image)
                            <img src="{{ $image->getAbsolutePath() }}" alt="" style="width: 50px;">
                        @endforeach
                    @endif
                </div>
            @else
                <div style="border-left: 15px solid #CCC; padding: 5px;">
                    <strong>{{ $message->created_at }}</strong> {{ $message->message }}
                    @if($message->hasMedia(config('constants.media_tags')))
                        <br>
                        @foreach($message->getMedia(config('constants.media_tags')) as $image)
                            <img src="{{ $image->getAbsolutePath() }}" alt="" style="width: 50px;">
                        @endforeach
                    @endif
                </div>
            @endif
        </div>
    @endforeach
</div>
</body>
</html>