<?php
    require_once __DIR__ . '/../vendor/autoload.php';
	use Ramsey\Uuid\Uuid;
	use Firebase\JWT\JWT;
	include __DIR__ . '/../helper/helperfunctions.php';
    include __DIR__ . '/../helper/includeheaders.php';
	$helpers = new HelperFunctions();
    http_response_code(302);
	
//	if(!isset($POST['state']))
//	{
//		die("WOW");
//	}
	

	// URL to post info to
	$tokenUrl = "https://canvas.granny.dev/login/oauth2/token";
	
//	$redirect_uri = 'https://cobrien2.greenriverdev.com/whalesong/oidc_login/authLogin.php';
	$redirect_uri ='https://cobrien2.greenriverdev.com/whalesong/pages/coursenav/';

	if(isset($_COOKIE['stateParameter']))
	{
		if($_POST['state'] != $_COOKIE['stateParameter'])
		{
			die("STATE DOES NOT MATCH. ERROR.");
		} else
		{
            if(isset($_POST['id_token']))
            {
				$newUuid = (string)Uuid::uuid4();

                $idToken = $_POST['id_token'];
                $authToken = $_POST['authenticity_token'];

                $jwtArr = explode('.', $idToken);
                $jwtPrt2 = base64_decode($jwtArr[1]);
				$jsonDecode = json_decode($jwtPrt2);

//                TODO: need to verify signature with  existing Canvas JWKs
                $clientID = $jsonDecode->aud;
                $payload = array
                    (
                        "iss" => "https://cobrien2.greenriverdev.com",
                        "sub" => $clientID,
                        "aud" => $tokenUrl,
                        "iat" => time(),
                        "exp" => time() + (60 * 60),
						"jti" => $newUuid
                    );

                //TODO: think about what the best practice is for ensuring that the private key stays private
                // THOUGHTS: could do whole oauth process up to authLogin with php, then have React handle the actual
                //  send/receive of the info to get the token - i.e. php hands off the signed JWT to React to forward
                //  to the oauth token Canvas URI
				$getPrivKey = file_get_contents('../db_comms/keys/private.key');
				$jwt = JWT::encode($payload,  $getPrivKey, 'RS256', 'uniqueWhaleSongGP2023');
				
                $tokenAssertion =
                    [
                        'grant_type' => "client_credentials",
                        'client_assertion_type' => "urn:ietf:params:oauth:client-assertion-type:jwt-bearer",
                        'client_assertion' => $jwt,
                        'scope' => "https://purl.imsglobal.org/spec/lti-ags/scope/lineitem https://purl.imsglobal.org/spec/lti-nrps/scope/contextmembership.readonly",
						'redirect_uri' => $redirect_uri
                    ];
				$body =
				    [
					    'form_params' => $tokenAssertion
				    ];

                $body = json_encode($body);
				$helpers::sendForm($tokenUrl, $tokenAssertion, $_COOKIE['targetLink'], $redirect_uri);
            } else
            {
                die("ERROR. NO ID TOKEN!");
            }
		}
	} else
    {
        die('Error. How can I compare states when there are no states?');
    }