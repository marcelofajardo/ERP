<form id="create-form" enctype="multipart/form-data" method="POST">
            @csrf
            <div class="modal-header">
                <h4 class="modal-title"></h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
            <div class="form-group">
                <label for=""> Account </label>
                <select class="form-control" name="account_id" id="">
                <option value="">Select account</option>
                    @foreach($accounts as $account)
                    <option value="{{$account->id}}">{{$account->first_name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for=""> Caption </label>
                <textarea name="caption" class="form-control" style="resize:none"  placeholder="Add Your Caption Here"></textarea>
            </div>

            <div class="form-group">
                <label for=""> Post </label>
                <textarea name="post_body" class="form-control" style="resize:none"  placeholder="Add Your Post Here"></textarea>
            </div>
            <div class="form-group">
                <label for=""> Image </label>
                <input  type="file" enctype="multipart/form-data" class="form-control" name="image" />
            </div>
            

            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-secondary">Submit</button> 
            </div>
        </form>