$(document).ready(function() {

    $('#calendar').html('');

    var eee = $('.myevents').text();

    //obj = $(obj);
    //var events_json = obj.attr('events_array');
    var events_js_obj = JSON.parse(eee);

    var date = new Date();
    var d = date.getDate();
    var m = date.getMonth();
    var y = date.getFullYear();

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
            }
        ]
    });

});

