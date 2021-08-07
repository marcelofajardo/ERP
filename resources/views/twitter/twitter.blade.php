@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Solo luxury User's Twitter Timeline</h2>

    <form method="POST" action="{{ route('post.tweet') }}" enctype="multipart/form-data">

        {{ csrf_field() }}

        @if(count($errors))
            <div class="alert alert-danger">
                <strong>Whoops!</strong> There were some problems with your input.
                <br/>
                <ul>
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="form-group">
            <label>Add Tweet Text:</label>
            <textarea class="form-control" name="tweet"></textarea>
        </div>
        <div class="form-group">
            <label>Add Multiple Images:</label>
            <input type="file" name="images[]" multiple class="form-control">
        </div>
        <div class="form-group">
            <button class="btn btn-success">Add New Tweet</button>
        </div>
    </form>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th width="50px">No</th>
                <th>Twitter Id</th>
                <th>Message</th>
                <th>Images</th>
                <th>Favorite</th>
                <th>Retweet</th>
            </tr>
        </thead>
        <tbody>
            @if(!empty($data))
                @foreach($data as $key => $value)
                    <tr>
                        <td>{{ ++$key }}</td>
                        <td>{{ $value['id'] }}</td>
                        <td>{{ $value['text'] }}</td>
                        <td>
                            @if(!empty($value['extended_entities']['media']))
                                @foreach($value['extended_entities']['media'] as $v)
                                    <img src="{{ $v['media_url_https'] }}" style="width:100px;">
                                @endforeach
                            @endif
                        </td>
                        <td>{{ $value['favorite_count'] }}</td>
                        <td>{{ $value['retweet_count'] }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="6">There are no data.</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>
@endsection