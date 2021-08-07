<form action="{{ route('development.issue.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <td colspan="14">
        <div class="row">
            <div class="col-md-2">
                <select class="form-control d-inline select2" name="module" id="module" style="width: 150px !important;">
                    <option value="0">Select Module</option>
                    @foreach($modules as $module)
                        <option value="{{$module->id}}">{{ $module->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <input type="text" name="subject" placeholder="Subject..." id="subject" class="form-control d-inline" style="width: 150px !important;">
            </div>
            <div class="col-md-2">
                <input type="text" name="issue" placeholder="Issue..." id="issue" class="form-control d-inline" style="width: 150px !important;">
            </div>
            <div class="col-md-2">
                <select class="form-control d-inline" name="priority" required style="width: 150px !important;">
                    <option value="">Select Priority...</option>
                    <option value="1" {{ old('priority') == '1' ? 'selected' : '' }}>Critical</option>
                    <option value="2" {{ old('priority') == '2' ? 'selected' : '' }}>Urgent</option>
                    <option value="3" {{ old('priority') == '3' ? 'selected' : '' }}>Normal</option>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-control select2" name="assigned_to" id="assigned_to">
                    <option value="">Assigned To...</option>
                    @foreach($users as $id=>$user)
                        <option value="{{$id}}">{{ $user }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-1">
                <input type="file" name="images[]" class="form-control d-inline" multiple style="width: 100px;">
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-secondary d-inline">Add Issue</button>
            </div>
        </div>
    </td>
</form>