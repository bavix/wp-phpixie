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

        Model::WHEEL_COMMENT => [
            'table' => 'wheelsComments'
        ],

        Model::OAUTH_CLIENT => [
            'table' => 'oauth_clients',
            'id'    => 'client_id'
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

        // images

        [
            'type'  => 'oneToOne',
            'owner' => Model::BRAND,
            'item' => Model::IMAGE,

            'itemOptions' => [
                'ownerKey'      => 'itemId',
            ]
        ],

        [
            'type'  => 'oneToOne',
            'owner' => Model::DEALER,
            'item'  => Model::IMAGE,

            'itemOptions' => [
                'ownerKey'      => 'itemId',
            ]
        ],

        [
            'type'  => 'oneToOne',
            'owner' => Model::WHEEL,
            'item'  => Model::IMAGE,

            'itemOptions' => [
                'ownerKey'      => 'itemId',
            ]
        ],

        [
            'type'  => 'oneToOne',
            'owner' => Model::USER,
            'item'  => Model::IMAGE,

            'itemOptions' => [
                'ownerKey'      => 'itemId',
            ]
        ],

        [
            'type'  => 'manyToMany',
            'left'  => Model::WHEEL,
            'right' => Model::VIDEO,
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

        // wheel <=> comment
        [
            'type'  => 'manyToMany',
            'left'  => Model::WHEEL,
            'right' => Model::COMMENT
        ],
        [
            'type'  => 'manyToMany',
            'left'  => Model::WHEEL,
            'right' => Model::IMAGE
        ],

        // wheel <=> like
        [
            'type'  => 'manyToMany',
            'left'  => Model::WHEEL,
            'right' => Model::USER,

            'leftOptions' => [
                'property' => 'likes'
            ],

            'rightOptions' => [
                'property' => 'likeWheels'
            ],

            'pivot' => 'wheelsLikes',

            'pivotOptions' => [
                'leftKey'  => 'wheelId',
                'rightKey' => 'userId',
            ]
        ],
        [
            'type'  => 'manyToMany',
            'left'  => Model::WHEEL,
            'right' => Model::USER,

            'leftOptions' => [
                'property' => 'favourites'
            ],

            'rightOptions' => [
                'property' => 'favouriteWheels'
            ],

            'pivot' => 'wheelsFavourites',

            'pivotOptions' => [
                'leftKey'  => 'wheelId',
                'rightKey' => 'userId',
            ]
        ],

        // brand <-> wheel
        [
            'type'  => 'oneToMany',
            'owner' => Model::BRAND,
            'items' => Model::WHEEL,
        ], [
            'type'  => 'oneToMany',
            'owner' => Model::BRAND,
            'items' => Model::COLLECTION,
        ],
        [
            'type'  => 'oneToMany',
            'owner' => Model::COLLECTION,
            'items' => Model::WHEEL,
        ],
        [ // fixme : проверить условие
          'type'  => 'oneToMany',
          'owner' => Model::COLLECTION,
          'items' => Model::COLLECTION,

          'ownerOptions' => array(
              'itemsProperty' => 'collections'
          ),

          'itemsOptions' => array(
              'ownerProperty' => Model::COLLECTION,
              'ownerKey'      => 'parentId'
          )
        ],

        // user <-> apps
        [
            'type'  => 'oneToMany',
            'owner' => Model::USER,
            'items' => Model::APP,
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

        [
            'type'  => 'manyToMany',
            'left'  => Model::BRAND,
            'right' => Model::ADDRESS
        ],

        [
            'type'  => 'manyToMany',
            'left'  => Model::DEALER,
            'right' => Model::ADDRESS
        ],

    ]

];