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
        ],

        // self
        [
            'type'  => 'oneToMany',
            'owner' => Model::Menu,
            'items' => Model::Menu,

            'ownerOptions' => array(
                'itemsProperty' => 'menus'
            ),

            'itemsOptions' => array(
                'ownerProperty' => Model::Menu,
                'ownerKey'      => 'parentId'
            )
        ],

        // brands & dealers

        [
            'type'  => 'manyToMany',
            'left'  => Model::Brand,
            'right' => Model::Dealer,
        ],
        [
            'type'  => 'manyToMany',
            'left'  => Model::Brand, // бренд + рубрика
            'right' => Model::Heading,
        ],
        [
            'type'  => 'manyToMany',
            'left'  => Model::Dealer, // бренд + рубрика
            'right' => Model::Heading,
        ],
        [
            'type'  => 'oneToMany',
            'owner' => Model::Brand,
            'items' => Model::Brand,

            'ownerOptions' => array(
                'itemsProperty' => 'brands'
            ),

            'itemsOptions' => array(
                'ownerProperty' => Model::Brand,
                'ownerKey'      => 'parentId'
            )
        ],
        [
            'type'  => 'oneToMany',
            'owner' => Model::Dealer,
            'items' => Model::Dealer,

            'ownerOptions' => array(
                'itemsProperty' => 'dealers'
            ),

            'itemsOptions' => array(
                'ownerProperty' => Model::Dealer,
                'ownerKey'      => 'parentId'
            )
        ],

    ]

];