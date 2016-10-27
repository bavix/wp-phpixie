<?php

return array(

    'type'      => 'group',
    'resolvers' => array(

        // a prefixed group for /cp/ routes
        'cp' => array(

            'type' => 'prefix',
            'path' => 'cp',
            'defaults' => array(
                'processor' => 'cp',
            ),

            'resolver' => array(
                'type' => 'group',
                'resolvers' => array(

                    'action' => array(
                        'type'     => 'pattern',
                        'path'     => '/<cpProcessor>/<action>'
                    ),

                    'processor' => array(
                        'type'     => 'pattern',
                        'path'     => '(/<cpProcessor>)',
                        'defaults' => array(
                            'cpProcessor' => 'dashboard',
                            'action'    => 'default'
                        )
                    ),
                )
            )
        ),

        'processor' => array(
            'type'     => 'pattern',
            'path'     => '(<processor>(/<action>))',
            'defaults' => array(
                'processor' => 'landing',
                'action'    => 'default'
            )
        ),
        
    )

);
