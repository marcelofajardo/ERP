@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h2 class="page-heading">Edit: Keyword-Instructions</h2>
        </div>
        <div class="col-md-12">
            @if(Session::has('message'))
                <div class="alert alert-info">
                    {{ Session::get('message') }}
                </div>
            @endif
            <div class="panel-group">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" href="#collapse1">
                                <strong><a class="btn btn-secondary" href="{{ action('KeywordInstructionController@index') }}">Back</a> &nbsp;Edit Instruction</strong>
                            </a>
                        </h4>
                    </div>
                    <div id="collapse1" class="panel-collapse">
                        <div class="panel-body">
                            <form action="{{ action('KeywordInstructionController@update', $keywordInstruction->id) }}" method="post">
                                @csrf
                                @method('PUT')
                                <div class="form-group">
                                    <label for="keywords">Keywords</label>
                                    <select style="width: 100%" multiple class="select2" name="keywords[]" id="keywords" data-placeholder="Enter Keywords..">
                                        @foreach($keywordInstruction->keywords as $keyword)
                                            <option selected value="{{ $keyword }}">{{ $keyword }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="instruction_category">Instruction Category</label>
                                    <select class="form-control" name="instruction_category" id="instruction_category">
                                        <option value="">Select Instructions...</option>
                                        @foreach($instructions as $instruction)
                                            <option {{ $keywordInstruction->instruction_category_id == $instruction->id ? 'selected' : '' }} value="{{ $instruction->id }}">{{ $instruction->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="remark">Remark</label>
                                    <textarea name="remark" id="remark" class="form-control" rows="4">{{ $keywordInstruction->remark }}</textarea>
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-secondary">Update Instruction</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                tags: true
            });
        });
    </script>
@endsection