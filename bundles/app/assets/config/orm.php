<?php

use Project\App\Model;

return [

    'models' => [

    ],

    'relationships' => [

        // permissions + roles
        [
            'type'  => 'manyToMany',
            'left'  => Model::Role,
            'right' => Model::Permission
        ],
        [
            'type'  => 'oneToMany',
            'owner' => Model::Role,
            'items' => Model::User
        ],
        [
            'type'  => 'nestedSet',
            'model' => Model::Role
        ]

    ]

];