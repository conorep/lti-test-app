<?php

    use JetBrains\PhpStorm\NoReturn;

    class HelperFunctions
    {
        private static $formSubmissionTimeout = 1000;

        /**
         * @return void
         */
        public static function sessionStuff(): void
        {
            if (empty(session_id()))
            {
                session_start(['cookie_lifetime' => 86400]);
            } else
            {
                session_start();
            }
            header('Access-Control-Allow-Origin: *');
            header('Access-Control-Allow-Methods: POST, GET');
        }

        /**
         * An easier method for setting cookies to our domain.
         * @param string $cookieName
         * @param string $cookieData
         * @return void
         */
        public static function setGoodCookie(string $cookieName, string $cookieData): void
        {
            setcookie($cookieName, $cookieData,
                ['domain' => 'cobrien2.greenriverdev.com', 'secure' => true, 'samesite' => 'None']);
        }

        /**
         * Generate a web page containing an auto-submitted form of parameters.
         *
         * @param string $url URL to which the form should be submitted
         * @param array $params Array of form parameters
		 * @param string $target form target
		 * @param string $path the page to redirect to
         * @return void
         */
        #[NoReturn] public static function sendForm(string $url, array $params, string $target, string $path): void
        {
            $page = ' ';
            $page .= <<< EOD
                            <!DOCTYPE html>
                            <body>

                            EOD;
            $page .= <<< EOD
                            
                                <form id="postSubmitIFrame" method="post" action="$url"  enctype="application/x-www-form-urlencoded" target="tool_content">

                            EOD;
            if (!empty($params))
            {
                foreach ($params as $key => $value)
                {
                    if (!is_array($value))
                    {
                        $page .= <<< EOD
                                    <input type="hidden" name="$key" id="id_$key" value="$value" />
                            EOD;
                    } else
                    {
                        foreach ($value as $element)
                        {
                            $page .= <<< EOD
                                    <input type="hidden" name="$key" id="id_$key" value="$element" />

                            EOD;
                        }
                    }
                }
            }
            $page .= <<< EOD
                                </form>
                                <script type="text/javascript">
                                    (function()
                                    {
                                        document.querySelector('form#postSubmitIFrame').submit();
                                        return true;
                                    })();
                                </script>
                            </body>
                        EOD;
            echo $page;
            exit();
        }

        /**
         * This was an attempt to post to a server page to run a function. I got no further using this.
         * @param string $url
         * @param mixed $params
         * @return void
         */
        public static function ajaxPost(string $url, mixed $params): void
        {
            $internalUrl = "https://cobrien2.greenriverdev.com/whalesong/oidc_login/authPost.php";
            $page = ' ';
            $page .= <<< EOD
                        <!DOCTYPE html>
                        <head>
                            <script type="text/javascript" src="/../whalesong/helper/jquerymin.js"></script>
                            <title>Ajax Post</title>
                        </head>
                        <script type="text/javascript">
                            let paramData = JSON.stringify($params);
                            fetch('$internalUrl', 
                            {
                                method: "POST",
                                mode: "cors",
                                headers: {
                                    "Accept": "application/json",
                                    "Content-Type": "application/json"
                                    },
                                body: paramData
                            })
                            .then(data => console.log(data))
                            .catch(e => console.error(e.message));
                            
                        </script>

                        EOD;
            echo $page;
        }

    /*end class*/
    }
