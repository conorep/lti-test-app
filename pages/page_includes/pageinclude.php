<?php
	include __DIR__ . '/../../helper/helperfunctions.php';
	
	echo '<h1>You\'ve reached the Guided Practice '. $pageString . ' page.</h1>';
	
	if (isset($_COOKIE['LTI_access_token']))
	{
		echo "Tool authorized, token retrieved!" . '<br/>';
		echo "TOKEN: ". $_COOKIE['LTI_access_token'] . '<br/>';
		echo "TYPE: ". $_COOKIE['LTI_token_type'] . '<br/>';
		echo "EXPIRATION: ". $_COOKIE['LTI_token_expiration'] . '<br/>';
		echo "RESOURCE SCOPE(S): ". $_COOKIE['LTI_token_scope'] . '<br/>';
		echo '<pre><br/></pre>';
		
		/*hard-coded - I grabbed this URL from the JWT that Canvas sent when running through launch stuff*/
		/* https://canvas.granny.dev/api/lti/courses/2/names_and_roles */
		$coureNamesAndRoles = 'https://canvas.granny.dev/api/lti/courses/2/names_and_roles';
		
		$authorization = "Authorization: Bearer " . $_COOKIE['LTI_access_token'];
		$headers = ['Content-Type: application/json', $authorization];
		$response = HelperFunctions::callAPI("GET", $coureNamesAndRoles, null, $headers);
		$response = json_decode($response, true);
		echo 'NAMES AND ROLES DATA DUMP: <br/>';
		print_r($response);
	}
