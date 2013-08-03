$(function(){

    /**
     * Block/unblock user
     */
    $('button[name="user-block"]').click(function (event) {
        var obj        = $(this);
        var id         = obj.attr('data-id');
        var is_block   = obj.hasClass('btn-warning') ? 1 : 0;
        var first_name = $('#'+id+'_first_name').text().toString().trim();
        var last_name  = $('#'+id+'_last_name').text().toString().trim();
        if(confirm('Do you really wanna '+(is_block ? 'block' : 'unblock')+' this user: "'+first_name+' '+last_name+'"?')) {
            $.ajax({
                url:  '/admin/user-block',
                type: 'POST',
                data: {
                    id_edit:  id,
                    is_block: is_block
                },
                success: function(response, textStatus) {

                    if(is_block){
                        obj.removeClass('btn-warning')
                           .addClass('btn-info')
                           .html('Unblock account');
                    } else {
                        obj.removeClass('btn-info')
                           .addClass('btn-warning')
                           .html('Block account');
                    }
                }
            });
        }
    });

    /**
     * Delete user
     */
    $('button[name="user-delete"]').click(function (event) {
        var obj        = $(this);
        var id         = obj.attr('data-id');
        var first_name = $('#'+id+'_first_name').text().toString().trim();
        var last_name  = $('#'+id+'_last_name').text().toString().trim();
        if(confirm('Do you really wanna delete forever this user: "'+first_name+' '+last_name+'"?')) {
            $.ajax({
                url:  '/admin/user-delete',
                type: 'POST',
                data: {
                    id_edit: id
                },
                success: function(response, textStatus) {
                    if('ok' == response) {
                        var row = obj.parent().parent();
                        row.hide('blind');
                        setTimeout(function(){
                            row.remove();
                        },1000)
                    } else {
                        alert('Sorry, we have some problems with deleting this user. Reload this page and try again');
                    }
                }
            });
        }
    });

    /**
     * load data for edit user
     */
    $('button[name="user-edit"]').click(function (event) {
        $.ajax({
            url:  '/admin/user-edit',
            type: 'POST',
            data: {
              id_edit: $(this).attr('data-id')
            },

            success: function(response, textStatus) {
                $('#user-modal').html(response);
            }
        });
    });

    /**
     * save data of user
     */
    $('body').on('click','button[name="user-save"]', function (event) {
        var obj         = $(this);
        var id          = obj.attr('data-id');
        var data        = obj.parents('form').serialize();
        data['id_edit'] = id;
        var errors = new messages();
        $.ajax({
            url:  '/admin/user-save',
            type: 'POST',
            data: data,
            beforeSend: function(){
                errors.hideErrors(obj.parents('form'));
            },
            success: function(response, textStatus) {
                var result = $.parseJSON(response);

                if('error' == result['status']) {
                    for(var i in result['errors']){
                        errors.showErrors(i, result['errors'][i])
                    }
                } else {
                    for(var i in result['user']) {
                        $('#'+id+'_'+i).html(result['user'][i]);
                    }
                    $('#user-modal').slideUp();
                    $('div.modal-backdrop').remove();
                }
            }
        });
        return false;
    });
});