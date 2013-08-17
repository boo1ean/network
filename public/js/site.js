var messages = function() {
    this.hideErrors = function(obj) {
        obj.find('div').removeClass('has-error');
        obj.find('.help-block').hide('blind').empty();
    };

    this.showErrors = function(id, error) {
        var obj = $('#' + id).parent();

        if (!obj.hasClass('has-error')) {
            obj.addClass('has-error');
        }

        obj.find('.help-block').attr('style', 'display:none').html(error).show('blind');
    };
}

var members = function(id) {
    this.member_source = new Array();
    this.member_full   = {};
    this.domObj        = $('#' + id);
}

$(document).ready(
    function() {

        /**
         * Open form to recover  password
         */
        $(document).on({
            click: function (event) {
                $.get('/auth/forgot')
                 .done(function(response, textStatus) {
                    $('#forgot-modal').html(response).modal('show');
                 })
                 .error(function(error) {
                    alert(error);
                 });
            }
        }, '#forgot-open');

        /**
         * sent settings on the email
         */
        $(document).on({
            click: function (event) {
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
                        } else if ('ok' == result['status']) {
                            $('#forgot-modal').modal('hide');
                            toastr['success']('<h4>'+result['message']+'</h4>');
                        } else if('redirect' == result['status']) {
                            window.location = result['redirect'];
                        }
                    },
                    error: function(error) {
                        alert(error);
                    }
                });
                return false;
            }
        }, '#forgot-save');

        // Tooltips for userBox
        $(document).on({
            mouseenter: function() {
                $(this).tooltip({'placement': 'bottom'});}
        },'#userBox a');


        // Toastr for notifications, on toasrt click redirect to event
        $(document).on({
            click: function() {
                // Show newest toast at bottom (top is default)
                toastr.options.newestOnTop = false;
                // Delete all previous toasts
                // Made in such way because toastr.clear() removes all toasts include new (which will be added in ajax success event)
                $('#toast-container').remove();
                // Data for request
                var ajaxData = {
                    url: "/notification/json",
                    type: 'get',
                    dataType : 'json',
                    success: function(data) {
                        // Links to notifications
                        for (var i = 0; i < data.length; i++) {
                            (function(){
                                const link = data[i].link;
                                toastr.options.onclick = function() {
                                    $(location).attr('href', link);
                                };
                                toastr['info'](data[i].description, data[i].title);
                            })();
                        }
                        // Link to all notifications
                        toastr.options.onclick = function() {
                            $(location).attr('href', '/notification');
                        };
                        toastr['success']("<h4>All notifications here</h4>");
                    },
                    error: function(error) {
                      alert(error);
                    }
                };
                $.ajax(ajaxData);
            }
        }, '#userBoxNotifications');

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
                            '/conversation/member-not-subscribe-list',
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
                                '/conversation/member-save',
                                {id_conversation: members_list.domObj.attr('data-id'), id_user: item['id']}
                            ).done(function(response) {
                                var result = $.parseJSON(response);
                                if('redirect' == result['status']) {
                                    window.location = result['redirect'];
                                } else if('ok' == result['status']) {
                                    members_list.member_source.splice(i, 1);
                                    members_list.member_full.splice(i, 1);

                                    var html = '<div class="btn-group" data-id="' + item['id'] + '" style="display: none;" >' +
                                        '<input name="members[' + item['id'] + ']" type="checkbox" style="display: none;" checked="checked" />' +
                                        '<button class="label btn label-success">' + item_current + '</button>' +
                                        '<button class="label btn label-success glyphicon glyphicon-remove" data-id="' + members_list.domObj.attr('data-id') + '"></button></div>';

                                    $('#member-list').append(html).find('div[style="display: none;"]').show('blind');
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
         * send message
         */
        $(document).on({
            click: function(event) {
                var obj    = $(this);
                var data   = obj.parents('form').serialize();
                var errors = new messages();
                var form   = obj.parents('form');

                data += '&id=' + obj.attr('data-id');
                $.ajax({
                    url:  '/conversation/message-send',
                    type: 'POST',
                    data: data,
                    beforeSend: function() {
                        errors.hideErrors(form);
                    },
                    success: function(response, textStatus) {
                        var result = $.parseJSON(response);

                        if ('error' == result['status']) {
                            for (var i in result['errors']) {
                                errors.showErrors(i, result['errors'][i])
                            }
                        } else if ('ok' == result['status']) {
                            var html = '<div class = "messageContainer"><div class = "messageUser">' + $('#avatar-container').html() +
                                '</div> <div class = "messageBody"> <div class = "popover right in" style="z-index: 0;">' +
                                '<div class = "arrow"></div> <h5 class="popover-title">' + obj.attr('data-title') + '</h5>' +
                                '<div class = "popover-content">' + result['message']['body'] + '</div> </div> </div> </div>';
                            $('#message-container').before(html);
                            form.find('textarea').val('');
                        } else if ('redirect' == result['status']) {
                            window.location = result['redirect'];
                        }
                    },
                    error: function(error) {
                        alert(error);
                    }
                });
                return false;
            }
        }, '#message-send');

        $(document).on({
            click: function (event) {
                if(!confirm('Do you really wanna removing this user from conversation?')) return false;
                var obj    = $(this);
                var parent = obj.parent();

                if('none' == obj.attr('data-action')) {
                    parent.remove();
                    return false;
                }

                var data = {
                    id_conversation: obj.attr('data-id'),
                    id_user:         parent.attr('data-id')
                };

                $.ajax({
                    url:  '/conversation/member-remove',
                    type: 'POST',
                    data: data,
                    success: function(response, textStatus) {
                        var result = $.parseJSON(response);

                        if ('error' == result['status']) {
                            alert('We have some problems with deleting this user. Please try again.');
                        } else if ('ok' == result['status']) {
                            parent.remove();
                        } else if ('redirect' == result['status']) {
                            window.location = result['redirect'];
                        }
                    },
                    error: function() {
                        alert('We have some problems with deleting this user. Please try again.');
                    }
                });
                return false;
            }
        },'.btn-group > .glyphicon-remove');

        /**
         * load form for create new conversation
         */
        $(document).on({
            click: function() {
                $.get('/conversation/conversation-create')
                    .done(function(response, textStatus) {
                        var result = $.parseJSON(response);

                        if('redirect' == result['status']) {
                            window.location = result['redirect'];
                            return false;
                        }

                        $('#conversation-create-modal').html(result['html']).modal('show');

                        var members_list = new members('new-member-list');

                        members_list.domObj.typeahead({
                            items:  5,
                            source: function() {
                                if(!members_list.member_source.length){
                                    $.post('/conversation/member-not-subscribe-list')
                                        .done(function(response) {
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
                                        members_list.member_source.splice(i, 1);
                                        members_list.member_full.splice(i, 1);

                                        var html = '<div class="btn-group" data-id="' + item['id'] + '" style="display: none;" >' +
                                            '<input name="members[' + item['id'] + ']" type="checkbox" style="display: none;" checked="checked" />' +
                                            '<button class="label btn label-success">' + item_current + '</button>' +
                                            '<button class="label btn label-success glyphicon glyphicon-remove" data-action="none"></button></div>';

                                        $('#member-list').append(html).find('div[style="display: none;"]').show('blind');

                                    }
                                });
                            }
                        });
                    }).error(function(error) {
                        alert(error);
                    });
                }
        }, '#conversation-create');

        /**
         * create new conversation
         */
        $(document).on({
            click: function (event) {
                var obj    = $(this);
                var data   = obj.parents('form').serialize();
                var errors = new messages();
                $.ajax({
                    url:  '/conversation/conversation-create',
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

                    },
                    error: function(error) {
                        alert(error);
                    }
                });
                return false;
            }
        }, '#conversation-save');
    }
);