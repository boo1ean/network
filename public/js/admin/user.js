function userBlock(obj) {
    obj = $(obj);
    var id         = obj.attr('data-id');
    var is_block   = obj.hasClass('btn-warning') ? 1 : 0;
    var first_name = $('#'+id+'_first_name').text().toString().trim();
    var last_name  = $('#'+id+'_last_name').text().toString().trim();
    if(confirm('Do you really wanna '+(is_block ? 'block' : 'unblock')+' this user: "'+first_name+' '+last_name+'"')) {
        $.ajax({
            url:  'user-block',
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
    return false;
}

function userEdit(obj, is_first) {
    var id   = obj.getAttribute('data-id');
    var data = is_first ? {} : JQuerySelectorFind(['input'], 'edit_user');

    data['id_edit']  = id;
    data['is_first'] = is_first;

    $.fancybox.showLoading();
    $.ajax({
        url:  'user-edit',
        type: 'POST',
        data: data,
        success: function(response, textStatus) {
            $.fancybox.hideLoading();
            if(is_first) {
                $('#fancy_frame_user').html(response);
                $.fancybox({
                    autoSize: true,
                    helpers: {
                        overlay: {
                            closeClick: false
                        }
                    },
                    href :  '#fancy_frame_user',
                    margin: 60,
                    title:  '<h2>Edit user data</h2>'
                });
            } else {
                var result = $.parseJSON(response);
                if(matchStr(result['status'], 'error')) {
                    for(var i in result['errors']){
                        showErrors(i, result['errors'][i])
                    }
                } else {
                    for(var i in result['user']) {
                        $('#'+id+'_'+i).html(result['user'][i]);
                    }
                    $.fancybox.close();
                }
            }
        }
    });
    return false;
}