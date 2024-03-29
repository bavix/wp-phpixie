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

                    'item' => array(
                        'type' => 'pattern',
                        'path' => '/<nextProcessor>/<action>/<id>'
                    ),

                    'action' => array(
                        'type' => 'pattern',
                        'path' => '/<nextProcessor>/<action>'
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

                    'item' => array(
                        'type' => 'pattern',
                        'path' => '/<nextProcessor>/<action>/<id>'
                    ),

                    'action' => array(
                        'type' => 'pattern',
                        'path' => '/<nextProcessor>/<action>'
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

                    'item' => array(
                        'type' => 'pattern',
                        'path' => '/<nextProcessor>/<action>/<id>'
                    ),

                    'action' => array(
                        'type' => 'pattern',
                        'path' => '/<nextProcessor>/<action>'
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

        'settings' => array(

            'type' => 'prefix',
            'path' => '/settings',

            'defaults' => array(
                'processor' => 'settings',
            ),

            'resolver' => array(

                'type' => 'group',

                'resolvers' => array(

                    'item' => array(
                        'type' => 'pattern',
                        'path' => '/<nextProcessor>/<action>/<id>'
                    ),

                    'action' => array(
                        'type' => 'pattern',
                        'path' => '/<nextProcessor>/<action>'
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

        'item' => array(
            'type' => 'pattern',
            'path' => '/<processor>/<action>/<id>'
        ),

        'action' => array(
            'type' => 'pattern',
            'path' => '/<processor>/<action>'
        ),

        'processor' => array(
            'type'     => 'pattern',
            'path'     => '(/<processor>)',
            'defaults' => array(
                'processor' => 'dashboard',
                'action'    => 'default'
            )
        ),
    )

);
