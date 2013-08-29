$(function(){
    $(document).on({
        click: function() {
            if($(this).val() == 2) {
                $('#e-book-load').show();
                $('#e-book-state').show();
            } else {
                $('#e-book-load').hide();
                $('#e-book-state').hide();
            }
        }
    }, '#book-types input[type="radio"]');

    /**
     * Delete book
     */
    $(document).on({
        click: function() {
            var obj    = $(this);
            var id     = obj.attr('data-id');
            var author = $('#' + id + '_author').text().toString().trim();
            var title  = $('#' + id + '_title').text().toString().trim();
            if (confirm('Do you really wanna delete forever this book: "' + title + ' (' + author + ')"?')) {
                $.ajax({
                    url:  '/admin/library-book-delete',
                    type: 'POST',
                    data: {
                        id_edit: id
                    },
                    success: function(response, textStatus) {
                        if ('ok' == response) {
                            var row = obj.parent().parent();
                            row.hide('blind');
                            setTimeout(function() {
                                row.remove();
                            },1000)
                        } else {
                            alert('Sorry, we have some problems with deleting this book. Reload this page and try again');
                        }
                    },
                    error: function(error) {
                        alert(error.statusText);
                    }
                });
            }
        }
    }, 'button[name="book-delete"]');

    /**
     * load data for edit book
     */
    $(document).on({
        click: function() {
            $.ajax({
                url:  '/admin/library-book-edit',
                type: 'POST',
                data: {
                    id_edit: $(this).attr('data-id')
                },
                success: function(response, textStatus) {
                    var result = $.parseJSON(response);

                    if('ok' == result['status']) {
                        $('#book-modal').html(result['html']).modal('show');
                        $('#tags').tagsInput();
                        $.ajax_upload($('#e-book-load'), {
                            action: '/admin/library-book-upload',
                            name: 'ebook',
                            onSubmit : function (file, ext) {
                                var allowed = ['fb2', 'txt', 'doc', 'docx', 'pdf', 'djvu'];

                                if ($.inArray(ext, allowed ) == -1) {
                                    alert('Invalid format. The only valid: *.fb2, *.txt, *.pdf, *.djvu, *.doc, *.docx');
                                    return false;
                                }

                                $('#e-book-state').val(file + ' loading...');
                                $('button[name="book-save"]').addClass('disabled');
                            },
                            onComplete: function (file, response) {
                                var result = $.parseJSON(response);

                                if('ok' == result['status']) {
                                    $('#e-book-state').val(file + ' successfully loaded');
                                    $('#e-book-load').attr('data-id', result['resource_id']);
                                } else {
                                    alert('We have some problems with uploading this file. Please try again.');
                                    $('#e-book-state').val('error ');
                                }

                                $('button[name="book-save"]').removeClass('disabled');
                            }
                        });
                    } else if ('redirect' == result['status']) {
                        window.location = result['redirect'];
                    }

                },
                error: function(error) {
                    alert(error.statusText);
                }
            });
        }
    }, 'button[name="book-edit"], #book-create');

    /**
     * Give the a book
     */
    $(document).on({
        click: function() {
            var obj      = $(this);
            var active   = obj.parents('form').find('div.active');
            var book_id  = obj.attr('data-id');
            var user_id  = active.attr('data-id');
            var returned = active.find('input').val();


            if('undefined' == typeof user_id) {
                alert('Select the user to give the a book!');
                return false;
            }

            var user = $('#' + user_id + '_user').text().toString().trim();
            if (confirm('Do you really want to give this book for ' + user + '?')) {
                $.ajax({
                    url:  '/admin/library-book-give',
                    type: 'POST',
                    data: {
                        book_id:  book_id,
                        returned: returned,
                        user_id:  user_id
                    },
                    beforeSend: function() {
                        $('#error').hide();
                    },
                    success: function(response, textStatus) {
                        var result = $.parseJSON(response);

                        if ('ok' == result['status']) {
                            var row = $('#' + book_id);
                            row.removeClass('warning').addClass('danger');
                            row.find('button[name="book-queue"]').remove();

                            row.find('[name="book-delete"]').after(
                                '<button class="col-sm-offset-1 btn btn-sm btn-primary" data-id-book="' + book_id + '"' +
                                    'data-id-user="' + user_id + '" name="book-queue"> Return </button>'
                            );

                            $('#book-modal').modal('hide');
                        } else if('error' == result['status']) {
                            for (var i in result['errors']) {
                                if(result['errors'][i]) {
                                    $('#error').html(result['errors'][i]).show();
                                }
                            }
                        } else {
                            window.location = result['redirect'];
                        }
                    },
                    error: function(error) {
                        alert(error.statusText);
                    }
                });
            }
            return false;
        }
    }, 'button[name="book-give"]');

    /**
     * load users list who staying in queue for the book
     */
    $(document).on({
        click: function() {
            $.ajax({
                url:  '/admin/library-book-queue',
                type: 'POST',
                data: {
                    book_id: $(this).attr('data-id')
                },
                success: function(response, textStatus) {
                    var result = $.parseJSON(response);

                    if('ok' == result['status']) {
                        $('#book-modal').html(result['html']).modal('show');
                    } else if ('redirect' == result['status']) {
                        window.location = result['redirect'];
                    }

                },
                error: function(error) {
                    alert(error.statusText);
                }
            });
        }
    }, 'button[name="book-queue"]');

    /**
     * pick user for give book
     */
    $(document).on({
        click: function() {
            var obj      = $(this).parent();
            var date     = new Date();
            var datetime = date.getDate() + '/' + (date.getMonth() + 1) + '/' + date.getFullYear() + ' ' +
                date.getHours() + ':' + date.getMinutes();

            obj.parent().find('.active').removeClass('active').find('.navbar-right').html('');
            obj.addClass('active');
            obj.find('.navbar-right').html(
                '<div class="date-time-picker input-group" style="width:200px;" title="Return date">' +
                '<input data-format="dd/MM/yyyy hh:mm" type="text" class="form-control" value="' + datetime + '"/>' +
                '<span class="input-group-addon add-on"> ' +
                '<i style="color: #000000" class="glyphicon glyphicon-calendar"></i> </span> </div>'
            );
            $('.date-time-picker').datetimepicker();
            return false;
        }
    }, '#user-queue span.cursorOnNoLink');

    /**
     * Return the a book
     */
    $(document).on({
        click: function() {
            var obj      = $(this);
            var book_id  = obj.attr('data-id-book');
            var user_id  = obj.attr('data-id-user');
            var author = $('#' + book_id + '_author').text().toString().trim();
            var title  = $('#' + book_id + '_title').text().toString().trim();

            var user = $('#' + user_id + '_user').text().toString().trim();
            if (confirm('Do you really want return this book: "' + title + ' (' + author + ')"?')) {
                $.ajax({
                    url:  '/admin/library-book-return',
                    type: 'POST',
                    data: {
                        book_id: book_id,
                        user_id: user_id
                    },
                    success: function(response, textStatus) {
                        var result = $.parseJSON(response);

                        if ('ok' == result['status']) {
                            var row = $('#' + book_id);
                            row.removeClass('danger').addClass('ask' == result['book_status'] ? 'warning' : 'success');
                            row.find('button[name="book-return"]').remove();

                            if('ask' == result['book_status']) {
                                row.find('[name="book-delete"]').after(
                                    '<button class="col-sm-offset-1 btn btn-sm btn-primary" data-id="' + book_id + '"' +
                                    'name="book-queue"> Queue</button>'
                                );
                            }

                            $('#book-modal').modal('hide');
                        } else if('error' == result['status']) {
                            alert('Sorry, we have some problems with the return of this book. Reload this page and try again');
                        } else {
                            window.location = result['redirect'];
                        }
                    },
                    error: function(error) {
                        alert(error.statusText);
                    }
                });
            }
            return false;
        }
    }, 'button[name="book-return"]');

    /**
     * save data of the book
     */
    $(document).on({
        click: function (event) {
            var obj         = $(this);
            var id          = obj.attr('data-id');
            var resource_id = $('#e-book-load').attr('data-id');
            var data        = obj.parents('form').serialize();
            data += '&resource_id=' + resource_id;
            data += '&id=' + id;
            var errors = new messages();
            $.ajax({
                url:  '/admin/library-book-save',
                type: 'POST',
                data: data,
                beforeSend: function() {
                    errors.hideErrors(obj.parents('form'));
                },
                success: function(response, textStatus) {
                    var result = $.parseJSON(response);

                    if ('error' == result['status']) {
                        for (var i in result['errors']) {
                            errors.showErrors(i, result['errors'][i])
                        }
                    } else {
                        var row_class;

                        if('E-book' == result['book']['type']) {
                            row_class = 'default';
                        } else {
                            switch (result['book']['status']) {
                                case 'ask':
                                    row_class = 'warning';
                                    break;
                                case 'available':
                                    row_class = 'success';
                                    break;
                                case 'taken':
                                    row_class = 'danger';
                                    break;
                            }
                        }

                        if(0 == id) {
                            var book = result['book'];
                            var html = '<tr id="' + book['id'] + '" class="' + row_class + '"';

                            html += '> <td id="' + book['id'] + '_author"> ' + book['author'] + ' </td>' +
                                 '<td id="' + book['id'] + '_title"> ' + book['title'] + ' </td>' +
                                 '<td id="' + book['id'] + '_type"> ' + book['type'] + ' </td>' +
                                 '<td > <button class="btn btn-sm btn-success" name="book-edit" data-id="' + book['id'] + '">Edit</button>' +
                                 '<button type="submit" class="btn btn-sm btn-danger" name="book-delete" data-id="' + book['id'] + '">Delete</button>';

                            if('E-book' == book['type']) {
                                html += '<a class="col-sm-offset-1 btn btn-sm btn-primary ' + (book['link'] ? '' : 'disabled') + '" ' +
                                    'name="book-download" href="' + (book['link'] ? book['link'] : '#') + '" ' +
                                    'data-id="' + book['id'] + '" target="_blank">Download</a>';
                            }

                            html += '</td> </tr>';
                            $('#recently-added').show().after(html);
                            $('#recently-added-bottom').show();
                        } else {
                            for (var i in result['book']) {
                                $('#' + id + '_' + i).html(result['book'][i]);

                                if('type' == i) {
                                    var parent = $('#' + id + '_' + i).parent();

                                    if('Paper' == result['book'][i]) {
                                        parent.attr('class', '').addClass(row_class);

                                        if(parent.find('[name="book-download"]').length) {
                                            parent.find('[name="book-download"]').remove();
                                        }

                                    } else {
                                        parent.attr('class', '').addClass('default');
                                        parent.find('[name="book-download"]').remove();
                                        parent.find('[name="book-delete"]').after(
                                            '<a href="' + (result['book']['link'] ? result['book']['link'] : '') + '" ' +
                                            'class="col-sm-offset-1 btn btn-sm btn-primary ' +
                                            (result['book']['link'] ? '' : 'disabled') + '" ' +
                                            'name="book-download" target="_blank">Download</a>'
                                        );
                                    }

                                }

                            }
                        }

                        $('#book-modal').modal('hide');
                    }

                },
                error: function(error) {
                    alert(error.statusText);
                }
            });
            return false;
        }
    }, 'button[name="book-save"]');
});