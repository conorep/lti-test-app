<?php
	
	/**
	 * This class provides several static methods, each of which provides a bit more readable and writable code.
	 */
    class HelperFunctions
    {

        //TODO: make 'my domain' dynamic
        /**
         * An easier method for setting cookies to our domain.
         * @param string $cookieName yep, indeed! the name of the cookie.
         * @param string $cookieData data to save as cookie
         * @param string|null $path the path for setcookie
         * @return void
         */
        public static function setGoodCookie(string $cookieName, string $cookieData, ?string $path = null): void
        {
            $options =
                [
                    'domain' => 'cobrien2.greenriverdev.com',
                    'secure' => true,
                    'samesite' => 'None'
                ];
            if($path != null)
            {
                $options['path'] = $path;
            }

            setcookie($cookieName, $cookieData, $options);
        }
		
		/**
		 * This is a helper function to allow setting of many cookies at once.
		 * @param array $cookieData contains more than one piece of info to set a cookie
		 * @return void
		 */
		public static function setGoodCookies(array $cookieData): void
		{
			foreach($cookieData as $cookieInfo)
			{
				$cookiePath = count($cookieInfo) == 3 ? $cookieInfo[2] : null;
				self::setGoodCookie($cookieInfo[0], $cookieInfo[1], $cookiePath);
			}
		}

        /**
         * @param $method string post, put, or get
         * @param $url string URL to contact
         * @param $data array|null data for request body or null
         * @param $header array|null array of header info or null
         * @return bool|string|void
         */
        public static function callAPI(string $method, string $url, mixed $data, ?array $header){
			$timeout = 10;
            $curl = curl_init();
            switch ($method){
                case "POST":
                    curl_setopt($curl, CURLOPT_POST, 1);
                    if ($data)
                        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                    break;
                case "PUT":
                    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                    if ($data)
                        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                    break;
                default:
                    if ($data)
                        $url = sprintf("%s?%s", $url, http_build_query($data));
            }

            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLINFO_HEADER_OUT, 1);
			curl_setopt($curl, CURLOPT_HEADER, 1);
            curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
			
            if($header != null)
            {
                curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
            }

            $result = curl_exec($curl);
            if(!$result)
            {
                die("Connection Failure");
            }
			
            curl_close($curl);
            return $result;
        }
		
		/**
		 * This function creates a log file in the same location as the file that calls it.
		 * It's currently setup to handle logging of objects, arrays, strings, integers, doubles, and booleans.
		 * @param mixed $data
		 * @throws UnexpectedValueException if data is null
		 * @return void
		 */
		public static function logData(mixed $data): void
		{
			if($data == null)
			{
				throw new UnexpectedValueException("error - no logging null data!");
			}
			$logData = gettype($data) == 'object' || gettype($data) == 'array' ? print_r($data, true) : $data;
			$log = "User: ".$_SERVER['REMOTE_ADDR'].' - '.date("F j, Y, g:i a").PHP_EOL.
				"Data: ". $logData . ' ' . PHP_EOL .
				'-------------------------' . PHP_EOL;
			
			file_put_contents('./log_'.date("j.n.Y").'.txt', $log, FILE_APPEND);
		}
		
		/**
		 * This function provides the means to generate a new OpenSSL asymmetric key pair (private and public) in the
		 *    PEM format.
		 * NOTE: The machine that is running these algorithms reportedly needs to have a valid openssl.cnf file
		 *    installed on it.
		 * NOTE: The key name can also be written in a way that provides a path to where you want the files saved.
		 * @param string|null $privKeyName choose your own private key name/file type - default is priv.key
		 * @param string|null $pubKeyName choose your own public key name/file type - default is pub.key
		 * @param string|null $jwkName choose your own public JWK name/file type - default is pubkey.json
		 * @param string|null $jwkID choose your own public JWK ID - default is uniqueWhaleSongGP2023
		 * @return void
		 */
		public static function generateKeys(?string $privKeyName = 'priv.key', ?string $pubKeyName = 'pub.key',
											?string $jwkName = 'pubkey.json', ?string $jwkID = 'uniqueWhaleSongGP2023'): void
		{
			// 1EdTech LTI specs require private key bits to be at least 2048. 4096 is being used here.
			// 1EdTech also requires RSA key type
			// RS256 is default, but the digest_alg option will be left here for information purposes (RS256 is another
			// 	spec requirement)
			$keyConfig = [
				"digest_alg" => "RS256",
				"private_key_bits" => 4096,
				"private_key_type" => OPENSSL_KEYTYPE_RSA,
			];
			
			// this generates a new openssl key
			$privPem = openssl_pkey_new($keyConfig);
			
			// this exports  PEM format private key
			openssl_pkey_export($privPem, $privKey);
			
			// this gets the public key for the private key
			$pubPem = openssl_pkey_get_details($privPem)['key'];
			
			// this creates a private key file, given that there isn't already an existing file of that name in the
			// 		directory. if there is, it will be overwritten.
			openssl_pkey_export_to_file($privKey, $privKeyName);
			
			// the same idea applies to creating/overwriting a public key file
			file_put_contents($pubKeyName, $pubPem);
			
			// get public key details for usage with public JWK generation
			$keyInfo = openssl_pkey_get_details(openssl_pkey_get_public($pubPem));
			
			$jsonPub = [
				'keys' => [
					[
						'kty' => 'RSA',
						'n' => rtrim(str_replace(['+', '/'], ['-', '_'], base64_encode($keyInfo['rsa']['n'])), '='),
						'e' => rtrim(str_replace(['+', '/'], ['-', '_'], base64_encode($keyInfo['rsa']['e'])), '='),
						"alg" => "RS256",
						"use" => "sig",
						"kid" => "$jwkID"
					]
				]
			];
			
			$pubJWK = json_encode($jsonPub, JSON_PRETTY_PRINT).PHP_EOL;
			
			//output your spiffy new public JWK
			file_put_contents($jwkName, $pubJWK);
		}

    /*end class*/
    }
