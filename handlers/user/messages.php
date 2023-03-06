<?php
    $link = include_once 'includes/connect.php';
    require_once('classes/Page.php');
    require_once('classes/DataBase.php');

    $page = new Page('templates/base.html', 'templates/user/messages.html');
    $layout = $page->createTemplateVars()->pasteContent();
    return $layout;