$(document).ready(function() {

    // Add data-pjax to all links after document load
    $('a:not(#userBoxLogout)').attr('data-pjax', '#pjax-container');

    $(document).pjax('a[data-pjax]', { container: '#pjax-container', timeout: 0})
        .on('pjax:success', function(event) {
            // If current target is calendar, call calendarReady function
            var isCalendar = event.relatedTarget.toString().search("calendar/calendar");

            if(isCalendar) {
                calendarReady();
            }

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
                                    alert(error.statusText);
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
                                                '<button class="btn btn-xs btn-success">' + item_current + '</button>' +
                                                '<button class="btn btn-success glyphicon glyphicon-remove" data-id="' +
                                                members_list.domObj.attr('data-id') + '" style="top:0px;height:22px"> </button></div>';

                                            $('#member-list').append(html).find('div[style="display: none;"]').show('blind');
                                        }
                                    }).error(function(error) {
                                        alert(error.statusText);
                                    });
                            }
                        });
                    }
                });
            }

            // Add data-pjax to all links
            $('a:not(#userBoxLogout)').attr('data-pjax', '#pjax-container');
        });
}); 