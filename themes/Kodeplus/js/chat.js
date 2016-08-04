$(function () {

    /***
     Initialization
     ***/
    window.isReceiveMessage = false;
    window.isReceiveSelfMessage = true;
    window.conversationList = [];
    window.conversationCount = 0;
    var socket;
    if(window.user_id != "") {
        socket = io('https://' + window.goChatServerIP + ':' + window.goChatServerPort, {
            transports: ['websocket', 'polling', 'flashsocket'],
            path: '/'+window.goChatServerPort+'/socket.io'
        });
        window.socket = socket;
        var jqxhr = $.ajax({
            url: '/kodeplus_chat/conversation/get-conversations',
            type: 'GET',
            dataType: 'json'
        });

        jqxhr.done(function (data) {
            if (data.success && data.result !== null) {
                $.each(data.result, function (index, conversation) {
                    socket.emit('join', {room: getConversationUniqueId(conversation)});
                    if (conversation.notifyNumber > 0) {
                        addNewChatNotification(conversation);
                    }
                    //setNotifyNumber(conversation.id, conversation.notifyNumber);
                });
                if ($('#chat-notification-list').text() == '') {
                    setChatNotificationEmptyText(true);
                }
                else {
                    setChatNotificationEmptyText(false);
                }
                $('#chat_loader_notifications').addClass('hide');
            }
        });

        function getConversationUniqueId(conversation){
            return conversation.id.toString()+'_'+conversation.unique_key;
        }

        function getUserUniqueId()
        {
            return window.user_id+'_'+window.user_id_unique_key;
        }

        /***
         Socket.io Events
         ***/

        socket.on('welcome', function (data) {
            console.log(data.Message);

            socket.emit('join', {room: 'user_' + getUserUniqueId()});
        });

        socket.on('joined', function (data) {
            console.log(data.Message);
        });

        socket.on('chat.messages', function (data) {
            if (data.Room == 'user_' + getUserUniqueId()) {
                data.Message.Body = JSON.parse(data.Message.Body);
                if (data.Message.Body.action == 0) {
                    socket.emit('join', {room: data.Message.Body.data});
                }
                else if (data.Message.Body.action == 1) {
                    socket.emit('leave', {room: data.Message.Body.data});
                }

                return;
            }
            var message = data.Message.Body,
                from_user_id = data.Message.User_id,
                conversationId = data.Room.split('_')[0],
                created_at = data.Message.Created_at,
                isPopup = data.Message.IsPopup;

            if (from_user_id != window.user_id) {
                setNotifyNumber(conversationId, -1);
                window.isReceiveSelfMessage = false;
            }
            else {
                window.isReceiveSelfMessage = true;
            }

            if (!$('#conversation_' + conversationId).length) {
                if (isPopup == false) {
                    return;
                }
                getConversationById(conversationId);
                window.isReceiveMessage = true;
            }
            else {
                updateMessageList(data);
                conversation = window.conversationList[conversationId].conversation;
                removeViewedMessage(conversation);
                if(window.isReceiveSelfMessage == false)
                {
                    addNewChatNotification(conversation);
                    isReceiveSelfMessage = true;
                }

            }

        });

        socket.on('chat.notifies', function (data) {
            var conversationId = data.Room.split('_')[0],
                from_user_id = data.User_id,
                action = data.Action,
                mdata = data.Data,
                created_at = data.Created_at;
            if (action == 'viewed') {
                if (typeof window.conversationList[conversationId] != 'undefined') {
                    window.conversationList[conversationId].conversation.last_view_members = JSON.parse(mdata);
                    setMessageViewed(window.conversationList[conversationId].conversation);
                }

            }
        });
    }

    $('.popup-chat-open').on('click', function (e) {
        e.preventDefault();
        var conversationId = parseInt(this.id.split('conversation_id_')[1]);
        if (conversationId == 0) {
            var user_id = $(this).find('.chat_user_id').val();
            getSingleConversation(user_id, $(this));
        }
        else {
            getConversationById(conversationId);
            setNotifyNumber(conversationId, '');
        }
    });


});
function imgUserError(image) {
    image.onerror = "";
    image.src = "/img/default_user.jpg";
    return true;
}
function processChatData(data, conversation) {

    $html = '';
    $.each(data.result, function (i, item) {
        if (typeof item.message_type != 'undefined' && item.message_type == '1') {
            $html += '<div class="row message_notify" id="system_message_' + conversation.id + '"><p>'
                + item.body + '</p></div>';
            return;
        }
        var member_object = getMemberObject(conversation, item.user_id);
        var img = '';
        if (member_object.guid != null) {
            img = '/uploads/profile_image/' + member_object.guid + '.jpg';
        }
        else img = '/img/default_user.jpg';
        if (item.user_id != window.user_id) {

            $html += '<div class="row msg_container base_sent"><div class="col-md-10 col-xs-10"><div class="messages msg_sent"><p>';
            $html += item.body;
            $html += '</p><time datetime="2009-11-13T20:00">' + item.created_at + '</time></div> </div><div class="col-md-2 col-xs-2 avatar">';
            $html += '<a href="/u/' + member_object.username + '" style="margin-left:-20px;">';
            $html += '<img class="img-circle" src="' + img + '" height="32" width="32" alt="32x32" data-src="holder.js/32x32" style="width: 32px; height: 32px;">';
            $html += '</a></div></div>';
        }
        else {
            $html += '<div class="row msg_container base_receive"><div class="col-md-2 col-xs-2 avatar">';
            $html += '<a href="/u/' + member_object.username + '">';
            $html += '<img class="img-circle" src="' + img + '" height="32" width="32" alt="32x32" data-src="holder.js/32x32" style="width: 32px; height: 32px;">';
            $html += '</a>';
            $html += '</div> <div class="col-md-10 col-xs-10">';
            $html += '<div class="messages msg_receive"><p>' + item.body + '</p> <time datetime="2009-11-13T20:00">' + item.created_at + '</time> </div> </div> </div> </div>';
        }
    });
    return $html;
}

