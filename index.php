<?php
    session_start();
    unset($_SESSION['view']);
    $url = $_SERVER['REQUEST_URI'];

    if(preg_match("#^\/(?<slug>login|register|logout)$#", $url, $match)) {
        $pocket = $match['slug'];
        if($pocket === 'register') {
            echo include_once 'handlers/auth/register.php';
        } else if($pocket === 'login') {
            echo include_once 'handlers/auth/login.php';
        } else if($pocket === 'logout') {
            session_destroy();
            header('Location: /login');
        }
    }

    if(preg_match('#^\/(profile)\/(?<id>\d+)$#', $url, $match)) {
        $_SESSION['view']['profile'] = $match['id'];
        echo include_once 'handlers/user/profile.php';
    }

    if(preg_match('#^\/(search)(\/(.+))*#', $url, $match)) {
        echo include_once 'handlers/user/search.php';
    }