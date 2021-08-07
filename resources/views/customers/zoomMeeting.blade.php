<div id="zoomModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Create Meeting</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <input type="hidden" value="" class="" id="user__id" name="user_id">
                <input type="hidden" value="" class="" id="user__type" name="user_id">
                <div class="form-group">
                    <label for="meeting_topic">Meeting Topic</label>
                    <input type="text" name="meeting_topic" id="meeting_topic" class="form-control"/>
                </div>
                <div class="form-group">
                    <button class="btn btn-secondary save-meeting">Save</button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<input type="hidden" value="{{action('Meeting\ZoomMeetingController@createMeeting')}}" class="" id="meetingUrl">
<input type="hidden" value="{{ csrf_token() }}" class="" id="csrfToken">
<div id="zoomMeetingModal" class="modal fade" role="dialog">
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