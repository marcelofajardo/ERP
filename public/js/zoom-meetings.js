$('#start_date_time').datetimepicker({
    format: 'YYYY-MM-DD HH:mm'
});
var offset = Intl.DateTimeFormat().resolvedOptions().timeZone;
// $('#timezone > option').each(function() {
//     var val = $(this).val()
//     if(offset == val){
//         $(this).attr('selected',true);
//     }
// });
$(document).on('click', '.set-meetings', function() {
    let userId = $(this).data('id');
    let userType = $(this).data('type');
    let mettingTitle = (typeof $(this).data('title') != "undefined") ?  $(this).data('title') : "";
    $('#user__id').val(userId);
    $('#user__type').val(userType);
    $('#meeting_topic').val(mettingTitle);

});
$('#zoomModal').on('hidden.bs.modal', function (e) {
    var offset = Intl.DateTimeFormat().resolvedOptions().timeZone;
    $(this)
        .find("input,textarea,select[name=meeting_duration]")
        .val('')
        .end()
        .find("input[type=checkbox], input[type=radio]")
        .prop("checked", "")
        .end()
        .find("select[name=timezone]")
        .val(offset)
        .end();
});
$(document).on('click', '.save-meeting', function () {
    $('.meeting_link').html('');
    let user_id = $('#user__id').val();
    let user_type = $('#user__type').val();
    let meeting_topic = $('#meeting_topic').val();
    let meeting_agenda = $('#meeting_agenda').val();
    let start_date_time = $('#start_date_time').val();
    let meeting_timezone = $('#timezone').val();
    let meeting_duration = $('#meeting_duration').val();
    var meeting_url = $('#meetingUrl').val();
    var csrf_token = $('#csrfToken').val();
    $.ajax({
        url: meeting_url,
        type: 'POST',
        success: function (response) {
            var status = response.success;
            if(false == status){
                toastr['error'](response.data.msg);
            }else{
                $('#zoomModal').modal('toggle');
                window.open(response.data.meeting_link);
                var html = '';
                html += response.data.msg+'<br>';
                html += 'Meeting URL: <a href="'+response.data.meeting_link+'" target="_blank">'+response.data.meeting_link+'</a><br><br>';
                html += '<a class="btn btn-primary" target="_blank" href="'+response.data.start_meeting+'">Start Meeting</a>';
                $('#zoomMeetingModal').modal('toggle');
                $('.meeting_link').html(html);
                toastr['success'](response.data.msg);
            }
        },
        data: {
            user_id: user_id,
            user_type: user_type,
            meeting_topic: meeting_topic,
            meeting_agenda: meeting_agenda,
            start_date_time: start_date_time,
            timezone: meeting_timezone,
            meeting_duration: meeting_duration,
            _token: csrf_token
        },
        beforeSend: function () {
            $(this).text('Loading...');
        }
    }).fail(function (response) {
        toastr['error'](response.responseJSON.message);

    });;
});