
<div id="add_order" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{  route('order.store') }}" method="POST" enctype="multipart/form-data" class="add_order_frm" data-reload='1'>
                <input type="hidden" name="redirect_back" value="{{ isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '' }}">
                <div class="modal-header">
                    <h2>Add Order</h2>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        @csrf
                        <input type="hidden" name="customer_id" value="" class="customer_id">
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <strong>Order Date:</strong>
                                    <input type="date" class="form-control datepicker-block" name="order_date" placeholder="Order Date" value="{{date('Y-m-d')}}" required />
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <strong>Date of Delivery:</strong>
                                    <input type="date" class="form-control datepicker-block" name="date_of_delivery" placeholder="Date of Delivery" value="" />
                                </div>
                            </div>    
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <strong>Advance Amount:</strong>
                                    <input type="text" class="form-control" name="advance_detail" placeholder="Advance Detail" value="" />
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <strong>Advance Date:</strong>
                                    <input type="date" class="form-control datepicker-block" name="advance_date" placeholder="Advance Date" value="" />
                                </div>
                            </div>    
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <strong>Balance Amount:</strong>
                                    <input type="text" class="form-control" name="balance_amount" placeholder="Balance Amount" value="" />
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <strong> Status :</strong>
                                    <?php
                                        $orderStatus = new \App\ReadOnly\OrderStatus;
                                        echo Form::select('order_status_id',$orderStatus->all(), 2, ['placeholder' => 'Select a status','class' => 'form-control']);
                                    ?>
                                </div>
                            </div>    
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <strong> Payment Mode :</strong>
                                    <?php
                                        $paymentModes = new \App\ReadOnly\PaymentModes(); 
                                        echo Form::select('payment_mode',$paymentModes->all(), null, ['placeholder' => 'Select a mode','class' => 'form-control']);
                                    ?>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <strong>Received By:</strong>
                                    <input type="text" class="form-control" name="received_by" placeholder="Received By" /> 
                                </div>
                            </div> 
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <strong>Note if any:</strong>
                                    <input type="text" class="form-control" name="note_if_any" placeholder="Note if any" /> 
                                </div>
                            </div> 
                        </div> 
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-default btn-event-order">Add</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
