<?php
	require_once __DIR__ . '/vendor/autoload.php';
	
    // The JWT library makes use of a leeway (in seconds) to account for when there is a clock skew times between
	// the signing and verifying servers
	Firebase\JWT\JWT::$leeway = 5;