<?php

return array(

    'translator' => array(
        'basePath' => '/'
    ),

    'resolver' => array(
        'type'      => 'group',
        'resolvers' => array(
            'cp'  => array(
                'type'     => 'prefix',
                'path'     => 'cp',
                'defaults' => array(
                    'bundle' => 'cp'
                ),
                'resolver' => array(
                    'type' => 'mount',
                    'name' => 'cp'
                )
            ),
            'api' => array(
                'type'     => 'prefix',
                'path'     => 'api',
                'defaults' => array(
                    'bundle' => 'api'
                ),
                'resolver' => array(
                    'type' => 'mount',
                    'name' => 'api'
                )
            ),
            'app' => array(
                'type'     => 'prefix',
                'defaults' => array(
                    'bundle' => 'app'
                ),
                'resolver' => array(
                    'type' => 'mount',
                    'name' => 'app'
                )
            ),
        )
    ),

    'exceptionResponse' => array(
        'template' => 'framework:http/exception'
        //'app:error/404'
    ),

    'notFoundResponse' => array(
        'template' => //'framework:http/notFound'
            'app:error/404'
    )

);