function getAcronym(words) {
    var matches = words.match(/\b(\w)/g);
    var acronym = matches.join('');
    if (acronym.length > 2) {
        acronym = acronym.substring(0, 2);
    }
    return acronym;
}
function createChatWindow(conversation) {

    var chatWindowHtml = ' <div class="chat-window hide" id="conversation_' + conversation.id + '">' +
        '<div class="col-xs-12 col-md-12">' +
        '<div class="panel panel-default">' +
        '<div class="panel-heading top-bar">' +
        '<div class="heading_place"><div class="col-md-2 col-xs-2"><div class="notifications">' +
        '<div class="btn-group chat-btn-group-space">' +
        '<a href="/' + conversation.space.guid + '"><div class="chat-btn-group-space-text space-profile-acronym-13 space-acronym" style="background-color: ' + conversation.space.color + '">' + getAcronym(conversation.space.name) + '</div> </a></div>' +
        '</div></div><div class="col-md-8 col-xs-8">' +
        '<h3 class="panel-title"><a class="recipient_link_" href="/u/' + getRecipientObject(conversation).username + '"><span id="recipient_name_' + conversation.id + '">' + conversation.name + '</span></a></h3>' +
        '</div>' +
        '<div class="col-md-1 col-xs-1" style="text-align: right; float:right">';
    if (conversation.type == 0)
        chatWindowHtml += '<a href="#"><span class="glyphicon glyphicon-remove icon_close" id="icon_close_' + conversation.id + '"></span></a>';
    else {
        chatWindowHtml += '<div class="dropdown">' +
            '<a href="#" data-toggle="dropdown"><i class="fa fa-cogs" aria-hidden="true"></i></a>' +
            '<ul class="dropdown-menu dropdown-menu-right">';
        if (conversation.created_by == window.user_id) {
            chatWindowHtml += '<li><a id="btn_close_room_' + conversation.id + '">Close room</a></li>';
        }
        /*else {
         chatWindowHtml += '<li><a id="btn_leave_room_' + conversation.id + '" href="#">Leave room</a></li>';
         }*/
        chatWindowHtml += '<li><a id="btn_view_member_' + conversation.id + '" href="#">Members</a></li>';
        chatWindowHtml += '</ul>' +
            '<a href="#"><span class="glyphicon glyphicon-remove icon_close" id="icon_close_' + conversation.id + '"></span></a>' +
            '</div>';
    }
    chatWindowHtml += '</div></div>' +
        '</div>' +
        '<div id="messageList_' + conversation.id + '" class="panel-body msg_container_base">' +
        '</div>' +
        '<div class="panel-footer">' +
        '<div class="input-group">' +
        '<textarea id="messageBox_' + conversation.id + '" placeholder="'+window.text_chat_message_box+'" class="form-control input-sm chat_input messageBox"></textarea>' +
        '<span class="input-group-btn">' +
        '<button  id="btnSendMessage_' + conversation.id + '" class="btn btn-primary btn-sm">'+window.text_send+'</button>' +
        '</span>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '</div>';


    $('#chat-popup-window').append(chatWindowHtml);
    var $messageList = $('#messageList_' + conversation.id);
    var $messageBox = $('#messageBox_' + conversation.id);
    var $btnSendMessage = $('#btnSendMessage_' + conversation.id);
    var $conversationWindow = $('#conversation_' + conversation.id);
    var $btnClose = $('#icon_close_' + conversation.id);
    var $btnLeaveGroup = $('#btn_leave_room_' + conversation.id);
    var $btnDeleteGroup = $('#btn_close_room_' + conversation.id);
    var $btnViewMember = $('#btn_view_member_' + conversation.id);


    $conversationWindow.removeClass('hide');
    getMessages(conversation).done(function (data) {
        setMessageList(data, true);
    });

    $btnSendMessage.on('click', function (evt) {
        evt.preventDefault();
        $temp_text = $messageBox.val();
        $messageBox.val('');
        $messageBox.focus();
        $temp_text = $temp_text.replace(/(?:\r\n|\r|\n)/g, '<br>');
        //removeViewedMessage(conversation.id);
        sendMessage($temp_text, conversation).done(function (data) {
            console.log(data);
        });
    });

    $messageBox.keydown(function (event) {
        if (event.keyCode == 13 && event.shiftKey) {
            // prevent default behavior
        }
        else if (event.keyCode == 13) {
            event.preventDefault();

            $btnSendMessage.trigger('click');
        }
    });

    $btnClose.on('click', function () {
        removeChatWindow();
    });

    var isAllowGetMessages = false;
    $messageList.on('scroll', function () {
        var currentScrollHeight = $(this)[0].scrollHeight;
        var currentScrollTop = $(this).scrollTop();
        if (currentScrollTop <= currentScrollHeight / 5) {
            if (isAllowGetMessages == true) {
                isAllowGetMessages = false;
                getMessages(conversation).done(function (data) {
                    temp_data = JSON.parse(data);
                    if (temp_data.result.length > 0) {
                        setMessageList(data, false);
                        $messageList.animate({scrollTop: ($messageList[0].scrollHeight - currentScrollHeight) + currentScrollTop}, 0);
                    }
                });
            }
        }
        else {
            isAllowGetMessages = true;
        }
    });


    $messageList.on('click', function () {
        sendViewedNotify(conversation);
    });

    $messageBox.on('click', function () {
        sendViewedNotify(conversation);
    });


    $btnLeaveGroup.on('click', function () {
        sendNotify(conversation, 'leave_room');
        window.socket.emit('leave', {room: conversation.id});
        removeChatWindow();
        $('#conversation_' + conversation.id).remove();
    });

    $btnDeleteGroup.on('click', function () {
        sendNotify(conversation, 'close_room');
        $('#conversation_' + conversation.id).remove();
    });

    $btnViewMember.on('click', function () {
        var jqxhr = $.ajax({
            url: '/kodeplus_chat/conversation/get-conversation-members',
            type: 'GET',
            data: {conversationId: conversation.id},
            dataType: 'json'
        });

        jqxhr.done(function (data) {
            $html = '';
            $.each(data.result, function (index, item) {
                if (item.id != window.user_id)
                    $html += '<li>' + item.username + '<a href="#" class="btn_kick_member_' + conversation.id + '" id="member_' + item.id + '">kick</a></li>';
            });
            $('#chat_member_list').html($html);
            $html = '<input type="hidden" name="conversationId" value="' + conversation.id + '"/>';
            $('#chat_form_hidden_conversation').html($html);
            $('#chatMembers').modal('show');
            $('.btn_kick_member_' + conversation.id).on('click', function () {
                var chat_user_id = this.id.split('member_')[1];
                sendNotify(conversation, 'kick_room_member', {user_id: chat_user_id});
                $('#chatMembers').modal('hide');
            });
        });
    });


    function removeChatWindow() {
        $conversationWindow.remove();
        window.conversationCount--;
        window.conversationList = window.conversationList.splice(window.conversationList.indexOf(conversation), 1);
    }

    function setMessageList(data, isScroll) {
        data = JSON.parse(data);
        window.conversationList[conversation.id].current_range += (data.message_limit + 1);
        $.each(data.result, function (index) {
            data.result[index] = JSON.parse(data.result[index]);
        });
        $messageList.prepend(processChatData(data, conversation));
        if (isScroll) {
            $messageBox.focus();
            scrollToBottom($messageList);
        }
    }
}


