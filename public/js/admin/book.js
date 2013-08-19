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
                            },
                            onComplete: function (file, response) {
                                var result = $.parseJSON(response);
                                if('ok' == result['status']) {
                                    $('#e-book-state').val(file + ' successfully loaded').attr('data-link', result['link']);
                                } else {
                                    alert('We have some problems with uploading this file. Please try again.');
                                    $('#e-book-state').val('error ');
                                }
                            }
                        });
                    } else if ('redirect' == result['status']) {
                        window.location = result['redirect'];
                    }
                },
                error: function(error) {
                    alert(error);
                }
            });
        }
    }, 'button[name="book-edit"], #book-create');

    /**
     * save data of the book
     */
    $(document).on({
        click: function (event) {
            var obj  = $(this);
            var id   = obj.attr('data-id');
            var link = $('#e-book-state').attr('data-link');
            var data = obj.parents('form').serialize();
            data += '&link_book=' + ('undefined' == link ? '' : link);
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
                        if(0 == id) {
                            var book = result['book'];
                            var html = '<tr id="'+book['id']+'" ';

                            if('E-book' == book['type']) {
                                html += 'class="default"';
                            } else {
                                html += 'available' == book['status'] ? 'class="success"' : 'class="danger"';
                            }

                            html += '> <td id="' + book['id'] + '_author"> ' + book['author'] + ' </td>' +
                                 '<td id="' + book['id'] + '_title"> ' + book['title'] + ' </td>' +
                                 '<td id="' + book['id'] + '_type"> ' + book['type'] + ' </td>' +
                                 '<td > <button class="btn btn-sm btn-success" name="book-edit" data-id="' + book['id'] + '">Edit</button>' +
                                 '<button type="submit" class="btn btn-sm btn-danger" name="book-delete" data-id="' + book['id'] + '">Delete</button>';

                            if('E-book' == book['type']) {
                                var link = 'undefined' == result['book']['link'] ? '#' : result['book']['link'];
                                html += '<a class="btn btn-sm btn-primary ' + ('#' == link ? 'disabled' : '') + '" ' +
                                    'name="book-download" href="' + link + '" ' +
                                    'data-id="' + book['id'] + '" target="_blank">Download</a>';
                            }

                            html += '</td> </tr>';
                            $('#book-table').find('tr').first().after(html);
                        } else {
                            for (var i in result['book']) {
                                $('#'+id+'_'+i).html(result['book'][i]);
                                if('type' == i) {
                                    var parent = $('#'+id+'_'+i).parent();
                                    if('Paper' == result['book'][i]) {
                                        parent.attr('class', '').addClass('available' == result['book']['status'] ? 'success' : 'danger');

                                        if(parent.find('[name="book-download"]').length) {
                                            parent.find('[name="book-download"]').remove();
                                        }
                                    } else {
                                        parent.attr('class', '').addClass('default');

                                        if(!parent.find('[name="book-download"]').length) {
                                            var link = 'undefined' == result['book']['link'] ? '#' : result['book']['link'];
                                            parent.find('[name="book-delete"]').after(
                                                '<a href="' + link + '" class="btn btn-sm btn-primary ' +
                                                ('#' == link ? 'disabled' : '') + '" ' +
                                                'name="book-download" target="_blank">Download</a>'
                                            );
                                        }
                                    }
                                }
                            }
                        }
                        $('#book-modal').modal('hide');
                    }
                },
                error: function(error) {
                    alert(error);
                }
            });
            return false;
        }
    }, 'button[name="book-save"]');
});