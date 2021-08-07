<div id="createCalculationModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Make Calculation</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <form action="{{ route('simplyduty.calculation') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="modal-body">
                    <div class="form-group">
                        <label>Hscode</label>
                        <input type="text" name="hscode" required />
                        @if ($errors->has('files'))
                            <div class="alert alert-danger">{{ $errors->first('files') }}</div>
                        @endif
                    </div>
                    <div class="form-group">
                        <label>Origin Country </label>
                        <select class="form-control selectpicker" data-live-search="true" name="origin">
                            @foreach ($countries as $country)
                            <option value="{{ $country->country_code }}" @if($country->country_code == 'AE') selected @endif>{{ $country->country_name }}</option>    
                            @endforeach
                        </select>
                        @if ($errors->has('files'))
                            <div class="alert alert-danger">{{ $errors->first('files') }}</div>
                        @endif
                    </div>
                    <div class="form-group">
                        <label>Destination Country  </label>
                        <select class="form-control selectpicker" data-live-search="true" name="destination">
                            @foreach ($countries as $country)
                            <option value="{{ $country->country_code }}">{{ $country->country_name }}</option>    
                            @endforeach
                        </select>
                       @if ($errors->has('files'))
                            <div class="alert alert-danger">{{ $errors->first('files') }}</div>
                        @endif
                    </div>

                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-secondary">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>
