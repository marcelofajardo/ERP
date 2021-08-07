                        <input type="hidden" name="id" value="{{ $data->id }}" id="id">
                        <div class="form-group row">
                            <label for="start" class="col-sm-3 col-form-label required">Store Website</label>
                            <div class="col-sm-8">
                                <select class="form-control select select2 required" name="store_website_id" id="edit_store_website_id" >
                                    <option value="">Please select</option>
                                    @foreach($store_websites as $web)
                                        <option value="{{ $web->id }}" {{ $web->id == $data->store_website_id ? "selected" : ""}}>{{ $web->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="code" class="col-sm-3 col-form-label required">Key</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control required" name="res_key" placeholder="Key" value="{{ $data->key}}" id="edit_key" />
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="code" class="col-sm-3 col-form-label required">Value</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control required" name="res_value" placeholder="Value" value="{{ $data->value}}" id="edit_value"  />
                            </div>
                        </div>