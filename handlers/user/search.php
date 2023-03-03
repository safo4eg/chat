<?php
    $link = include_once 'includes/connect.php';
    require_once('classes/Page.php');
    require_once('classes/DataBase.php');
    $db = new DataBase($link);
    $page = new Page('templates/base.html');

    if(!empty($_GET)) {
        $responseData = [];
        $users = $db->getUserStartWith($_GET['chars'], $_SESSION['auth']['id']);
        if(empty($users)) {
            $_SESSION['view']['users'] = null;
        } else {
            $_SESSION['view']['users'] = $users;
        }

        $content = $page->getDynamicCleanContent('templates/user/search.php');
        $content = str_replace(["\r\n", "\n"], '', $content);
        $content = preg_replace('#<div class="search.+?<\/div>#', '', $content);
        echo $content;
        die();
    } else {
        $users = $db->getUsers($_SESSION['auth']['id']);
        $_SESSION['view']['users'] = $users;
        return $page->getDynamicContent('templates/user/search.php');
    }