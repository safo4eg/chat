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

    if(preg_match('#^\/profile\/(?<id>\d+)$#', $url, $match)) {
        $_SESSION['view']['profile'] = $match['id'];
        echo include_once 'handlers/user/profile.php';
    }

    if(preg_match('#^\/search(\?chars=(.+)*)?$#', $url, $match)) {
        echo include_once 'handlers/user/search.php';
    }

    if(preg_match('#^\/messages$#', $url, $match)) {
        echo include_once 'handlers/user/messages.php';
    }

    if(preg_match('#^\/dialogue/(?<id>\d+)$#', $url, $match)) {
        $_SESSION['view']['members_id'] = $match['id'];
        echo include_once 'handlers/user/dialogue.php';
    }

    if(preg_match('#^\/update$#', $url, $match)) {
        echo include_once 'handlers/ajax/update.php';
    }