function getSingleConversation(chat_user_id, $parent) {
    var jqxhr = $.ajax({
        url: window.getSingleConversationUrl,
        data: {recipientId: chat_user_id},
        type: 'GET',
        dataType: 'json'
    });

    jqxhr.done(function (data) {
        if (data.success) {
            var conversation = data.result;
            $parent.attr('id', 'conversation_id_' + conversation.id);
            processChatConversation(conversation);
        }
    });
}

function getConversationById(conversationId) {
    var jqxhr = $.ajax({
        url: '/kodeplus_chat/conversation/get-conversation-by-id',
        data: {conversationId: conversationId},
        type: 'GET',
        dataType: 'json'
    });

    jqxhr.done(function (data) {
        if (data.success) {
            processChatConversation(data.result);
        }
    });
}

function processChatConversation(conversation) {
    if (!$('#conversation_' + conversation.id).length) {
        conversation.last_view_members = $.parseJSON(conversation.last_view_members);
        window.conversationList[conversation.id] = {
            conversation: conversation,
            current_range: 10,
        };
        createChatWindow(conversation);
        if (!window.isReceiveMessage)
            sendViewedNotify(conversation);
        setMessageViewed(conversation);

        if (!window.isReceiveSelfMessage) {
            addNewChatNotification(conversation);
            isReceiveSelfMessage = true;
        }
        window.isReceiveMessage = false;
    }
}

