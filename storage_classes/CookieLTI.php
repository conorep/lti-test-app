<?php
	namespace CookieLTI;
	use Packback\Lti1p3\Interfaces\ICookie;
	
	/**
	 * The CacheLTI class implements a required interface (ICookie) for cookie storage.
	 */
	class CookieLTI implements ICookie
	{
		
		public function getCookie(string $name): ?string
		{
			// TODO: Implement getCookie() method.
			return null;
		}
		
		public function setCookie(string $name, string $value, $exp = 3600, $options = []): void
		{
			// TODO: Implement setCookie() method.
		}
	}