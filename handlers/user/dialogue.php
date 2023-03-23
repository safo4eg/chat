<?php
    $link = include_once 'includes/connect.php';
    require_once('classes/Page.php');
    require_once('classes/DataBase.php');

    $userId = $_SESSION['auth']['id'];
    $membersId = $_SESSION['view']['members_id'];
    $db = new DataBase($link);
    $dialogueData = $db->getMessages($userId, $membersId);
    $companionInfo = $db->getUser($dialogueData[0]['companion']);
    $userInfo = $db->getUser($userId);
    $_SESSION['view']['companion_info'] = $companionInfo;
    $_SESSION['view']['user_info'] = $userInfo;
    $_SESSION['view']['messages'] = $dialogueData;

    $page = new Page('templates/base.html');
    $layout = $page->getDynamicContent('templates/user/dialogue.php');
    return $layout;