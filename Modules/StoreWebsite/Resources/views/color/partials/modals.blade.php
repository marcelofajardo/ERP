<div id="colorCreateModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <form action="{{ route('store-website.color.save') }}" method="POST">
          @csrf

          <div class="modal-header">
            <h4 class="modal-title">Add Store Color</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <strong>Store Website:</strong>
              {!!Form::select('store_website_id', $store_websites, old('store_website_id') , ['class' => 'form-control form-control-sm'])!!}

            </div>

            <div class="form-group">
              <strong>ERP Color:</strong>
              {!!Form::select('erp_color', $erp_colors, old('erp_color') , ['class' => 'form-control form-control-sm'])!!}

            </div>

            <div class="form-group">
              <strong>Store Color:</strong>
              <input type="text" name="store_color" class="form-control" value="{{ old('store_color') }}">

              @if ($errors->has('store_color'))
                <div class="alert alert-danger">{{$errors->first('store_color')}}</div>
              @endif
            </div>

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-secondary">Add</button>
          </div>
        </form>
      </div>

    </div>
  </div>

  <div id="colorEditModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <form action="" method="POST">
          @csrf
          @method('PUT')
          <div class="modal-header">
            <h4 class="modal-title">Update Store Color</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
            <div class="form-group">
                <strong>Store Website:</strong>
                {!!Form::select('store_website_id', [null=>'Select a website'] + $store_websites, old('store_website_id') , ['class' => 'form-control form-control-sm', 'id' => 'store_website_id'])!!}
            </div>
            <div class="form-group">
                <strong>ERP Color:</strong>
                {!!Form::select('erp_color', $erp_colors, old('erp_color') , ['class' => 'form-control form-control-sm', 'id' => 'erp_color'])!!}
            </div>

            <div class="form-group">
              <strong>Store Color:</strong>
              <input type="text" name="store_color" class="form-control" value="{{ old('store_color') }}" id="store_color">

              @if ($errors->has('store_color'))
                <div class="alert alert-danger">{{$errors->first('store_color')}}</div>
              @endif
            </div>

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-secondary">Update</button>
          </div>
        </form>
      </div>

    </div>
  </div>
