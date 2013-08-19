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
    }, 'button[name="book-edit"]');

    /**
     * save data of book
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
                        for (var i in result['book']) {
                            $('#'+id+'_'+i).html(result['book'][i]);
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