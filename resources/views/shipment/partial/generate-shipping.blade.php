<div id="addShipment" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content ">
            <form action="javascript:void(0)" id="generate-shipment-form" method="POST">
                <input type="hidden" name="order_id" value="{{ isset($id) ? $id : '' }}">
                <div class="modal-header">
                    <h4 class="modal-title">Generate AWB</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                
                <div class="modal-body">
                <div class="col-md-4">
                        <b>From</b>
                    </div>
                    <div class="col-md-3 col-md-offset-1">
                        <button type="button" class="btn btn-sm btn-primary" id="swtichForm">Switch</button>
                    </div>
                    <div class="col-md-4">
                        <b>To</b>
                    </div>
                    
                    <div class="form-group mb-2 any-message">

                    </div>
                    
                    <div class="col-md-6">
                    <div class="form-group" id="div_from_customer_name">
                        <strong>Customer Name:</strong>
                        <select class="form-control" name="from_customer_id" id="from_customer_id">
                            <option value="{{$fromdatadefault['person_name']}}">{{$fromdatadefault['person_name']}}</option>
                        </select>
                        <span class="form-error"></span>
                    </div>
                    <div class="form-group mb-2">
                        <strong>Customer City:</strong>
                        <input type="text" name="from_customer_city" id="from_customer_city" class="form-control input_customer_city" value="{{$fromdatadefault['city']}}" >
                        <span class="form-error"></span>
                    </div>
                    <div class="form-group mb-2">
                        <strong>Customer Country (ISO 2):</strong>
                        <select name="from_customer_country" id="from_customer_country" style="text-transform: capitalize" class="form-control input_customer_country">
                            <option value="" selected>Select Country</option>
                            @if(isset($countries) && count($countries))
                                @foreach($countries as $key=>$country)
                                    <option value="{{$key}}">{{ ucfirst(strtolower($country['name'])) }}</option>
                                @endforeach
                            @endif
                        </select>
                        <span class="form-error"></span>
                    </div>
                    <div class="form-group mb-2">
                        <strong>Customer Phone:</strong>
                        <input type="number" name="from_customer_phone" id="from_customer_phone" class="form-control input_customer_phone" value="{{$fromdatadefault['phone']}}">
                        <span class="form-error"></span>

                    </div>

                    <div class="form-group mb-2">
                        <strong>Customer Address 1:</strong>
                        <input type="text" name="from_customer_address1" id="from_customer_address1" class="form-control input_customer_address1" value="{{$fromdatadefault['street']}}">
                        <span class="form-error"></span>

                    </div>

                    <div class="form-group mb-2">
                        <strong>Customer Address 2:</strong>
                        <input type="text" name="from_customer_address2" id="from_customer_address2" class="form-control input_customer_address2" >
                        <span class="form-error"></span>

                    </div>

                    <div class="form-group mb-2">
                        <strong>Customer Pincode:</strong>
                        <input type="number" name="from_customer_pincode" id="from_customer_pincode"class="form-control input_customer_pincode" max="999999" value="{{$fromdatadefault['postal_code']}}">
                        <span class="form-error"></span>

                    </div>
                    <div class="form-group mb-2">
                        <strong>Company Name:</strong>
                        <input type="text" name="from_company_name" id="from_company_name" class="form-control input_customer_pincode"  value="">
                        <span class="form-error"></span>
                    </div>
                    <?php /* ?>
                    <div class="form-group mb-2">
                        <strong>Actual Weight:</strong>
                        <input type="number" name="from_actual_weight" id="from_actual_weight" class="form-control input_actual_weight" value="1" step="0.01" >
                        <span class="form-error"></span>

                    </div>

                    <div class="row">
                        <div class="col">
                            <div class="form-group mb-2">
                                <strong>Length:</strong>
                                <input type="number" name="from_box_length" id="from_box_length" class="form-control input_box_length" placeholder="1.0" value="" step="0.1" max="1000" >
                                <span class="form-error"></span>

                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group mb-2">
                                <strong>Width:</strong>
                                <input type="number" name="from_box_width" id="from_box_width" class="form-control input_box_width" placeholder="1.0" value="" step="0.1" max="1000" >
                                <span class="form-error"></span>

                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group mb-2">
                                <strong>Height:</strong>
                                <input type="number" name="from_box_height" id="from_box_height" class="form-control input_box_height" placeholder="1.0" value="" step="0.1" max="1000" >
                                <span class="form-error"></span>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group mb-2">
                                <strong>Amount:</strong>
                                <input type="number" name="from_amount" id="from_amount" class="form-control input_amount" value="" >
                                <span class="form-error"></span>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group mb-2">
                                <strong>Currency:</strong>
                                <select name="from_currency" id="from_currency" class="form-control input_currency">
                                    <option selected>USD</option>
                                    <option>GBP</option>
                                    <option>EURO</option>
                                    <option>AED</option>
                                    <option>JPY</option>
                                    <option>CNY</option>
                                </select>
                                <span class="form-error"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group mb-2">
                                <strong>Pick Up Date and Time</strong>
                                <div class='input-group date' id='pickup-datetime'>
                                    <input type='text' class="form-control input_pickup_time" name="from_pickup_time" id="from_pickup_time" value="{{ date('Y-m-d H:i') }}"  />
                                    <span class="input-group-addon">
                                      <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                                <span class="form-error"></span>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group mb-2">
                                <strong>Service Type</strong>
                                <select name="from_service_type" id="from_service_type" style="text-transform: capitalize" class="form-control input_customer_country">
                                    <option value="" selected>Select Service type</option>
                                    <option value="P">International non-document shipments</option>
                                    <option value="D">International document shipments</option>
                                    <option value="N">Domestic shipments</option>
                                    <option value="U">Intra-Europe shipments</option>
                                </select>
                                <span class="form-error"></span>
                            </div>
                        </div>
                    </div>
                    <?php */ ?>
                </div>


                <div class="col-md-6">
                    <div class="form-group mb-2" id="div_to_customer_name">
                        <strong>Customer Name:</strong>
                        <select class="form-control" name="customer_id" id="customer_id">
                            <option value="">Select Customer</option>
                            @if(isset($customers))
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                @endforeach
                            @endif
                        </select>
                        <span class="form-error"></span>
                    </div>
                    <div class="form-group mb-2">
                        <strong>Customer City:</strong>
                        <input type="text" name="customer_city" id="customer_city" class="form-control input_customer_city" value="" >
                        <span class="form-error"></span>
                    </div>
                    <div class="form-group mb-2">
                        <strong>Customer Country (ISO 2):</strong>
                        <select name="customer_country" id="customer_country" style="text-transform: capitalize" class="form-control input_customer_country">
                            <option value="" selected>Select Country</option>
                            @if(isset($countries) && count($countries))
                                @foreach($countries as $key=>$country)
                                    <option value="{{$key}}">{{ ucfirst(strtolower($country['name'])) }}</option>
                                @endforeach
                            @endif
                        </select>
                        <span class="form-error"></span>
                    </div>
                    <div class="form-group mb-2">
                        <strong>Customer Phone:</strong>
                        <input type="number" name="customer_phone" id="customer_phone" class="form-control input_customer_phone" >
                        <span class="form-error"></span>
                    </div>

                    <div class="form-group mb-2">
                        <strong>Customer Address 1:</strong>
                        <input type="text" name="customer_address1" id="customer_address1" class="form-control input_customer_address1" >
                        <span class="form-error"></span>

                    </div>

                    <div class="form-group mb-2">
                        <strong>Customer Address 2:</strong>
                        <input type="text" name="customer_address2" id="customer_address2" class="form-control input_customer_address2" >
                        <span class="form-error"></span>

                    </div>

                    <div class="form-group mb-2">
                        <strong>Customer Pincode:</strong>
                        <input type="number" name="customer_pincode" id="customer_pincode" class="form-control input_customer_pincode" max="999999" >
                        <span class="form-error"></span>

                    </div>

                    <div class="form-group mb-2">
                        <strong>Company Name:</strong>
                        <input type="text" name="company_name" id="company_name" class="form-control input_customer_pincode"  value="">
                        <span class="form-error"></span>
                    </div>

                    <div class="form-group mb-2">
                        <strong>Actual Weight:</strong>
                        <input type="number" name="actual_weight" id="actual_weight" class="form-control input_actual_weight" value="1" step="0.01" >
                        <span class="form-error"></span>

                    </div>

                    <div class="row">
                        <div class="col">
                            <div class="form-group mb-2">
                                <strong>Length:</strong>
                                <input type="number" name="box_length" id="box_length" class="form-control input_box_length" placeholder="1.0" value="" step="0.1" max="1000" >
                                <span class="form-error"></span>

                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group mb-2">
                                <strong>Width:</strong>
                                <input type="number" name="box_width" id="box_width" class="form-control input_box_width" placeholder="1.0" value="" step="0.1" max="1000" >
                                <span class="form-error"></span>

                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group mb-2">
                                <strong>Height:</strong>
                                <input type="number" name="box_height" id="box_height" class="form-control input_box_height" placeholder="1.0" value="" step="0.1" max="1000" >
                                <span class="form-error"></span>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group mb-2">
                                <strong>Amount:</strong>
                                <input type="number" name="amount" id="amount" class="form-control input_amount" value="" >
                                <span class="form-error"></span>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group mb-2">
                                <strong>Currency:</strong>
                                <select name="currency" id="currency" class="form-control input_currency">
                                    <option selected>USD</option>
                                    <option>GBP</option>
                                    <option>EURO</option>
                                    <option>AED</option>
                                    <option>JPY</option>
                                    <option>CNY</option>
                                </select>
                                <span class="form-error"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group mb-2">
                                <strong>Pick Up Date and Time</strong>
                                <div class='input-group date' id='pickup-datetime'>
                                    <input type='text' class="form-control input_pickup_time" name="pickup_time" id="pickup_time" value="{{ date('Y-m-d H:i') }}"  />
                                    <span class="input-group-addon">
                                      <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                                <span class="form-error"></span>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group mb-2">
                                <strong>Service Type</strong>
                                <select name="service_type" id="service_type" style="text-transform: capitalize" class="form-control input_customer_country">
                                    <option value="" selected>Select Service type</option>
                                    <option value="P">International non-document shipments</option>
                                    <option value="D">International document shipments</option>
                                    <option value="N">Domestic shipments</option>
                                    <option value="U">Intra-Europe shipments</option>
                                </select>
                                <span class="form-error"></span>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>

               
                <div class="modal-footer">
                    <div class="row">
                        <div class="col price-break-down">

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="row">
                        <button type="submit" style="margin-top: 5px;" class="btn btn-secondary btn-create-shipment-request">
                            <i class="fa fa-spinner fa-spin"></i>Create Shipment on DHL</button>
                        {{--<button type="button" style="margin-top: 5px;" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" style="margin-top: 5px;" class="btn btn-secondary btn-rate-request">Calculate Rate Request</button>
                        <button type="submit" style="margin-top: 5px;" class="btn btn-secondary">Update and Generate</button>--}}
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let errorFields = $('#generate-shipment-form .form-error'),
        submitButton = $('.btn-create-shipment-request')[0],
        loaderField = $('.btn-create-shipment-request .fa'),
        anyMessageField = $('.any-message');

    const sendMessages = (e,self) => {
        if (!e.success && e.errors){
            for (let i in e.errors) if (e.errors.hasOwnProperty(i)) {
                if (i === 'order_id') continue
                self.find(`[name=${i}]`).parents('.form-group').find('.form-error').show().text(e.errors[i][0]);
            }
        }else if(!e.success && e.globalErrors){
            let html = '';
            if (typeof e.globalErrors === 'string'){
                html = `<div class="col-lg-12 alert alert-danger">${e.globalErrors}</div>`;
            }else{
                html = '<div class="col-lg-12 alert alert-danger"><ul>';

                for (let i in e.globalErrors) if (e.globalErrors.hasOwnProperty(i)) {
                    html += `<li>${e.globalErrors[i]}</li>`
                }
                html += '</ul></div>'
            }

            anyMessageField.html(html)
        }
        else if(e.success){
            anyMessageField.html('<div class="alert alert-success" role="alert">Shipment created successfully</div>')
        }else{
            let html = `<div class="col-lg-12 alert alert-danger">Something get wrong please try again!</div>`
            anyMessageField.html(html)
        }
    }
    $('#swtichForm').on('click',function(e){
        var from_customer_id=$("#from_customer_id");
        var from_customer_city=$("#from_customer_city");
        var from_customer_country=$("#from_customer_country");
        var from_customer_phone=$("#from_customer_phone");
        var from_customer_address1=$("#from_customer_address1");
        var from_customer_address2=$("#from_customer_address2");
        var from_customer_pincode=$("#from_customer_pincode");
        var from_company_name=$("#from_company_name");
        /* var from_actual_weight=$("#from_actual_weight");
        var from_box_length=$("#from_box_length");
        var from_box_width=$("#from_box_width");
        var from_box_height=$("#from_box_height");
        var from_amount=$("#from_amount");
        var from_currency=$("#from_currency");
        var from_pickup_time=$("#from_pickup_time");
        var from_service_type=$("#from_service_type"); */
        //"TO" section
        var customer_id=$("#customer_id");
        var customer_city=$("#customer_city");
        var customer_country=$("#customer_country");
        var customer_phone=$("#customer_phone");
        var customer_address1=$("#customer_address1");
        var customer_address2=$("#customer_address2");
        var customer_pincode=$("#customer_pincode");
        var company_name=$("#company_name");
        var actual_weight=$("#actual_weight");
        var box_length=$("#box_length");
        var box_width=$("#box_width");
        var box_height=$("#box_height");
        var amount=$("#amount");
        var currency=$("#currency");
        var pickup_time=$("#pickup_time");
        var service_type=$("#service_type");

        /* var pre_from_customer_id=from_customer_id.val();
        from_customer_id.val(customer_id.val());
        customer_id.val(pre_from_customer_id); */
        var pre_from_customer_id_name=from_customer_id.attr('name');
        var pre_from_customer_id_id=from_customer_id.attr('name');
        from_customer_id.attr('name',customer_id.attr('name'));
        from_customer_id.attr('id',customer_id.attr('id'));
        customer_id.attr('name',pre_from_customer_id_name);
        customer_id.attr('id',pre_from_customer_id_id);
        
        var pre_from_customer_name=$("#div_from_customer_name").html();
        $("#div_from_customer_name").html($("#div_to_customer_name").html());
        $("#div_to_customer_name").html(pre_from_customer_name);
        
        var pre_from_customer_city=from_customer_city.val();
        from_customer_city.val(customer_city.val());
        customer_city.val(pre_from_customer_city);

        var pre_from_customer_country=from_customer_country.val();
        from_customer_country.val(customer_country.val());
        customer_country.val(pre_from_customer_country);

        var pre_from_customer_phone=from_customer_phone.val();
        from_customer_phone.val(customer_phone.val());
        customer_phone.val(pre_from_customer_phone);

        var pre_from_customer_address1=from_customer_address1.val();
        from_customer_address1.val(customer_address1.val());
        customer_address1.val(pre_from_customer_address1);

        var pre_from_customer_address2=from_customer_address2.val();
        from_customer_address2.val(customer_address2.val());
        customer_address2.val(pre_from_customer_address2);

        var pre_from_customer_pincode=from_customer_pincode.val();
        from_customer_pincode.val(customer_pincode.val());
        customer_pincode.val(pre_from_customer_pincode);

        var pre_from_company_name=from_company_name.val();
        from_company_name.val(company_name.val());
        company_name.val(pre_from_company_name);

        /* var pre_from_actual_weight=from_actual_weight.val();
        from_actual_weight.val(actual_weight.val());
        actual_weight.val(pre_from_actual_weight);

        var pre_from_box_length=from_box_length.val();
        from_box_length.val(box_length.val());
        box_length.val(pre_from_box_length);

        var pre_from_box_width=from_box_width.val();
        from_box_width.val(box_width.val());
        box_width.val(pre_from_box_width);

        var pre_from_box_height=from_box_height.val();
        from_box_height.val(box_height.val());
        box_height.val(pre_from_box_height);
        
        var pre_from_amount=from_amount.val();
        from_amount.val(amount.val());
        amount.val(pre_from_amount);

        var pre_from_currency=from_currency.val();
        from_currency.val(currency.val());
        currency.val(pre_from_currency);
        
        var pre_from_pickup_time=from_pickup_time.val();
        from_pickup_time.val(pickup_time.val());
        pickup_time.val(pre_from_pickup_time);

        var pre_from_service_type=from_service_type.val();
        from_service_type.val(service_type.val());
        service_type.val(pre_from_service_type); */
    
    });

    $('#generate-shipment-form').on('submit', function(e){
        let self = ($(this)),
            formData = {};
        self.serializeArray().forEach(x => formData[x.name] = x.value);
        errorFields.html('');
        submitButton.disabled = true
        anyMessageField.html('')
        loaderField.show()
        $.ajax({
            url: '{{ route('shipment/generate') }}',
            method: 'post',
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            data: formData,
            success: function(e){
                submitButton.disabled = false
                $('#addShipment').animate({scrollTop: 0}, 'slow')
                sendMessages(e,self)
                loaderField.hide()
            },
            error: function(e){
                $('#addShipment').animate({scrollTop: 0}, 'slow')
                submitButton.disabled = false
                let html = `<div class="col-lg-12 alert alert-danger">Something get wrong please try again!</div>`
                anyMessageField.html(html)
            }
        });
    });
</script>