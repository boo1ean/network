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
                    $('#myModal').html(html);
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
                    $('#myModal').html(html);
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
                        $('body').html(html);
                        $('body').removeClass('mycontainer');
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

});