function deleteEvent(obj) {
    obj = $(obj);
    var id = obj.attr('event-id');

    if(confirm('Do you really wanna delete this event ?')) {
        $.ajax({
            url:  'deleteevent',
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
    }
}
