<!-- Modal -->
<div id="user-event-model" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">User Event</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id="create_event_form" action="/calendar/events" method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="date">Date</label>
                        <input id="date" class="form-control" type="text" />
                    </div>
                    <div class="form-group">
                        <label for="time">Time</label>
                        <input id="time" class="form-control" type="text" />
                    </div>
                    <div class="form-group">
                        <label for="subject">Subject</label>
                        <input id="subject" class="form-control" type="text" />
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <input id="description" class="form-control" type="text" />
                    </div>
                    <div class="form-group">
                        <label for="contacts">Vendors</label>
                        <?php echo Form::select("vendors[]",\App\Vendor::all()->pluck("name","id")->toArray(),null,[
                            "id" => "vendors" , "class" => "form-control select2-vendor", "multiple" => true , "style" => "width:100%"
                        ]); ?>
                    </div>
                    <div class="form-group">
                        <label for="contacts">Contacts (Comma separated for multiple)</label>
                        <input id="contacts" class="form-control" type="text" />
                    </div>
                </div>
                <div class="modal-footer">
                    <input onclick="form_submit()" type="submit" class="btn btn-default" data-dismiss="modal" value="Save" />
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#date').datetimepicker({
            format: 'YYYY-MM-DD'
        });

        $('#time').datetimepicker({
            format: 'HH:mm'
        });

        $(".select2-vendor").select2({ tags : true});

    })

    function form_submit() {

        const modal = document.getElementById('user-event-model');


        const date = modal.querySelector('#date').value;
        const time = modal.querySelector('#time').value;
        const subject = modal.querySelector('#subject').value;
        const description = modal.querySelector('#description').value;
        const contacts = modal.querySelector('#contacts').value;
        const vendors = [];    
        $("#vendors :selected").each(function(){
            vendors.push($(this).val()); 
        });

        $.post(
            '/calendar/events', {
                date,
                time,
                subject,
                description,
                contacts,
                vendors
            },
            function(result) {
                console.log(reuslt);
            }
        );
    }
</script>