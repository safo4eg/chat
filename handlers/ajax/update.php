<?php
    $link = include 'includes/connect.php';
    include 'classes/DataBase.php';

    if(isset($_POST['action'])) {
        $currentUserId = $_SESSION['auth']['id'];
        $db = new DataBase($link);
        if(isset($_POST['timestamp'])) {
            $response = $db->setTimestamp($currentUserId);
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
            die();
        }

        if(isset($_POST['searchMessage'])) {
            $message = $_POST['message'];
            $companionUserId = $_POST['id'];
            if($message !== '') {
                $db->createDialogues($currentUserId, $companionUserId);
                $db->sendMessage($currentUserId, $companionUserId, $message);
                die();
            }
        }

        if($_POST['action'] === 'newMessage') {
            $message = $_POST['message'];
            $companionUserId = $_POST['companionId'];
            $insertedMessageData = $db->sendMessage($currentUserId, $companionUserId, $message);
            echo json_encode($insertedMessageData, JSON_UNESCAPED_UNICODE);
            die();
        }

        if($_POST['action'] === 'input') {
            $companionId = $_POST['companionId'];
            $response = $db->setTimeStampDialogue($currentUserId, $companionId);
            echo json_encode($response, JSON_UNESCAPED_UNICODE); die();
        }

        if($_POST['action'] === 'activity') {
            $companionId = $_POST['companionId'];
            $response = $db->getTimeStampDialogue($currentUserId, $companionId);
            echo json_encode($response, JSON_UNESCAPED_UNICODE); die();
        }
    }
