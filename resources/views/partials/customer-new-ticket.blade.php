@php
$assigned_to = \App\User::with('roles')->get();
$statuses = \App\TicketStatuses::all();
@endphp
<style>
    #show_tickes_table {
        table-layout: fixed; 
        width: 100%
    }

    #show_tickes_table td {
        word-wrap: break-word;
    }
</style>
<!--form modal-->
<div class="modal fade" id="create-customer-ticket-modal" tabindex="-1" role="dialog" aria-labelledby="create-customer-ticket-modal-label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="create-customer-ticket-modal-label">Create Ticket</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="ticket_form">
                    <input type="hidden" id="ticket_customer_id" name="ticket_customer_id">
                    <input type="hidden" id="source_of_ticket" name="source_of_ticket" value="customer">
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Subject:</label>
                        <input type="text" class="form-control" name="ticket_subject" id="ticket_subject">
                        <span class="text-danger" id="ticket_subject_error"></span>
                    </div>
                    <div class="form-group">
                        <label for="message-text" class="col-form-label">Message:</label>
                        <textarea class="form-control" name="ticket_message" id="ticket_message"></textarea>
                        <span class="text-danger" id="ticket_message_error"></span>
                    </div>
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Assigned To:</label>
                        <select class="form-control" name="ticket_assigned_to" id="ticket_assigned_to">
                            @foreach($assigned_to as $user)
                            @php
                            $selected = '';
                            if($user->id == 6){
                            $selected = 'selected';
                            }
                            @endphp
                            <option value="{{$user->id}}" {{$selected}}>{{$user->name}}</option>
                            @endforeach
                        </select>
                        <span class="text-danger" id="ticket_assigned_to_error"></span>
                    </div>
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Status:</label>
                        <select class="form-control" name="ticket_status_id" id="ticket_status_id">
                            @foreach($statuses as $status)
                            <option value="{{$status->id}}">{{$status->name}}</option>
                            @endforeach
                            <span class="text-danger" id="ticket_status_id_error"></span>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" id="submit_ticket_form" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </div>
</div>

<!--table modal-->
<div class="modal fade" id="show-customer-tickets-modal" tabindex="-1" role="dialog" aria-labelledby="show-customer-tickets-modal-label" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="show-customer-tickets-modal-label">Show Tickets</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-striped-custom" id="show_tickes_table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Subject</th>
                            <th scope="col">Message</th>
                            <th scope="col">Status</th>
                            <th scope="col">Created At</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('body').on('click', '.create-customer-ticket-modal', function () {
            $('#ticket_customer_id').val($(this).attr('data-customer_id'));
        });

        $('#submit_ticket_form').click(function (e) {
            e.preventDefault();
            if ($('#ticket_subject').val() == '') {
                $('#ticket_subject_error').text('Subject filed is required.');
                return false;
            } else {
                $('#ticket_subject_error').text('');
            }
            if ($('#ticket_assigned_to').val() == '') {
                $('#ticket_assigned_to_error').text('Please select assigned to.');
                return false;
            } else {
                $('#ticket_assigned_to_error').text('');
            }
            if ($('#ticket_status_id').val() == '') {
                $('#ticket_status_id_error').text('Please select status.');
                return false;
            } else {
                $('#ticket_status_id_error').text('');
            }

            $.ajax({
                type: "POST",
                url: window.location.origin + '/livechat/create-ticket',
                data: $('#ticket_form').serialize(), // serializes the form's elements.
                success: function (data)
                {
                    if (data.status == 'success') {
                        alert('Ticket created successfully.');
                        $('#ticket_form').trigger("reset");
                        $('#create-customer-ticket-modal').modal('toggle');
                    }
                }, error: function (jqXHR, exception) {
                    var msg = '';
                    if (jqXHR.status === 0) {
                        msg = 'Not connect.\n Verify Network.';
                    } else if (jqXHR.status == 404) {
                        msg = 'Requested page not found. [404]';
                    } else if (jqXHR.status == 500) {
                        msg = 'Internal Server Error [500].';
                    } else if (exception === 'parsererror') {
                        msg = 'Requested JSON parse failed.';
                    } else if (exception === 'timeout') {
                        msg = 'Time out error.';
                    } else if (exception === 'abort') {
                        msg = 'Ajax request aborted.';
                    } else {
                        msg = 'Uncaught Error.\n' + jqXHR.responseText;
                    }
                    alert(msg);
                }
            });

        });

        $('body').on('click', '.show-customer-tickets-modal', function () {
            $.ajax({
                type: "GET",
                url: window.location.origin + '/livechat/get-tickets-data',
                data: {customer_id: $(this).attr('data-customer_id')},
                success: function (response)
                {
                    if (response.status == 'success') {
                        var c = [];
                        $('#show_tickes_table tbody').html('');
                        $.each(response.data, function (i, item) {
                            c.push("<tr><td>" + (parseInt(i) + 1) + "</td>");
                            c.push("<td>" + item.subject + "</td>");
                            c.push("<td>" + item.message + "</td>");
                            c.push("<td>" + item.ticket_status.name + "</td>");
                            c.push("<td>" + item.created_at + "</td></tr>");
                        });
                        $('#show_tickes_table tbody').html(c.join(""));
                    }
                }, error: function (jqXHR, exception) {
                    var msg = '';
                    if (jqXHR.status === 0) {
                        msg = 'Not connect.\n Verify Network.';
                    } else if (jqXHR.status == 404) {
                        msg = 'Requested page not found. [404]';
                    } else if (jqXHR.status == 500) {
                        msg = 'Internal Server Error [500].';
                    } else if (exception === 'parsererror') {
                        msg = 'Requested JSON parse failed.';
                    } else if (exception === 'timeout') {
                        msg = 'Time out error.';
                    } else if (exception === 'abort') {
                        msg = 'Ajax request aborted.';
                    } else {
                        msg = 'Uncaught Error.\n' + jqXHR.responseText;
                    }
                    alert(msg);
                }
            });
        });
    });
</script>