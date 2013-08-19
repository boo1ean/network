$(function(){
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
            var obj         = $(this);
            var id          = obj.attr('data-id');
            var data        = obj.parents('form').serialize();
            data['id_edit'] = id;
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