function updateMessageList(data) {
    var message = data.Message.Body,
        from_user_id = data.Message.User_id,
        conversationId = data.Room.split('_')[0],
        created_at = data.Message.Created_at;
    if (typeof data.Message.Message_type != 'undefined') {
        data.result = [{
            body: message,
            user_id: from_user_id,
            created_at: created_at,
            message_type: data.Message.Message_type
        }];
    }
    else {
        data.result = [{body: message, user_id: from_user_id, created_at: created_at}];
    }
    var $messageList = $('#messageList_' + conversationId);
    $messageList.append(processChatData(data, window.conversationList[conversationId].conversation));
    scrollToBottom($messageList);
}


function sendNotify(conversation, action, data) {
    var jqxhr = $.ajax({
        url: '/kodeplus_chat/message/notify',
        type: 'POST',
        data: {conversationId: conversation.id, action: action, data: data},
        dataType: 'json'
    });

    return jqxhr;
}

function getMessages(conversation) {
    var jqxhr = $.ajax({
        url: '/kodeplus_chat/message',
        type: 'GET',
        data: {conversationId: conversation.id, current_range: window.conversationList[conversation.id].current_range},
        dataType: 'html'
    });

    return jqxhr;
}

function sendMessage(body, conversation) {
    var jqxhr = $.ajax({
        url: '/kodeplus_chat/message/store',
        type: 'POST',
        data: {body: body, conversationId: conversation.id},
        dataType: 'json'
    });

    return jqxhr;
}

function scrollToBottom($messageList) {
    if ($messageList.length) {
        $messageList.animate({scrollTop: $messageList[0].scrollHeight}, 500);
    }
}

function setNotifyNumber($conversationId, notifyNumber) {
    var $badge_selector = $('#conversation_id_' + $conversationId).find('.badge');

    if (notifyNumber != -1) {
        if (notifyNumber > 0) {
            $badge_selector.text(notifyNumber);
        }
        else {
            $badge_selector.text('');
        }
    }
    else {
        $number = $badge_selector.text();
        if ($number == '') $number = '0';
        $number = parseInt($number) + 1;
        $badge_selector.text($number);
    }
}

function setMessageViewed(conversation, isNotScroll) {
    var last_view_members = conversation.last_view_members;
    var membersNameExtracted = '';
    var listViewedUserId = [];
    $.each(last_view_members, function (index, item) {
        if (item.viewed == true) {
            var member_object = getMemberObject(conversation, item.member_id);
            if (member_object != null) {
                if (membersNameExtracted == '') {
                    membersNameExtracted += member_object.username;
                }
                else {
                    membersNameExtracted += ', ' + member_object.username;
                }
                listViewedUserId.push(item.member_id);
            }
        }
    });
    if (conversation.type == 0 && listViewedUserId[0] == window.user_id) {
        return;
    }
    if (membersNameExtracted != '') {
        removeViewedMessage(conversation);
        var messageViewed = membersNameExtracted + ' '+ window.text_viewed;
        var $messageList = $('#messageList_' + conversation.id);
        if ($messageList.length) {
            $messageList.append('<div class="row message_notify" id="message_viewed_' + conversation.id + '"><p>'
                + '<i class="fa fa-check fa-1" aria-hidden="true"></i>' + messageViewed + '</p></div>');
            if (!isNotScroll)
                scrollToBottom($messageList);
        }
    }
}

