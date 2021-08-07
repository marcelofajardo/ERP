<div id="add_lead" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
        <form action="{{ route('leads.erpLeads.store') }}" method="POST" enctype="multipart/form-data" class="erp_lead_frm" data-reload='1'>
            <div class="modal-header">
                <h2>Add Lead</h2>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    @csrf
                    <input type="hidden" name="customer_id" value="" class="customer_id">
                    <input type="hidden" name="assigned_user" value="6">
                    <input type="hidden" name="rating" value="1">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="form-group">
                                <strong>Brand:</strong>
                                <select name="brand_id" class="form-control multi_brand multi_brand_select select-multiple" multiple data-placeholder="Select Brands">
                                    @php 
                                    $brand_segments = \App\Brand::all();
                                    @endphp
                                    @foreach($brand_segments as $brand_item)
                                        <option value="{{$brand_item->id}}" data-brand-segment="{{$brand_item->brand_segment}}">{{$brand_item->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <strong>Category</strong>
                                {!! \App\Category::attr(['name' => 'category_id','class' => 'form-control select-multiple'])->selected(['1'])->renderAsDropdown()  !!}
                            </div>
                            <div class="form-group">
                                <strong>Brand Segment:</strong>
                                {{ App\Helpers\ProductHelper::getBrandSegment('brand_segment[]', [], ['class' => "form-control brand_segment_select", 'multiple' => ''])}}
                            </div>
                            <div class="form-group">
                                <strong>status:</strong>
                                    <Select name="lead_status_id" class="form-control">
                                        @foreach((New \App\Status)->all() as $key => $value)
                                            <option value="{{$value}}" {{$value == '3' ? 'selected':''}}>{{$key}}</option>
                                        @endforeach
                                    </Select>
                            </div>
                            <div class="form-group">
                                <strong>Sizes:</strong>
                                <input type="text" name="size" value="" class="form-control" placeholder="S, M, L">
                            </div>
                            <div class="form-group">
                                <strong>Gender:</strong>
                                <select name="gender" class="form-control">
                                    <option value="male" selected>Male</option>
                                    <option value="female">Female</option>
                                </select>
                            </div>
                            <input type="hidden"  name="oldImage[0]" value="-1">
                            <div class="row">
                                <div class="form-group show-product-image">                                    
                                </div>
                            </div>
                            <div class="form-group new-image" style="">
                                <strong>Upload Image:</strong>
                                <input  type="file" enctype="multipart/form-data" class="form-control" name="image[]" multiple />
                            </div>
                        </div>
                    </div>
                </div>
              <div class="modal-footer">
                <button type="submit" class="btn btn-default">Add</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              </div>
        </div>
    </form>
  </div>
</div>
</div>