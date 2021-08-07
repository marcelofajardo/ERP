@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h2 class="page-heading">Sitejabber Q/A</h2>
        </div>
        <div class="col-md-12">
            <div class="accordion" id="accordionExample">
            @foreach($sjs as $key => $sj)
                    <div class="card">
                        <div class="card-header" id="qah_{{$key}}">
                            <h2 class="mb-0">
                                <div style="cursor: pointer;" data-toggle="collapse" data-target="#qa_{{$key}}" aria-expanded="true" aria-controls="qa_{{$key}}">
                                    <h3 class="mb-0 pb-0"><span class="badge badge-primary">{{ $key+1 }}</span> {{ $sj->text }}</h3>
                                </div>
                            </h2>
                        </div>

                        <div id="qa_{{$key}}" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                            <div class="card-body">
                                @foreach($sj->answers as $answer)
                                    <blockquote style="font-size: 18px;" class="blockquote mb-0 alert mb-2">
                                        <p><strong>{{ $answer->text }}</strong></p>
                                        <footer class="blockquote-footer">{{ $answer->author }} <span class="badge badge-success">{{$answer->type}}</span> @if ($answer->type == 'reply') <span class="badge badge-primary">Posted</span> @endif</footer>
                                    </blockquote>
                                    <hr>
                                @endforeach

                                <div class="form-group">
                                    <form action="{{ action('SitejabberQAController@update', $sj->id) }}" method="post">
                                        @csrf
                                        @method('put')
                                        <input type="text" name="reply" class="form-control" placeholder="Type reply...">
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
            @endforeach
            </div>
        </div>
    </div>
@endsection