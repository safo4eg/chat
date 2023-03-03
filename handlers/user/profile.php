<?php
    $link = include_once 'includes/connect.php';
    require_once('classes/Page.php');
    require_once('classes/DataBase.php');
    if(!empty($_POST)) {

    } else {
        $id = $_SESSION['view']['profile'];
        $db = new DataBase($link);
        $user = $db->getUser($id);
        if(!isset($user)) {
            echo 'fdsfsd';
        }
        if($user['id'] == $_SESSION['auth']['id']) $_SESSION['view']['myself'] = 'yes';
        $_SESSION['view']['profile'] = $user;

        $page = new Page('templates/base.html');
        return $page->getDynamicContent('templates/user/profile.php');
    }