@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h1>Instagram Keywords</h1>
        </div>
        <div class="col-md-12">
            @if(Session::has('message'))
                <script>
                    alert("{{Session::get('message')}}")
                </script>
            @endif
            <form method="post" action="{{ action('KeywordsController@store') }}">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Keywords</label>
                            <input type="text" name="name" id="name" placeholder="Eg: delivery" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Add?</label>
                            <button class="btn-block btn btn-success">Add Keyword</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-12">
            <table class="table-striped table table-sm">
                <tr>
                    <th>S.N</th>
                    <th>Keyword</th>
                    <th>Actions</th>
                </tr>
                @foreach($keywords as $key=>$hashtag)
                    <tr>
                        <td>{{ $key+1 }}</td>
                        <td>
                                {{ $hashtag->text }}
                        </td>
                        <td>
                            <form method="post" action="{{ action('KeywordsController@destroy', $hashtag->id) }}">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/media-card.css') }}">
@endsection

@section('scripts')
    <script>
        var cid = null;
        $(function(){
            $('.show-details').on('click',function() {
                let id = $(this).attr('data-pid');
                $('.reveal-'+id).slideToggle('slow');
            });

            $('.card-reveal .close').on('click',function(){
                $(this).parent().slideToggle('slow');
            });

        });
    </script>
@endsection