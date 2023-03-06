<?php
    $link = include 'includes/connect.php';
    include 'classes/DataBase.php';

    if(!empty($_POST)) {
        $db = new DataBase($link);
        if(isset($_POST['timestamp'])) {
            $response = $db->setTimestamp($_SESSION['auth']['id']);
            echo json_encode('debug1', JSON_UNESCAPED_UNICODE);
            die();
        }
        echo 'debug2';
        die();
    }
