function editUser(id_user){
    $.fancybox.showLoading();
    $.ajax({
        url:  'user-edit',
        type: 'POST',
        data: {
            id_user: id_user
        },
        success: function(response, textStatus) {
            $('#fancy_frame_user').html(response);
            $.fancybox.hideLoading();
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
        }
    });
}