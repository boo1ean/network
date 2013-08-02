function sortBooks(obj) {
    obj = $(obj);
    var id = obj.attr('id');

    $.ajax({
        url:  'books',
        type: 'POST',
        data: {
            id: id
        },
        success: function(html) {
            $('body').html(html);
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
        success: function(html) {
            $('body').html(html);
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
        success: function(html) {
            $('body').html(html);
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
            success: function(html) {
                $('body').html(html);
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
