<div class="modal fade" id="multipleWhatsappModal" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Send Products Images Through Whats App</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="{{action('WhatsAppController@sendMessage', 'quicksell_group')}}" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <input id="Button1" type="button" value="Click To Send From Rating" onclick="switchVisible();"/ class="form-control">
                    <div id="Div1">
                        <br>
                          <select class="selectpicker" data-show-subtext="true" data-live-search="true" name="customers[]" multiple>
                          @foreach($customers as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                          @endforeach
                            </select>
                    </div>
                    <input type="hidden" name="status" value="2">
                    <div id="Div2">
                    <div class="form-group">
                        <input type="checkbox" id="send_type" name="to_all">
                        <label for="send_type">Send Message to All Existing Customers</label>
                    </div>
                  
                    <div class="form-group">
                        <label for="send_type">Number of selected Checkbox</label>
                        <p id="selected_checkbox"></p>
                    </div>
                    <input type="hidden" id="products" name="products"/>
                    <hr>

                    <div class="form-group">
                        <strong>Select Group of Customers</strong>
                        <select class="form-control" name="rating">
                            <option value="">Select a Rating</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <strong>Both Gender</strong>
                        <select class="form-control" name="gender">
                            <option value="">Select Gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                    </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-default">Send WhatsApp</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>