<?php
    $link = include_once 'includes/connect.php';
    require_once('classes/Page.php');
    require_once('classes/Auth.php');
    require_once('classes/Image.php');

    if(!empty($_POST)) {
        $responseData = [];

        if(isset($_POST['submit'])) {
            $login = $_POST['login'];
            $pass1 = $_POST['password1'];
            $pass2 = $_POST['password2'];

            $auth = new Auth($link);
            $registration = $auth->registerUser($login, $pass1, $pass2);

            if(isset($registration)) $responseData['errors'] = $registration;
            else {
                $responseData['url'] = "/profile/{$_SESSION['auth']['id']}";
                echo json_encode($responseData, JSON_UNESCAPED_UNICODE);
                die();
            }


        } elseif(isset($_POST['change'])) {
            Image::deleteTmpImages();
            if(!empty($_FILES['avatar']['name'])) {
                $image = new Image($_FILES['avatar']);
                $image->uploadTmp();
                $responseData['image']['path'] = Image::$uploadedPath;
                $responseData['image']['name'] = Image::$tmpUploadedName;
            }
        }

        echo json_encode($responseData, JSON_UNESCAPED_UNICODE);
        die();

    } else {
        Image::deleteTmpImages();
        $page = new Page('templates/base.html', 'templates/auth/register.html');
        $page->createTemplateVars();
        return $page->pasteContent();
    }