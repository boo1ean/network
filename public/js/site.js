var messages = function() {
    this.hideErrors = function(obj) {
        obj.find('span').removeClass('error').hide('blind').empty();
        obj.find('input').removeClass('error');
    };

    this.showErrors = function(id, error) {
        var obj = $('#'+id);
        if(!obj.hasClass('error')){
            obj.addClass('error');
        }
        obj.parent().find('span').addClass('error').attr('style', 'display:none').html(error).show('blind');
    };
}

var members = function(id){
    this.member_source = new Array();
    this.member_full   = {};
    this.domObj        = $('#'+id);
}

//tags input
$(document).ready(
    function(){
        if($('#not-member-list').length > 0){
            var members_list = new members('not-member-list');
            members_list.domObj.typeahead({
                items: 10,
                source: function(){
                    if(members_list.member_source.length == 0){
                        $.post(
                            '/message/member-not-subscribe-list',
                            {id_conversation: members_list.domObj.attr('data-id')}
                        ).done(function(response){
                            var data = $.parseJSON(response);

                            $.each(data, function (i, item) {
                                members_list.member_source.push(item['name']);
                            });
                            members_list.member_full = data;
                        });
                    }
                    this.process(members_list.member_source);
                },
                updater: function(item_current) {
                    $.each(members_list.member_full, function (i, item) {
                        if(item['name'] == item_current) {
                            $.post(
                                '/message/member-save',
                                {id_conversation: members_list.domObj.attr('data-id'), id_user: item['id']}
                            ).done(function(response){
                                if('ok' != response && 'error' != response) {
                                    window.location = response;
                                } else {
                                    members_list.member_source.splice(i, 1);
                                    members_list.member_full.splice(i, 1);

                                    var html = '<li style="display: none;"><a href="#" class="btn btn-small disabled">'+
                                                item_current+
                                                '</li>';

                                    $('#member-list').append(html).find('li[style="display: none;"]').show('blind');
                                }
                            }).error(function(error){
                                alert(error);
                            });
                        }
                    });
                }
            });
        }

        $('#tags').tagsInput();

        $('.ebook').hide();
    }
);