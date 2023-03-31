<?php
	namespace DatabaseLTI;
	use Packback\Lti1p3\Interfaces\IDatabase;
	
	/**
	 * The CacheLTI class implements a required interface (IDatabase) for db registration checks.
	 */
	class DatabaseLTI implements IDatabase
	{
		
		public function findRegistrationByIssuer($iss, $clientId = null)
		{
			// TODO: Implement findRegistrationByIssuer() method.
		}
		
		public function findDeployment($iss, $deploymentId, $clientId = null)
		{
			// TODO: Implement findDeployment() method.
		}
	}