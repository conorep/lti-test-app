<?php
	require_once __DIR__ . '/vendor/autoload.php';
	
    // The JWT library makes use of a leeway (in seconds) to account for when there is a clock skew times between
	// the signing and verifying servers
	Firebase\JWT\JWT::$leeway = 5;
	
	/*hard coded, retrieved from queries*///$jwtString = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJjYW52YXNfZG9tYWluIjoiY29ub3JlcC1zdXByZW1lLWNhcHliYXJhLXI0NHZycXc5cmpoNnB4LTMwMDAucHJldmlldy5hcHAuZ2l0aHViLmRldi8iLCJjb250ZXh0X3R5cGUiOiJDb3Vyc2UiLCJjb250ZXh0X2lkIjoxMDAwMDAwMDAwMDAwMSwiY2FudmFzX2xvY2FsZSI6ImVuIiwiaW5jbHVkZV9zdG9yYWdlX3RhcmdldCI6dHJ1ZSwiZXhwIjoxNjgwMzA3OTQzfQ.edUe2H7h0N6DLuNA9uNTcVveck9Xzbsfm7V2qjYw7OY';
    $jwtString = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJjYW52YXNfZG9tYWluIjoiY29ub3JlcC1zdXByZW1lLWNhcHliYXJhLXI0NHZycXc5cmpoNnB4LTMwMDAucHJldmlldy5hcHAuZ2l0aHViLmRldi8iLCJjb250ZXh0X3R5cGUiOiJDb3Vyc2UiLCJjb250ZXh0X2lkIjoxMDAwMDAwMDAwMDAwMSwiY2FudmFzX2xvY2FsZSI6ImVuIiwiaW5jbHVkZV9zdG9yYWdlX3RhcmdldCI6dHJ1ZSwiZXhwIjoxNjgwMzAwNDQ5fQ.0fqny8cKT1XaeevfC7K355vDClXnaxq7J75uxz-Ibe0';

    $jwtArr = explode('.', $jwtString);

    echo(base64_decode($jwtArr[0]));
    echo(base64_decode($jwtArr[1]));
    /*DON'T WORRY ABOUT THE SIG. THAT'S FOR CANVAS - SENDING IT BACK TO THEM ONLY.*/
//    echo(base256_decode($jwtArr[2]));