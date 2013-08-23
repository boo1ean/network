$(document).ready(function() {
    calendarReady();

    $('#myModal').on('shown.bs.modal', function() {
        $('#colorpicker').spectrum({
            showButtons: false,
            showPalette: true,
            palette: [
                ['#d14d4d', '#b04497'],
                ['#7c44b0', '#444db0'],
                ['#44a9b0', '#44b061'],
                ['#85b044', '#b09a44'],
                ['#362ea6', '#b8402b'],
                ['#3e9e18', '#ff0000']
            ]
        });
    });
});

function calendarReady() {

    $('#myModal').on('shown.bs.modal', function() {
        $('#colorpicker').spectrum({
            showButtons: false,
            showPalette: true,
            palette: [
                ['#d14d4d', '#b04497'],
                ['#7c44b0', '#444db0'],
                ['#44a9b0', '#44b061'],
                ['#85b044', '#b09a44'],
                ['#362ea6', '#b8402b'],
                ['#3e9e18', '#ff0000']
            ]
        });
    });

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
            right: 'month, agendaWeek, agendaDay'
        },
        theme: true,
        editable: true,
        eventStartEditable: true,
        eventDurationEditable: true,
        selectable: true,
        firstDay: 1,
        contentHeight: 700,
        aspectRatio: 1.8,
        handleWindowResize: true,
        eventSources: [
            {
                events: events_js_obj,
                textColor: 'white'
            },
            {
                url: gcal_url,
                className: 'gcal-event',
                currentTimezone: 'Europe/Kiev',
                editable: true,
                borderColor: 'white',
                backgroundColor: '#2F4F4F',
                textColor: 'white'
            }
        ],
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
        eventClick: function(event, element) {
            $.ajax({
                url:  'eventpage',
                type: 'POST',
                data: {
                    id: event.id
                },
                success: function(response) {
                    window.location.replace('/calendar/eventpage/' + response);
                }
            });
        },
        eventDrop: function(event,dayDelta,minuteDelta,allDay,revertFunc) {
            $.ajax({
                url:  'dropevent',
                type: 'POST',
                data: {
                    id: event.id,
                    start: event.start,
                    end: event.end
                },
                success: function(html) {
                    $('body').html(html);
                }
            });
        },
        eventResize: function(event,dayDelta,minuteDelta,revertFunc) {
            $.ajax({
                url:  'dropevent',
                type: 'POST',
                data: {
                    id: event.id,
                    start: event.start,
                    end: event.end
                },
                success: function(html) {
                    $('body').html(html);
                }
            });
        }
    });

}


