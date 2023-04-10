<?php
    require_once __DIR__ . '/../vendor/autoload.php';
    include __DIR__ . '/../helper/helperfunctions.php';
    include __DIR__ . '/../helper/includeheaders.php';

    $helpers = new HelperFunctions();

    $tokenUrl = "https://conorep-zany-cod-pqq64gxvjjgfrjv9-3000.preview.app.github.dev/login/oauth2/token";

    $content = trim(file_get_contents("php://input"));
    $_arr = json_decode($content, true);

    $helpers::sendForm($tokenUrl, $_arr, '', '');

    exit();
