<div class="row">
    <div class="col-md-12">
        <div class="row row_next_action">
            <div class="col-12 d-inline form-inline">
                <input style="width: 87%" type="text" name="add_next_action" placeholder="Add New Next Action" class="form-control mb-3 add_next_action_txt">
                <button class="btn btn-secondary add_next_action" style="position: absolute;  margin-left: 8px;">+</button>
            </div>
            <div class="col-12 d-inline form-inline">
                <div style="float: left; width: 88%">
                    <select name="next_action" class="form-control next_action" data-id="{{$customer->id}}">
                        <option value="">Select Next Action</option> 
                        <?php foreach ($nextActionArr as $value => $option) { ?>
                            <option value="{{$value}}" {{$value == $customer->customer_next_action_id ? 'selected' : ''}}>{{$option}}</option>
                        <?php } ?>
                    </select>
                </div>
                <div style="float: right; width: 12%;">
                    <a class="btn btn-image delete_next_action"><img src="/images/delete.png"></a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 expand-row dis-none">
    </div>
</div>