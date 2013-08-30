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
    $('#jquery-wrapped-fine-uploader').fineUploader({
        request: {
            endpoint: 'server/handleUploads'
        },
        text: {
            uploadButton: '<div><span class="glyphicon glyphicon-cloud-upload white"></span> Upload a file</div>'
        },
        deleteFile: {
            enabled: true, // defaults to false
            endpoint: '/my/delete/endpoint'
        },
        template: '<div class="qq-uploader span12">' +
            '<pre class="qq-upload-drop-area span12"><span>{dragZoneText}</span></pre>' +
            '<div class="btn btn-success qq-upload-button" style="width: auto;">{uploadButtonText}</div>' +
            '<span class="qq-drop-processing"><span>{dropProcessingText}</span><span class="qq-drop-processing-spinner"></span></span>' +
            '<ul class="qq-upload-list" style="margin-top: 10px; text-align: center;"></ul>' +
            '</div>',
        classes: {
            success: 'alert alert-success',
            fail: 'alert alert-danger'
        }
    });
};