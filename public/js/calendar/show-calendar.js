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
        dayClick: function(date) {
            $.ajax({
                url:  'addevent',
                type: 'POST',
                data: {
                    date: date
                },
                success: function(html) {
                    $('#myModal').html(html);
                }
            });
            $('#myModal').modal();
        },
        eventClick: function(event, element) {
            $.ajax({
                url:  'edit-event',
                type: 'POST',
                data: {
                    title: event.title,
                    start: event.start,
                    end: event.end
                },
                success: function(response) {
                    $('#myModal').html(response);
                }
            });

            $('#myModal').modal();
        },
        editable: true,
        selectable: true,
        firstDay: 1,
        eventSources: [
            {
                events: events_js_obj,
                borderColor: '#483D8B',
                backgroundColor: '#483D8B',
                textColor: 'white'
            },
            {
                url: gcal_url,
                className: 'gcal-event',
                currentTimezone: 'Europe/Kiev',
                editable: true,
                borderColor: '#2F4F4F',
                backgroundColor: '#2F4F4F',
                textColor: 'white'
            }
        ],
        select: function(startDate, endDate, allDay, jsEvent, view) {
            $.ajax({
                url:  'addevent',
                type: 'POST',
                data: {
                    start: startDate,
                    end: endDate
                },
                success: function(html) {
                    $('#myModal').html(html);
                }
            });
            $('#myModal').modal();
        },
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

