<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 'on');

    $link = mysqli_connect('chat', 'root', '', 'chat');

    if(mysqli_connect_errno()) {
        echo 'Произошла ошибка: '.mysqli_connect_error();
        die();
    }

    return $link;