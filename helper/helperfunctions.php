<?php
	
	/**
	 * This class provides several static methods, each of which provides a bit more readable and writable code.
	 */
    class HelperFunctions
    {

        /**
         * An easier method for setting cookies to our domain.
         * @param string $cookieName yep, indeed! the name of the cookie.
         * @param string $cookieData data to save as cookie
         * @param string|null $path the path for setcookie
         * @return void
         */
        public static function setGoodCookie(string $cookieName, string $cookieData, string|null $path = null): void
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
        public static function callAPI(string $method, string $url, array|null $data, array|null $header){
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

    /*end class*/
    }
