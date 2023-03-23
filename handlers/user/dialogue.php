<?php
    $link = include_once 'includes/connect.php';
    require_once('classes/Page.php');
    require_once('classes/DataBase.php');

    $userId = $_SESSION['auth']['id'];
    $db = new DataBase($link);


    $page = new Page('templates/base.html');
    $layout = $page->getDynamicContent('templates/user/dialogue.php');
    return $layout;