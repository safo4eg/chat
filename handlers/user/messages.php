<?php
    $link = include_once 'includes/connect.php';
    require_once('classes/Page.php');
    require_once('classes/DataBase.php');

    $userId = $_SESSION['auth']['id'];
    $db = new DataBase($link);
    $dialogues = $db->getUserDialogues($userId);
    $lastMessages = $db->getMessages($userId, 'last');
    foreach($dialogues as $index => $dialogue) {
        foreach($lastMessages as $message) {
            if($dialogue['members_id'] == $message['members_id']) {
                $dialogues[$index]['last_message'] = $message['message'];
                $dialogues[$index]['message_user_id'] = $message['user_id'];
            }
        }
    }
    $_SESSION['view']['dialogues'] = $dialogues;

    $page = new Page('templates/base.html');
    $layout = $page->getDynamicContent('templates/user/messages.php');
    return $layout;