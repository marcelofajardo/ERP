@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Today's Benchmark - {{ $for_date }}</h2>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 col-md-offset-2">

            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            <form method="post" action="{{ route('benchmark.store') }}" class="form-horizontal" role="form">
                {!! csrf_field() !!}

                <div class="panel panel-info">
                    <div class="panel-heading">
                        <i class="glyphicon glyphicon-flash"></i>
                        <strong></strong>
                    </div>

                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-7 col-md-offset-2">
                                <div class="form-group">
                                    <strong> Selections:</strong>
                                    <input type="number" class="form-control" name="selections" placeholder="Selections" value="{{ old('selections') ? old('selections') : $selections }}"/>
                                    @if ($errors->has('selections'))
                                        <div class="alert alert-danger">{{$errors->first('selections')}}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-7 col-md-offset-2">
                                <div class="form-group">
                                    <strong> Searches:</strong>
                                    <input type="number" class="form-control" name="searches" placeholder="searches" value="{{ old('searches') ? old('searches') : $searches }}"/>
                                    @if ($errors->has('searches'))
                                        <div class="alert alert-danger">{{$errors->first('searches')}}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-7 col-md-offset-2">
                                <div class="form-group">
                                    <strong> Attributes:</strong>
                                    <input type="number" class="form-control" name="attributes" placeholder="attributes" value="{{ old('attributes') ? old('attributes') : $attributes }}"/>
                                    @if ($errors->has('attributes'))
                                        <div class="alert alert-danger">{{$errors->first('attributes')}}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-7 col-md-offset-2">
                                <div class="form-group">
                                    <strong> Supervisor:</strong>
                                    <input type="number" class="form-control" name="supervisor" placeholder="supervisor" value="{{ old('supervisor') ? old('supervisor') : $supervisor }}"/>
                                    @if ($errors->has('supervisor'))
                                        <div class="alert alert-danger">{{$errors->first('supervisor')}}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-7 col-md-offset-2">
                                <div class="form-group">
                                    <strong> Imagecropper:</strong>
                                    <input type="number" class="form-control" name="imagecropper" placeholder="imagecropper" value="{{ old('imagecropper') ? old('imagecropper') : $imagecropper }}"/>
                                    @if ($errors->has('imagecropper'))
                                        <div class="alert alert-danger">{{$errors->first('imagecropper')}}</div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-7 col-md-offset-2">
                                <div class="form-group">
                                    <strong> Approver:</strong>
                                    <input type="number" class="form-control" name="approver" placeholder="approver" value="{{ old('approver') ? old('approver') : $approver }}"/>
                                    @if ($errors->has('approver'))
                                        <div class="alert alert-danger">{{$errors->first('approver')}}</div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-7 col-md-offset-2">
                                <div class="form-group">
                                    <strong> Lister:</strong>
                                    <input type="number" class="form-control" name="lister" placeholder="lister" value="{{ old('lister') ? old('lister') : $lister }}"/>
                                    @if ($errors->has('lister'))
                                        <div class="alert alert-danger">{{$errors->first('lister')}}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row m-b-md">
                    <div class="col-md-12">
                        <button class="btn-secondary btn">
                            Save Benchmark
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>



@endsection
