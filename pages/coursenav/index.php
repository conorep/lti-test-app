<?php
    include __DIR__ . '/../../helper/includeheaders.php';


    if (isset($_POST['access_token']))
    {
        setcookie('oauthcodeLTI', $_POST['code'],
            ['domain' => 'cobrien2.greenriverdev.com', 'secure' => true, 'samesite' => 'None']);
        header("Location: " . $_COOKIE['targetLink'], true,302);
        die();
    }


    echo '<h1>Congrats. You\'ve reached the Guided Practice course navigation link!</h1>';
    