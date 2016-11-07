<?php

return array(

    'type' => 'group',
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

                    'sow' => array(

                        'type' => 'prefix',
                        'path' => '/sow',

                        'defaults' => array(
                            'cpProcessor' => 'wheel',
                        ),

                        'resolver' => array(

                            'type' => 'group',

                            'resolvers' => array(

                                'item' => array(
                                    'type' => 'pattern',
                                    'path' => '/<wheelProcessor>/<action>/<id>'
                                ),

                                'action' => array(
                                    'type' => 'pattern',
                                    'path' => '/<wheelProcessor>/<action>'
                                ),

                                'processor' => array(
                                    'type' => 'pattern',
                                    'path' => '/<wheelProcessor>',
                                    'defaults' => array(
                                        'wheelProcessor' => 'wheel',
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
                        'type' => 'pattern',
                        'path' => '(/<cpProcessor>)',
                        'defaults' => array(
                            'cpProcessor' => 'dashboard',
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
            'type' => 'pattern',
            'path' => '(/<processor>)',
            'defaults' => array(
                'processor' => 'landing',
                'action' => 'default'
            )
        ),

    )

);
