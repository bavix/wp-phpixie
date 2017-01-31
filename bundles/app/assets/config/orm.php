<?php

use Project\Model;

return [

    'models' => [

        Model::BRAND_SOCIAL => [
            'table' => 'brandsSocials'
        ],

        Model::BRAND_DEALER => [
            'table' => 'brandsDealers'
        ],

        Model::BRAND_HEADING => [
            'table' => 'brandsHeadings'
        ],

    ],

    'relationships' => [

        // permissions + roles
        [
            'type'  => 'manyToMany',
            'left'  => Model::ROLE,
            'right' => Model::PERMISSION
        ],
        [
            'type'  => 'oneToMany',
            'owner' => Model::ROLE,
            'items' => Model::USER
        ],
        [
            'type'  => 'nestedSet',
            'model' => Model::ROLE
        ],

        // user invites
        [
            'type'  => 'oneToMany',
            'owner' => Model::USER,
            'items' => Model::INVITE
        ],

        [
            'type'  => 'oneToMany',
            'owner' => Model::ROLE,
            'items' => Model::INVITE
        ],

        // self
        [
            'type'  => 'oneToMany',
            'owner' => Model::MENU,
            'items' => Model::MENU,

            'ownerOptions' => array(
                'itemsProperty' => 'menus'
            ),

            'itemsOptions' => array(
                'ownerProperty' => Model::MENU,
                'ownerKey'      => 'parentId'
            )
        ],

        // brands & dealers

        [
            'type'  => 'manyToMany',
            'left'  => Model::BRAND,
            'right' => Model::DEALER,
        ],
        [
            'type'  => 'manyToMany',
            'left'  => Model::DEALER, // бренд + рубрика
            'right' => Model::HEADING,
        ],
        [
            'type'  => 'oneToMany',
            'owner' => Model::BRAND,
            'items' => Model::BRAND,

            'ownerOptions' => array(
                'itemsProperty' => 'brands'
            ),

            'itemsOptions' => array(
                'ownerProperty' => Model::BRAND,
                'ownerKey'      => 'parentId'
            )
        ],
        [
            'type'  => 'oneToMany',
            'owner' => Model::DEALER,
            'items' => Model::DEALER,

            'ownerOptions' => array(
                'itemsProperty' => 'dealers'
            ),

            'itemsOptions' => array(
                'ownerProperty' => Model::DEALER,
                'ownerKey'      => 'parentId'
            )
        ],
        [
            'type'  => 'oneToMany',
            'owner' => Model::HEADING,
            'items' => Model::HEADING,

            'ownerOptions' => array(
                'itemsProperty' => 'headings'
            ),

            'itemsOptions' => array(
                'ownerProperty' => Model::HEADING,
                'ownerKey'      => 'parentId'
            )
        ],

        // brand <-> social
        [
            'type'  => 'manyToMany',
            'left'  => Model::BRAND,
            'right' => Model::SOCIAL
        ],
        [
            'type'  => 'oneToMany',
            'owner' => Model::BRAND,
            'items' => Model::BRAND_SOCIAL
        ],
        [
            'type'  => 'oneToMany',
            'owner' => Model::SOCIAL,
            'items' => Model::BRAND_SOCIAL
        ],

        // brand <-> wheel
        [
            'type'  => 'oneToMany',
            'owner' => Model::BRAND,
            'items' => Model::WHEEL,
        ],
        [
            'type'  => 'oneToMany',
            'owner' => Model::COLLECTION,
            'items' => Model::WHEEL,
        ],

        // brand <-> heading
        [
            'type'  => 'manyToMany',
            'left'  => Model::BRAND,
            'right' => Model::HEADING
        ],
        [
            'type'  => 'oneToMany',
            'owner' => Model::BRAND,
            'items' => Model::BRAND_HEADING
        ],
        [
            'type'  => 'oneToMany',
            'owner' => Model::HEADING,
            'items' => Model::BRAND_HEADING
        ],

        [
            'type'  => 'oneToMany',
            'owner' => Model::USER,
            'items' => Model::LOG
        ],
    ]

];