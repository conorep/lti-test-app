<?php

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
//            header('Referrer-Policy: origin-when-cross-origin');
        }


        /**
         * Generate a web page containing an auto-submitted form of parameters.
         *
         * @param string $url URL to which the form should be submitted
         * @param array $params Array of form parameters
         * @return string
         */
        public static function sendForm(string $url, array $params): string
        {
            $page = ' ';
            $page .= <<< EOD
                            <!DOCTYPE html PUBLIC>
                            <html lang="en" xml:lang="en" xmlns="http://www.w3.org/1999/xhtml">
                            <head>
                                <meta http-equiv="content-language" content="EN" />
                                <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
                                <title>WhaleSong LTI Message</title>
                            </head>
                            
                            <body>

                            EOD;
            $page .= <<< EOD
                            
                                <form id="postSubmitIFrame" method="post" action="$url" target=""
                                encType="application/x-www-form-urlencoded">

                            EOD;
            if (!empty($params))
            {
                foreach ($params as $key => $value)
                {
                    $key = htmlentities($key, ENT_COMPAT | ENT_HTML401, 'UTF-8');
                    if (!is_array($value))
                    {
                        $value = htmlentities($value, ENT_COMPAT | ENT_HTML401, 'UTF-8');
                        $page .= <<< EOD
                                    <input type="hidden" name="$key" id="id_$key" value="$value" />
                            EOD;
                    } else
                    {
                        foreach ($value as $element)
                        {
                            $element = htmlentities($element, ENT_COMPAT | ENT_HTML401, 'UTF-8');
                            $page .= <<< EOD
                                    <input type="hidden" name="$key" id="id_$key" value="$element" />

                            EOD;
                        }
                    }
                }
            }

            $page .= <<< EOD
                        			<button type="submit">submit</button>
                                </form>
                                
                                <script type="text/javascript">
                                </script>
                            </body>
                        </html>
                        EOD;
            return $page;
        }



    /*end class*/
    }