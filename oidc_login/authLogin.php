<?php
    require_once __DIR__ . '/../vendor/autoload.php';
	use Ramsey\Uuid\Uuid;
	use Firebase\JWT\JWT;
	include __DIR__ . '/../helper/helperfunctions.php';
    include __DIR__ . '/../helper/includeheaders.php';
	$helpers = new HelperFunctions();

    if (isset($_POST['access_token']))
    {
        setcookie('oauthcodeLTI', $_POST['code'],
            ['domain' => 'cobrien2.greenriverdev.com', 'secure' => true, 'samesite' => 'None']);
        header("Location: " . $_COOKIE['targetLink'], true,302);
        die();
    }

	// URL to post info to
	$tokenUrl = "https://conorep-zany-cod-pqq64gxvjjgfrjv9-3000.preview.app.github.dev/login/oauth2/token";

	if(isset($_COOKIE['stateParameter']))
	{
		var_dump($_POST);
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
                        "exp" => 1609459200,
						"jti" => $newUuid
                    );

                //TODO: think about what the best practice is for ensuring that the private key stays private
                // THOUGHTS: could do whole oauth process up to authLogin with php, then have React handle the actual
                //  send/receive of the info to get the token - i.e. php hands off the signed JWT to React to forward
                //  to the oauth token Canvas URI
				$getPrivKey = file_get_contents('../db_comms/keys/private.key');
				$jwt = JWT::encode($payload,  $getPrivKey, 'RS256', 'uniqueWhaleSongGP2023');
//				echo 'JWT: ' . $jwt;
				
                $tokenAssertion =
                    [
                        'grant_type' => "client_credentials",
                        'client_assertion_type' => "urn:ietf:params:oauth:client-assertion-type:jwt-bearer",
                        'client_assertion' => $jwt,
                        'scope' => "https://purl.imsglobal.org/spec/lti-ags/lineitem https://purl.imsglobal.org/spec/lti-ags/result/read",
                    ];
				
				$body =
				    [
					    'form_params' => $tokenAssertion
				    ];
//                $body = json_encode($body);

//                $newPageInfo = $helpers::sendForm($tokenUrl, $tokenAssertion, $_COOKIE['targetLink']);
//                $newPageInfo = submitData($tokenUrl, $body);
//                echo $newPageInfo;
				echo $helpers::sendForm($tokenUrl, $tokenAssertion);
                die();

            } else
            {
                die("ERROR. NO ID TOKEN!");
            }
		}
	} else
    {
        die('Error. How can I compare states when there are no states?');
    }

    /**
     * @param string $url
     * @param array $data
     * @return string page HTML/JS
     */
    function submitData(string $url, array $data) : string
    {
        $jsonData = json_encode($data);
        $page = '';
        $page .= <<<EOD
                    <div>
                    <script src="/whalesong/helper/jquerymin.js" type="text/javascript" crossorigin="anonymous"></script>
                        <script type="text/javascript" crossorigin="anonymous">
                            $.support.cors = true;
                            $.post("$url", $jsonData )
                            .done(function()
                            {
                                console.log('success!');
                            })
                            .fail(function()
                            {
                                console.log('failed.');
                                window.location.replace("{$_COOKIE['targetLink']}");
                            })
                            .then(function()
                            {
                                window.location.replace("{$_COOKIE['targetLink']}");
                            });
                            
                        </script>
                    </div>
                    EOD;

        return $page;
    }