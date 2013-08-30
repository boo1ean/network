$(document).ready(function() {

    // Add data-pjax to all links after document load
    $('a:not(#userBoxLogout)').attr('data-pjax', '#pjax-container');
    // Make textarea avaliable for files drop
    uploadFiles();

    $(document).pjax('a[data-pjax]', { container: '#pjax-container', timeout: 0})
        .on('pjax:success', function(event) {
            // If current target is calendar, call calendarReady function
            var isCalendar = event.relatedTarget.toString().search("calendar/calendar");
            var isConversation = event.relatedTarget.toString().search("conversation");

            if(isCalendar) {
                calendarReady();
            }

            if(isConversation) {
                uploadFiles();
            }

            /**
             * create typeahead list of the don't subscribed users
             */
            if ($('#not-member-list').length) {
                var tmp = new conversation_list();
                tmp.initTypeahead();
            }

            // Add data-pjax to all links
            $('a:not(#userBoxLogout)').attr('data-pjax', '#pjax-container');
        });
});

var uploadFiles = function() {
    var item = $('#body');
    item.fileUpload({
        url: '/',
        type: 'POST',
        dataType: 'json',
        beforeSend: function () {
            item.addClass('uploading');
        },
        complete: function () {
            item.removeClass('uploading');
        }
    });
};