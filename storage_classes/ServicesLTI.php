<?php
    namespace ServicesLTI;

//    use DatabaseLTI\DatabaseLTI;
//    use Packback\Lti1p3\ImsStorage\ImsCache;
//    use Packback\Lti1p3\ImsStorage\ImsCookie;
    use Packback\Lti1p3\Interfaces\ICache;
    use Packback\Lti1p3\Interfaces\ICookie;
    use Packback\Lti1p3\Interfaces\IDatabase;
    use Packback\Lti1p3\Interfaces\ILtiServiceConnector;
    use Packback\Lti1p3\JwksEndpoint;
    use Packback\Lti1p3\LtiDeepLinkResource;
    use Packback\Lti1p3\LtiMessageLaunch;
    use Packback\Lti1p3\LtiOidcLogin;
    use Packback\Lti1p3\OidcException;
    use Packback\Lti1p3\LtiException;
    use Packback\Lti1p3\ServiceRequest;

    /**
     * The ServicesLTI class simplifies instantiation of the DatabaseLTI, ImsCache, and ImsCookie classes.
     * Much of this code mirrors the 'packbackbooks' implementation online (as of 4/1/2023).
     */
    class ServicesLTI
    {
        public IDatabase $db;
        public ICache $cache;
        public ICookie $cookie;
        public ILtiServiceConnector $serviceConnector;
        private String $launchUrl;

        function __construct
        (
            IDatabase $db,
            ICache $cache,
            ICookie $cookie,
            ILtiServiceConnector $serviceConnector,
            String $launchUrl
        )
        {
            $this->db = $db;
            $this->cache = $cache;
            $this->cookie = $cookie;
            $this->serviceConnector = $serviceConnector;

            $this->launchUrl = $launchUrl;
        }

        // TODO: check if this works without adding in a request for doOidcLoginRedirect
        // allegedly it should grab stuff from $_POST
        /**
         * Validate an LTI launch.
         * @param array $requestData array of data from request
         * @return LtiMessageLaunch
         * @throws LtiException
         */
        public function validateLaunch(array $requestData): LtiMessageLaunch
        {
            return LtiMessageLaunch::new($this->db, $this->cache, $this->cookie, $this->serviceConnector)
                ->validate($requestData);
        }

        /**
         * Launch a deep link.
         * @param LtiMessageLaunch $launch
         */
        public function launchDeepLink(LtiMessageLaunch $launch): void
        {
            $resource = LtiDeepLinkResource::new()
                ->setUrl($this->launchUrl);
            $launch->getDeepLink()->outputResponseForm([$resource]);
        }

    // TODO: check if this works without adding in a request for doOidcLoginRedirect
    // allegedly it should grab stuff from $_REQUEST
        /**
         * Get the URL for an OIDC login redirect.
         *
         * @throws OidcException
         */
        public function login(): string
        {
            return LtiOidcLogin::new($this->db, $this->cache, $this->cookie)
                ->doOidcLoginRedirect($this->launchUrl)
                ->getRedirectUrl();
        }

//        /**
//         * Get a JWKS objects (optionally by ID).
//         */
//        public function jwks(string $id = null): array
//        {
//            $issuer = Issuer::findOrFail($id);
//
//            return JwksEndpoint::new([$issuer->kid => $issuer->tool_private_key])->getPublicJwks();
//        }

    }