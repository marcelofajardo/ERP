<link rel="stylesheet" href="{{ URL::asset('libs/fullcalendar/core/main.css') }}">
<link rel="stylesheet" href="{{ URL::asset('libs/fullcalendar/daygrid/main.css') }}" />
<link rel="stylesheet" href="{{ URL::asset('libs/fullcalendar/timegrid/main.css') }}" />
<link rel="stylesheet" href="{{ URL::asset('css/user-calendar.css') }}" />

<div class="row">
    <div style="text-align: center; background-color: #f1f1f1;">
        <h2>Public Calendar</h2>
        <h3>{{isset($user) ? $user->name : ''}}</h3>
    </div>
</div>

<div id="calendar"></div>


<script type="text/javascript" src="{{ URL::asset('libs/fullcalendar/core/main.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('libs/fullcalendar/daygrid/main.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('libs/fullcalendar/timegrid/main.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('libs/fullcalendar/interaction/main.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        calendar = new FullCalendar.Calendar(calendarEl, {
            plugins: [ 'interaction', 'dayGrid', 'timeGrid', 'list' ],
              header: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
              },
            defaultView: 'dayGridMonth',
            allDaySlot: false,
            eventSources: [{
                url: '/calendar/public/events/{{ $calendarId }}',
                method: 'GET',
                failure: function() {
                    alert('there was an error while fetching events!');
                }
            }]
        });
        calendar.render();
    });
</script>