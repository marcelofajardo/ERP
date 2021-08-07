@extends('layouts.app')

@section("styles")
<style type="text/css">
.grid {display: inline-block; max-width: 200px; max-height: 200px;}

.grid img {max-width: 100%; height:auto; margin-bottom: 10px;}

</style>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2> {{ $sender }}</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-secondary" href="{{ url('/scrap/gmail') }}"> Back</a>
            </div>
        </div>
    </div>

     <div class="row">
        <div class="col-md-12">
            <div class="panel-group">
                <div class="panel mt-5 panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" href="#collapse1">Tags</a>
                        </h4>
                    </div>
                    <div id="collapse1" class="panel-collapse collapse">
                        <div class="panel-body">
                            <table class="table table-bordered table-striped" id="phone-table">
                                <thead>
                                <tr>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($datas as $data)
                                     <tr> 
                                    <td>@if(is_array($data->tags)) {{ implode(' , ',$data->tags) }} @endif</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="row">
        
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Images:</strong>
                <div class="row">
                    @foreach($datas as $data)
                    @if(is_array($data->images))
                    @foreach($data->images as $image)
                    <div class="col-md-3">
                        <div class="grid">
                        <img src="{{ $image }}" alt="" class="img-responsive">
                        </div>
                    </div>
                    @endforeach
                    @endif
                    @endforeach
                </div>
                
            </div>
        </div>
        
    </div>
@endsection



