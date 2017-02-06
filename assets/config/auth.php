<?php

return [
    'domains' => [

        'default' => [

            // using the 'user' repository from the 'app' bundle
            'repository' => 'app.user',
            'providers'  => [

                // include session support
                'session'  => [
                    'type' => 'http.session'
                ],

                // password login support
                'social'   => [
                    'type'             => 'social.oauth',
                    'persistProviders' => ['session']
                ],

                // include persistent cookies (remember me]
                'cookie'   => [
                    'type'             => 'http.cookie',

                    // when a cookie is used to login
                    // persist login using session too
                    'persistProviders' => ['session'],

                    // token storage
                    'tokens'           => [
                        'storage' => [
                            'type'            => 'database',
                            'table'           => 'tokens',
                            'defaultLifetime' => 3600 * 24 * 14 // two weeks
                        ]
                    ]
                ],

                // password login support
                'password' => [
                    'type'             => 'login.password',

                    // remember the user in session
                    // note that we did not add 'cookies' to this array
                    // because we don't want every login to be persistent
                    'persistProviders' => ['session']
                ]
            ]
        ]

    ]
];