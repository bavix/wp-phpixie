<?php

return array(

    'type'      => 'group',
    'resolvers' => array(

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
                'action' => 'default'
            )
        ),

    )

);
