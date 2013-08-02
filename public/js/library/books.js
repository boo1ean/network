function sortBooks(obj) {
    obj = $(obj);
    var id = obj.attr('id');

    $.ajax({
        url:  'books',
        type: 'POST',
        data: {
            id: id
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

function takeBook(obj) {
    obj = $(obj);
    var id = obj.attr('id');

    $.ajax({
        url:  'takebook',
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

function untakeBook(obj) {
    obj = $(obj);
    var id = obj.attr('id');

    $.ajax({
        url:  'untakebook',
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

function deleteBook(obj) {
    obj = $(obj);
    var id = obj.attr('book-id');

    if(confirm('Do you really wanna delete this book ?')) {
        $.ajax({
            url:  'deletebook',
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

function showEbookUpload() {
    $('.ebook').show();
}

function showPaperBook() {
    $('.ebook').hide();
}
