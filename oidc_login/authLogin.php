<?php
    require_once __DIR__ . '/../vendor/autoload.php';
	
	use GuzzleHttp\Psr7\Request;
	use Ramsey\Uuid\Uuid;
	use Firebase\JWT\JWT;
	use GuzzleHttp\Client;
    include __DIR__ . '/../helper/helperfunctions.php';

    sessionStuff();
    if (isset($_POST['access_token']))
    {
        setcookie('oauthcodeLTI', $_POST['code'],
            ['domain' => 'cobrien2.greenriverdev.com', 'secure' => true, 'samesite' => 'None']);
    }

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
				
                $tokenAssertion =
                    [
                        "grant_type" => "client_credentials",
                        "client_assertion_type" => "urn:ietf:params:oauth:client-assertion-type:jwt-bearer",
                        "client_assertion" => $jwt,
                        "scope" => "https://purl.imsglobal.org/spec/lti-ags/lineitem https://purl.imsglobal.org/spec/lti-ags/result/read",
                    ];

                $headers =
                    [
                        'Content-Type' => 'application/x-www-form-urlencoded',
                        'Accept' => 'application/json'
                    ];
				$body =
				    [
					'form_params' => $tokenAssertion
				    ];

				$client = new Client();

				$req = new Request('POST', $tokenUrl, [
                    'allow_redirects' => [
                        'max'             => 10,
                        'referer'         => true
                    ]
                ]);
				$response = $client->sendAsync($req, [$headers, $tokenAssertion]);
                $response->then(
                    function($res)
                    {
                        echo $res;
                    }
                );
                $response->wait(true);
                var_dump($response);
//                echo $response->getBody();
//                $resBody = (string)$response->getBody();
//                var_dump($resBody);
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