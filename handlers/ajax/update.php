<?php
    $link = include 'includes/connect.php';
    include 'classes/DataBase.php';

    if(!empty($_POST)) {
        $currentUserId = $_SESSION['auth']['id'];
        $db = new DataBase($link);
//        if(isset($_POST['timestamp'])) {
//            $response = $db->setTimestamp($currentUserId);
//            echo json_encode($response, JSON_UNESCAPED_UNICODE);
//            die();
//        }

        if(isset($_POST['searchMessage'])) {
            $message = $_POST['message'];
            $companionUserId = $_POST['id'];
            if($message !== '') {
                $db->createDialogues($currentUserId, $companionUserId);
                $db->sendMessage($currentUserId, $companionUserId, $message);
                die();
            }
        }

        if(isset($_POST['newMessage'])) {
            $message = $_POST['message'];
            $companionUserId = $_POST['companion_id'];
            $insertedMessageData = $db->sendMessage($currentUserId, $companionUserId, $message);
            echo json_encode($insertedMessageData, JSON_UNESCAPED_UNICODE);
            die();
        }
    }
