<?php
	namespace CacheLTI;
	use Packback\Lti1p3\Interfaces\ICache;
	
	/**
	 * The CacheLTI class implements a required interface (ICache) for caching.
	 */
	class CacheLTI implements ICache
	{
		
		public function getLaunchData(string $key): ?array
		{
			// TODO: Implement getLaunchData() method.
			return null;
		}
		
		public function cacheLaunchData(string $key, array $jwtBody): void
		{
			// TODO: Implement cacheLaunchData() method.
		}
		
		public function cacheNonce(string $nonce, string $state): void
		{
			// TODO: Implement cacheNonce() method.
		}
		
		public function checkNonceIsValid(string $nonce, string $state): bool
		{
			// TODO: Implement checkNonceIsValid() method.
			return true;
		}
		
		public function cacheAccessToken(string $key, string $accessToken): void
		{
			// TODO: Implement cacheAccessToken() method.
		}
		
		public function getAccessToken(string $key): ?string
		{
			// TODO: Implement getAccessToken() method.
			return "ACCESS TOKEN";
		}
		
		public function clearAccessToken(string $key): void
		{
			// TODO: Implement clearAccessToken() method.
		}
	}