<form action="{{ route('dubbizle.bulk.whatsapp') }}" method="POST">
        @csrf

        <div class="modal-header">
          <h4 class="modal-title">Translated product Details</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
    <input type="hidden" name="product_id" id="product_id" value="{{$product_translation->product_id}}">
    <input type="hidden" name="product_translation_id" id="product_translation_id" value="{{$product_translation->id}}">
            <div class="form-group">
                <strong>Locale:</strong>
                <select class="form-control" name="locale" id="select-locale" required>
                    <option value="">Select Locale</option>
                    @foreach ($locales as $locale)
                        <option value="{{ $locale }}" {{ $product_translation->locale == $locale ? 'selected' : ''}}>{{ $locale }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <strong>Site ID:</strong>
                <select class="form-control" name="site_id" id="site_id" required>
                    <option value="">Select Site ID</option>
                    @foreach ($sites as $site)
                        <option value="{{ $site->id }}" {{ $product_translation->site_id == $site->id ? 'selected' : ''}}>{{ $site->title }}</option>
                    @endforeach
                </select>
            </div>

          <div class="form-group">
            <strong>Title:</strong>
            <textarea name="title" class="form-control" id="title" cols="30" rows="6">{{$product_translation->title}}</textarea>
          </div>
          <div class="form-group">
            <strong>Description:</strong>
            <textarea name="description" class="form-control" id="description" cols="30" rows="6">{{$product_translation->description}}</textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-secondary  edit-translation">Edit</button>
        </div>
      </form>