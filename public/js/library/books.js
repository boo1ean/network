$(document).ready(function() {
    tagsReady();
});

function tagsReady() {
    $('#tags').tagsInput();

    $('.ebook').hide();
}

function sortBooks(obj) {
    obj = $(obj);
    var id = obj.attr('id');

    //unselect all tags
    $('p a.label-warning').removeClass('label label-warning').addClass('label label-info');

    //status before click
    var id_status = $('ul.nav-pills li.active a').attr('id');

    switch(id) {
        case 'all':
            $('li:has(a#available, a#taken)').removeClass('active');
            $('li:has(a#all)').addClass('active');
            break;
        case 'available':
            $('li:has(a#all, a#taken)').removeClass('active');
            $('li:has(a#available)').addClass('active');
            break;
        case 'taken':
            $('li:has(a#available, a#all)').removeClass('active');
            $('li:has(a#taken)').addClass('active');
            break;
    }

    $('li:has(a#author, a#title)').removeClass('active');

    switch(id) {
        case 'title':
            $('li:has(a#author)').removeClass('active');
            $('li:has(a#title)').addClass('active');
            break;
        case 'author':
            $('li:has(a#title)').removeClass('active');
            $('li:has(a#author)').addClass('active');
            break;
    }

    var sortBy = $('li.active:has(a#title, a#author)').find('a#title, a#author').attr('id');

    if (sortBy === undefined) {
        //if sort param undefined
        id_status = id;
        id = null;
    }

    $.ajax({
        url:  'books',
        type: 'POST',
        data: {
            id_status: id_status,
            id_param: id,
            partial: 'yes'
        },
        beforeSend: function() {
            $('body').addClass('mycontainer');
        },
        success: function(html) {
            $('.bookslist').html(html);
            $('body').removeClass('mycontainer');
        }
    });
}

function showByTags(obj) {
    obj = $(obj);
    var id = obj.attr('id');

    $('li:has(a#available, a#taken)').removeClass('active');
    $('li:has(a#all)').addClass('active');

    $('p a#'+id+'').toggleClass('label label-info label label-warning');

    $('li:has(a#author, a#title)').removeClass('active');

    //massive of selected tags
    var tags_arr = new Array();
    $('p a.label-warning').each(function(index,el) {
        tags_arr.push($(el).attr('id'));
    });

    $.ajax({
        url:  'books',
        type: 'POST',
        data: {
            sel_tags: tags_arr,
            partial: 'yes'
        },
        beforeSend: function() {
            $('body').addClass('mycontainer');
        },
        success: function(html) {
            $('.bookslist').html(html);
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
