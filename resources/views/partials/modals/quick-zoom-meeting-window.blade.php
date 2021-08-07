<div id="quick-zoomModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Create Meeting</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="meeting_topic">Meeting Topic</label>
                    <input type="text" name="meeting_topic" id="quick_meeting_topic" class="form-control"/>
                </div>
                <div class="form-group">
                    <label for="user_id">Vendor</label>
                    <?php echo Form::select("user_id",\App\Vendor::all()->pluck("name","id")->toArray(),null,[
                        "class" => "form-control select2-vendor" , 
                        "id" => "quick_user_id",
                        "style" => "width:100%;"
                    ]); ?>
                </div>
                <div class="form-group">
                    <button class="btn btn-secondary save-meeting-zoom">Save</button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<input type="hidden" value="{{action('Meeting\ZoomMeetingController@createMeeting')}}" class="" id="quick_meetingUrl">
<input type="hidden" value="{{ csrf_token() }}" class="" id="quick_csrfToken">
<div id="qickZoomMeetingModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Join Meeting</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="meeting_link"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>