<div id="mergeModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Merge Customers</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="{{ route('erp.customer.merge') }}" method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <strong>Main Client:</strong>
                        <select class="form-control" data-live-search="true" data-size="15" name="first_customer" id="first_customer" title="Choose a Main Customer" required>
                        </select>

                        @if ($errors->has('first_customer'))
                            <div class="alert alert-danger">{{$errors->first('first_customer')}}</div>
                        @endif
                    </div>

                    <div class="form-group">
                        <strong>Additional Client:</strong>
                        <select class="form-control" data-live-search="true" data-size="15" name="second_customer" id="second_customer" title="Choose a Main Customer" required>
                        </select>

                        @if ($errors->has('second_customer'))
                            <div class="alert alert-danger">{{$errors->first('second_customer')}}</div>
                        @endif
                    </div>

                    <div class="form-group">
                        <button type="button" class="btn btn-secondary load-customers">Load Data</button>
                    </div>

                    <div class="row" id="customers-data" style="display: none;">
                        <div class="col-md-6">
                            @csrf
                            <input type="hidden" name="first_customer_id" id="first_customer_id" value="">
                            <input type="hidden" name="second_customer_id" id="second_customer_id" value="">
                            <div class="form-group">
                                <strong>Name:</strong>
                                <input type="text" class="form-control" name="name" placeholder="Client Name" id="first_customer_name" value="" required />
                            </div>

                            <div class="form-group">
                                <strong>Email:</strong>
                                <input type="email" class="form-control" name="email" placeholder="example@example.com" id="first_customer_email" value=""/>
                            </div>

                            <div class="form-group">
                                <strong>Phone:</strong>
                                <input type="number" class="form-control" name="phone" placeholder="900000000" id="first_customer_phone" value="" />
                            </div>

                            <div class="form-group">
                                <strong>Instagram Handle:</strong>
                                <input type="text" class="form-control" name="instahandler" placeholder="instahandle" id="first_customer_instahandler" value="" />
                            </div>

                            <div class="form-group">
                                <strong>Rating:</strong>
                                <Select name="rating" class="form-control" id="first_customer_rating" required>
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
                                </Select>
                            </div>

                            <div class="form-group">
                                <strong>Address:</strong>
                                <input type="text" class="form-control" name="address" placeholder="Street, Apartment" id="first_customer_address" value="" />
                            </div>

                            <div class="form-group">
                                <strong>City:</strong>
                                <input type="text" class="form-control" name="city" placeholder="Mumbai" id="first_customer_city" value="" />
                            </div>

                            <div class="form-group">
                                <strong>Country:</strong>
                                <input type="text" class="form-control" name="country" placeholder="India" id="first_customer_country" value="" />
                            </div>

                            <div class="form-group">
                                <strong>Pincode:</strong>
                                <input type="number" class="form-control" name="pincode" placeholder="411060" id="first_customer_pincode" value="" />
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <strong>Name:</strong>
                                <input type="text" class="form-control" id="second_customer_name" value="" readonly />
                            </div>

                            <div class="form-group">
                                <strong>Email:</strong>
                                <input type="email" class="form-control" id="second_customer_email" value="" readonly />
                            </div>

                            <div class="form-group">
                                <strong>Phone:</strong>
                                <input type="number" class="form-control" id="second_customer_phone" value="" readonly />
                            </div>

                            <div class="form-group">
                                <strong>Instagram Handle:</strong>
                                <input type="text" class="form-control" id="second_customer_instahandler" value="" readonly />
                            </div>

                            <div class="form-group">
                                <strong>Rating:</strong>
                                <input type="text" class="form-control" id="second_customer_rating" readonly />
                            </div>

                            <div class="form-group">
                                <strong>Address:</strong>
                                <input type="text" class="form-control" id="second_customer_address" value="" readonly />
                            </div>

                            <div class="form-group">
                                <strong>City:</strong>
                                <input type="text" class="form-control" id="second_customer_city" value="" readonly />
                            </div>

                            <div class="form-group">
                                <strong>Country:</strong>
                                <input type="text" class="form-control" id="second_customer_country" value="" readonly />
                            </div>

                            <div class="form-group">
                                <strong>Pincode:</strong>
                                <input type="number" class="form-control" name="pincode" placeholder="" id="second_customer_pincode" value="" readonly />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-secondary" id="mergeButton" disabled>Merge</button>
                </div>
            </form>
        </div>

    </div>
</div>
