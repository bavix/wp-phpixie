<?php

return [

    'facebook' => [
        'type'       => 'facebook',
        'appId'      => '',
        'appSecret'  => '',
        'scope'      => [
            'public_profile',
            'user_friends',
            'email',
        ],
        'apiVersion' => '2.8'
    ],

    'twitter' => [
        'type'           => 'twitter',
        'consumerKey'    => '',
        'consumerSecret' => ''
    ],

    'google' => [
        'type'      => 'google',
        'appId'     => '',
        'appSecret' => '',
        'scope'     => [
            'profile',
            'email'
        ]
    ],

    'vk' => [
        'type'      => 'vk',
        'appId'     => '',
        'appSecret' => '',
        'scope'     => [
            'email'
        ],
    ],

    'instagram' => [
        'type'      => 'instagram',
        'appId'     => '',
        'appSecret' => '',
        'scope'     => [
            'basic',
            'public_content'
        ],
    ],

    'github' => [
        'type'      => 'github',
        'appId'     => '',
        'appSecret' => '',
        'scope'     => [
            'user', 'gist', 'user:email'
        ]
    ],

    'dropbox' => [
        'type'      => 'dropbox',
        'appId'     => '',
        'appSecret' => '',
    ],

];
