$(document).ready(function() {

    $('#calendar').html('');

    var events_json = $('.myevents').text();

    if (events_json !== '') {
        var events_js_obj = JSON.parse(events_json);
    } else {
        var events_js_obj = '';
    }

    var gcal_url = $('.gcal').text();

    $('#calendar').fullCalendar({
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,basicWeek,basicDay'
        },
        dayClick: function() {
            alert('a day has been clicked!');
        },
        eventClick: function(event, element) {
            $.ajax({
                url:  'edit-event',
                type: 'POST',
                data: {
                    title: event.title
                },
                success: function(response) {
                    $('#myModal').html(response);
                }
            });

            $('#myModal').modal();
        },
        editable: true,
        firstDay: 1,
        eventSources: [
            {
                events: events_js_obj,
                borderColor: 'red'
            },
            {
                url: gcal_url,
                className: 'gcal-event',
                currentTimezone: 'Europe/Kiev',
                editable: true,
                borderColor: 'green'
            }
        ],
        eventDrop: function(event,dayDelta,minuteDelta,allDay,revertFunc) {
            $.ajax({
                url:  'dropevent',
                type: 'POST',
                data: {
                    title: event.title,
                    start: event.start,
                    end: event.end
                },
                success: function(html) {
                    $('body').html(html);
                }
            });
        }
    });

});

