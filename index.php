<?php
	require_once __DIR__ . '/vendor/autoload.php';
	
    // The JWT library makes use of a leeway (in seconds) to account for when there is a clock skew times between
	// the signing and verifying servers
	Firebase\JWT\JWT::$leeway = 5;
	
	/*hard coded, retrieved from queries*/
	$jwtString = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJjYW52YXNfZG9tYWluIjoiY29ub3JlcC1zdXByZW1lLWNhcHliYXJhLXI0NHZycXc5cmpoNnB4LTMwMDAucHJldmlldy5hcHAuZ2l0aHViLmRldi8iLCJjb250ZXh0X3R5cGUiOiJDb3Vyc2UiLCJjb250ZXh0X2lkIjoxMDAwMDAwMDAwMDAwMSwiY2FudmFzX2xvY2FsZSI6ImVuIiwiaW5jbHVkZV9zdG9yYWdlX3RhcmdldCI6dHJ1ZSwiZXhwIjoxNjgwMzA3OTQzfQ.edUe2H7h0N6DLuNA9uNTcVveck9Xzbsfm7V2qjYw7OY";
	
	$jwtsFromCanvas = file_get_contents('https://canvas.instructure.com/api/lti/security/jwks');
	
//	$jwtsFromCanvas = Firebase\JWT\JWT::urlsafeB64Decode($jwtsFromCanvas);
	
//	$jwtsFromCanvas = Firebase\JWT\JWT::jsonDecode($jwtsFromCanvas);

	$jwtsFromCanvas = json_decode($jwtsFromCanvas, true);
	print_r($jwtsFromCanvas);
	$publicKeys = Firebase\JWT\JWK::parseKeySet($jwtsFromCanvas);
	
	$decoded = Firebase\JWT\JWT::decode($jwtString, $publicKeys);
	
	var_dump($decoded);