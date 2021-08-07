@extends('layouts.app')

@section('title', 'Google Server List')

@section('content')

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <h2 class="page-heading">Update Google Translation Setting</h2>
            <div class="pull-right">
                <button type="button" class="btn btn-secondary ml-3" data-toggle="modal" data-target="#googleServerCreateModal">+</button>
            </div>
        </div>
    </div>
    <div class="mt-3 col-md-12">
        <form class="add_translation_language" action="{{ route('google-traslation-settings.update', $data->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h4 class="modal-title">Add Goole Translation Setting</h4>
            </div>
            <input type="hidden" name="id" class="form-control" value="{{ old('id', $data->id) }}">
            <div class="modal-body">
                 <!-- email , account_json , status, last_note , created_at -->
                <div class="form-group">
                    <strong>Email:</strong>
                    <input type="text" name="email" class="form-control"
                    value="{{ old('email', $data->email) }}">

                    @if ($errors->has('email'))
                    <div class="alert alert-danger">{{$errors->first('email')}}</div>
                    @endif
                </div>

                <div class="form-group">
                    <strong>Account JSON:</strong>
                    <textarea class="form-control" name="account_json" required>
                        {{ old('account_json', $data->account_json) }}
                    </textarea>
                    @if ($errors->has('account_json'))
                    <div class="alert alert-danger">{{$errors->first('account_json')}}</div>
                    @endif
                </div>

                <div class="form-group">
                    <strong>Status:</strong>
                    <select name="status" class="form-control">
                        <option value="1" <?php echo ($data->status == "1")? 'selected':''; ?>>Enable</option>
                        <option value="0" <?php echo ($data->status == "0")? 'selected':''; ?>>Disable</option>
                    </select>
                    @if ($errors->has('status'))
                    <div class="alert alert-danger">{{$errors->first('status')}}</div>
                    @endif
                </div>

                <div class="form-group">
                    <strong>Last Note:</strong>
                    <input type="text" name="last_note" class="form-control" value="{{ old('last_note', $data->last_note) }}" required>

                    @if ($errors->has('last_note'))
                    <div class="alert alert-danger">{{$errors->first('last_note')}}</div>
                    @endif
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-secondary">Update</button>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
@endsection
