<?php
	namespace LTI_DB;
	require_once __DIR__ . '/../vendor/autoload.php';
	session_start();
	use Packback\Lti1p3;
	
	/**
	 * This placeholder class acts as a dummy database.
	 */
    class LTI_DB
	{
        private static String $clientID = '10000000000010';
        private static String $devKey = 'r7JH5HMkfoqZ3Jaesdp72skA0NyVs56mmqZs35PGnwzmlHGlxZRZeDJhAofJdaWI';

        /**
         * This function returns the project's public key.
         * @return bool|string false if key not found, string representation of key if found
         */
        public static function getPubKey(): bool|string
        {
            return file_get_contents('/keys/public.key');
        }

        /*TODO: this is not particularly safe. this key needs to be private. good for testing only.*/
        /**
         * This function returns the project's private key.
         * @return bool|string false if key not found, string representation of key if found
         */
        public function getPrivKey(): bool|string
        {
            return file_get_contents('/keys/private.key');
        }

        /**
         * This function returns the client_id associated with the project in Canvas codespace.
         * @return string client ID registered with Canvas Github codespace
         */
        public static function getClientId(): string
        {
            return self::$clientID;
        }

        /**
         * This function returns the development key associated with the project in Canvas codespace.
         * @return string dev key registered with Canvas Github codespace
         */
        public static function getDevKey(): string
        {
            return self::$devKey;
        }
	
	}