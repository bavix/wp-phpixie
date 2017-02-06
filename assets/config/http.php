<?php

return [

    'translator' => [
        'basePath' => '/'
    ],

    'resolver' => [
        'type'      => 'group',
        'resolvers' => [

            'cp'   => [
                'type'     => 'prefix',
                'path'     => 'cp',
                'defaults' => [
                    'bundle' => 'cp'
                ],
                'resolver' => [
                    'type' => 'mount',
                    'name' => 'cp'
                ]
            ],

            'api'  => [
                'type'     => 'prefix',
                'host'     => 'api.*',
                'defaults' => [
                    'bundle' => 'api'
                ],
                'resolver' => [
                    'type' => 'mount',
                    'name' => 'api'
                ]
            ],

            'api2' => [
                'type'     => 'prefix',
                'path'     => 'api/',
                'defaults' => [
                    'bundle' => 'api'
                ],
                'resolver' => [
                    'type' => 'mount',
                    'name' => 'api'
                ]
            ],

            'app'  => [
                'type'     => 'prefix',
                'defaults' => [
                    'bundle' => 'app'
                ],
                'resolver' => [
                    'type' => 'mount',
                    'name' => 'app'
                ]
            ],

        ]
    ],

    'exceptionResponse' => [
        'template' => 'framework:http/exception'
        //'app:error/404'
    ],

    'notFoundResponse' => [
        'template' => //'framework:http/notFound'
            'app:error/404'
    ]

];