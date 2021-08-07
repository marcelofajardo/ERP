<html>
<head>
    <title>Images</title>
    <style>
        body {
            background: #eeeeee;
        }
        * {
            padding: 0;
            margin: 0
        }

        .main {
            text-align: center;
            padding: 5%;
        }

        .row {
            display: block;
            clear: both;
        }

        .box_0 {
            width: 45%;
            display: inline-block;
            float: left;
            border-radius: 10px;
        }

        .box_1 {
            width: 45%;
            display: inline-block;
            float: right;
            border-radius: 10px;
        }
    </style>
</head>
<body>
<div class="main">
    @foreach($medias->chunk(2) as $subMedias)
        @php $key = 0 @endphp
        <div class="row">
            @foreach($subMedias as $subMedia)
                <div class="box_{{$key}}">
                    <img src="{{ $subMedia->getAbsolutePath() }}" alt="Image" style="width: 100%">
                </div>
                @php $key++ @endphp
            @endforeach
        </div>
    @endforeach
</div>
</body>
</html>