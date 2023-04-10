<?php
    session_start();
//    include __DIR__ . '/../../helper/includeheaders.php';
    var_dump($_REQUEST);
    echo "HERE";

    if (isset($_POST['access_token']))
    {
        echo "WHAT";
        exit();

    }

    echo '<h1>You\'ve reached the Guided Practice course navigation page.</h1>';
    