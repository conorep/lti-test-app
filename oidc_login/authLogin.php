<?php
    require_once __DIR__ . '/../vendor/autoload.php';
    use Firebase\JWT\JWT;
	
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
                $thisJWT = new JWT();
                $thisIAT = JWT::$timestamp;

                $idToken = $_POST['id_token'];
                $authToken = $_POST['authenticity_token'];

                $jwtArr = explode('.', $idToken);

                $jwtPrt1 = base64_decode($jwtArr[0]);
                $jwtPrt2 = base64_decode($jwtArr[1]);
                echo('ID TOKEN PART 1: ' . $jwtPrt1 . '<br/>');
                echo('ID TOKEN PART 2: ' . $jwtPrt2 . '<br/>');

//                TODO: need to verify signature with  existing Canvas JWKs
                $clientID = $jwtPrt2["aud"];

                //TODO: is is correct?
                //TODO: iat needs to be 'issued at' time
                //TODO: exp
                //TODO: jti needs to be JWT id?
                // "jti" => "dffdbdce-a9f1-427b-8fca-604182198783"
                $clientAssertion = array
                    (
                        "iss" => "cobrien2.greenriverdev.com",
                        "sub" => $clientID,
                        "aud" => $tokenUrl,
                        "iat" => $thisIAT,
                        "exp" => 36000
                    );

                /*sample scope, need to config client_assertion*/
                $tokenAssertion = array
                    (
                        "grant_type" => "client_credentials",
                        "client_assertion_type" => "urn:ietf:params:oauth:client-assertion-type:jwt-bearer",
                        "client_assertion" => "MAKE JWT WITH SIG HERE",
                        "scope" => "https://purl.imsglobal.org/spec/lti-ags/lineitem https://purl.imsglobal.org/spec/lti-ags/result/read"

                    );

                /*
                 * Example request:
                    {
                        "grant_type": "client_credentials",
                        "client_assertion_type": "urn:ietf:params:oauth:client-assertion-type:jwt-bearer",
                        "client_assertion": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImtpZCI6IjIwMTktMDYtMjFUMTQ6NTk6MzBaIn0.eyJpc3MiOiJodHRwczovL3d3dy5teS10b29sLmNvbSIsInN1YiI6Ilx1MDAzY2NsaWVudF9pZFx1MDAzZSIsImF1ZCI6Imh0dHA6Ly9cdTAwM2NjYW52YXNfZG9tYWluXHUwMDNlL2xvZ2luL29hdXRoMi90b2tlbiIsImlhdCI6MTU2MTc1MDAzMSwiZXhwIjoxNTYxNzUwNjMxLCJqdGkiOiJkZmZkYmRjZS1hOWYxLTQyN2ItOGZjYS02MDQxODIxOTg3ODMifQ.lUHCwDqx2ukKQ2vwoz_824IVcyq-rNdJKVpGUiJea5-Ybk_VfyKW5v0ky-4XTJrGHkDcj0T9J8qKfYbikqyetK44yXx1YGo-2Pn2GEZ26bZxCnuDUDhbqN8OZf4T8DnZsYP4OyhOseHERsHCzKF-SD2_Pk6ES5-Z8J55_aMyS3w3tl4nJtwsMm6FbMDp_FhSGE4xTwkBZ2KNM4JqkCwHGX_9KcpsPsHRFQjn9ysTeg-Qf7H2QFgFMFjsfQX-iSL_bQoC2npSz7rQ8awKMhCEYdMYZk2vVhQ7XQ8ysAyf3m1vlLbHjASpztcAB0lz_DJysT0Ep-Rh311Qf_vXHexjVA",
                        "scope": "https://purl.imsglobal.org/spec/lti-ags/lineitem https://purl.imsglobal.org/spec/lti-ags/result/read"
                     }
                  * Example of the decoded client_assertion JWT in the above request:
                    {
                        "iss": "https://www.my-tool.com",
                        "sub": "<client_id>",
                        "aud": "https://<canvas_domain>/login/oauth2/token",
                        "iat": 1561750031,
                        "exp": 1561750631,
                        "jti": "dffdbdce-a9f1-427b-8fca-604182198783"
                     }

                */
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
	$canvasKeys = file_get_contents('https://conorep-zany-cod-pqq64gxvjjgfrjv9-3000.preview.app.github.dev/api/lti/security/jwks');

//    echo($canvasKeys);
///	$jwtsFromCanvas = json_decode($jwtsFromCanvas, true);
	
///	echo $jwtsFromCanvas;
	
///	$publicKeys = Firebase\JWT\JWK::parseKeySet($jwtsFromCanvas);
	
///	$decoded = Firebase\JWT\JWT::decode($jwtString, $publicKeys);