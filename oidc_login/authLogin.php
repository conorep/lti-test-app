<?php
    require_once __DIR__ . '/../vendor/autoload.php';
	use Ramsey\Uuid\Uuid;
	use Firebase\JWT\JWT;
	use Firebase\JWT\JWK;
    include __DIR__ . '/../helper/includeheaders.php';
	include __DIR__ . '/../helper/helperfunctions.php';
    http_response_code(302);

	// URL to post info to
	//TODO: make this dynamic! no hard-coded URI.
	$tokenUrl = "https://canvas.granny.dev/login/oauth2/token";

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
				
				//TODO: make this dynamic! no hard-coded URI.
				$canvasJWKs = file_get_contents('https://canvas.granny.dev/api/lti/security/jwks');
				$canvasJWKs = json_decode($canvasJWKs, true);
				try
				{
					$jwkArr = JWK::parseKeySet($canvasJWKs, 'RS256');
					$jwkVerify = JWT::decode($idToken, $jwkArr);
					$jwkAssocArr = json_decode(json_encode($jwkVerify), true);
				}
				catch(Exception $e)
				{
					echo $e->getMessage() . "<br/>";
					while($e = $e->getPrevious())
					{
						echo 'Previous exception: '.$e->getMessage() . "<br/>";
					}
					die();
				}
				
				//Write action to txt log
				$log  = "User: ".$_SERVER['REMOTE_ADDR'].' - '.date("F j, Y, g:i a").PHP_EOL.
					"Data: ". print_r($jwkAssocArr, true) . ' ' . PHP_EOL .
					'-------------------------' . PHP_EOL;
				//-
				file_put_contents('./log_'.date("j.n.Y").'.txt', $log, FILE_APPEND);
				
                $clientID = $jwkAssocArr['aud'];
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
                        'scope' => "https://purl.imsglobal.org/spec/lti-ags/scope/lineitem https://purl.imsglobal.org/spec/lti-nrps/scope/contextmembership.readonly"
                    ];
				$body =
				    [
					    'form_params' => $tokenAssertion
				    ];

                $body = json_encode($body);

                $response = HelperFunctions::callAPI("POST", $tokenUrl, $tokenAssertion, null);
                $response = json_decode($response, true);

                if(isset($response['access_token']))
                {
					$cookieData =
						[
							['LTI_access_token', $response['access_token'], '/'],
							['LTI_token_type', $response['token_type'], '/'],
							['LTI_token_expiration', $response['expires_in'], '/'],
							['LTI_token_scope', $response['scope'], '/'],
						];
					HelperFunctions::setGoodCookies($cookieData);

                    header("Location: " . $_COOKIE['targetLink'], FALSE, 302);
                    exit();
                } else{
                    die("Error: token not obtained!");
                }

            } else
            {
                die("ERROR. NO ID TOKEN!");
            }
		}
	} else
    {
        die('Error. How can I compare states when there are no states?');
    }