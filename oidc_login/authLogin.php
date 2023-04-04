<?php
    require_once __DIR__ . '/../vendor/autoload.php';
	
	use GuzzleHttp\Psr7\Request;
	use Ramsey\Uuid\Uuid;
	use Firebase\JWT\JWT;
	use GuzzleHttp\Client;
	use GuzzleHttp\Promise;
	use GuzzleHttp\Exception;
	
	if(empty(session_id()))
	{
		session_start();
	}
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding');
    header('Access-Control-Allow-Methods: POST, GET');
//    header('Content-type:  text/html');
	
	// The JWT library makes use of a leeway (in seconds) to account for when there is a clock skew times between
	// the signing and verifying servers
	
	// URL to post info to
	$tokenUrl = "https://conorep-zany-cod-pqq64gxvjjgfrjv9-3000.preview.app.github.dev/login/oauth2/token";

	if(isset($_COOKIE['stateParameter']))
	{
		if($_POST['state'] != $_COOKIE['stateParameter'])
		{
			die("STATE DOES NOT MATCH. ERROR.");
		} else
		{
			$localState = $_COOKIE['stateParameter'];
			
            if(isset($_POST['id_token']))
            {
                JWT::$leeway = 20;
				$newUuid = (string)Uuid::uuid4();
				setcookie('newUuid', $newUuid, ['domain'=>'cobrien2.greenriverdev.com', 'secure'=>true,'samesite'=>'None']);

                $idToken = $_POST['id_token'];
                $authToken = $_POST['authenticity_token'];

                $jwtArr = explode('.', $idToken);

                $jwtPrt1 = base64_decode($jwtArr[0]);
                $jwtPrt2 = base64_decode($jwtArr[1]);
				$jsonDecode = json_decode($jwtPrt2);


//                TODO: need to verify signature with  existing Canvas JWKs
                $clientID = $jsonDecode->aud;
				
                $payload = array
                    (
                        "iss" => "cobrien2.greenriverdev.com",
                        "sub" => $clientID,
                        "aud" => $tokenUrl,
                        "iat" => time(),
                        "exp" => 360000,
						"jti" => $newUuid
                    );
				
				$getPrivKey = file_get_contents('../db_comms/keys/private.key');
				$jwt = JWT::encode($payload,  $getPrivKey, 'RS256');
//				echo 'JWT: ' . $jwt;
				
                $tokenAssertion = array
                    (
                        "grant_type" => "client_credentials",
                        "client_assertion_type" => "urn:ietf:params:oauth:client-assertion-type:jwt-bearer",
                        "client_assertion" => $jwt,
                        "scope" => "https://purl.imsglobal.org/spec/lti-ags/lineitem https://purl.imsglobal.org/spec/lti-ags/result/read",
						"redirect_uri" => "https://cobrien2.greenriverdev.com/whalesong/pages/coursenav/index.ph"
                    );
				
				$options = array
				(
					'headers' => ['Content-Type' => 'application/x-www-form-urlencoded'],
					'form_params' => $tokenAssertion,
					'debug' => true
				);
				
				
//				$tokenUrl .=
//					'?grant_type=client_credentials'.
//					'&client_assertion_type=urn:ietf:params:oauth:client-assertion-type:jwt-bearer'.
//					'&client_assertion='.$jwt.
//					'&scope=https://purl.imsglobal.org/spec/lti-ags/lineitem,https://purl.imsglobal.org/spec/lti-ags/result/read';
				
				$client = new Client();
//				$res =$client->request('GET', $tokenUrl, $options);
//				die();
//				var_dump($res->getHeaders());
//				echo '<br/>';
//				var_dump($res->getStatusCode());
//				echo '<br/>';
//				var_dump($res->getBody());
				$req = new Request('POST', $tokenUrl, $options);
				$promise = $client->sendAsync($req)->then(function($response)
				{
					print_r($response->getBody());
				});
				var_dump ($promise->wait(true));
            } else
            {
                die("ERROR. NO ID TOKEN!");
            }
		}
	} else
    {
        die('Error. How can I compare states when there are no states?');
    }
	
	
	// URL containing jwks - need to use to decode incoming jwt string
	//$canvasKeys = file_get_contents('https://conorep-zany-cod-pqq64gxvjjgfrjv9-3000.preview.app.github
	//.dev/api/lti/security/jwks');

//    echo($canvasKeys);
///	$jwtsFromCanvas = json_decode($jwtsFromCanvas, true);
	
///	echo $jwtsFromCanvas;
	
///	$publicKeys = Firebase\JWT\JWK::parseKeySet($jwtsFromCanvas);
	
///	$decoded = Firebase\JWT\JWT::decode($jwtString, $publicKeys);