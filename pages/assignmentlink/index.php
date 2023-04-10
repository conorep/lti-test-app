<?php
    include __DIR__ . '/../../helper/includeheaders.php';
    var_dump($_REQUEST);

    if (isset($_POST['access_token']))
    {
        echo "WHAT";
        exit();
//        setcookie('oauthcodeLTI', $_POST['code'],
//            ['domain' => 'cobrien2.greenriverdev.com', 'secure' => true, 'samesite' => 'None']);
//        header("Location: " . $_COOKIE['targetLink'], true,302);
//        exit();
    }

    echo '<h1>Congrats. You\'ve reached the Guided Practice course navigation link!</h1>';

