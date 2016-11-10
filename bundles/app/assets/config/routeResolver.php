<?php

return array(

    'type'      => 'group',
    'resolvers' => array(

        // a prefixed group for /cp/ routes
        'cp' => array(

            'type'     => 'prefix',
            'path'     => 'cp',
            'defaults' => array(
                'processor' => 'cp',
            ),

            'resolver' => array(

                'type'      => 'group',
                'resolvers' => array(

                    'sow' => array(

                        'type' => 'prefix',
                        'path' => '/sow',

                        'defaults' => array(
                            'cpProcessor' => 'sow',
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
                            'cpProcessor' => 'soc',
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
                            'cpProcessor' => 'sou',
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
                        'path' => '/<cpProcessor>/<action>/<id>'
                    ),

                    'action' => array(
                        'type' => 'pattern',
                        'path' => '/<cpProcessor>/<action>'
                    ),

                    'processor' => array(
                        'type'     => 'pattern',
                        'path'     => '(/<cpProcessor>)',
                        'defaults' => array(
                            'cpProcessor' => 'dashboard',
                            'action'      => 'default'
                        )
                    ),

                )
            )
        ),

        'item' => array(
            'type' => 'pattern',
            'path' => '<processor>/<action>/<id>'
        ),

        'action' => array(
            'type' => 'pattern',
            'path' => '<processor>/<action>'
        ),

        'processor' => array(
            'type'     => 'pattern',
            'path'     => '(<processor>)',
            'defaults' => array(
                'processor' => 'landing',
                'action'    => 'default'
            )
        ),

    )

);
