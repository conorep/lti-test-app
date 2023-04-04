<?php
	require_once __DIR__ . '/../vendor/autoload.php';
	if(empty(session_id()))
	{
		session_start();
	}
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding');
    header('Access-Control-Allow-Methods: POST, GET');
//    header('Content-type:  text/html');

	// USED WITH MY CODESPACE
	$authUrl = 'https://conorep-zany-cod-pqq64gxvjjgfrjv9-3000.preview.app.github.dev/api/lti/authorize_redirect';
	
	
	//$authUrl = 'https://canvas.instructure.com/api/lti/authorize_redirect'; <--- auth url for production environs
	//$authUrl = 'https://sso.canvaslms.com/api/lti/authorize_redirect'; <--- new auth url for production environs
	// (have to use as of July 1s, 2023)
	$stateParam = uniqid();
	$nonceParam = uniqid();
	
	$redirect_uri = 'https://cobrien2.greenriverdev.com/whalesong/oidc_login/authLogin.php';

	$client_id = $_POST['client_id'] ?? die('ERROR - NO CLIENT ID!');
	$target_link_uri = $_POST['target_link_uri'] ?? die('ERROR - NO TARGET LINK URI!');
	$login_hint = $_POST['login_hint'] ?? die('ERROR - NO LOGIN HINT!');
	$lti_message_hint = $_POST['lti_message_hint'] ?? die('ERROR - LTI MESSAGE HINT!');
	
	$dataArgs =
	'?scope=openid'.
    '&response_type=id_token'.
	'&client_id='.$client_id.
	'&redirect_uri='.$redirect_uri.
    '&login_hint='.$login_hint.
	'&state='.$stateParam.
	'&response_mode=form_post'.
    '&nonce='.$nonceParam.
	'&prompt=none'.
    '&lti_message_hint='.$lti_message_hint;

    $authUrl .= $dataArgs;
	
	setcookie('stateParameter', $stateParam, ['domain'=>'cobrien2.greenriverdev.com', 'secure'=>true,'samesite'=>'None']);
	setcookie('nonceParameter', $nonceParam, ['domain'=>'cobrien2.greenriverdev.com', 'secure'=>true,'samesite'=>'None']);
	
	header('Location: '.$authUrl, 302);
    die();
	