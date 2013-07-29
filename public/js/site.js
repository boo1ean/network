function editUser(id_edit, is_first) {
    var data = is_first ? {} : JQuerySelectorFind(['input'], 'edit_user');

    data['id_edit']  = id_edit;
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
                        $('#'+id_edit+'_'+i).html(result['user'][i]);
                    }
                    $.fancybox.close();
                }
            }
        }
    });
    return false;
}

function matchStr(s1, s2) {
    return typeof s1 != 'undefined' && typeof s2 != 'undefined' && s1.length == s2.length && s1.indexOf(s2) == 0;
}

function showErrors(id, error) {
    var obj = $('#'+id);
    if(!obj.hasClass('error')){
        obj.addClass('error');
    }
    obj.parent().find('span').html(error);
}

function JQuerySelectorFind(teg, id_form) {
    var data = {};
    for(var i in teg)
    {
        $('#'+id_form+' '+teg[i]).each(function(indx, element) {
            var o_element = $(element);
            var id = o_element.attr('id');

            if(!matchStr(typeof id, 'undefined'))
            {
                if(matchStr(teg[i], 'input'))
                {
                    var type = o_element.attr('type');
                    if(matchStr(type, 'text') || matchStr(type, 'password') || matchStr(type, 'hidden'))
                        data[id] = o_element.val();
                    else if(matchStr(type, 'checkbox'))
                        data[id] = o_element.prop('checked') ? 1 : 0;
                    else if(matchStr(type, 'radio') && o_element.prop('checked'))
                        data[id] = o_element.val();
                }
                else if(matchStr(teg[i], 'textarea'))
                {
                    if(matchStr(typeof FCKeditorAPI.GetInstance(id), 'undefined'))
                        data[id] = o_element.val();
                    else
                        data[id] = FCKeditorAPI.GetInstance(id).GetData();
                }
                else if(matchStr(teg[i], 'select'))
                    data[id] = $('#'+id+' option:selected').val();
            }
        });
    }
    return data;
}