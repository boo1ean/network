$(document).ready(function() {

    $('#calendar').html('');

    var events_json = $('.myevents').text();

    if (events_json !== '') {
        var events_js_obj = JSON.parse(events_json);
    } else {
        var events_js_obj = '';
    }

    var gcal_url = $('.gcal').text();

    /*
    var date = new Date();
    var d = date.getDate();
    var m = date.getMonth();
    var y = date.getFullYear();
    */

    $('#calendar').fullCalendar({
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,basicWeek,basicDay'
        },
        editable: true,
        firstDay: 1,
        eventSources: [
            {
                events: events_js_obj,
                borderColor: 'darkblue',
                textColor: 'red'
            },
            {
                url: gcal_url,
                className: 'gcal-event',
                currentTimezone: 'Europe/Kiev'
            }
        ],
        eventDrop: function(event,dayDelta,minuteDelta,allDay,revertFunc) {
            alert(
                event.title + " was moved " +
                    dayDelta + " days and " +
                    minuteDelta + " minutes."
            );

            //var start_date = event.start.split(' ');
            alert(event.start);

            if (allDay) {
                alert("Event is now all-day");
            }else{
                alert("Event has a time-of-day");
            }

            if (!confirm("Are you sure about this change?")) {
                revertFunc();
            }
            /*
            $.ajax({
                url:  'dropevent',
                type: 'POST',
                data: {
                    id:  id
                },
                beforeSend: function() {
                    $('body').addClass('mycontainer');
                },
                success: function(html) {
                    $('body').html(html);
                    $('body').removeClass('mycontainer');
                }
            });
            */
        }
    });

});