function getMemberObject(conversation, memberId) {
    var member_object = null;
    $.each(conversation.users, function (index, item) {
        if (memberId == item.id) {
            member_object = item;
            return true;
        }
    });
    return member_object;
}

function getRecipientObject(conversation) {
    var member_object = null;
    $.each(conversation.users, function (index, item) {
        if (window.user_id != item.id) {
            member_object = item;
            return true;
        }
    });
    return member_object;
}

function removeViewedMessage(conversation) {
    var messageViewed = '#message_viewed_' + conversation.id;
    if ($(messageViewed).length)
        $(messageViewed).remove();
}

function sendViewedNotify(conversation) {
    sendNotify(conversation, 'viewed');
    setNotifyNumber(conversation.id, '');
    removeChatNewNotification(conversation);
}

function getImgPathFromUser(member_object) {
    var img = '';
    if (member_object.guid != null) {
        img = '/uploads/profile_image/' + member_object.guid + '.jpg';
    }
    else img = '/img/default_user.jpg';
    return img;
}

function setChatNotificationBadge() {
    var $chat_badge_notify_selector = $('#chat-badge-notifications');
    var newNotifyNumber = $('#chat-notification-list li.new').length;
    if(newNotifyNumber > 0)
    {
        $chat_badge_notify_selector.show();
        $chat_badge_notify_selector.text(newNotifyNumber);
    }
    else {
        $chat_badge_notify_selector.hide();
    }
}

function setChatNotificationEmptyText(set) {
    if (set == true) {
        var html = '<li class="placeholder">'+window.text_chat_no_notification+'</li>';
        $('#chat-notification-list').append(html);
    }
    else {
        $('#chat-notification-list').find('li.placeholder').remove();
    }

}

function addNewChatNotification(conversation) {
    var $selector_chat_notification = $('#chat-notification-' + conversation.id);
    setChatNotificationEmptyText(false);
    if ($selector_chat_notification.length > 0) {
        $selector_chat_notification.remove();
    }



    var recipientObject = getRecipientObject(conversation);
    var img = getImgPathFromUser(recipientObject);
    var timeago = $.timeago(conversation.lastMessageCreatedAt);
    var html = '';
    html += '<li id="chat-notification-' + conversation.id + '" class="new">';
    html += '<a id="chat-link-notification-' + conversation.id + '" href="#">';
    html += '<div class="media">';
    html += '<img class="media-object img-rounded pull-left" data-src="holder.js/32x32" alt="32x32" style="width: 32px; height: 32px;" src="' + img + '">';
    html += '<div class="media-body">';
    html += window.text_new_notification+' <strong>' + conversation.name + '</strong> '+window.text_at+' <strong>'+conversation.space.name+'</strong>';
    html += '<span class="time"> ' + timeago + ' </span>';
    html += '<span class="label label-danger">New</span>';
    html += '</div>';
    html += '</div>';
    html += '</a>';
    html += '</li>';
    $('#chat-notification-list').prepend(html);
    var $selector_chat_link_notification = $('#chat-link-notification-' + conversation.id);
    $selector_chat_link_notification.click(function (e) {
        e.preventDefault();
        removeChatNewNotification(conversation);
        var conversationId = parseInt(this.id.split('chat-link-notification-')[1]);
        getConversationById(conversationId);
        sendViewedNotify(conversation);
    });
    setChatNotificationBadge();
}

function removeChatNewNotification(conversation) {
    var $selector_chat_notification = $('#chat-notification-' + conversation.id);
    if ($selector_chat_notification.length > 0) {
        $selector_chat_notification.removeClass("new");
        $selector_chat_notification.find('span.label').remove();
        var html = '<li id="chat-notification-' + conversation.id+'">';
        html += $selector_chat_notification.html();
        html += '</li>';
        $selector_chat_notification.remove();
        $('#chat-notification-list').append(html);
        setChatNotificationBadge();
    }

}

function getLocalConversationById(conversationId)
{
    var conversation = null;
    $.each(window.conversationList, function( index ) {
        conversation = window.conversationList[index];
        if(conversation.id == conversationId)
        {
            return false;
        }
    });
    return conversation;
}

function markChatNotificationsAsSeen()
{
    $('#chat-notification-list').children('li').each(function () {
        var conversationId = parseInt(this.id.split('chat-notification-')[1]);
        var conversation = {id:conversationId};
        removeChatNewNotification(conversation);
        sendViewedNotify(conversation);
    });
}