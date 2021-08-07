@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h2 class="page-heading">Keyword-Instructions</h2>
        </div>
        <div class="col-md-12">
            <div class="panel-group">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" href="#collapse1">
                                <strong>Add Instruction</strong>
                            </a>
                        </h4>
                    </div>
                    <div id="collapse1" class="panel-collapse collapse">
                        <div class="panel-body">
                            <form action="{{ action('KeywordInstructionController@store') }}" method="post">
                                @csrf
                                <div class="form-group">
                                    <label for="keywords">Keywords</label>
                                    <select style="width: 100%" multiple class="select2" name="keywords[]" id="keywords" data-placeholder="Enter Keywords.."></select>
                                </div>
                                <div class="form-group">
                                    <label for="instruction_category">Instruction Category</label>
                                    <select class="form-control" name="instruction_category" id="instruction_category">
                                        <option value="">Select Instructions...</option>
                                        @foreach($instructions as $instruction)
                                            <option value="{{ $instruction->id }}">{{ $instruction->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="remark">Remark</label>
                                    <textarea name="remark" id="remark" class="form-control" rows="4"></textarea>
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-secondary">Add Instruction</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <table class="table table-striped table-bordered">
                <tr>
                    <th>S.N</th>
                    <th>Keywords</th>
                    <th>Instruction Category</th>
                    <th>Remarks</th>
                    <th>Action</th>
                </tr>
                @foreach($keywordInstructions as $key=>$keywordInstruction)
                    <tr>
                        <td>
                            {{ $key+1 }}
                        </td>
                        <td>
                            @if($keywordInstruction->keywords)
                                {{ implode(', ', $keywordInstruction->keywords) }}
                            @else
                                N/A
                            @endif
                        </td>
                        <td>
                            {{ $keywordInstruction->instruction->name }}
                        </td>
                        <td>
                            {{ $keywordInstruction->remark }}
                        </td>
                        <td>
                            <a class="btn btn-image" href="{{ action('KeywordInstructionController@edit', $keywordInstruction->id) }}">
                                <img src="{{ asset('images/edit.png') }}" alt="Edit" title="Edit">
                            </a>
                            <form method="post" action="{{ action('KeywordInstructionController@destroy', $keywordInstruction->id) }}" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-image">
                                    <img src="{{ asset('images/delete.png') }}" alt="Delete">
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </table>
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