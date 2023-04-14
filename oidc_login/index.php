<?php
    include __DIR__ . '/../helper/includeheaders.php';
    include __DIR__ . '/../helper/helperfunctions.php';
    http_response_code(302);
	
	$stateParam = uniqid();
	$nonceParam = uniqid();

	$client_id = $_POST['client_id'] ?? die('ERROR - NO CLIENT ID!');
	$target_link_uri = $_POST['target_link_uri'] ?? die('ERROR - NO TARGET LINK URI!');
	$login_hint = $_POST['login_hint'] ?? die('ERROR - NO LOGIN HINT!');
	$lti_message_hint = $_POST['lti_message_hint'] ?? die('ERROR - LTI MESSAGE HINT!');
	
	$launchOrigin = $_SERVER['HTTP_ORIGIN'] ?? die('ERROR - NO ORIGIN!');
	$authUrl = $launchOrigin . '/api/lti/authorize_redirect';
	
	//TODO: make this dynamic (or tied to ENV)
	$redirect_uri = 'https://cobrien2.greenriverdev.com/whalesong/oidc_login/authLogin.php';
	
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
	
	HelperFunctions::setGoodCookies([['stateParameter', $stateParam], ['nonceParameter', $nonceParam],
		['clientId', $client_id], ['origin', $launchOrigin]]);
	if($target_link_uri != null)
    {
		HelperFunctions::setGoodCookie('targetLink', $target_link_uri);
    }

	header('Location: '.$authUrl, 302);
    exit();