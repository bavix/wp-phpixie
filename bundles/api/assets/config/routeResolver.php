<?php

return array(
    'type'      => 'group',
    'resolvers' => array(

        'sow' => array(

            'type' => 'prefix',
            'path' => '/sow',

            'defaults' => array(
                'processor' => 'sow',
            ),

            'resolver' => array(

                'type' => 'group',

                'resolvers' => array(

                    'nextItem' => array(
                        'type' => 'pattern',
                        'path' => '/<nextProcessor>/<id>/<action>/<nextId>'
                    ),

                    'nextAction' => array(
                        'type' => 'pattern',
                        'path' => '/<nextProcessor>/<id>/<action>'
                    ),

                    'item' => array(
                        'type'     => 'pattern',
                        'path'     => '/<nextProcessor>/<id>',
                        'defaults' => array(
                            'action' => 'item'
                        )
                    ),

                    'processor' => array(
                        'type'     => 'pattern',
                        'path'     => '/<nextProcessor>',
                        'defaults' => array(
                            'action' => 'default'
                        )
                    ),

                )
            )
        ),

        'soc' => array(

            'type' => 'prefix',
            'path' => '/soc',

            'defaults' => array(
                'processor' => 'soc',
            ),

            'resolver' => array(

                'type' => 'group',

                'resolvers' => array(

                    'nextItem' => array(
                        'type' => 'pattern',
                        'path' => '/<nextProcessor>/<id>/<action>/<nextId>'
                    ),

                    'nextAction' => array(
                        'type' => 'pattern',
                        'path' => '/<nextProcessor>/<id>/<action>'
                    ),

                    'item' => array(
                        'type'     => 'pattern',
                        'path'     => '/<nextProcessor>/<id>',
                        'defaults' => array(
                            'action' => 'item'
                        )
                    ),

                    'processor' => array(
                        'type'     => 'pattern',
                        'path'     => '/<nextProcessor>',
                        'defaults' => array(
                            'action' => 'default'
                        )
                    ),

                )
            )
        ),

        'sou' => array(

            'type' => 'prefix',
            'path' => '/sou',

            'defaults' => array(
                'processor' => 'sou',
            ),

            'resolver' => array(

                'type' => 'group',

                'resolvers' => array(

                    'nextItem' => array(
                        'type' => 'pattern',
                        'path' => '/<nextProcessor>/<id>/<action>/<nextId>'
                    ),

                    'nextAction' => array(
                        'type' => 'pattern',
                        'path' => '/<nextProcessor>/<id>/<action>'
                    ),

                    'item' => array(
                        'type'     => 'pattern',
                        'path'     => '/<nextProcessor>/<id>',
                        'defaults' => array(
                            'action' => 'item'
                        )
                    ),

                    'processor' => array(
                        'type'     => 'pattern',
                        'path'     => '/<nextProcessor>',
                        'defaults' => array(
                            'action' => 'default'
                        )
                    ),

                )
            )
        ),

        'auth' => [
            'type'     => 'pattern',
            'path'     => '/auth/<action>',
            'defaults' => [
                'processor'     => 'auth',
                //                'nextProcessor' => 'auth',
                'action'        => 'authorize',
            ]
        ],

        'nextItem' => array(
            'type' => 'pattern',
            'path' => '/<nextProcessor>/<id>/<action>/<nextId>'
        ),

        'nextAction' => array(
            'type' => 'pattern',
            'path' => '/<nextProcessor>/<id>/<action>'
        ),

        'item' => array(
            'type'     => 'pattern',
            'path'     => '/<nextProcessor>/<id>',
            'defaults' => array(
                'action' => 'item'
            )
        ),

        'processor' => array(
            'type'     => 'pattern',
            'path'     => '/<nextProcessor>',
            'defaults' => array(
                'action' => 'default'
            )
        ),

    )

);
