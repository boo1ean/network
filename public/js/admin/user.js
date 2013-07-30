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