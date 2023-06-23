<?php

return [
    /**
     * SSO Admin URL
     *
     * Generally https://your-admin-server.com
     */
    'admin_url' => env('KEYCLOAK_ADMIN_URL', ''),

    /**
     * SSO URL
     *
     * Generally https://your-server.com
     */
    'base_url' => env('KEYCLOAK_BASE_URL', ''),

    /**
     * SSO Realm
     *
     * Default is master
     */
    'realm' => env('KEYCLOAK_REALM', 'master'),

    /**
     * The SSO Server realm public key (string).
     *
     * @see SSO >> Realm Settings >> Keys >> RS256 >> Public Key
     */
    'realm_public_key' => env('KEYCLOAK_REALM_PUBLIC_KEY', null),

    /**
     * SSO Client ID
     *
     * @see SSO >> Clients >> Installation
     */
    'client_id' => env('KEYCLOAK_CLIENT_ID', null),

    /**
     * SSO Client Secret
     *
     * @see SSO >> Clients >> Installation
     */
    'client_secret' => env('KEYCLOAK_CLIENT_SECRET', null),

    /**
    * GuzzleHttp Client options
    *
    * @link http://docs.guzzlephp.org/en/stable/request-options.html
    */
    'guzzle_options' => [],
    
    'web' => [
        /**
         * Page to redirect after callback if there's no "intent"
         *
         * @see RistekUSDI\SSO\Controllers\AuthController::callback()
         */
        'redirect_url' => '/home',

        /**
         * Routes name config.
         */
        'routes' => [
            'login' => 'sso.web.login',
            'callback' => 'sso.web.callback',
            'logout' => 'sso.web.logout',
        ],
    ]
];
