var messages = function() {
    this.hideErrors = function(obj) {
        obj.find('div').removeClass('has-error');
        obj.find('p.help-block').hide('blind').empty();
    };

    this.showErrors = function(id, error) {
        var obj = $('#' + id).parent();

        if (!obj.hasClass('has-error')) {
            obj.addClass('has-error');
        }

        obj.find('p.help-block').attr('style', 'display:none').html(error).show('blind');
    };
}

var members = function(id) {
    this.member_source = new Array();
    this.member_full   = {};
    this.domObj        = $('#' + id);
}

//tags input
$(document).ready(
    function() {

        /**
         * Open form to recover  password
         */
        $('#forgot-open').click(function (event) {
            $.get('/auth/forgot')
             .done(function(response, textStatus) {
                $('#forgot-modal').html(response).modal('show');
            });
        });

        /**
         * sent settings on the email
         */
        $('body').on('click','#forgot-save', function (event) {
            var obj    = $(this);
            var data   = obj.parents('form').serialize();
            var errors = new messages();
            $.ajax({
                url:  '/auth/forgot-save',
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
                        $('#forgot-modal').modal('hide');
                        toastr['success']('<h4>'+result['message']+'</h4>');
                    }

                }
            });
            return false
        });

        // Tooltips for userBox
        $('#userBox a').tooltip({
            'placement': 'bottom'
        });

        // Toastr for notifications, on toasrt click redirect to event
        $('#userBoxNotifications').click(function(){
            // Show newest toast at bottom (top is default)
            toastr.options.newestOnTop = false;
            var ajaxData = {
                url: "/notification/json",
                type: 'get',
                dataType : 'json',
                success: function(data) {
                    // Links to notifications
                    for (var i = 0; i < data.length; i++) {
                        var link = data[i].link;
                        toastr.options.onclick = function() {
                            $(location).attr('href', link);
                        };
                        toastr['info'](data[i].description, data[i].title);
                    }
                    // Link to all notifications
                    toastr.options.onclick = function() {
                        $(location).attr('href', '/notification');
                    };
                    toastr['success']("<h4>All notifications here</h4>");
                }
            };
            $.ajax(ajaxData);
        });

        /**
         * create typeahead list of the don't subscribed users
         */
        if ($('#not-member-list').length) {
            var members_list = new members('not-member-list');
            members_list.domObj.typeahead({
                items:  10,
                source: function() {
                    if (!members_list.member_source.length) {
                        $.post(
                            '/message/member-not-subscribe-list',
                            {id_conversation: members_list.domObj.attr('data-id')}
                        ).done(function(response) {
                            var data = $.parseJSON(response);

                            $.each(data, function (i, item) {
                                members_list.member_source.push(item['name']);
                            });

                            members_list.member_full = data;
                        }).error(function(error) {
                            alert(error);
                        });
                    }
                    this.process(members_list.member_source);
                },
                updater: function(item_current) {
                    $.each(members_list.member_full, function (i, item) {
                        if(item && item['name'] == item_current) {
                            $.post(
                                '/message/member-save',
                                {id_conversation: members_list.domObj.attr('data-id'), id_user: item['id']}
                            ).done(function(response) {
                                if('ok' != response && 'error' != response) {
                                    window.location = response;
                                } else {
                                    members_list.member_source.splice(i, 1);
                                    members_list.member_full.splice(i, 1);

                                    var html = '<label class="label label-success" style="display: none;">' +
                                        '<input name="members[' + item['id'] + ']" type="checkbox" style="display: none;" checked="checked" />' +
                                        item_current +
                                        '</label>';

                                    $('#member-list').append(html).find('label[style="display: none;"]').show('blind');
                                }
                            }).error(function(error) {
                                alert(error);
                            });
                        }
                    });
                }
            });
        }


        /**
         * load form for create new conversation
         */
        $('#conversation-create').click(function (event) {
            $.get('/message/conversation-create')
             .done(function(response, textStatus) {
                $('#conversation-create-modal').html(response).modal('show');

                var members_list = new members('new-member-list');

                members_list.domObj.typeahead({
                    items:  5,
                    source: function() {
                        if(!members_list.member_source.length){
                            $.post('/message/member-not-subscribe-list')
                                .done(function(response) {
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

                            if(item && item['name'] == item_current) {
                                members_list.member_source.splice(i, 1);
                                members_list.member_full.splice(i, 1);

                                var html = '<label class="label label-success" style="display: none;">' +
                                '<input name="members[' + item['id'] + ']" type="checkbox" style="display: none;" checked="checked" />' +
                                 item_current +
                                '</label>';

                                $('#member-list').append(html).find('label[style="display: none;"]').show('blind');
                            }
                        });
                    }
                });
            });
        });


        /**
         * create new conversation
         */
        $('body').on('click','button#conversation-save', function (event) {
            var obj    = $(this);
            var data   = obj.parents('form').serialize();
            var errors = new messages();
            $.ajax({
                url:  '/message/conversation-create',
                type: 'POST',
                data: data,
                beforeSend: function() {
                    errors.hideErrors(obj.parents('form'));
                },
                success: function(response, textStatus) {
                    var result = $.parseJSON(response);

                    if('error' == result['status']) {
                        for(var i in result['errors']){
                            errors.showErrors(i, result['errors'][i])
                        }
                    } else {
                        window.location = result['redirect'];
                    }

                }
            });
            return false;
        });

        $('#tags').tagsInput();

        $('.ebook').hide();
    }
);