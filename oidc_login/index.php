<?php
    require_once __DIR__ . '/../vendor/autoload.php';
    session_start();
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Max-Age: 1000");
    header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
    header("Access-Control-Allow-Methods: PUT, POST, GET, OPTIONS, DELETE");
//    header('Content-type: application/json');
    header('Content-type: text/html');

    // USED WITH MY CODESPACE
    //$authUrl = 'https://conorep-supreme-capybara-r44vrqw9rjh6px-3000.preview.app.github.dev/api/lti/authorize_redirect';

    // TESTING WITH SALTIRE
    $authUrl = 'https://saltire.lti.app/platform/login/oauth2/token';
    // AUTH URL FOR PRODUCTION ENVIRONMENT (THIS IS WHAT SHOULD BE USED IN CODESPACE AND PRODUCTION)
    //$authUrl = 'https://canvas.instructure.com/api/lti/authorize_redirect';
    // NEW AUTH URL - REQUIRED TO MIGRATE TO THIS AS OF July 1st, 2023:
    //$authUrl = 'https://sso.canvaslms.com/api/lti/authorize_redirect';
    // TEST VERSION
    //$authUrl = 'https://canvas.test.instructure.com/api/lti/authorize_redirect';
    // BETA VERSION
    //$authUrl = 'https://canvas.beta.instructure.com/api/lti/authorize_redirect';
    $stateParam = uniqid('state_');
    $nonceParam = uniqid('nonce_');

//    var_dump($_POST);

    $redirect_uri = "https://cobrien2.greenriverdev.com/whalesong/oidc_login/index.php";

    $client_id = $_POST["client_id"];
    $target_link_uri = $_POST["target_link_uri"];
    $login_hint = $_POST["login_hint"];
    $lti_message_hint = $_POST["lti_message_hint"];

//    $lti_message_hint = explode('.', $lti_message_hint);
//    $lti_message_hint = $lti_message_hint[2];

    echo '<br/>';
//    echo 'LTIMESSAGEHINT: ' . $lti_message_hint;
    echo '<br/>';

    $data = array(
        'scope' => 'openid',
        'response_type' => 'id_token',
        'client_id' => $client_id,
        'redirect_uri' => $redirect_uri,
        'login_hint' => $login_hint,
        'state' => $stateParam,
        'response_mode' => 'form_post',
        'nonce' => $nonceParam,
        'prompt' => 'none',
        'lti_message_hint' => $_POST["lti_message_hint"]
    );

    $jsonData = json_encode($data);

    echo $jsonData;

    $_SESSION['lti_message_hint'] = $lti_message_hint;
    if(!isset($_SESSION['stateParam']))
    {
        $_SESSION['stateParam'] = $stateParam;
    }
    if(!isset($_SESSION['nonceParam']))
    {
        $_SESSION['nonceParam'] = $nonceParam;
    }

//    $dataArgs =
//        'lti_message_hint='.$lti_message_hint.'&scope=openid&response_type=id_token&client_id='.$client_id.
//        '&redirect_uri='.$redirect_uri.'&state='.$stateParam.'&response_mode=form_post&nonce='.$nonceParam.
//        '&prompt=none'.'&login_hint='.$login_hint;
//
//    echo $dataArgs;

    $ch = curl_init($authUrl);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type:application/json',
        'Content-Length: ' . strlen($jsonData)
    ));

    $response = curl_exec($ch);
    if(curl_errno($ch)) {
        echo 'Error: ' . curl_error($ch);
    } else {
        echo $response;
    }
    curl_close($ch);