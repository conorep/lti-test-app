<?php
    require_once __DIR__ . '/../vendor/autoload.php';
	
	if(empty(session_id()))
	{
		session_start();
	}
	
//    header("Access-Control-Allow-Origin: *");
//    header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
//    header("Access-Control-Allow-Methods: POST, GET");
//    header('Content-type: application/json');
	
	// The JWT library makes use of a leeway (in seconds) to account for when there is a clock skew times between
	// the signing and verifying servers
//	Firebase\JWT\JWT::$leeway = 20;
	
	// URL to post info to
	$tokenUrl = "https://conorep-zany-cod-pqq64gxvjjgfrjv9-3000.preview.app.github.dev/login/oauth2/token";
	
	/*
	 *     currently in session (hopefully)
	 * $_SESSION['lti_message_hint'] = $lti_message_hint;
	 * $_SESSION['stateParam'] = $stateParam;
	 * $_SESSION['nonceParam'] = $nonceParam;
	 * $_SESSION['targetUri'] = $target_link_uri;
	 *
	 * 		needs to be sent back to token
	 *
	 *
	 * */
//	header("Location: " . $_SESSION['targetUri']);
	setcookie('you', 'arethereeeeee');
	if(isset($_COOKIE['stateParam']))
	{
		setcookie('yourehere', 'WOW');
		if($_POST['state'] != $_SESSION['stateParam'])
		{
			echo "STATE DOES NOT MATCH. ERROR.";
//			die();
		} else
		{
			echo "NICEEEEEE";
			$localState = $_SESSION['stateParam'];
//			header("Location: " . $_SESSION['targetUri']);
		}
	}
	
	//$authToken = $_POST['authenticity_token'];
	//$jwtString = $_POST['id_token'];
	
	
	// URL containing jwks - need to use to decode incoming jwt string
//	$jwtsFromCanvas =
//		file_get_contents("https://conorep-zany-cod-pqq64gxvjjgfrjv9-3000.preview.app.github.dev/api/lti/security/jwks");
//
//	$jwtsFromCanvas = json_decode($jwtsFromCanvas, true);
	
//	echo $jwtsFromCanvas;
	
//	$publicKeys = Firebase\JWT\JWK::parseKeySet($jwtsFromCanvas);
	
//	$decoded = Firebase\JWT\JWT::decode($jwtString, $publicKeys);
	

echo 'hey its authlogin';