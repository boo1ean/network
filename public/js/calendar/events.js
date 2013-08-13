$(function(){

    /*
     * Add event click
     */
    $('a[name="event-add"]').click(function (event) {
        $.ajax({
            url:  'addevent',
            type: 'POST',
            /*data: {
                event_id:  id
            },*/
            success: function(html) {
                $('#myModal').html(html);
            }
        });
    });

    /*
     * Edit event click
     */
    $('a[name="event-edit"]').click(function (event) {
        var obj         = $(this);
        var id          = obj.attr('event-id');

        $.ajax({
            url:  'edit-event',
            type: 'POST',
            data: {
                event_id:  id
            },
            success: function(html) {
                $('#myModal').html(html);
            }
        });
    });

    /*
     * Save event click
     */
    $('body').on('click','button[name="event-save"]', function (event) {
        var obj         = $(this);
        var id          = obj.attr('event_id');
        var data        = obj.parents('form').serialize();
        data['event_id'] = id;
        var errors = new messages();

        $.ajax({
            url:  'save-event',
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
                    window.location.replace('calendar');
                }
            }
        });
        return false;
    });

    /*
     * Delete event click
     */
    $('a[name="event-delete"]').click(function (event) {
        if(confirm('Do you really wanna delete this event ?')) {
            $.ajax({
                url:  'deleteevent',
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
    });

});