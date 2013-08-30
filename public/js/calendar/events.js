$(function(){

    /*
     * Add event link click from calendar and events pages
     */
    $(document).on({
        click: function() {
            $.ajax({
                url:  'addevent',
                type: 'POST',
                success: function(html) {
                    showModal(html, 0);
                }
            });
        }
    }, 'a[name="event-add"]');

    /*
     * Edit event link click from calendar and events pages
     */
    $(document).on({
        click: function() {
            var obj = $(this);
            var id = obj.attr('event-id');

            $.ajax({
                url:  '/calendar/edit-event',
                type: 'POST',
                data: {
                    event_id:  id
                },
                success: function(html) {
                    showModal(html, id);
                }
            });
        }
    }, 'a[name="event-edit"]');

    /*
     * Save event button click after add or edit event
     * name="event-add"
     * name="event-edit"
     */
    $('body').on('click','button[name^="event"]', function (event) {
        var obj = $(this);
        var data = obj.parents('form').serialize();
        var errors = new messages();

        $.ajax({
            url:  '/calendar/save-event',
            type: 'POST',
            data: data,
            beforeSend: function() {
                errors.hideErrors(obj.parents('form'));
            },
            success: function(response) {
                var result = $.parseJSON(response);

                if ('error' == result['status']) {
                    for (var i in result['errors']) {
                        errors.showErrors(i, result['errors'][i])
                    }
                } else {
                    $('#myModal').modal('hide');

                    if (window.location.pathname.substring(0,20) == '/calendar/eventpage/') {
                        window.location.replace(window.location.pathname.substring(20,24));
                    } else if (window.location.pathname.substring(0,16) == '/calendar/events') {
                        window.location.replace(window.location.pathname.substring(0,16));
                    } else {
                        window.location.replace('/calendar/calendar');
                    }
                }
            }
        });
        return false;
    });

    /*
     * Delete event button click from modal window
     */
    $('body').on('click','button[name="event-delete"]', function (event) {
        $.ajax({
            url:  '/calendar/deleteevent',
            type: 'POST',
            data: {
                id:  $(this).attr('event-id')
            },
            success: function(html) {
                window.location.replace('/calendar/calendar');
            }
        });
    });

    /*
     * Delete event link click from event list or eventpage
     */
    $(document).on({
        click: function() {
            if(confirm('Do you really wanna delete this event ?')) {
                $.ajax({
                    url:  '/calendar/deleteevent',
                    type: 'POST',
                    data: {
                        id:  $(this).attr('event-id')
                    },
                    beforeSend: function() {
                        $('body').addClass('mycontainer');
                    },
                    success: function(html) {
                        $('body').removeClass('mycontainer');

                        if (window.location.pathname.substring(0,20) == '/calendar/eventpage/') {
                            window.location.replace('/calendar/calendar');
                        } else if (window.location.pathname.substring(0,16) == '/calendar/events') {
                            window.location.replace(window.location.pathname.substring(0,16));
                        } else {
                            window.location.replace('/calendar/calendar');
                        }
                    }
                });
            }
        }
    }, 'a[name="event-delete"]');

    /*
     * Post comment to event
     */
    $(document).on({
        click: function() {
            var data = $(this).parents('form').serialize();

            var comment = $('#comment_text').val();

            if (comment == '' || comment === undefined || !/\w+/.exec(comment)) {
                return false;
            }

            $.ajax({
                url:  '/calendar/comment',
                type: 'POST',
                data: data,
                success: function(html) {
                    $('.event_comments').html(html);
                }
            });
            return false;
        }
    }, 'button[name="post-comment"]');

    /*
     * Filter by event type
     */
    $(document).on({
        click: function() {
            var filter_id = $(this).attr('id');

            if (filter_id == 'default') {
                $('li:has(a#birthday, a#corpevent, a#holiday, a#dayoff)').removeClass('active');
                $('li:has(a[id='+filter_id+'])').addClass('active');
            } else {
                $('li:has(a#default)').removeClass('active');
                $('li:has(a[id='+filter_id+'])').toggleClass('active');
            }

            //massive of selected filters
            var actives_arr = new Array();
            $('li.active:has(a[name=event-filter])').each(function(index,el) {
                if($(el).children('a').attr('id') !== 'default') {
                    actives_arr.push($(el).children('a').attr('id'));
                }
            });

            if (actives_arr.length == 0) {
                $('li:has(a#default)').addClass('active');
            }

            $.ajax({
                url:  '/calendar/events',
                type: 'POST',
                data: {
                    filter_id:  filter_id,
                    sel_filters: actives_arr
                },
                success: function(html) {
                    $('#filtered_events').html(html);

                    $('.panel-body:has(.panel-body-hidden-child)').hide();
                }
            });
            return false;
        }
    }, 'a[name="event-filter"]');

    /*
     * Toggle events in agenda
     */
    $(document).on({
        click: function(event) {

            if (event.target.nodeName === "SPAN") {
                return false;
            }

            var body_id = $(this).children('.panel-head-hidden-child').text();

            $('.panel-body:has(.panel-body-hidden-child:contains('+body_id+'))').toggle('show hide');
        }
    }, '.panel-heading:has(.panel-head-hidden-child)');

    /*
     * Choose type of event - change color
     */
    $(document).on({
        click: function() {
            var color;

            switch ($(':selected').val()) {
                case '0':
                    color = '#d17875';
                    break;
                case '1':
                    color = '#b06f9f';
                    break;
                case '2':
                    color = '#6cb080';
                    break;
                case '3':
                    color = '#6d70b0';
                    break;
                default:
                    color = '#d17875';
                    break;
            }

            $('#colorpicker').val(color);

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
                ],
                color: color
            });
        }
    }, '.row .drop :input');

});