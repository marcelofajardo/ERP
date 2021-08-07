<h3>Modules</h3>
<form class="form-inline" action="{{ route('development.module.store') }}" method="POST">
    @csrf
    <input type="hidden" name="priority" value="5">
    <input type="hidden" name="status" value="Planned">
    <div class="form-group">
        <input type="text" class="form-control" name="name" placeholder="Module" value="{{ old('name') }}" required>

        @if ($errors->has('name'))
            <div class="alert alert-danger">{{$errors->first('name')}}</div>
        @endif
    </div>

    <button type="submit" class="btn btn-secondary ml-3">Add Module</button>
</form>