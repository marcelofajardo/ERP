<div id="generateAWBMODAL" class="modal fade" role="dialog">
   <div class="modal-dialog modal-lg">
      <!-- Modal content-->
      <div class="modal-content ">
         <form action="{{ route('order.generate.awb') }}" method="POST">
            @csrf
            <input type="hidden" name="order_id" value="{{ isset($id) ? $id : '' }}">
            <div class="modal-header">
               <h4 class="modal-title">Generate AWB</h4>
               <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
              <div id="validation-errors"></div>
               <div class="col-md-4"><b>From</b></div>
               <div class="col-md-3 col-md-offset-1"><button type="button" class="btn btn-sm btn-primary" id="swtichForm">Switch</button></div>
               <div class="col-md-4"><b>To</b></div>
               <div class="col-md-6">
                  <div class="form-group" id="div_from_customer_name">
                     <strong>Customer Name:</strong>
                     <input type="text" name="from_customer_name" id="from_customer_name" class="form-control input_customer_name" value="{{@$fromdatadefault['person_name']}}" required>
                     <span class="form-error"></span>
                  </div>
                  <div class="form-group">
                     <strong>Customer City:</strong>
                     <input type="text" name="from_customer_city" id="from_customer_city" class="form-control input_customer_city" value="{{@$fromdatadefault['city']}}" >
                     <span class="form-error"></span>
                  </div>
                  <div class="form-group">
                     <strong>Customer Country (ISO 2):</strong>
                     <input type="text" name="from_customer_country" id="from_customer_country" class="form-control input_customer_country" value="{{@$fromdatadefault['country_code']}}" required>
                  </div>
                  <div class="form-group mb-2">
                     <strong>Customer Phone:</strong>
                     <input type="number" name="from_customer_phone" id="from_customer_phone" class="form-control input_customer_phone" value="{{@$fromdatadefault['phone']}}">
                     <span class="form-error"></span>
                  </div>
                  <div class="form-group mb-2">
                     <strong>Customer Address 1:</strong>
                     <input type="text" name="from_customer_address1" id="from_customer_address1" maxlength="45" class="form-control input_customer_address1" value="{{@$fromdatadefault['street']}}">
                     <span class="form-error"></span>
                  </div>
                  <div class="form-group mb-2">
                     <strong>Customer Address 2:</strong>
                     <input type="text" name="from_customer_address2" id="from_customer_address2" class="form-control input_customer_address2" >
                     <span class="form-error"></span>
                  </div>
                  <div class="form-group mb-2">
                     <strong>Customer Pincode:</strong>
                     <input type="number" name="from_customer_pincode" id="from_customer_pincode"class="form-control input_customer_pincode" max="999999" value="{{@$fromdatadefault['postal_code']}}">
                     <span class="form-error"></span>
                  </div>
                  <div class="form-group mb-2">
                     <strong>Company Name:</strong>
                     <input type="text" name="from_company_name" id="from_company_name" class="form-control input_customer_pincode"  value="{{@$fromdatadefault['company_name']}}">
                     <span class="form-error"></span>
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="form-group">
                     <strong>Customer Name:</strong>
                     <input type="text" name="customer_name" id="customer_name" class="form-control input_customer_name" value="{{ isset($customer) ? $customer->name : '' }}" required>
                  </div>
                  <div class="form-group">
                     <strong>Customer City:</strong>
                     <input type="text" name="customer_city" id="customer_city" class="form-control input_customer_city" value="" required>
                  </div>
                  <div class="form-group">
                     <strong>Customer Country (ISO 2):</strong>
                     <input type="text" name="customer_country" id="customer_country" class="form-control input_customer_country" value="" required>
                  </div>
                  <div class="form-group">
                     <strong>Customer Email:</strong>
                     <input type="email" name="customer_email" id="customer_email" class="form-control input_customer_email" value="{{ isset($customer) ? $customer->email : '' }}" required>
                  </div>
                  <div class="form-group">
                     <strong>Customer Phone:</strong>
                     <input type="number" name="customer_phone" id="customer_phone" class="form-control input_customer_phone" value="{{ isset($customer) ? $customer->phone : '' }}" required>
                  </div>
                  <div class="form-group">
                     <strong>Customer Address 1:</strong>
                     <input type="text" name="customer_address1" id="customer_address1" maxlength="45" class="form-control input_customer_address1" value="{{ isset($customer) ? $customer->address : '' }}" required>
                  </div>
                  <div class="form-group">
                     <strong>Customer Address 2:</strong>
                     <input type="text" name="customer_address2" id="customer_address2" class="form-control input_customer_address2" value="{{ isset($customer) ? $customer->city : '' }}" required>
                  </div>
                  <div class="form-group">
                     <strong>Customer Pincode:</strong>
                     <input type="number" name="customer_pincode" id="customer_pincode" class="form-control input_customer_pincode" value="{{ isset($customer) ? $customer->pincode : ''}}" max="999999" required>
                  </div>
                  <div class="form-group mb-2">
                     <strong>Company Name:</strong>
                     <input type="text" name="company_name" id="company_name" class="form-control input_customer_pincode"  value="{{ isset($customer) ? $customer->to_company_name:''}}">
                     <span class="form-error"></span>
                  </div>
               </div>
               <div class="col-md-12">
                    <div class="row">
                      <div class="col">
                        <div class="form-group">
                           <strong>Actual Weight:</strong>
                           <input type="number" name="actual_weight" id="actual_weight" class="form-control input_actual_weight" value="1" step="0.01" required>
                        </div>
                      </div>  
                       <div class="col">
                          <div class="form-group">
                             <strong>Length:</strong>
                             <input type="number" name="box_length" id="box_length" class="form-control input_box_length" placeholder="1.0" value="10" step="0.1" max="1000" required>
                          </div>
                       </div>
                       <div class="col">
                          <div class="form-group">
                             <strong>Width:</strong>
                             <input type="number" name="box_width" id="box_width" class="form-control input_box_width" placeholder="1.0" value="10" step="0.1" max="1000" required>
                          </div>
                       </div>
                       <div class="col">
                          <div class="form-group">
                             <strong>Height:</strong>
                             <input type="number" name="box_height" id="box_height" class="form-control input_box_height" placeholder="1.0" value="10" step="0.1" max="1000" required>
                          </div>
                       </div>
                    </div>
                    <div class="row">
                      <div class="col">
                          <div class="form-group">
                             <strong>Notes:</strong>
                             <input type="text" name="notes" id="notes" class="form-control input_box_notes" value="" required>
                          </div>
                       </div>
                    </div>
                    <div class="row">
                       <div class="col">
                          <div class="form-group">
                             <strong>Amount:</strong>
                             <input type="number" name="amount" id="amount" class="form-control input_amount" value="" required>
                          </div>
                       </div>
                       <div class="col">
                          <div class="form-group">
                             <strong>Currency:</strong>
                             <input type="text" name="currency" id="currency" class="form-control input_currency" value="" required>
                          </div>
                       </div>
                       <div class="col">
                          <div class="form-group">
                             <strong>Pick Up Date and Time</strong>
                             <div class='input-group date' id='pickup-datetime'>
                                <input type='text' class="form-control input_pickup_time" name="pickup_time" id="pickup_time" value="{{ date('Y-m-d H:i') }}" required />
                                <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                             </div>
                          </div>
                       </div>
                       <div class="col">
                          <div class="form-group">
                             <strong>Duty Mode:</strong>
                             <select name="duty_mode" class="form-control">
                                <option value="DAP">Delivered at place</option>
                                <option value="DDP">Delivered duty paid</option>
                             </select>
                          </div>
                       </div>
                    </div>
                    <div class="row">
                       
                    </div>
                    <div class="row">
                       <div class="col">
                          <div class="form-group">
                             <strong>Description:</strong>
                             <input type="text" name="description" id="description" class="form-control description" value="" required>
                          </div>
                       </div>
                    </div>
               </div> 
               <div class="col-md-12 card product-items-list">
                  
               </div>
               <div class="col-md-12">
                  <button type="button" class="btn btn-secondary btn-add-items">Add Items</button>
               </div>
            </div>
            <div class="modal-footer">
               <div class="row price-break-down" style="width: 100%">
                  
               </div>
            </div>
            <div class="modal-footer">
               <div class="row">
                  <button type="button" style="margin-top: 5px;" class="btn btn-default" data-dismiss="modal">Close</button>
                  <button type="button" style="margin-top: 5px;" class="btn btn-secondary btn-rate-request">Calculate Rate Request</button>
                  <button type="button" style="margin-top: 5px;" class="btn btn-secondary btn-create-shipment-request">Genarate Shimpment on DHL</button>
                  <button type="submit" style="margin-top: 5px;" class="btn btn-secondary">Update and Generate</button>
               </div>
            </div>
         </form>
      </div>
   </div>
</div>
<script></script>