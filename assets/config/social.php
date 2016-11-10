<?php

return array(

    'facebook' => array(
        'type'       => 'facebook',
        'appId'      => '',
        'appSecret'  => '',
        'scope'      => array(
            'public_profile',
            'user_friends',
            'email',
        ),
        'apiVersion' => '2.8'
    ),

    'twitter' => array(
        'type'           => 'twitter',
        'consumerKey'    => '',
        'consumerSecret' => ''
    ),

    'google' => array(
        'type'      => 'google',
        'appId'     => '',
        'appSecret' => '',
        'scope'     => array(
            'profile',
            'email'
        )
    ),

    'vk' => array(
        'type'      => 'vk',
        'appId'     => '',
        'appSecret' => '',
        'scope'     => array(
            'email'
        ),
    ),

    'instagram' => array(
        'type'      => 'instagram',
        'appId'     => '',
        'appSecret' => '',
        'scope'     => array(
            'basic',
            'public_content'
        ),
    ),

    'github' => array(
        'type'      => 'github',
        'appId'     => '',
        'appSecret' => '',
        'scope'     => array(
            'user', 'gist', 'user:email'
        )
    ),

    'dropbox' => array(
        'type'      => 'dropbox',
        'appId'     => '',
        'appSecret' => '',
    ),

);
