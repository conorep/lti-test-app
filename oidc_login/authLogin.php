<?php
    require_once __DIR__ . '/../vendor/autoload.php';
	
	if(empty(session_id()))
	{
		session_start();
	}
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding');
    header('Access-Control-Allow-Methods: POST, GET');
    header('Content-type:  text/html');
	
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
	if(isset($_COOKIE['stateParameter']))
	{
		if($_POST['state'] != $_COOKIE['stateParameter'])
		{
			die("STATE DOES NOT MATCH. ERROR.");
		} else
		{
			$localState = $_COOKIE['stateParameter'];
		}
	} else
    {
        die('Error. How can I compare states when there are no states?');
    }
	
	$authToken = $_POST['authenticity_token'];
    echo $authToken;
//	$jwtString = $_POST['id_token'];
//
//    $jwtArr = explode('.', $jwtString);
//
//    echo(base64_decode($jwtArr[0]));
//    echo(base64_decode($jwtArr[1]));
	
	
	// URL containing jwks - need to use to decode incoming jwt string
	$canvasKeys = file_get_contents('https://conorep-zany-cod-pqq64gxvjjgfrjv9-3000.preview.app.github.dev/api/lti/security/jwks');

//    echo($canvasKeys);
///	$jwtsFromCanvas = json_decode($jwtsFromCanvas, true);
	
///	echo $jwtsFromCanvas;
	
///	$publicKeys = Firebase\JWT\JWK::parseKeySet($jwtsFromCanvas);
	
///	$decoded = Firebase\JWT\JWT::decode($jwtString, $publicKeys);