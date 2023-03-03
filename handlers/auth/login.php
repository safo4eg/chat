<?php
    $link = include_once 'includes/connect.php';
    require_once('classes/Page.php');
    require_once('classes/Auth.php');

    if(!empty($_POST)) {
        $login = $_POST['login'];
        $pass = $_POST['password'];

        $responseData = [];
        $auth = new Auth($link);
        $status = $auth->login($login, $pass);

        if(isset($status)) {
            $responseData['errors'] = $status;
        } else {
            $responseData['url'] = "/profile/{$_SESSION['auth']['id']}";
        }
        echo json_encode($responseData, JSON_UNESCAPED_UNICODE);
        die();

    } else {
        $page = new Page('templates/base.html', 'templates/auth/login.html');
        $page->createTemplateVars();
        return $page->pasteContent();
    }