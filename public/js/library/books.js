$(function(){
    /**
     * Ask for book
     */
    $(document).on({
        click: function() {
            var obj          = $(this);
            var id           = obj.attr('data-id');
            var title_author = $('#'+id+'-title-author');
            var author       = title_author.find('div').text().toString().trim();
            var title        = title_author.find('b').text().toString().trim();
            if (confirm('Do you really wanna delete forever this book: "' + title + ' (' + author + ')"?')) {
                $.ajax({
                    url:  '/library/ask-for-book',
                    type: 'POST',
                    data: {
                        id_book: id
                    },
                    success: function(response, textStatus) {
                        var result = $.parseJSON(response);

                        if ('ok' == result['status']) {
                            obj.parents('li').removeClass('panel-success').removeClass('panel-success').addClass('panel-warning');
                            obj.remove();
                        } else if('error' == result['status']) {
                            alert(result['errors']['id_book']);
                        } else {
                            window.location = result['redirect'];
                        }
                    },
                    error: function(error) {
                        alert(error.statusText);
                    }
                });
            }
        }
    }, 'button[name="book-ask"]');
});

