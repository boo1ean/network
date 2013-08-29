$(document).ready(function() {
    calendarReady();

    $('.panel-body:has(.panel-body-hidden-child)').hide();
});

$(document).on({
    click: function (event) {

        if(!confirm('Do you really wanna removing this user from event?')) {
            return false;
        }

        $(this).parent().remove();
        return false;
    }
},'#member-event-list .glyphicon-remove');

function showModal(html, id) {
    // Get modal
    var modal = $('#myModal');
    modal.html(html);
    // Set colorpicker
    $('#colorpicker').spectrum({
        showButtons: false,
        showPalette: true,
        palette: [
            ['#d17875', '#b06f9f'],
            ['#7c44b0', '#6d70b0'],
            ['#44a9b0', '#6cb080'],
            ['#85b044', '#b09a44'],
            ['#362ea6', '#b8402b'],
            ['#3e9e18', '#ff0000']
        ]
    });

    var members_list = new members('new-member-list');

    members_list.domObj.typeahead({
        items:  5,
        source: function() {
            if(!members_list.member_source.length){
                $.post('/calendar/member-not-subscribe-list', {id_event: id})
                    .done(function(response) {
                        var data = $.parseJSON(response);

                        $.each(data, function (i, item) {
                            members_list.member_source.push(item['name']);
                        });

                        members_list.member_full = data;
                    }).error(function(error) {
                        alert(error.statusText);
                    });
            }
            this.process(members_list.member_source);
        },
        updater: function(item_current) {
            $.each(members_list.member_full, function (i, item) {

                if(item && item['name'] == item_current) {
                    members_list.member_source.splice(i, 1);
                    members_list.member_full.splice(i, 1);

                    var html = '<div class="btn-group navbar-btn" data-id="' + item['id'] + '" style="display: none;" >' +
                        '<input name="invitations[' + item['id'] + ']" type="checkbox" style="display: none;" checked="checked" value="' + item['id'] + '" />' +
                        '<a href="/user/profile/' + item['id'] + '" target="_blank" class="btn btn-xs btn-success">' + item_current + '</a>' +
                        '<button class="btn btn-success glyphicon glyphicon-remove" ' +
                        'data-action="none" style="top:0px;height:22px;"> </button></div>';

                    $('#member-event-list').append(html).find('div[style="display: none;"]').show('blind');

                }
            });
        }
    });

    // Show modal
    modal.modal('show');
}

function calendarReady() {

    $('.panel-body:has(.panel-body-hidden-child)').hide();

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
        contentHeight: 600,
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
        select: function(startDate, endDate, allDay, jsEvent, view) {
            // getTime gets timestamp in milliseconds
            // php use timestamp in seconds
            // so we have to divide js timestamp into 1000 to get correct php timestamp
            $.ajax({
                url:  '/calendar/addevent',
                type: 'POST',
                data: {
                    start: startDate.getTime() / 1000,
                    end: endDate.getTime() / 1000
                },
                success: function(html) {
                    showModal(html, 0);
                }
            });
        },
        eventClick: function(event, element) {
            $.ajax({
                url:  '/calendar/eventpage',
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
                url:  '/calendar/dropevent',
                type: 'POST',
                data: {
                    id: event.id,
                    start: event.start,
                    end: event.end
                },
                success: function(html) {
                    //$('body').html(html);
                }
            });
        },
        eventResize: function(event,dayDelta,minuteDelta,revertFunc) {
            $.ajax({
                url:  '/calendar/dropevent',
                type: 'POST',
                data: {
                    id: event.id,
                    start: event.start,
                    end: event.end
                },
                success: function(html) {
                    //$('body').html(html);
                }
            });
        }
    });

}


